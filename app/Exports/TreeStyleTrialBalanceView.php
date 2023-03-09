<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TreeStyleTrialBalanceView implements  FromView ,  ShouldAutoSize
{

    public function __construct($report_name,$name_of_company,$financial_year,$start_date,$end_date,$account_level,$account_ids,$show_foreign_currency,$total_opening_debitcredit_diff,$total_closing_debit_credit_balance_diff,$total_total_debit_credit_diff,$fcamt_total_opening_debitcredit_diff,$fcamt_total_total_debit_credit_diff,$fcamt_total_closing_debit_credit_balance_diff,$all_balances ,$alltotals,$open_childaccounts=false,$parent_account_name=NULL){

        $this->report_name=$report_name;
        $this->name_of_company=$name_of_company;
        $this->financial_year=$financial_year;
        $this->account_ids=$account_ids;
        $this->start_date=$start_date;
        $this->end_date=$end_date;
        $this->account_level=$account_level;
        $this->show_foreign_currency=$show_foreign_currency;
        $this->total_opening_debitcredit_diff=$total_closing_debit_credit_balance_diff;
        $this->total_closing_debit_credit_balance_diff=$total_closing_debit_credit_balance_diff;
        $this->total_total_debit_credit_diff=$total_total_debit_credit_diff;
        $this->fcamt_total_opening_debitcredit_diff=$fcamt_total_opening_debitcredit_diff;
        $this->fcamt_total_total_debit_credit_diff=$fcamt_total_total_debit_credit_diff; 
        $this->fcamt_total_closing_debit_credit_balance_diff=$fcamt_total_closing_debit_credit_balance_diff;
        $this->alltotals=$alltotals;
        $this->open_childaccounts=$open_childaccounts;
        $this->parent_account_name=$parent_account_name;
        $this->all_balances=$all_balances;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        
        return view('reports.downloadformats.tree_style_trial_balances_format',[ 'report_name'=>$this->report_name,'name_of_company'=>$this->name_of_company,'financial_year'=>$this->financial_year
       , 'start_date'=>$this->start_date,'end_date'=> $this->end_date,'account_level'=>  $this->account_level   ,'accounts_data'=>  $this->account_ids,'show_foreigncurrency'=>$this->show_foreign_currency ,
    'total_opening_debitcredit_diff'=>$this->total_opening_debitcredit_diff,
    'total_closing_debit_credit_balance_diff'=>$this->total_closing_debit_credit_balance_diff,
    'total_total_debit_credit_diff'=> $this->total_total_debit_credit_diff,
    'fcamt_total_opening_debitcredit_diff'=>$this->fcamt_total_opening_debitcredit_diff ,
    'fcamt_total_total_debit_credit_diff'=>$this->fcamt_total_total_debit_credit_diff ,
    'fcamt_total_closing_debit_credit_balance_diff'=>  $this->fcamt_total_closing_debit_credit_balance_diff ,
    'alltotals'=> $this->alltotals ,'open_childaccounts'=>  $this->open_childaccounts ,'parent_account_name'=>  $this->parent_account_name ,'all_balances'=> $this->all_balances
    ]);
    }
}
