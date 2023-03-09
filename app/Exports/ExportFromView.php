<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection; 

class ExportFromView implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function __construct($names){
        $this->names=$names;
    }
    public function view( ): View
    {

        // $names=new Collection([
        //     ['fname'=>'Rohit','lname'=>'Kumar'],
        //     ['fname'=>'Akshay','lname'=>'Kumar']
        // ]);

        return view('reports.downloadformats.downloadpdf', [
            'names' => $this->names
        ]);
    }
}
