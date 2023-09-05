<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\ToolRequestService;

use App\Helper\Helper;
use Response;

use App\Http\Requests\AcceptRequestToolRequest;
use App\Http\Requests\AdminToolRequestList;

class RequestToolsController extends Controller
{   
    public $toolRequestService;

    public function __construct(
        ToolRequestService $toolRequestService
        )
    {
        $this->toolRequestService = $toolRequestService;
    }

    public function requestToolList(Request $request)
    {           
        Helper::handleDataTableQuery($request, 'tool_requests.id');
        $userRequest = $request->all();
        $allRequestTools = $this->toolRequestService->getAllRequestToolsAsJsonForDatatable($request);

        return Response::dataTableJson($allRequestTools, $request->draw);
    }

    public function rejectToolRequest($requestToolID)
    {
        return $this->toolRequestService->rejectToolRequest($requestToolID);
    }

    public function acceptToolRequest(AcceptRequestToolRequest $request)
    {   
        return $this->toolRequestService->acceptToolRequest($request);
    }
}
