<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\CityService;
use App\Services\Admin\ProvinceService;
use App\Http\Requests\CreateUpdateCityRequest;
use App\Models\City;
use App\Helper\Helper;
use Response;

class CityController extends Controller
{
    public $cityService;
    public $provinceService;

    public function __construct(CityService $cityService, ProvinceService $provinceService)
    {
        $this->cityService = $cityService;
        $this->provinceService = $provinceService;
    }

    public function index()
    {       
        $allProvinces = $this->provinceService->getAllProvinces();
        return view('admin.pages.city', compact('allProvinces'));

    }

    public function list(Request $request)
    {   
        Helper::handleDataTableQuery($request, 'cities.id');
        $userRequest = $request->all();
        $cityData = $this->cityService->getAllCitiesAsJsonForDatatable($request);
       
        return Response::dataTableJson($cityData, $request->draw);
    }

    /**
     * Function will create city
     *
     * @param CreateUpdateCityRequest $request
     * @return void
     */
    public function store(CreateUpdateCityRequest $request)
    {
        return $this->cityService->saveNewCity($request);
    }

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail(City $city)
    {
        
        return $this->cityService->getCity($city);
    }

    /**
     * Function will update the requested city
     *
     * @param CreateUpdateCityRequest $request
     *
     * @return void
     */
    public function update(CreateUpdateCityRequest $request)
    {
        return $this->cityService->updateCity($request);
    }

    /**
     * Function will detete request city
     *
     *
     * @return void
     */
    public function delete(City $city)
    {
        return $this->cityService->deleteCity($city);
    }

}
