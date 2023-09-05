<?php
namespace App\Services\Admin;
use auth;

use App\Models\ToolRequest;
use App\Models\Tool;
use App\Models\User;
use App\Models\Site;
use Exception;

use Carbon\Carbon;
use DB;

use Illuminate\Support\Facades\App;
use App\Events\RejectToolRequestProcessed;
use App\Events\AcceptToolRequestProcessed;

class ToolRequestService
{
    public function getAllRequestToolsAsJsonForDatatable($request){

        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;
        $type = $request->type;

        $tools = ToolRequest::select('tool_requests.created_at', 'tool_requests.delivery_date', 'tool_requests.id', 'tool_requests.pickup_type', 'tool_requests.status', 
        'sites.address AS siteAddress', 'tools.description', 'tools.serial_no', 'tools.product_no', 'users.name', 'users.employee_id', 
        'tools.calibration_date', 'tools.tool_condition','tool_requests.details', 'tools.image', 'tools.qr_code', 'cities.name AS cityName','provinces.name AS provinceName', DB::raw(' 
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
        ->Join('tools', 'tool_requests.tool_id', '=', 'tools.id')
        ->Join('sites', 'tool_requests.site_id', '=', 'sites.id')
        ->Join('users', 'tool_requests.user_id', '=', 'users.id')
        ->Join('cities', 'sites.city_id', '=', 'cities.id')
        ->Join('provinces', 'sites.province_id', '=', 'provinces.id')
        ->where(function ($query) use ($name) {
            $query->orWhere('tools.description', 'like', "%$name%")
            ->orWhere('users.name', 'like', "%$name%")
            ->orWhere('tools.serial_no', 'like', "%$name%")
            ->orWhere('tools.product_no', 'like', "%$name%")
            ->orWhere('tool_requests.pickup_type', 'like', "%$name%");
        })

        ->when($type, function ($query) use ($type) {
            if($type == 'new'){
                $query->where('tool_requests.status', '=', "pending");
            }else{
                $query->where('tool_requests.status', '!=', "pending");
            }
        })

        ->whereNull('tool_requests.deleted_at')
        ->orderBy($sortBy, $orderBy)
        ->paginate($length)->toArray();

        return $tools;
    }

    public function rejectToolRequest($requestToolID)
    {
        try {
           $toolRequest = ToolRequest::with(['site'])->findOrFail($requestToolID);

            $enginer = User::where('id', $toolRequest->user_id)->withTrashed()->first();
            $admin = User::findOrFail(1);
            $toolRequest->status = 'rejected';
            $toolRequest->save();
            $pickupType = "";
            if($toolRequest->pickup_type == 'UPS'){
                $pickupType = "Shipped by UPS";
            }else if($toolRequest->pickup_type == 'EPT'){
                $pickupType = "Shipped by EPT";
            }else if($toolRequest->pickup_type == 'FSE'){
                $pickupType = "Picked by FSE";
            }
            if (!App::environment('local')) {
                event(new RejectToolRequestProcessed(['user' => 
                    ['name' => $enginer->name, 'email' => $enginer->email], 
                    'tools' => Tool::with(['modality', 'site'])->where('id', $toolRequest->tool_id)->first(),
                    'admin' => ['name' => $admin->name, 'email' => $admin->email],
                    'created_at' => Carbon::parse($toolRequest->created_at)->format('m/d/Y'),
                    'delivery_date' => $toolRequest->delivery_date,
                    'pickup_type' => $pickupType,
                    'site_address' => Site::with(['city', 'province'])->find($toolRequest->site_id),
                    ]
                ));
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.manage_tool_requests.rejected'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function acceptToolRequest($request)
    {
        DB::beginTransaction();
        try {
           $toolRequest = ToolRequest::findOrFail($request->requestToolId);
           $tool = Tool::findOrFail($toolRequest->tool_id);

           $toolRequest->pickup_type = $request->pickup;
           $toolRequest->details = ($request->details) ? strip_tags(trim($request->details)) : null;

            $toolRequest->status = 'approved';
            $toolRequest->save();

            /*updating tool current site location and status*/
            $tool->site_id =  $toolRequest->site_id;
            $tool->status =  'busy';
            $tool->save();
            $pickupType = "";
            if($toolRequest->pickup_type == 'UPS'){
                $pickupType = "Shipped by UPS";
            }else if($toolRequest->pickup_type == 'EPT'){
                $pickupType = "Shipped by EPT";
            }else if($toolRequest->pickup_type == 'FSE'){
                $pickupType = "Picked by FSE";
            }
            DB::commit();

            $enginer = User::where('id', $toolRequest->user_id)->withTrashed()->first();
            $admin = User::findOrFail(1);
            if (!App::environment('local')) {
                event(new AcceptToolRequestProcessed(['user' => 
                    ['name' => $enginer->name, 'email' => $enginer->email], 
                    'tools' => Tool::with(['modality', 'site'])->where('id', $toolRequest->tool_id)->first(),
                    'admin' => ['name' => $admin->name, 'email' => $admin->email],
                    'created_at' => Carbon::parse($toolRequest->created_at)->format('m/d/Y'),
                    'delivery_date' => $toolRequest->delivery_date,
                    'pickup_type' => $pickupType,
                    'site_address' => Site::with(['city', 'province'])->find($toolRequest->site_id),
                    ]
                ));
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.manage_tool_requests.accepted'),
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getAllRequestTools()
    {   
        return ToolRequest::all();
    }
}