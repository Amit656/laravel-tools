<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Admin\CityService;

class CommonRequestController extends Controller
{   
    public $cityService;

    public function __construct( 
        CityService $cityService
        )
    {
        $this->cityService = $cityService;
    }
    
    public function getcities(Request $request)
    {
        return $this->cityService->getAllCities($request->id);
    }
}
