<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PandLReportView implements  FromView ,  ShouldAutoSize
{
     public function __construct($all_accounts, $name_of_company,$financial_year,$show_foreigncurrency, $start_date_string, $end_date_string,$report_name,$total_expenses,$total_fcamt_expenses,$total_incomes,$total_fcamt_incomes,$report_type,$all_balances,$income_accounts,$expense_accounts ){
      
        $this->all_accounts=$all_accounts;
        $this->name_of_company=$name_of_company;
        $this->financial_year=$financial_year;
        $this->show_foreigncurrency=$show_foreigncurrency;
        $this->start_date_string= $start_date_string;
        $this->end_date_string=$end_date_string;
        $this->report_name=$report_name;
        $this->total_expenses=$total_expenses;
        $this->total_fcamt_expenses=$total_fcamt_expenses;
        $this->total_incomes=$total_incomes;
        $this->total_fcamt_incomes=$total_fcamt_incomes;
        $this->report_type=$report_type;
        $this->income_accounts=$income_accounts;
        $this->expense_accounts=$expense_accounts; 
        $this->all_balances=$all_balances;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function  view(): View
    {
             
     return view("reports.downloadformats.treestyle_pandl_format",[  'all_accounts'=>$this->all_accounts,'name_of_company'=>$this->name_of_company ,'financial_year'=>$this->financial_year,'show_foreigncurrency'=>$this->show_foreigncurrency,'start_date'=> $this->start_date_string ,'end_date'=> $this->end_date_string,'report_name'=>$this->report_name,'total_expenses'=>$this->total_expenses,'total_fcamt_expenses'=> $this->total_fcamt_expenses,'total_incomes'=>$this->total_incomes,'total_fcamt_incomes'=>$this->total_fcamt_incomes,'report_type'=>$this->report_type,'income_accounts'=>$this->income_accounts,'expense_accounts'=>$this->expense_accounts,'all_balances'=>$this->all_balances]);
  

    }
}
