<?php
namespace App\Services\Admin;

use App\Models\Province;
use Illuminate\Support\Facades\DB;
use Exception;

class ProvinceService
{
    public function getAllProvinces()
    {   
        return Province::where('country_id', 191)->orderBy('name')->get();
    }

    public function getAllProvincesAsJsonForDatatable($request)
    {   
        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        return Province::select('id', 'name', 'created_at')
            ->where('country_id', 191)
            ->when($name, function ($nameQuery) use ($name) {
            /**
             * if name present then add name filter
             */
                return $nameQuery->where('name', 'like', "%$name%");
            })
            ->orderBy($sortBy, $orderBy)
            ->paginate($length)->toArray();
    }

    public function saveNewProvince($request)
    {
        try {
            $province = new Province();

            $province->name = $request->provinceName;
            $province->country_id  = '191';
            $province->save();

            return response()->json([
                'success' => true,
                'message' => __('messages.province.created'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getProvince($province)
    {
       try{
            return response()->json([
                'success' => true,
                'data' => $province,
            ]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function updateProvince($request)
    {
        try {
            $province = Province::findOrFail($request->provinceId);

            $province->name = $request->provinceName;
            $province->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.province.updated'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }

    }

    public function deleteProvince($province)
    {
        try {
            $citieCount = $province->cities->count();
            $siteCount = $province->sites->count();
            if ($citieCount || $siteCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.province.notDeleted'),
                ]);
             }
            $province->delete();
            return response()->json([
                'success' => true,
                'message' => __('messages.province.deleted'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }
}