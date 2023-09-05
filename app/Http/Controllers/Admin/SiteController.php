<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Services\Admin\ModalityService;
use App\Services\Admin\ProvinceService;
use App\Services\Admin\CityService;
use App\Services\Admin\SiteService;
use App\Services\Admin\ToolService;
use App\Http\Requests\CreateUpdateSiteRequest;
use App\Models\Site;
use App\Helper\Helper;
use Response;

class SiteController extends Controller
{   

    public $modalityService;
    public $provinceService;
    public $cityService;
    public $siteService;
    public $toolService;

    public function __construct(
        ModalityService $modalityService, 
        ProvinceService $provinceService,
        CityService $cityService,
        SiteService $siteService,
        ToolService $toolService
        )
    {
        $this->modalityService = $modalityService;
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
        $this->siteService = $siteService;
        $this->toolService = $toolService;
    }

    public function index()
    {
        $allProvinces = $this->provinceService->getAllProvinces();
        $cityData = $this->cityService->getAllCities(3152);//getting Eastern province saudi
        
        $allCities = $cityData['data'];
        return view('admin.pages.sites', compact('allProvinces', 
                'allCities'));
    }

    public function list(Request $request)
    {   
        Helper::handleDataTableQuery($request, 'sites.id');
        $userRequest = $request->all();
        $siteData = $this->siteService->getAllSitesAsJsonForDatatable($request);
       
        return Response::dataTableJson($siteData, $request->draw);
    }

    /**
     * Function will create sites
     *
     * @param CreateUpdateSiteRequest $request
     * @return void
     */
    public function store(CreateUpdateSiteRequest $request)
    {
        return $this->siteService->saveNewSite($request);
    }

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail(Site $site)
    {
        
        return $this->siteService->getSite($site);
    }

    /**
     * Function will update the requested sites
     *
     * @param CreateUpdateSiteRequest $request
     *
     * @return void
     */
    public function update(CreateUpdateSiteRequest $request)
    {
        return $this->siteService->updateSite($request);
    }

    /**
     * Function will detete request sites
     *
     *
     * @return void
     */
    public function delete(Site $site)
    {
        return $this->siteService->deleteSite($site);
    }

    public function getSites(Request $request)
    {
        return $this->siteService->getAllSitesByCity($request->id);
    }
}
