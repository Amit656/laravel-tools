<?php
namespace App\Services\Admin;

use App\Models\Tool;
use App\Models\Modality;
use App\Models\ToolRequest;
use App\Models\ToolAvailabilityNotication;
use Illuminate\Support\Facades\DB;
use App\Models\ToolReturn;
use Exception;
use auth;

use Carbon\Carbon;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use App\Events\CalibrationDateProcessed;

class ToolService
{
    public function getAllToolsAsJsonForDatatable($request)
    {   
        $name = $request['search']['value']??''; 
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        $tools = Tool::with('calibrationReport')->select('tools.*', 'modality.name AS modalityName', 'sites.name AS siteName', 
                        DB::raw('datediff(`calibration_date`, NOW()) as due_in_days'))
            ->Join('modality', 'tools.modality_id', '=', 'modality.id')
            ->Join('sites', 'tools.site_id', '=', 'sites.id')
            ->when($name, function ($nameQuery) use ($name) {
            /**
             * if name present then add name filter
             */
                return $nameQuery->where('tools.description', 'like', "%$name%")
                                ->orWhere('tools.tool_id', 'like', "%$name%")
                                ->orWhere('tools.serial_no', 'like', "%$name%")
                                ->orWhere('tools.product_no', 'like', "%$name%")
                                ->orWhere('modality.name', 'like', "%$name%");
            })
            ->orderBy($sortBy, $orderBy)
            ->paginate($length)->toArray();

        return $tools;
    }

    public function getAllTools()
    {   
        return Tool::all();
    }

    public function saveNewTool($request)
    {
        try {
            $tool = new Tool();
            //dd($request->image);
            $tool->tool_id = $request->toolNo;
            $tool->modality_id = $request->modality;
            $tool->site_id = $request->site;
            $tool->serial_no = $request->serialNo;
            $tool->product_no = $request->productNo;
            $tool->type_of_use = $request->typeOfUse;
            $tool->asset = $request->asset;
            $tool->sort_field = $request->sortField;
            
            $tool->description = $request->desc;
            $tool->status = $request->toolStatus;
            if(!empty($request->calibrationDate)){

                $tool->calibration_date = Carbon::parse($request->calibrationDate)->format('Y-m-d');
            }
            
            if($request->hasFile('image')){

                $storeImage = \Storage::disk('public')->put('tools', $request->image);
                $tool->image = $storeImage;
            }
            $tool->save();
            if($request->hasFile('qr_code')){
                
                $storeQrCode = \Storage::disk('public')->put('qr_code/'.$tool->id, $request->qr_code);
                $tool->where('id', $tool->id)->update(["qr_code" => $storeQrCode]);
            }
           
            return response()->json([
                'success' => true,
                'message' => __('messages.tool.created'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getTool($tool)
    {
       try{
            $tool['site'] = $tool->site->name;
            return response()->json([
                'success' => true,
                'data' => $tool,
            ]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function updateTool($request)
    {
        try {
            $tool = Tool::findOrFail($request->toolId);

            $tool->tool_id = $request->toolNo;
            $tool->modality_id = $request->modality;
            $tool->site_id = $request->site;
            $tool->serial_no = $request->serialNo;
            $tool->product_no = $request->productNo;
            $tool->type_of_use = $request->typeOfUse;
            $tool->asset = $request->asset;
            $tool->sort_field = $request->sortField;

            if(!empty($request->calibrationDate)){

                $tool->calibration_date = Carbon::parse($request->calibrationDate)->format('Y-m-d');
            }

            $tool->description = $request->desc;
            $tool->status = $request->toolStatus;
            //Image check
            if($request->hasFile('image')){
                if(!empty($tool->image)){    
                    $imagePath = \Storage::disk('public')->exists($tool->image); 
                    if ($imagePath) {
                        \Storage::disk('public')->delete($tool->image);
                    }
                }
                
                $storeImage = \Storage::disk('public')->put('tools', $request->image);
                $tool->image = $storeImage;
            }
            //QR code check
            if($request->hasFile('qr_code')){
                if(!empty($tool->qr_code)){    
                    $qrPath = \Storage::disk('public')->exists($tool->qr_code); 
                    if ($qrPath) {
                        \Storage::disk('public')->delete($tool->qr_code);
                    }
                }
                
                $storeQR = \Storage::disk('public')->put('qr_code/'.$tool->id, $request->qr_code);
                $tool->qr_code = $storeQR;
            }
            
            $tool->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.tool.updated'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function deleteTool($request)
    {
        try {
            $tool = Tool::findOrFail($request['toolId']);
            
             if(!empty($tool->image)){    
                $path = \Storage::disk('public')->exists($tool->image); 
                if ($path) {
                    \Storage::disk('public')->delete($tool->image);
                }
            }
            $tool->delete();
            return response()->json([
                'success' => true,
                'message' => __('messages.tool.deleted'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getAllToolsByModality($modalityID, $search='')
    {  
        Modality::findOrFail($modalityID);
        $alltools = Tool::where('modality_id', $modalityID)
        ->whereNull('deleted_at')
        ->whereNotIn('id', (ToolRequest::where(['user_id' => auth::user()->id, 'status' => 'pending'])->pluck('tool_id')))
        ->where(function ($query) use ($search) {
            $query->orWhere('tools.description', 'like', "%$search%")
            ->orWhere('tools.serial_no', 'like', "%$search%")
            ->orWhere('tools.product_no', 'like', "%$search%");
        })
        ->orderBy('description')
        ->with(['site', 'requestTools'])->get();
        
        $data = $alltools->map(function ($tool) {
            $expectedDelivery = 'N/A'; 
            $ownerName = 'N/A'; 
            if (!empty($tool->lastRequestTools)) { 
                $expectedDelivery = $tool->lastRequestTools->expected_return_date;
                $ownerName = $tool->lastRequestTools->user->name;
            }
            
            return [
                'id'=> $tool->id,
                'owner' => $ownerName,
                'description'=> $tool->description,
                'serial_no'=> $tool->serial_no,
                'product_no'=> $tool->product_no,
                'modality_id'=> $tool->modality_id,
                'calibration_date'=> $tool->calibration_date,
                'site_id'=> $tool->site_id,
                'site_address'=> !is_null($tool->site) ? $tool->site->address : "N/A",
                'status'=> $tool->status,
                'statusMSG'=> ($tool->status == 'busy' ?  __('common.expected') . " " .__('common.available'). " " . __('common.date'). " " . $expectedDelivery . "." : ''),
                'expected_delivery'=> $expectedDelivery,
                'notifyTools'=> ToolAvailabilityNotication::where(['user_id' => auth::user()->id, 'notified' => 0])->pluck('tool_id')->toArray(),
                'tool_condition'=> $tool->tool_condition,
                'toolColor' => $tool->tool_condition_status,
            ];
        });
        
        return $data;
    }

    public function getToolsByToolID($toolsIDS)
    {   
        $alltools = Tool::whereIn('id', $toolsIDS)
        ->whereNull('deleted_at')
        ->orderBy('description')
        ->with('site')->get();

        $data = $alltools->map(function ($tool) {
            $ownerName = 'N/A'; 
            if (!empty($tool->requestTools)) { 
                $ownerName = $tool->requestTools->user->name;
            }

            return [
                'id'=> $tool->id,
                'owner' => $ownerName,
                'description'=> $tool->description,
                'serial_no'=> $tool->serial_no,
                'product_no'=> $tool->product_no,
                'modality_id'=> $tool->modality_id,
                'calibration_date'=> $tool->calibration_date,
                'site_id'=> $tool->site_id,
                'site_address'=> !is_null($tool->site) ? $tool->site->address : "N/A",
                'status'=> $tool->status,
            ];
        });

        return $data;
    }

    public function sendCalibrationDateNotification(){
        $from = Carbon::parse(Carbon::now()->addDays(2))->format('Y-m-d') . ' 00:00:01';
        $to = Carbon::parse(Carbon::now()->addDays(2))->format('Y-m-d') . ' 23:59:59';

        $admin = User::findOrFail(1);
        $tools = Tool::with('modality')->whereBetween('calibration_date', [$from, $to])->get();

        event(new CalibrationDateProcessed([
            'tools' => $tools,
            'admin' => ['name' => $admin->name, 'email' => $admin->email],
            'calibrationDate' => Carbon::parse(Carbon::now()->addDays(2))->format('m/d/Y')
            ]
        ));

        return true;
    }
}