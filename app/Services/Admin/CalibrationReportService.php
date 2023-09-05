<?php
namespace App\Services\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\CalibrationReport;
use Illuminate\Support\Facades\DB;

class CalibrationReportService
{

    public function saveCalibrationReport($request)
    {
        try {
            $calibrationReport = new CalibrationReport(); 
            $calibrationReport->tool_id = $request->tool_id;
            $calibrationReport->calibrated_on = $request->calibrated_on ? Carbon::parse($request->calibrated_on)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            if($request->hasFile('report')){
                $file = $request->file('report');
                $fileName = $file->getClientOriginalName();
                $file->storeAs('calibration-report/' .$request->tool_id, $fileName);

                $calibrationReport->report = $fileName;
            }
            $calibrationReport->save();
            if($request->next_calibration_due_date){
                $calibrationReport->tool->update([
                    'calibration_date' => Carbon::parse($request->next_calibration_due_date)->format('Y-m-d'),
                ]);
            }
            $calibrationReport->tool->update([
                'tool_condition' => $request->tool_condition ?? "good",
            ]);
            return response()->json([
                'success' => true,
                'message' => __('messages.calibration_report.added'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getAllCalibrationReoprtsAsJsonForDatatable($request , $tool)
    {   
        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by'] ?? 'id';
        $orderBy = $request['order_by'] ?? 'asc';
        $length = $request['length']??10;

        return CalibrationReport::select('calibration_reports.*', 'tools.tool_id as toolId')
            ->where('calibration_reports.tool_id', $tool)
            ->Join('tools', 'calibration_reports.tool_id', '=', 'tools.id')
            ->when($name, function ($nameQuery) use ($name) {
            /**
             * if name present then add name filter
             */
                return $nameQuery->where('calibration_reports.calibrated_on', 'like', "%$name%");
            })
             ->orderBy($sortBy, $orderBy)
            ->paginate($length)->toArray();
    }

    public function downloadCalibrationReport($tool, $report)
    {   
        return \Storage::download('calibration-report/' .$tool. '/'. $report);
    }
}