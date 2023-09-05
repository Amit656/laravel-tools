<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Helper\Helper;

use Illuminate\Http\Request;

use App\Services\Admin\SiteService;
use App\Http\Controllers\Controller;

use App\Services\Admin\ToolReturnService;
use App\Http\Requests\AdminToolReturnList;
use App\Http\Requests\AcceptReturnToolRequest;

class ReturnToolsController extends Controller
{   
    public $toolReturnService;
    public $siteService;

    public function __construct(
        ToolReturnService $toolReturnService,
        SiteService $siteService
        )
    {
        $this->toolReturnService = $toolReturnService;
        $this->siteService = $siteService;
    }

    public function index()
    {           
        $allSites = $this->siteService->getAllSites();

        return view('admin.pages.manage_tool_return', compact('allSites'));
    }

    public function returnToolList(Request $request)
    {           
        Helper::handleDataTableQuery($request, 'tool_returns.id');
        $allReturnTools = $this->toolReturnService->getAllRequestToolsAsJsonForDatatable($request);
        return Response::dataTableJson($allReturnTools, $request->draw);
    }

    public function acceptToolReturn(AcceptReturnToolRequest $request)
    {   
        return $this->toolReturnService->acceptToolReturn($request);
    }
}
