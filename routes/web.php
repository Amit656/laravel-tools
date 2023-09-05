<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CSVController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CommonRequestController;
use App\Http\Controllers\Admin\ModalityController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Engineer\ReturnToolsController;

use App\Http\Controllers\Engineer\RequestToolsController;
use App\Http\Controllers\Admin\CalibrationReportController;
use App\Http\Controllers\Admin\ReturnToolsController as AdminReturnToolsController;
use App\Http\Controllers\Admin\RequestToolsController as AdminRequestToolsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function() {
    return view('auth.login');
});

Route::group(['prefix' => 'engineer/', 'middleware' => ['auth']],function(){

    Route::get('dashboard', function() {
        return view('engineer.pages.dashboard');
    })->name('engineer-dashboard');

    Route::get('request-tools', [RequestToolsController::class, 'requestToolsView']);
    Route::post('request-tools/store', [RequestToolsController::class, 'store']);
    Route::POST('request-tools/notify-availability', [RequestToolsController::class, 'notifyAtAvailability']);

    Route::post('get-requested-tools-by-id', [RequestToolsController::class, 'getToolsByRequestToolID']);

    Route::get('history-request-tools', function() {
        return view('engineer.pages.request-tools-history');
    });

    Route::get('history-return-tools', function() {
        return view('engineer.pages.return-tools-history');
    });

    Route::get('return-tools', [ReturnToolsController::class, 'returnToolsView']);
    Route::post('return-tools/store', [ReturnToolsController::class, 'store']);

    Route::get('history-request-tools-list', [RequestToolsController::class, 'requestToolList']);
    Route::get('history-return-tools-list', [ReturnToolsController::class, 'returnToolList']);
});

Route::group(['prefix' => 'admin/', 'middleware' => ['auth'], 'namespace' => 'App\Http\Controllers\Admin'], function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');

    // Manage Users
    
    Route::get('/users/{role}', [UserController::class, 'index'])->name('users');
    Route::get('/users/{role}/list', [UserController::class, 'list'])->name('displayUsers');
    Route::post('/users/create', [UserController::class, 'store'])->name('storeUsers');
    Route::get('/users/getinfo/{user}', [UserController::class, 'getDetail'])->name('editUsers');
    Route::post('/users/update', [UserController::class, 'update'])->name('updateUsers');
    Route::delete('/users/delete/{user_id}', [UserController::class, 'delete'])->name('deleteUsers');

    // Manage Tools
    
    Route::get('/tools', [ToolController::class, 'index'])->name('tools');
    Route::get('/tools/list', [ToolController::class, 'list'])->name('displayTools');
    Route::post('/tools/create', [ToolController::class, 'store'])->name('storeTools');
    Route::get('/tools/getinfo/{tool}', [ToolController::class, 'getDetail'])->name('editTools');
    Route::post('/tools/update', [ToolController::class, 'update'])->name('updateTools');
    Route::delete('/tools/delete/{toolId}', [ToolController::class, 'delete'])->name('deleteTools');

    // Manage Tool Requests
    Route::get('tool-requests', function() {
        return view('admin.pages.manage_tool_requests');
    })->name('toolRequests');

    Route::get('/tool-requests/list', [AdminRequestToolsController::class, 'requestToolList'])->name('displayToolRequests');
    Route::post('/tool-requests/reject/{toolRequest}', [AdminRequestToolsController::class, 'rejectToolRequest'])->name('rejectToolRequest');
    Route::post('/tool-requests/accept', [AdminRequestToolsController::class, 'acceptToolRequest'])->name('acceptToolRequest');

    // Manage Tool Return
    Route::get('/tool-return', [AdminReturnToolsController::class, 'index'])->name('toolReturn');
    Route::get('/tool-return/list', [AdminReturnToolsController::class, 'returnToolList'])->name('displayToolReturn');
    Route::post('/tool-return/accept', [AdminReturnToolsController::class, 'acceptToolReturn'])->name('acceptToolReturn');

    // Manage Sites
    
    Route::get('/sites', [SiteController::class, 'index'])->name('sites');
    Route::get('/sites/list', [SiteController::class, 'list'])->name('displaySites');
    Route::post('/sites/create', [SiteController::class, 'store'])->name('storeSites');
    Route::get('/sites/getinfo/{site}', [SiteController::class, 'getDetail'])->name('editSites');
    Route::post('/sites/update', [SiteController::class, 'update'])->name('updateSites');
    Route::delete('/sites/delete/{site}', [SiteController::class, 'delete'])->name('deleteSites');

    // Manage Modality

    Route::get('modalities', function() {
        return view('admin.pages.modality');
    })->name('modality');
    
    Route::get('/modality/list', [ModalityController::class, 'list'])->name('displayModality');
    Route::post('/modality/create', [ModalityController::class, 'store'])->name('storeModality');
    Route::get('/modality/edit/{id}', [ModalityController::class, 'getDetail'])->name('editModality');
    Route::post('/modality/update', [ModalityController::class, 'update'])->name('updateModality');
    Route::delete('/modality/delete/{id}', [ModalityController::class, 'delete'])->name('deleteModality');

    // Manage City
    
    Route::get('/cities', [CityController::class, 'index'])->name('city');
    Route::get('/city/list', [CityController::class, 'list'])->name('displayCity');
    Route::post('/city/create', [CityController::class, 'store'])->name('storeCity');
    Route::get('/city/getinfo/{city}', [CityController::class, 'getDetail'])->name('editCity');
    Route::post('/city/update', [CityController::class, 'update'])->name('updateCity');
    Route::delete('/city/delete/{city}', [CityController::class, 'delete'])->name('deleteCity');

    // Manage Provinces

    Route::get('provinces', function() {
        return view('admin.pages.provinces');
    })->name('provinces');
    
    Route::get('/provinces/list', [ProvinceController::class, 'list'])->name('displayProvinces');
    Route::post('/provinces/create', [ProvinceController::class, 'store'])->name('storeProvinces');
    Route::get('/provinces/edit/{province}', [ProvinceController::class, 'getDetail'])->name('editProvinces');
    Route::post('/provinces/update', [ProvinceController::class, 'update'])->name('updateProvinces');
    Route::delete('/provinces/delete/{province}', [ProvinceController::class, 'delete'])->name('deleteProvinces');

    // Manage Calibration Report
    
    Route::get('/calibration-report/{tool}', [CalibrationReportController::class, 'index'])->name('calibrationReport');
    Route::post('/calibration-report/create', [CalibrationReportController::class, 'store'])->name('storeCalibrationReport');
    Route::get('/calibration-report/list/{tool}', [CalibrationReportController::class, 'list'])->name('displayCalibrationReport');
    Route::get('/calibration-report/download/{tool}/{report}', [CalibrationReportController::class, 'download'])->name('downloadCalibrationReport');

    // Export Tools

    Route::get('export', [CSVController::class, 'export'])->name('export');
    
});

//common routes

Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::get('profile/getInfo', [ProfileController::class, 'getDetail'])->name('getProfile');
Route::post('change/password', [ProfileController::class, 'changePassword'])->name('changePassword');
Route::post('change/image', [ProfileController::class, 'changeProfileImage'])->name('changeProfileImage');
Route::post('profile/update', [ProfileController::class, 'update'])->name('updateProfile');
Route::post('get-tools-by-modality', [ToolController::class, 'getToolsByModality']);
Route::post('get-tools-by-id', [ToolController::class, 'getToolsByToolID']);
Route::post('get-cities', [CommonRequestController::class, 'getCities']);
Route::post('get-sites', [SiteController::class, 'getSites']);
