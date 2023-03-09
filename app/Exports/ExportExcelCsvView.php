<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection; 


class ExportExcelCsvView implements  FromView
{

    public function __construct( $view_page_name ,$data_array){

        $this->view_page_name=$view_page_name;
        $this->data_array=$data_array;
 
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public  function view(): View
    {
        return view(  $this->view_page_name,  $this->data_array );

    }
}
