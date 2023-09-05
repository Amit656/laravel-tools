<?php

namespace App\Exports;

use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ToolsExport implements FromView
{
    protected $search;

    function __construct($search) {
            $this->search = $search;
    }

    public function view(): View
    {
        $name = $this->search??''; 
        $tools = Tool::with('calibrationReport')->select('tools.*', 'modality.name AS modalityName', 'sites.name AS siteName', 
        DB::raw('datediff(`calibration_date`, NOW()) as due_in_days'))
                ->Join('modality', 'tools.modality_id', '=', 'modality.id')
                ->Join('sites', 'tools.site_id', '=', 'sites.id')
                ->when($this->search, function ($nameQuery) use ($name) {
                /**
                * if name present then add name filter
                */
                return $nameQuery->where('tools.description', 'like', "%$name%")
                                ->orWhere('tools.tool_id', 'like', "%$name%")
                                ->orWhere('tools.serial_no', 'like', "%$name%")
                                ->orWhere('tools.product_no', 'like', "%$name%")
                                ->orWhere('modality.name', 'like', "%$name%");
                })
                ->orderby('tools.id', 'asc')
                ->get();

        return view('admin.export.tools', [
            'tools' => $tools,
        ]);
    }
}
