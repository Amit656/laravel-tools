<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Engineer\ToolRequestService;
use App\Services\Engineer\ToolReturnService;
use App\Http\Requests\CreateUpdateToolReturn;
use App\Helper\Helper;
use Response;

class ReturnToolsController extends Controller
{   
    public $toolRequestService;
    public $toolReturnService;

    public function __construct(
        ToolRequestService $toolRequestService,
        ToolReturnService $toolReturnService
        )
    {
        $this->toolRequestService = $toolRequestService;
        $this->toolReturnService = $toolReturnService;
    }

    public function returnToolsView(){
        $allCurrrentInHandTools = $this->toolRequestService->getCurrrentInHandTools();

        return view('engineer.pages.return-tools', 
            compact('allCurrrentInHandTools'));
    }

    public function store(CreateUpdateToolReturn $request)
    {   
        return $this->toolReturnService->saveToolReturn($request);
    }

    public function returnToolList(Request $request)
    {   
        Helper::handleDataTableQuery($request, 'tool_returns.id');
        $userRequest = $request->all();
        $allRequestTools = $this->toolReturnService->getAllReturnedToolsAsJsonForDatatable($request);
        return Response::dataTableJson($allRequestTools, $request->draw);
    }
}
