<?php
namespace App\Services\Admin;

use App\Models\Site;
use Illuminate\Support\Facades\DB;
use App\Models\ToolRequest;
use App\Models\ToolReturn;
use App\Models\Tool;
use Exception;

class SiteService
{
    public function getAllSitesAsJsonForDatatable($request)
    {   
        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        return Site::select('sites.id', 'cities.name AS cityName','provinces.name AS provinceName', 'sites.name', 'address', 'sites.province_id', 'sites.city_id', 'description', 'type', 'sites.created_at')
            ->Join('cities', 'sites.city_id', '=', 'cities.id')
            ->Join('provinces', 'sites.province_id', '=', 'provinces.id')
            ->when($name, function ($nameQuery) use ($name) {
            /**
             * if name present then add name filter
             */
                return $nameQuery->where('sites.name', 'like', "%$name%");
            })
             ->orderBy($sortBy, $orderBy)
            ->paginate($length)->toArray();
    }

    public function getAllSites()
    {
        return Site::all();
    }

    public function saveNewSite($request)
    {
        try {
            $site = new Site();

            $site->name = $request->siteName;
            $site->address = $request->siteAddress;
            $site->province_id = $request->province;
            $site->city_id = $request->city;
            $site->description = $request->desc;
            $site->type = $request->siteType;
          
            $site->save();
        return response()->json([
                'success' => true,
                'message' => __('messages.site.created'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getSite($site)
    {
       try{
            $site['city'] = $site->city->name;
            return response()->json([
                'success' => true,
                'data' => $site,
            ]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function updateSite($request)
    {
        try {
            $site = Site::findOrFail($request->siteId);

            $site->name = $request->siteName;
            $site->address = $request->siteAddress;
            $site->province_id = $request->province;
            $site->city_id = $request->city;
            $site->description = $request->desc;
            $site->type = $request->siteType;
          
            $site->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.site.updated'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }

    }

    public function deleteSite($site)
    {
        try {
            $siteCountRequest = ToolRequest::where('site_id', '=', $site->id)->count();
            $siteCountReturn = ToolReturn::where('site_id', '=', $site->id)->count();
            $siteCountInTool = Tool::where('site_id', '=', $site->id)->count();
            if ($siteCountRequest || $siteCountReturn || $siteCountInTool > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.site.notDeleted'),
                ]);
             }
            $site->delete();
            return response()->json([
                'success' => true,
                'message' => __('messages.site.deleted'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getAllSitesByCity($cityID)
    {   
        return Site::where('city_id', $cityID)
        ->whereNull('deleted_at')
        ->get();
    }
}