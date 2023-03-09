<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection; 

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GeneralLedgerView implements FromView ,  ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct( $name_of_company,$financial_year, $startEndDate,$selected_costcenter,$selected_division  , $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,$report_name="General Ledger"){


        $this->name_of_company=$name_of_company;

        $this->financial_year=$financial_year;

        $this->startEndDate=$startEndDate;

        $this->selected_costcenter=$selected_costcenter;

        $this->selected_division=$selected_division;

        $this->ChequeNo=$ChequeNo;

        $this->ChequeStatus=$ChequeStatus;

        $this->ClearingDate=$ClearingDate;

        $this->CostCentre=$CostCentre;

        $this->division=$division;

        $this->Executive=$Executive; 

        $this->ForeignCurrency=$ForeignCurrency;

        $this->account_ids=$account_ids;
 

        $this->no_of_additional_columns=$no_of_additional_columns;
        
        $this->report_name=$report_name;
 

    }
    public function view(): View
    {

        return view('reports.downloadformats.general_ledger_format', [ 'name_of_company'=>$this->name_of_company ,'financial_year'=>$this->financial_year   ,'report_name'=>  $this->report_name,    'companyDates' => $this->startEndDate,'selected_costcenter'=>$this->selected_costcenter,'selected_division'=>$this->selected_division,
        "chequeno"=>    $this->ChequeNo,'chequestatus'=> $this->ChequeStatus,'clearingdate'=>  $this->ClearingDate,'costcentre'=> $this->CostCentre ,
        'division'=> $this->division ,'executive'=> $this->Executive ,'foreigncurrency'=> $this->ForeignCurrency, 'accounts_data'=>   $this->account_ids,'no_of_additional_columns'=> $this->no_of_additional_columns ]);
    }
    





}
