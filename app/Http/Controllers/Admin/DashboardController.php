<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\SiteService;

use App\Services\Admin\ToolService;
use App\Services\Admin\UserService;
use App\Http\Controllers\Controller;
use App\Services\Admin\ModalityService;
use App\Services\Admin\ToolReturnService;
use App\Services\Admin\ToolRequestService;

class DashboardController extends Controller
{   

    public $modalityService;
    public $userService;
    public $siteService;
    public $toolService;
    public $toolRequestService;
    public $toolReturnService;

    public function __construct(
        ModalityService $modalityService, 
        UserService $userService, 
        SiteService $siteService,
        ToolService $toolService,
        ToolRequestService $toolRequestService,
        ToolReturnService $toolReturnService
        )
    {
        $this->modalityService = $modalityService;
        $this->userService = $userService;
        $this->siteService = $siteService;
        $this->toolService = $toolService;
        $this->toolRequestService = $toolRequestService;
        $this->toolReturnService = $toolReturnService;
    }

    public function index()
    {
        $modalities = count($this->modalityService->getAllModalities());
        $users = count($this->userService->getAllUsers());
        $sites = count($this->siteService->getAllSites());
        $tools = count($this->toolService->getAllTools());
        $requestTools = count($this->toolRequestService->getAllRequestTools());
        $returnTools = count($this->toolReturnService->getAllReturnTools());
        return view('admin.pages.dashboard', compact('modalities', 'users', 'sites', 'tools', 'requestTools', 'returnTools'));
    }
}
