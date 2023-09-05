<?php
namespace App\Services\Admin;

use App\Models\City;
use Illuminate\Support\Facades\DB;
use Exception;

class CityService
{
    public function getAllCities($provinceID)
    {   
        //for now we set india can change later on
        $cities = City::where('province_id', $provinceID)->orderBy('name')->get();

        if (count($cities) > 0) {
            return ['data' => $cities];
        }else{
            return ['data' => [], 'message' => __('common.no_city')];
        }
    }

    public function getAllCitiesAsJsonForDatatable($request)
    {   
        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        return City::select('cities.id', 'cities.name','provinces.name AS provinceName', 'cities.created_at')
            ->where('provinces.country_id', 191)
            ->Join('provinces', 'cities.province_id', '=', 'provinces.id')
            ->when($name, function ($nameQuery) use ($name) {
            /**
             * if name present then add name filter
             */
                return $nameQuery->where('cities.name', 'like', "%$name%");
            })
             ->orderBy($sortBy, $orderBy)
            ->paginate($length)->toArray();
    }

    public function saveNewCity($request)
    {
        try {
            $city = new City();

            $city->name = $request->cityName;
            $city->province_id = $request->province;
          
            $city->save();
        return response()->json([
                'success' => true,
                'message' => __('messages.city.created'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getCity($city)
    {
       try{
            return response()->json([
                'success' => true,
                'data' => $city,
            ]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function updateCity($request)
    {
        try {
            $city = City::findOrFail($request->cityId);

            $city->name = $request->cityName;
            $city->province_id = $request->province;
          
            $city->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.city.updated'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }

    }

    public function deleteCity($city)
    {
        try {
            $siteCount = $city->sites->count();
            
            if ($siteCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.city.notDeleted'),
                ]);
             }
            $city->delete();
            return response()->json([
                'success' => true,
                'message' => __('messages.city.deleted'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }
}