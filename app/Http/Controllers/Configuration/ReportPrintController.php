<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportPrintController extends Controller
{
    

    public function testreportprint(){

     
        return view('printreports.testreport');

    }
}
