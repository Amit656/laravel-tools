<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ModalityService;
use App\Services\Admin\ProvinceService;
use App\Services\Admin\CityService;
use App\Services\Admin\SiteService;
use App\Services\Admin\ToolService;
use App\Services\Engineer\ToolRequestService;
use App\Http\Requests\CreateUpdateToolRequest;
use App\Helper\Helper;
use Response;

class RequestToolsController extends Controller
{   
    public $modalityService;
    public $provinceService;
    public $cityService;
    public $siteService;
    public $toolService;
    public $toolRequestService;

    public function __construct(
        ModalityService $modalityService, 
        ProvinceService $provinceService,
        CityService $cityService,
        SiteService $siteService,
        ToolService $toolService,
        ToolRequestService $toolRequestService
        )
    {
        $this->modalityService = $modalityService;
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
        $this->siteService = $siteService;
        $this->toolService = $toolService;
        $this->toolRequestService = $toolRequestService;
    }

    public function requestToolsView()
    {   
        $allModalities = $this->modalityService->getAllModalities();
        $allProvinces = $this->provinceService->getAllProvinces();
        $citiesData = $this->cityService->getAllCities(3152);//getting Eastern province saudi

        $allCities = $citiesData['data'];
        $allSites = $this->siteService->getAllSitesByCity(37432);//Ha'il city
        $allTools = $this->toolService->getAllToolsByModality($allModalities->first()->id);
        $allNotifyTools = $this->toolRequestService->notifyTools();

        return view('engineer.pages.request-tools', 
            compact('allModalities', 'allProvinces', 
                'allCities', 'allSites', 'allTools', 'allNotifyTools'));
    }

    public function store(CreateUpdateToolRequest $request)
    {   
        return $this->toolRequestService->saveToolRequest($request);
    }

    public function getToolsByRequestToolID(Request $request)
    {   
        return $this->toolRequestService->getCurrrentInHandTools($request->id, $request->search);
    }

    public function requestToolList(Request $request)
    {           
        Helper::handleDataTableQuery($request, 'tool_requests.id');
        $userRequest = $request->all();
        $allRequestTools = $this->toolRequestService->getAllRequestToolsAsJsonForDatatable($request);
        return Response::dataTableJson($allRequestTools, $request->draw);
    }

    public function notifyAtAvailability(Request $request)
    {   
        return $this->toolRequestService->notifyAtAvailability($request);
    }
}
