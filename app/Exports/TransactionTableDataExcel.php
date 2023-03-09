<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Controllers\Services\Function4FilterService;
use Illuminate\Support\Facades\Log; 

class TransactionTableDataExcel implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
  
    // $tablename,
    // ,$fieldfunctions
    public function __construct($headerfields,$datacollection){

        //  $function4filterservice=new Function4FilterService;

        $this->headerfields=$headerfields;

        $this->datacollection=$datacollection;

        // $this->fieldfunctions=$fieldfunctions;

        // $this->function4filter= $function4filterservice;
        // $this->function4filter->tablename=  $tablename;

    

    }


    public function collection()
    { 

        // $function4allvalues= $this->function4filter->getTableAllFunction4FieldValuesInArray();



        // $transaction_data=$this->datacollection;


        // $column_names=array_keys(  $this->headerfields);



        // $datacollection = collect([]);


        // $index=0;

        // $fieldfunctions=  $this->fieldfunctions;

        // foreach( $transaction_data as $single_transaction_data){
        

        //         $single_row=array();

        //         $column_index=0;

        //         foreach(  $column_names as   $column_name){ 
 
                
        //             if(  $fieldfunctions[$column_index]==4){

        //                 if(array_key_exists($single_transaction_data->{$column_name},$function4allvalues[$column_name])){

        //                     $showdata=  $function4allvalues[$column_name][$single_transaction_data->{$column_name}];
        //                 }
        //                 else{
        //                     $showdata=""; 
        //                 }

        //             }
        //                 else if(  $fieldfunctions[$column_index]==31 ||  $fieldfunctions[$column_index]==27 ||  $fieldfunctions[$column_index]==6 )
        //                 {
        //                     $showdata=date("d/m/Y",strtotime($single_transaction_data->{$column_name}));
        //                 }
        //                 else{
        //                     $showdata=$single_transaction_data->{$column_name}; 
        //                 }


        //             $single_row[$column_name]=$showdata;

        //             $column_index++;

        //         }

        //         $datacollection[ $index]=   $single_row; 
        //         $index++;
        // }
 
        // return   $datacollection;

        
        // return collect([ ['rohit','asfasf']]);


        return  $this->datacollection;
         

    }

    public function headings():array
    {
        return   array_values(     $this->headerfields);
 
    }


}
