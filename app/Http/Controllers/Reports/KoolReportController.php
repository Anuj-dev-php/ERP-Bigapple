<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Reports\MyReport;

class KoolReportController extends Controller
{
     
    public function openkoolreport(Request $request){

        $report = new MyReport;
        $report->run(); 

        return view('reports.koolreportexamples',["report"=>$report]);

    }
}
