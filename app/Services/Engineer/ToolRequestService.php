<?php
namespace App\Services\Engineer;
use auth;
use App\Models\Tool;
use App\Models\User;
use App\Models\Site;
use App\Models\ToolRequest;
use App\Models\ToolReturn;
use App\Models\ToolAvailabilityNotication;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use App\Events\ToolRequestProcessed;

class ToolRequestService
{
    public function saveToolRequest($request){
        
        $dataToBeSaved = array();
        $toolRequestIDS = $request->requestedTool ?? [];

        if (count($toolRequestIDS) < 1) {
            return response()->json([
                'success' => true,
                'message' => __('messages.sometimes_went_wrong'),
            ]);
        }

        $toolRequestToBeSaved = array();
        $toolNotificationToBeSaved = array();
        foreach ($toolRequestIDS as $toolID) {
            $toolRequestToBeSaved[] = [
                'tool_id' => $toolID,
                'user_id' => auth::user()->id,
                'delivery_date' => Carbon::parse($request->deliveryDate)->format('Y-m-d'),
                'expected_return_date' => Carbon::parse($request->expectedReturnDate)->format('Y-m-d'),
                'site_id' => $request->site,
                'pickup_type' => $request->pickup,
                'details' => ($request->details) ? strip_tags(trim($request->details)) : null,
                'created_at' => Carbon::now()->toDateTimeString()
            ];
        }

        DB::beginTransaction();
        try {
            ToolRequest::insert($toolRequestToBeSaved);

                DB::commit();
                $pickupType = "";
                if($request->pickup == 'UPS'){
                    $pickupType = "Shipped by UPS";
                }else if($request->pickup == 'EPT'){
                    $pickupType = "Shipped by EPT";
                }else if($request->pickup == 'FSE'){
                    $pickupType = "Picked by FSE";
                }
                $admin = User::findOrFail(1);
                if (!App::environment('local')) {
                    event(new ToolRequestProcessed(['user' => 
                        ['name' => auth::user()->name, 'email' => auth::user()->email], 
                        'tools' => Tool::with(['modality', 'site'])->whereIn('id', $toolRequestIDS)->get(),
                        'admin' => ['name' => $admin->name, 'email' => $admin->email],
                        'created_at' => Carbon::parse(Carbon::now()->toDateTimeString())->format('m/d/Y'),
                        'expected_return_date' => $request->expectedReturnDate,
                        'pickup_type' => $pickupType,
                        'site_address' => Site::find($request->site)->address,
                        ]
                    ));
                }
            
            return response()->json([
                'success' => true,
                'message' => __('messages.request_tool.submitted'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getCurrrentInHandTools($toolRequestIDS = array(), $search=''){
        $allCurrrentInHandTools = ToolRequest::select('tool_requests.*', 'tools.tool_id as tool_no','tools.description', 'tools.serial_no',
         'tools.product_no', 'tools.calibration_date', DB::raw(' 
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
        ->where('user_id', auth::user()->id)
        ->Join('tools', 'tool_requests.tool_id', '=', 'tools.id')
        ->where('tool_requests.status', 'approved')
        ->whereNotIn('tool_requests.id', (ToolReturn::where('user_id', auth::user()->id)->pluck('tool_request_id')))
        ->whereNull('tool_requests.deleted_at')
        ->where(function ($query) use ($search) {
            $query->orWhere('tools.description', 'like', "%$search%")
            ->orWhere('tools.serial_no', 'like', "%$search%")
            ->orWhere('tools.product_no', 'like', "%$search%");
        })
        ->where(function($query) use ($toolRequestIDS)
        {   
            if (!empty($toolRequestIDS)) { 
                if (count($toolRequestIDS) > 0) {
                    $query->whereIn('tool_requests.id', $toolRequestIDS);
                }
            }
        })
        ->with(['site', 'user'])
        ->orderBy('tool_requests.id', 'DESC')
        ->get();
        
        $data = $allCurrrentInHandTools->map(function ($toolRequest) {

            return [
                'id'=> $toolRequest->id,
                'tool_id'=> $toolRequest->tool_id,
                'delivery_date'=> Carbon::parse($toolRequest->delivery_date)->format('m/d/Y'),
                'expected_return_date'=> Carbon::parse($toolRequest->expected_return_date)->format('m/d/Y'),
                'pickup_type'=> $toolRequest->pickup_type,
                'status'=> $toolRequest->status,
                'created_at'=> Carbon::parse($toolRequest->created_at)->format('m/d/Y'),
                'updated_at'=> (!empty($toolRequest->updated_at) ? Carbon::parse($toolRequest->updated_at)->format('m/d/Y') : ""),
                'site_id'=> $toolRequest->site_id,
                'site_name'=> $toolRequest->site->name,
                'site_address'=> $toolRequest->site->address,
                'user_id'=> $toolRequest->user_id,
                'user_name'=> $toolRequest->user->name,
                'user_name'=> $toolRequest->user->name,
                'tool_number'=> $toolRequest->tool_no,
                'tool_description'=> $toolRequest->description,
                'tool_serial_no'=> $toolRequest->serial_no,
                'tool_product_no'=> $toolRequest->product_no,
                'modality_id'=> $toolRequest->tool->modality_id,
                'tool_calibration_date'=> (!empty($toolRequest->calibration_date) ? Carbon::parse($toolRequest->calibration_date)->format('m/d/Y') : ""),
                'toolColor' => $toolRequest->tool_condition_status,
            ];
        });
        
        return $data;
    }

    public function getAllRequestToolsAsJsonForDatatable($request){

        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        $tools = ToolRequest::select('tool_requests.created_at', 'tool_requests.pickup_type', 'tool_requests.details', 
        'tool_requests.status', 'sites.address AS siteAddress', 'tools.description', 'tools.serial_no', 'tools.product_no', 
        'tools.tool_condition', 'tools.calibration_date', DB::raw(' 
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
        ->where('tool_requests.user_id', auth::user()->id)
        ->where(function ($query) use ($name) {
            $query->orWhere('tools.description', 'like', "%$name%")
            ->orWhere('tools.serial_no', 'like', "%$name%")
            ->orWhere('tools.product_no', 'like', "%$name%")
            ->orWhere('tool_requests.pickup_type', 'like', "%$name%");
        })
        ->whereNull('tool_requests.deleted_at')
        ->orderBy($sortBy, $orderBy)
        ->paginate($length)->toArray();

        return $tools;
    }

    public function notifyAtAvailability($request){
        try {
            Tool::findOrFail($request->id);

            $msg = 'messages.request_tool.notify_added';

            if ($request->state == 'false') {
                ToolAvailabilityNotication::where(['user_id' => auth::user()->id, 'tool_id' => $request->id])->delete();
                $msg = 'messages.request_tool.notify_deleted';
            }else{
                ToolAvailabilityNotication::create(
                    ['user_id' => auth::user()->id, 'tool_id' => $request->id]
                );
            }

            return response()->json([
                'success' => true,
                'message' => __($msg),
            ]);
            
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function notifyTools(){
        return ToolAvailabilityNotication::where(['user_id' => auth::user()->id, 'notified' => 0])->pluck('tool_id')->toArray();
    }
}