<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Models\Tool;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Services\Admin\SiteService;
use App\Services\Admin\ToolService;
use App\Http\Controllers\Controller;
use App\Services\Admin\ModalityService;
use App\Http\Requests\ToolDeleteRequest;
use App\Http\Requests\CreateUpdateAdminToolRequest;

class ToolController extends Controller
{   

    public $toolService;
    public $siteService;
    public $modalityService;

    public function __construct(
        ToolService $toolService,
        SiteService $siteService,
        ModalityService $modalityService
        )
    {
        $this->toolService = $toolService;
        $this->siteService = $siteService;
        $this->modalityService = $modalityService;
    }

    public function index()
    {
        $allSites = $this->siteService->getAllSites();
        $allModalities = $this->modalityService->getAllModalities();
        return view('admin.pages.tools', compact('allSites', 'allModalities'));
    }

    public function list(Request $request)
    {   
        Helper::handleDataTableQuery($request, 'tools.id');
        $userRequest = $request->all();
        $toolData = $this->toolService->getAllToolsAsJsonForDatatable($request);
        
        return Response::dataTableJson($toolData, $request->draw);
    }

    /**
     * Function will create Tools
     *
     * @param CreateUpdateAdminToolRequest $request
     * @return void
     */
    public function store(CreateUpdateAdminToolRequest $request)
    {
        return $this->toolService->saveNewTool($request);
    }

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail(Tool $tool)
    {
        return $this->toolService->getTool($tool);
    }

    /**
     * Function will update the requested tool
     *
     * @param CreateUpdateAdminToolRequest $request
     *
     * @return void
     */
    public function update(CreateUpdateAdminToolRequest $request)
    {
        return $this->toolService->updateTool($request);
    }

    /**
     * Function will detete request tool
     *
     *
     * @return void
     */
    public function delete(ToolDeleteRequest $request)
    {
        return $this->toolService->deleteTool($request->validated());
    }


    /**
     * Get detail view
     *
     * @return void
     */
    public function viewDetail($toolId)
    {
        $data = $this->toolService->getToolById($toolId);
        $allSites = $this->siteService->getAllSites();
        $allModalities = $this->modalityService->getAllModalities();
        $detail = "true";
        return view('admin.pages.tools.form', compact('detail', 'data', 'allSites', 'allModalities'));
    }

    public function getToolsByModality(Request $request)
    {
        return $this->toolService->getAllToolsByModality($request->id, $request->search);
    }

    public function getToolsByToolID(Request $request)
    {   
        //$request->id will be an array
        return $this->toolService->getToolsByToolID($request->id);
    }
}
