<?php
namespace App\Services\Admin;

use App\Models\Modality;
use Illuminate\Http\Request;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Exception;

class ModalityService
{   
    public function getAllModalitiesAsJsonForDatatable($request)
    {   
        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        return Modality::select('id', 'name', 'created_at')
             ->when($name, function ($nameQuery) use ($name) {
            /**
             * if name present then add name filter
             */
                return $nameQuery->where('name', 'like', "%$name%");
            })
            ->orderBy($sortBy, $orderBy)
            ->paginate($length)->toArray();
    }

    public function getAllModalities()
    {
        return Modality::all();
    }

    public function saveNewModality($request)
    {
        try {
            $modality = new Modality();

            $modality->name = $request->modalityName;
          
            $modality->save();

            return response()->json([
                'success' => true,
                'message' => __('messages.modality.created'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getModalityById($modalityId)
    {
       return Modality::findOrFail($modalityId);
    }

    public function updateModality($request)
    {
        try {
            $modality = Modality::findOrFail($request->modalityId);

            $modality->name = $request->modalityName;
          
            $modality->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.modality.updated'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function deleteModality($modalityId)
    {

        try {
            if (Tool::where('modality_id', '=', $modalityId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.modality.notDeleted'),
                ]);
             }
           $modality = Modality::findOrFail($modalityId);

            $modality->delete();
            return response()->json([
                'success' => true,
                'message' => __('messages.modality.deleted'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

}