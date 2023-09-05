<?php
namespace App\Services\Admin;
use auth;

use App\Models\ToolReturn;
use App\Models\ToolRequest;
use App\Models\Tool;
use App\Models\User;
use App\Models\Site;
use App\Models\ToolAvailabilityNotication;
use Exception;
use DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use App\Events\AcceptToolReturnProcessed;

class ToolReturnService
{
    public function getAllRequestToolsAsJsonForDatatable($request){

        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;
        $type = $request->type ?? 'history';
        
        $tools = ToolReturn::select('tool_returns.created_at', 'tool_returns.comment', 'tool_returns.id', 'tool_returns.details',
        'tool_returns.drop_type', 'tool_returns.return_status', 'sites.address AS siteAddress', 'tool_requests.status', 
        'tools.description', 'tools.serial_no', 'tools.product_no', 'users.name', 'users.employee_id', 'tools.calibration_date', 
        'tools.tool_condition', 'tools.image', 'tools.qr_code', DB::raw(' 
        CASE
            WHEN tool_condition = "good" THEN 
                CASE 
                    WHEN (calibration_date  IS NOT NULL) THEN 
                        CASE 
                            WHEN (calibration_date <= NOW()) THEN "red"
                            WHEN (calibration_date > DATE_ADD(NOW(), INTERVAL 1 MONTH)) Then "green"
                            Else "yellow" 
                        END
                    ELSE "green"
                END
            ELSE "red"
        END as tool_condition_status'))
        ->Join('tool_requests', 'tool_returns.tool_request_id', '=', 'tool_requests.id')
        ->Join('tools', 'tool_returns.tool_id', '=', 'tools.id')
        ->Join('sites', 'tool_returns.site_id', '=', 'sites.id')
        ->Join('users', 'tool_returns.user_id', '=', 'users.id')
        ->where(function ($query) use ($name) {
            $query->orWhere('tools.description', 'like', "%$name%")
            ->orWhere('users.name', 'like', "%$name%")
            ->orWhere('tools.serial_no', 'like', "%$name%")
            ->orWhere('tools.product_no', 'like', "%$name%")
            ->orWhere('tool_returns.drop_type', 'like', "%$name%");
        })
        ->when($type, function ($query) use ($type) {
            if($type == 'new'){
                $query->where('tool_requests.status', '!=', 'returned');
            }else{
                $query->where('tool_requests.status', "returned");
            }
        })
    
        ->whereNull('tool_returns.deleted_at')
        ->orderBy($sortBy, $orderBy)
        ->paginate($length)->toArray();
       
        return $tools;
    }

    public function acceptToolReturn($request)
    {
        DB::beginTransaction();
        try {
           $toolReturn = ToolReturn::where('id', $request->returnToolId)->withTrashed()->first(); 
           $tool = Tool::where('id', $toolReturn->tool_id)->withTrashed()->first();
           $toolRequest = ToolRequest::where('id', $toolReturn->tool_request_id)->withTrashed()->first();

           $toolReturn->drop_type = $request->pickup; 
            $toolReturn->details = ($request->details) ? strip_tags(trim($request->details)) : null;
            $toolReturn->save();

            /*updating tool request to returned*/
            $toolRequest->status = 'returned';
            $toolRequest->save();

            /*updating tool current site location and status*/
            $tool->site_id =  $request->site;
            $tool->status =  $request->toolStatus;
            $tool->save();

            $enginer = User::where('id', $toolRequest->user_id)->withTrashed()->first();
            $admin = User::findOrFail(1);

            $toolAvailabiltyNotityUsers = array();
            if ($tool->status == 'available') {
                $toolAvailabiltyNotityUsers = ToolAvailabilityNotication::with(['user'])->where(['tool_id' => $toolReturn->tool_id, 'notified' => 0])->get()->toArray();
            }

            $pickupType = "";
            if($toolReturn->drop_type == 'UPS'){
                $pickupType = "Dropped by UPS";
            }else if($toolReturn->drop_type == 'EPT'){
                $pickupType = "Dropped by EPT";
            }else if($toolReturn->drop_type == 'FSE'){
                $pickupType = "Dropped by FSE";
            }

            if (!App::environment('local')) {
                event(new AcceptToolReturnProcessed(
                    ['user' => ['name' => $enginer->name, 'email' => $enginer->email], 
                    'tools' => Tool::with(['modality', 'site'])->where('id', $toolRequest->tool_id)->first(),
                    'admin' => ['name' => $admin->name, 'email' => $admin->email],
                    'created_at' => Carbon::parse($toolRequest->created_at)->format('m/d/Y'),
                    'pickup_type' => $pickupType,
                    'site_address' => Site::find($toolRequest->site_id)->address,
                    'tool_status' => $request->toolStatus,
                    'toolAvailabiltyNotityUsers' => $toolAvailabiltyNotityUsers,
                    ]
                ));
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.manage_tool_return.accepted'),
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getAllReturnTools()
    {   
        return ToolReturn::all();
    }
}