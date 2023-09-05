<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ModalityService;
use App\Http\Requests\CreateUpdateModalityRequest;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Response;

class ModalityController extends Controller
{
    public $modalityService;

    public function __construct(ModalityService $modalityService)
    {
        $this->modalityService = $modalityService;
    }

    public function list(Request $request)
    {   
        Helper::handleDataTableQuery($request, 'modality.id');
        $userRequest = $request->all();
        $modalityData = $this->modalityService->getAllModalitiesAsJsonForDatatable($request);

        return Response::dataTableJson($modalityData, $request->draw);
    }

    /**
     * Function will create modality
     *
     * @param CreateUpdateModalityRequest $request
     * @return void
     */
    public function store(CreateUpdateModalityRequest $request)
    {
        return $this->modalityService->saveNewModality($request);
    }

    

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail($modalityId)
    {
        return $data = $this->modalityService->getModalityById($modalityId);
    }

    /**
     * Function will update the requested modality
     *
     * @param CreateUpdateModalityRequest $request
     *
     * @return void
     */
    public function update(CreateUpdateModalityRequest $request)
    {
        return $this->modalityService->updateModality($request);
    }

    /**
     * Function will detete request modality
     *
     *
     * @return void
     */
    public function delete($modalityId)
    {
        return $this->modalityService->deleteModality($modalityId);
    }

}
