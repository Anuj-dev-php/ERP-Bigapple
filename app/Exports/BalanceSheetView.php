<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection; 


class BalanceSheetView implements FromView
{
 
    public function __construct($data_array){

        $this->data_array=$data_array;
 
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view( ): View
    {  
        return view("reports.downloadformats.balance_sheet_format",  $this->data_array );
    }
}
