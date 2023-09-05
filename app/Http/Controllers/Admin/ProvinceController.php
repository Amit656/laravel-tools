<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ProvinceService;
use App\Http\Requests\CreateUpdateProvinceRequest;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Helper\Helper;
use Response;

class ProvinceController extends Controller
{
    public $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function list(Request $request)
    {   
        Helper::handleDataTableQuery($request, 'provinces.id');
        $userRequest = $request->all();
        $provinceData = $this->provinceService->getAllProvincesAsJsonForDatatable($request);

        return Response::dataTableJson($provinceData, $request->draw);
    }

    /**
     * Function will create Province
     *
     * @param CreateUpdateProvinceRequest $request
     * @return void
     */
    public function store(CreateUpdateProvinceRequest $request)
    {
        return $this->provinceService->saveNewProvince($request);
    }

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail(Province $province)
    {
        return $this->provinceService->getProvince($province);
    }

    /**
     * Function will update the requested Province
     *
     * @param CreateUpdateProvinceRequest $request
     *
     * @return void
     */
    public function update(CreateUpdateProvinceRequest $request)
    {
        return $this->provinceService->updateProvince($request);
    }

    /**
     * Function will detete request Province
     *
     *
     * @return void
     */
    public function delete(Province $province)
    {
        return $this->provinceService->deleteProvince($province);
    }
}
