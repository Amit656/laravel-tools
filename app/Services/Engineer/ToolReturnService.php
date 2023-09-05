<?php
namespace App\Services\Engineer;
use auth;
use App\Models\Tool;
use App\Models\Modality;
use App\Models\City;
use App\Models\Province;
use App\Models\Site;
use App\Models\User;
use App\Models\ToolRequest;
use App\Models\ToolReturn;
use Illuminate\Support\Facades\DB;
use Exception;

use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use App\Events\ToolReturnProcessed;

class ToolReturnService
{
    public function saveToolReturn($request){
        $dataToBeSaved = array();
        $inputToolIDS = $request->returnTool;
        $inputConditions = $request->condition;
        $inputComments = $request->comment;
        $toolIDs = [];
        if ((count($inputToolIDS) != count($inputConditions)) && count($inputToolIDS) != count($inputComments)) {
            return redirect()->back()->with('error', __('messages.return_tool.went_wrong'));
        }
        
        $tools = ToolRequest::with(['tool'])->where('status', 'approved')
        ->whereIn('id', array_values($inputToolIDS))->get();
       
        foreach ($tools as $details) {
            $dataToBeSaved[] = [
                'tool_id' => $details->tool_id,
                'tool_request_id' => $details->id,
                'site_id' => $details->site_id,
                'user_id' => auth::user()->id,
                'drop_type' => $request->pickup,
                'details' => ($request->details) ? strip_tags(trim($request->details)) : null,
                'return_status' => $inputConditions[$details->id] ?? "good",
                'comment' => $inputComments[$details->id] ?? "",
                'created_at' => Carbon::now()->toDateTimeString()
            ];
            array_push($toolIDs, $details->tool_id);
            $details->tool->update([
                'tool_condition' => $inputConditions[$details->id] ?? "good",
            ]);
        }
       
        try {
            ToolReturn::insert($dataToBeSaved);
            $pickupType = "";
            if($request->pickup == 'UPS'){
                $pickupType = "Dropped by UPS";
            }else if($request->pickup == 'EPT'){
                $pickupType = "Dropped by EPT";
            }else if($request->pickup == 'FSE'){
                $pickupType = "Dropped by FSE";
            }
            $admin = User::findOrFail(1);
            if (!App::environment('local')) {
                event(new ToolReturnProcessed(['user' => 
                    ['name' => auth::user()->name, 'email' => auth::user()->email], 
                    'tools' => Tool::with(['modality', 'site'])->whereIn('id', $toolIDs)->get(),
                    'admin' => ['name' => $admin->name, 'email' => $admin->email],
                    'created_at' => Carbon::parse(Carbon::now()->toDateTimeString())->format('m/d/Y'),
                    'drop_type' => $pickupType,
                    ]
                ));
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.return_tool.submitted'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getCurrrentInHandTools(){
        $allCurrrentInHandTools = ToolRequest::where('user_id', auth::user()->id)
        ->where('status', 'approved')
        ->whereNull('deleted_at')
        ->with(['tool', 'site', 'user'])
        ->orderBy('id', 'DESC')
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
                'tool_number'=> $toolRequest->tool->tool_id,
                'tool_description'=> $toolRequest->tool->description,
                'tool_serial_no'=> $toolRequest->tool->serial_no,
                'tool_product_no'=> $toolRequest->tool->product_no,
                'modality_id'=> $toolRequest->tool->modality_id,
                'tool_calibration_date'=> (!empty($toolRequest->tool->calibration_date) ? Carbon::parse($toolRequest->tool->calibration_date)->format('m/d/Y') : ""),
            ];
        });

        return $data;
    }

    public function getAllReturnedToolsAsJsonForDatatable($request){

        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        $tools = ToolReturn::select('tool_returns.created_at', 'tool_returns.drop_type', 'tool_requests.status', 
        'sites.address AS siteAddress', 'tools.description', 'tools.serial_no', 'tools.product_no', 'tool_returns.return_status', 
        'tool_returns.details', 'tools.tool_condition', 'tools.calibration_date', DB::raw(' 
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
        ->where('tool_returns.user_id', auth::user()->id)
        ->Join('tools', 'tool_returns.tool_id', '=', 'tools.id')
        ->Join('sites', 'tool_returns.site_id', '=', 'sites.id') 
        ->Join('tool_requests', 'tool_returns.tool_request_id', '=', 'tool_requests.id') 
        ->where(function ($query) use ($name) {
            $query->orWhere('tools.description', 'like', "%$name%")
            ->orWhere('tools.serial_no', 'like', "%$name%")
            ->orWhere('tools.product_no', 'like', "%$name%")
            ->orWhere('tool_returns.drop_type', 'like', "%$name%");
        }) 
        
        ->whereNull('tool_returns.deleted_at')
        ->orderBy($sortBy, $orderBy)
        ->paginate($length)->toArray();

        return $tools;
    }
}