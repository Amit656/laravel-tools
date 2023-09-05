<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ToolsExport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CSVController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request) 
    {
        return Excel::download(new ToolsExport($request->search), 'tools.csv');
    }
}
