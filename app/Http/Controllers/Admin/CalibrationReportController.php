<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Models\Tool;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\CalibrationReportService;
use App\Http\Requests\CreateCalibrationToolRequest;

class CalibrationReportController extends Controller
{
    public $calibrationReportService;

    public function __construct(CalibrationReportService $calibrationReportService)
    {
        $this->calibrationReportService = $calibrationReportService;
    }

    public function index(Tool $tool)
    {       
        return view('admin.pages.calibration_report', compact('tool'));

    }

    public function list(Request $request, $tool)
    {   
        Helper::handleDataTableQuery($request, 'calibration_reports.id');
        $calibrationReportData = $this->calibrationReportService->getAllCalibrationReoprtsAsJsonForDatatable($request, $tool);
       
        return Response::dataTableJson($calibrationReportData, $request->draw);
    }

    /**
     * Function will create Calibration Report
     *
     * @param CreateCalibrationToolRequest $request
     * @return void
     */
    public function store(CreateCalibrationToolRequest $request)
    {
        return $this->calibrationReportService->saveCalibrationReport($request);
    }

    /**
     * Function will download Calibration Report
     *
     * @return void
     */
    public function download($tool, $report)
    {
        return $this->calibrationReportService->downloadCalibrationReport($tool, $report);
    }
}
