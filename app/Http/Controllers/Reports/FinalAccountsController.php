<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\CostCentre;
use App\Models\Department;
use App\Http\Controllers\Services\ReportService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCustomer;
use App\Models\Customer;
use App\Models\Division;
use Illuminate\Support\Facades\Cache;
use  App\Models\Company;
use Illuminate\Support\Facades\Log;
use PDF;
use Excel;
use App\Exports\TreeStyleTrialBalanceView;
use App\Models\TblAuditData;
use App\Models\TableMaster;
use File;
use ZipArchive;
use App\Exports\PandLReportView;
use App\Exports\BalanceSheetView;

class FinalAccountsController extends Controller
{
     protected $reportservice;

     public function __construct(ReportService $rservice){

      $this->reportservice= $rservice;


     }
    public function reportFreeStyleTrialBalances($companyname,$account_id=NULL,$level_no=NULL ,Request $request){

        $accounts=Account::where('Parent2',0)->orderby('ACName','asc')->select('ACName as account_name','Id as id','G-A as ga')->get();

        $costcenters=CostCentre::all();

        $divisions=Division::orderby('division','asc')->pluck('division','Id')->toArray(); 

        $showzeros=(empty($request->showzeros)?0:1);

        $show_foreigncurrency=(empty($request->showforeigncurrency)?0:1);

        $open_childaccounts=false;

        $selected_account_level=(isset($request->selected_account_level)?$request->selected_account_level:"");

        $cost_center= (isset($request->cost_center)?$request->cost_center:"");

        $division= (isset($request->division)?$request->division:"");

        $single_account_name='';   
        $account_tree_data=array();

        $balances= array();
        $child_balances=array();
        $firstlevel_accountids_data=array();

        $selected_accounts=array();

        $companyname = Session::get('company_name');
    
        $startEndDate = Company::getStartEndDate($companyname);

        $name_of_company= $startEndDate ->comp_name;

        $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ".date('d/m/Y',strtotime($startEndDate->fe_date));
  
        $alltotals=array();
      if($request->method()=="POST") {
 

        $start_date_string=$request->start_date;

        $end_date_string=$request->end_date;

        $division=$request->division;
 
        $start_date_array=explode('-', $start_date_string);

        $end_date_array=explode('-',$end_date_string);


        $date_start= implode('-',array_reverse($start_date_array));

        $date_end=implode('-',array_reverse($end_date_array)); 
        $this->reportservice->start_date=  $date_start;
        $this->reportservice->end_date=  $date_end;
        $this->reportservice->cost_center= $cost_center;
        $this->reportservice->division=   $division;  

        $this->reportservice->add_subtotals=true;
        $this->reportservice->show_foreign_currency=  $show_foreigncurrency; 
        $this->reportservice->company_name= $companyname;
        $this->reportservice->user_id=Auth::user()->id;
        $this->reportservice->setAccountTreeData();
        $this->reportservice->calculateAccountWiseTotalsByQuery(); 
        $this->reportservice->getAllAccountTotals();  
 
        $firstlevel_accountids=Account::where('Parent2',0)->pluck('Id')->toArray();
 

        foreach(   $firstlevel_accountids as   $selected_account){
 
          $this->reportservice->account_id= $selected_account;
          $result= $this->reportservice->getAccountTotals();
 
            if(   $showzeros==0 && $result['opening_debitbalance']==0 && $result['opening_creditbalance']==0 && $result['total_debit']==0 && $result['total_credit']==0 && $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
              continue;
            }  


            array_push(   $balances,$result);


        } 
 
        $total_opening_debitbalance=array_sum(array_column($balances,'opening_debitbalance'));

         array_push(  $alltotals,  $total_opening_debitbalance);
        

        $total_opening_creditbalance=array_sum(array_column($balances,'opening_creditbalance'));

        array_push(  $alltotals,   $total_opening_creditbalance);
        

        $total_opening_debitcredit_diff=$total_opening_debitbalance-$total_opening_creditbalance;

        $total_total_debit=array_sum(array_column($balances,'total_debit'));
        
        array_push(  $alltotals,    $total_total_debit);
 
        $total_total_credit=array_sum(array_column($balances,'total_credit'));
        
        array_push(  $alltotals,  $total_total_credit);

        $total_total_debit_credit_diff=   $total_total_debit-  $total_total_credit;
 

        $total_closing_debit_balance=array_sum(array_column($balances,'closing_debit_balance'));

        array_push(  $alltotals,      $total_closing_debit_balance);
        
        $total_closing_credit_balance=array_sum(array_column($balances,'closing_credit_balance'));
        
        array_push(  $alltotals,     $total_closing_credit_balance);

        $total_closing_debit_credit_balance_diff=    $total_closing_debit_balance- $total_closing_credit_balance;


        
        $fcamt_total_opening_debitbalance=array_sum(array_column($balances,'fcamt_opening_debitbalance'));
        
        array_push(  $alltotals,    $fcamt_total_opening_debitbalance);

        $fcamt_total_opening_creditbalance=array_sum(array_column($balances,'fcamt_opening_creditbalance'));
 
        array_push(  $alltotals,     $fcamt_total_opening_creditbalance);

        $fcamt_total_opening_debitcredit_diff=$fcamt_total_opening_debitbalance-$fcamt_total_opening_creditbalance;

        $fcamt_total_total_debit=array_sum(array_column($balances,'fcamt_total_debit'));

        array_push(  $alltotals,   $fcamt_total_total_debit);
        
        $fcamt_total_total_credit=array_sum(array_column($balances,'fcamt_total_credit'));
 
        array_push(  $alltotals, $fcamt_total_total_credit);

        $fcamt_total_total_debit_credit_diff=   $fcamt_total_total_debit-  $fcamt_total_total_credit;
 

        $fcamt_total_closing_debit_balance=array_sum(array_column($balances,'fcamt_closing_debit_balance'));

        array_push(  $alltotals,   $fcamt_total_closing_debit_balance);
        
        $fcamt_total_closing_credit_balance=array_sum(array_column($balances,'fcamt_closing_credit_balance'));
 
        array_push(  $alltotals,  $fcamt_total_closing_credit_balance);

        $fcamt_total_closing_debit_credit_balance_diff=    $fcamt_total_closing_debit_balance- $fcamt_total_closing_credit_balance;
  
        if(!empty($selected_account_level)){
          $this->reportservice->account_level= $selected_account_level; 
          $this->reportservice->parent_account_ids=  $firstlevel_accountids;
          $child_accounts= $this->reportservice->getAccountLevelAccountIds(); 
           $this->reportservice->account_ids=   $child_accounts; 
        }
        else{
         
          $this->reportservice->account_ids= array();
        }

       

        $child_accounts=$this->reportservice->getAccountsSequenctially();
      
        $selected_accounts=array();
 
        $child_balances=array();
  
        foreach(  $child_accounts as   $child_account){
 
          $this->reportservice->account_id= $child_account;
          $result= $this->reportservice->getAccountTotals();
 
            if(   $showzeros==0 && $result['opening_debitbalance']==0 && $result['opening_creditbalance']==0 && $result['total_debit']==0 && $result['total_credit']==0 && $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
              continue;
            } 

            $child_balances[ $child_account]=$result; 

            array_push($selected_accounts,$child_account);

        }
        $account_tree_data=$this->reportservice->account_tree_data;

        $account_level=1;

        if(!empty(Auth::user()->id."_tree_style_trial_balances_input")){
          Cache::forget(Auth::user()->id."_tree_style_trial_balances_input");
        }

        $treestyle_trial_balances_input=array("all_accounts"=>$selected_accounts,"all_balances"=>$child_balances  ,'start_date'=> $start_date_string,'end_date'=>$end_date_string,'cost_center'=>$cost_center,'division'=>$division,'show_foreign_currency'=> $show_foreigncurrency  ,
        'show_zeros'=>$showzeros,
        'total_opening_debitcredit_diff'=>$total_opening_debitcredit_diff ,
        'total_total_debit_credit_diff'=>$total_total_debit_credit_diff ,
        'total_closing_debit_credit_balance_diff'=>$total_closing_debit_credit_balance_diff,
        'fcamt_total_opening_debitcredit_diff'=>$fcamt_total_opening_debitcredit_diff ,
        'fcamt_total_total_debit_credit_diff'=>$fcamt_total_total_debit_credit_diff ,
        'fcamt_total_closing_debit_credit_balance_diff'=>$fcamt_total_closing_debit_credit_balance_diff ,
        'selected_account_level'=>$selected_account_level
        ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,'alltotals'=>$alltotals
      );

 
 
        Cache::put(Auth::user()->id."_tree_style_trial_balances_input",json_encode($treestyle_trial_balances_input));

  
      }
      else if(!empty($account_id) && !empty($level_no)){
 
       $single_account_name= Account::where('Id',$account_id)->value('ACName');

        $start_date_string="";

        $end_date_string="";

        $selected_accounts=array(); 
 
        $this->reportservice->add_subtotals=true; 
        $this->reportservice->show_foreign_currency=false; 

        $treestyle_trial_balance_inputs=  json_decode( Cache::get(Auth::user()->id."_tree_style_trial_balances_drilldown_inputs"),true);
       $startdate_string=  $treestyle_trial_balance_inputs['start_date'];
       $enddate_string=$treestyle_trial_balance_inputs['end_date']; 
       
       $startdate_array=explode("-",  $startdate_string);
       $enddate_array=explode("-",    $enddate_string); 
       $startdate=implode("-",array_reverse($startdate_array));
       $enddate=implode("-",array_reverse($enddate_array));
  
       $this->reportservice->start_date=    $startdate;
       $this->reportservice->end_date=  $enddate; 
        $this->reportservice->account_id= $account_id;
        $this->reportservice->company_name= $companyname;
        $this->reportservice->user_id=Auth::user()->id; 
        $this->reportservice->setAccountTreeData();
        // $this->reportservice->calculateAccountWiseTotalsByQuery();
   
        $this->reportservice->getAllAccountTotals(); 
        
        $selected_accounts=   $this->reportservice->getChildAccountIds();

        $open_childaccounts_balances=array();
        
        foreach(  $selected_accounts as   $selected_account){
 
          $this->reportservice->account_id= $selected_account;

          $result= $this->reportservice->getAccountTotals();

          $open_childaccounts_balances[$selected_account]=  $result; 

          array_push($balances,   $result);
        }

        $account_level=$level_no+1;
 
        $total_opening_debitbalance=array_sum(array_column($balances,'opening_debitbalance'));

        array_push( $alltotals, $total_opening_debitbalance );
        

        $total_opening_creditbalance=array_sum(array_column($balances,'opening_creditbalance'));


        array_push( $alltotals,  $total_opening_creditbalance );
        

        $total_opening_debitcredit_diff=round($total_opening_debitbalance-$total_opening_creditbalance,2);

        $total_total_debit=array_sum(array_column($balances,'total_debit'));


        array_push( $alltotals, $total_total_debit);
        
        
        $total_total_credit=array_sum(array_column($balances,'total_credit'));
 
        array_push( $alltotals,    $total_total_credit);
 
        $total_total_debit_credit_diff= round(  $total_total_debit-  $total_total_credit,2);
 

        $total_closing_debit_balance=array_sum(array_column($balances,'closing_debit_balance'));

        
        array_push( $alltotals,  $total_closing_debit_balance);

        $total_closing_credit_balance=array_sum(array_column($balances,'closing_credit_balance'));
        
        array_push( $alltotals,  $total_closing_credit_balance);

        $total_closing_debit_credit_balance_diff=  round( $total_closing_debit_balance- $total_closing_credit_balance,2);
 
        $fcamt_total_opening_debitbalance=array_sum(array_column($balances,'fcamt_opening_debitbalance'));
        

        array_push( $alltotals,    $fcamt_total_opening_debitbalance);

        $fcamt_total_opening_creditbalance=array_sum(array_column($balances,'fcamt_opening_creditbalance'));


        array_push( $alltotals,    $fcamt_total_opening_creditbalance);

        $fcamt_total_opening_debitcredit_diff=round($fcamt_total_opening_debitbalance-$fcamt_total_opening_creditbalance,2);

        $fcamt_total_total_debit=array_sum(array_column($balances,'fcamt_total_debit'));

        array_push( $alltotals,    $fcamt_total_total_debit);
        
        $fcamt_total_total_credit=array_sum(array_column($balances,'fcamt_total_credit'));

        
        array_push( $alltotals,  $fcamt_total_total_credit);

        $fcamt_total_total_debit_credit_diff=  round( $fcamt_total_total_debit-  $fcamt_total_total_credit,2);
 

        $fcamt_total_closing_debit_balance=array_sum(array_column($balances,'fcamt_closing_debit_balance'));

        
        array_push( $alltotals,   $fcamt_total_closing_debit_balance);
        
        $fcamt_total_closing_credit_balance=array_sum(array_column($balances,'fcamt_closing_credit_balance'));
        
        array_push( $alltotals,    $fcamt_total_closing_credit_balance);

        $fcamt_total_closing_debit_credit_balance_diff=    round($fcamt_total_closing_debit_balance- $fcamt_total_closing_credit_balance,2);
 
        $open_childaccounts=true;
 

        if(!empty(Cache::get(Auth::user()->id."_tree_style_trial_balances_open_childaccounts") ) ){
          Cache::forget(Auth::user()->id."_tree_style_trial_balances_open_childaccounts");
        }
 
        Cache::put(Auth::user()->id."_tree_style_trial_balances_open_childaccounts",json_encode( array( 'account_ids'=> $selected_accounts ,'parent_account_name'=>  $single_account_name ,'all_balances'=> $open_childaccounts_balances,'all_totals'=> $alltotals,    
            'total_opening_debitcredit_diff'=>$total_opening_debitcredit_diff ,
        'total_total_debit_credit_diff'=>$total_total_debit_credit_diff ,
        'total_closing_debit_credit_balance_diff'=>$total_closing_debit_credit_balance_diff,
        'fcamt_total_opening_debitcredit_diff'=>$fcamt_total_opening_debitcredit_diff ,
        'fcamt_total_total_debit_credit_diff'=>$fcamt_total_total_debit_credit_diff ,
        'fcamt_total_closing_debit_credit_balance_diff'=>$fcamt_total_closing_debit_credit_balance_diff ) ));
   

      }
      else if(!empty( Cache::get(Auth::user()->id."_tree_style_trial_balances_input"))){

        $tree_style_trial_balances_inputs_json= Cache::get(Auth::user()->id."_tree_style_trial_balances_input");
        $tree_style_trial_balances_inputs=json_decode($tree_style_trial_balances_inputs_json,true);
  
         $division=  $tree_style_trial_balances_inputs['division'];
         $account_level=1;
         
          $start_date_string=$tree_style_trial_balances_inputs['start_date'];

         $end_date_string=$tree_style_trial_balances_inputs['end_date'];

         $start_date_array=explode('-',$tree_style_trial_balances_inputs['start_date']);

         $end_date_array=explode('-',$tree_style_trial_balances_inputs['end_date']);
                 
         $date_start= implode('-',array_reverse($start_date_array));

         $date_end=implode('-',array_reverse($end_date_array)); 

          $this->reportservice->start_date=  $date_start;
         $this->reportservice->end_date=  $date_end;
         $this->reportservice->cost_center= $cost_center;
         $this->reportservice->division=   $division; 
          $this->reportservice->add_subtotals=true;
         $this->reportservice->show_foreign_currency=  $show_foreigncurrency; 
         $this->reportservice->company_name= $companyname; 
         $this->reportservice->user_id=Auth::user()->id; 

         $selected_accounts=array();

         $child_balances=array(); 

       $total_opening_debitcredit_diff=   $tree_style_trial_balances_inputs['total_opening_debitcredit_diff'];

       $total_total_debit_credit_diff=   $tree_style_trial_balances_inputs['total_total_debit_credit_diff'];

       $total_closing_debit_credit_balance_diff=  $tree_style_trial_balances_inputs['total_closing_debit_credit_balance_diff'];

       $fcamt_total_opening_debitcredit_diff=$tree_style_trial_balances_inputs['fcamt_total_opening_debitcredit_diff'];

       $fcamt_total_total_debit_credit_diff=$tree_style_trial_balances_inputs['fcamt_total_total_debit_credit_diff'];

       $fcamt_total_closing_debit_credit_balance_diff= $tree_style_trial_balances_inputs['fcamt_total_closing_debit_credit_balance_diff'];
       $selected_account_level=$tree_style_trial_balances_inputs['selected_account_level'];

       $selected_accounts    = $tree_style_trial_balances_inputs["all_accounts"];

       $show_foreigncurrency = $tree_style_trial_balances_inputs["show_foreign_currency"];; 
        $showzeros= $tree_style_trial_balances_inputs["show_zeros"];; 

        $alltotals=$tree_style_trial_balances_inputs["alltotals"];
 

     }
      else{

         
        $start_date_string= date("d-m-Y",strtotime($startEndDate->fs_date));

        $end_date_string=date("d-m-Y",strtotime($startEndDate->fe_date));;

        $selected_accounts=array();

        $balances=array();


        $total_opening_debitcredit_diff=0;
        $total_total_debit_credit_diff=0; 
        $total_closing_debit_credit_balance_diff=0;
        $account_level="";

        $fcamt_total_opening_debitcredit_diff=0;
        $fcamt_total_total_debit_credit_diff=0; 
        $fcamt_total_closing_debit_credit_balance_diff=0;
        
      }

       $selected_accounts_collection = collect( $selected_accounts); 
      

       if(empty($account_id) && empty($level_no)){
        
        $selected_accounts_data= $this->reportservice->paginate(['company_name'=>$companyname],'company.treestyle-trial-balances',$selected_accounts_collection, 10 );
       }
       else{
        $selected_accounts_data= $selected_accounts_collection ;
       }
     

        $report_name="Tree Style Trial Balances";
  
        return view('reports.treestyle-trial-balances',compact('accounts' ,'companyname','costcenters' ,'start_date_string','end_date_string','balances','total_opening_debitcredit_diff','total_total_debit_credit_diff','total_closing_debit_credit_balance_diff','showzeros','account_level','show_foreigncurrency','fcamt_total_opening_debitcredit_diff','fcamt_total_total_debit_credit_diff','fcamt_total_closing_debit_credit_balance_diff','open_childaccounts','account_id','single_account_name','account_level','divisions','division','account_tree_data','child_balances','report_name','selected_account_level','selected_accounts_data','alltotals','cost_center' ));
        

    }



    public function getChildAccounts($companyname,$accountid){

         $accounts= Account::getChildAccounts($accountid);

         return response()->json(['accounts'=>$accounts]);

    }


    public function submitFreeStyleTrialBalances($companyname,Request $request){
 
   

 

    }


    public function testReport(){

      $this->reportservice->account_level=2;

      $this->reportservice->add_subtotals=true;


      $this->reportservice->start_date='2022-04-01';

      
      $this->reportservice->end_date='2023-03-31';


     $result= $this->reportservice->getAccountLevelAccountIds();

 


    }



    public function searchAccountByName($companyname,$searchtext){


         $this->reportservice->search_account=$searchtext;

        $result= $this->reportservice->getAccountLocationInTree();
 
        return response()->json(['status'=>$result['status'],"locations"=>$result['locations']]);
 
    }


    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }



    
    public function searchByAccountName(Request $request){ 

      $searchterm=empty($request->searchTerm)?'':$request->searchTerm;
 

       $accounts= Account::where('ACName','Like','%'.$searchterm.'%')->take(5)->select('ACName as text','Id as id')->get()->toArray();

       
         return response()->json($accounts);

     }


     public function setReportFreeStyleSearchValuesInSession(Request $request){
 

         $startdate_string=$request->start_date;

         $enddate_string=$request->end_date;

         $startdate_array=explode("-", $startdate_string);

         $date_start=$startdate_array[2]."-".$startdate_array[1]."-".$startdate_array[0];

         $enddate_array=explode("-", $enddate_string);

         $date_end=$enddate_array[2]."-".$enddate_array[1]."-".$enddate_array[0];

         $costcenter=$request->cost_center;

         $department=$request->department;


         $session_data=array('start_date'=> $date_start,'end_date'=>$date_end,'cost_center'=> $costcenter,'department'=> $department);

         $session_data_string=json_encode(  $session_data);

         $free_style_trial_balance_subledger_json=Session::get("free_style_trial_balance_subledger_json");

         if(!empty($free_style_trial_balance_subledger_json)){

          Session::forget("free_style_trial_balance_subledger_json");

         }

         Session::put("free_style_trial_balance_subledger_json",  $session_data_string);

         return response()->json(['status'=>true]);
 

     }


     public function openSubledgerFromFreeStyleReport($companyname,$accountid){
 
      $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get();
      $deptdata = Department::select('Id', 'DeptName')->orderBy('DeptName', 'ASC')->get(); 
      $companyname=Session::get('company_name');

      $subledger_json_string= Session::get("free_style_trial_balance_subledger_json");

      $free_style_array=json_decode( $subledger_json_string,true);

      $account_name= Account::where('Id',$accountid )->value('ACName');
 
      return view('Company.subledger', ['id' => $accountid , 'account_name'=>$account_name , 'treestyletrialbalance'=>'yes', 'costdata' => $costdata, 'deptdata' => $deptdata ,'companyname'=>$companyname,'free_style_array'=>$free_style_array]);
  


     }


     public function openAccountReport(){

      $this->reportservice->getAllAccountTotals(); 
      $this->reportservice->company_name= $companyname;
      $this->reportservice->user_id=Auth::user()->id;
      $this->reportservice->setAccountTreeData();
 
 
      $this->reportservice->start_date=date("Y-m-d",strtotime('now'));
      $this->reportservice->end_date=date("Y-m-d",strtotime('now'));
  
      $this->reportservice->add_subtotals=true; 
      $this->reportservice->show_foreign_currency=false; 

      $this->reportservice->account_id= 1;

      $result= $this->reportservice->getAccountTotals();
 
 
     }


     public function searchByAccountNameRestricted(Request $request){ 
          $searchterm=empty($request->searchTerm)?'':$request->searchTerm; 
 

          $user_id=Auth::user()->id;

          $is_admin=false;

          $role_id=Session::get("role_id");


          if(   $role_id==1){
            $is_admin=true;
          }


          if(   $is_admin==false){
            $customer_ids= UserCustomer::where('uid',$user_id)->pluck('cst')->toArray();


            if(count($customer_ids)>0){

              $account_ids= Customer::whereIn('Id', $customer_ids)->pluck('Acc_id')->toArray();
            }
            else{
              $account_ids=array();

              $is_admin=true;
            }


          }
          else{
            $customer_ids=array();
            $account_ids=array();

          } 
          $accounts= Account::where('ACName','Like','%'.$searchterm.'%')->when($is_admin==false,function($query)use($account_ids){
            $query->whereIn('Id',  $account_ids);
          })->take(5)->select('ACName as text','Id as id')->get()->toArray();
 
        return response()->json($accounts);

     }

     public function reportTrialBalances($companyname,$account_id=NULL,$level_no=NULL ,Request $request){

              
            $accounts=Account::where('Parent2',0)->orderby('ACName','asc')->select('ACName as account_name','Id as id','G-A as ga')->get();

            $costcenters=CostCentre::all();

            $divisions=Division::orderby('division','asc')->pluck('division','Id')->toArray(); 

            $showzeros=(empty($request->showzeros)?0:1);

            $show_foreigncurrency=(empty($request->showforeigncurrency)?0:1);

            $open_childaccounts=false;

            $selected_account_level=(isset($request->selected_account_level)?$request->selected_account_level:"");

            $cost_center= (isset($request->cost_center)?$request->cost_center:"");

            $division= (isset($request->division)?$request->division:"");

            $single_account_name='';   
            $account_tree_data=array();

            $balances= array();
            $child_balances=array();
            $firstlevel_accountids_data=array();
            $alltotals=array(); 
            $selected_accounts=array();

            $companyname = Session::get('company_name');

            $startEndDate = Company::getStartEndDate($companyname);

            $name_of_company= $startEndDate ->comp_name;

            $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ".date('d/m/Y',strtotime($startEndDate->fe_date));


            if($request->method()=="POST") {


            $start_date_string=$request->start_date;

            $end_date_string=$request->end_date;

            $division=$request->division;

            $start_date_array=explode('-', $start_date_string);

            $end_date_array=explode('-',$end_date_string);


            $date_start= implode('-',array_reverse($start_date_array));

            $date_end=implode('-',array_reverse($end_date_array)); 
            $this->reportservice->start_date=  $date_start;
            $this->reportservice->end_date=  $date_end;
            $this->reportservice->cost_center= $cost_center;
            $this->reportservice->division=   $division;  

            $this->reportservice->add_subtotals=false;
            $this->reportservice->show_foreign_currency=  $show_foreigncurrency; 
            $this->reportservice->company_name= $companyname;
            $this->reportservice->user_id=Auth::user()->id;
            $this->reportservice->setAccountTreeData();
            $this->reportservice->calculateAccountWiseTotalsByQuery(); 
            $this->reportservice->getAllAccountTotals();  

            $firstlevel_accountids=Account::where('Parent2',0)->pluck('Id')->toArray();


            foreach(   $firstlevel_accountids as   $selected_account){

              $this->reportservice->account_id= $selected_account;
              $result= $this->reportservice->getAccountTotals();

                if(   $showzeros==0 && $result['opening_debitbalance']==0 && $result['opening_creditbalance']==0 && $result['total_debit']==0 && $result['total_credit']==0 && $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
                  continue;
                }  


                array_push(   $balances,$result);


            } 

            $total_opening_debitbalance=array_sum(array_column($balances,'opening_debitbalance'));

            array_push(    $alltotals,     $total_opening_debitbalance);


            $total_opening_creditbalance=array_sum(array_column($balances,'opening_creditbalance'));

            array_push(    $alltotals,  $total_opening_creditbalance);

            $total_opening_debitcredit_diff=$total_opening_debitbalance-$total_opening_creditbalance;

            $total_total_debit=array_sum(array_column($balances,'total_debit'));
 
            array_push(    $alltotals,  $total_total_debit);

            $total_total_credit=array_sum(array_column($balances,'total_credit'));
            
            array_push(    $alltotals,  $total_total_credit);

            $total_total_debit_credit_diff=   $total_total_debit-  $total_total_credit;


            $total_closing_debit_balance=array_sum(array_column($balances,'closing_debit_balance'));

            array_push(    $alltotals,   $total_closing_debit_balance);

            $total_closing_credit_balance=array_sum(array_column($balances,'closing_credit_balance'));

            array_push(    $alltotals,      $total_closing_credit_balance);

            $total_closing_debit_credit_balance_diff=    $total_closing_debit_balance- $total_closing_credit_balance;



            $fcamt_total_opening_debitbalance=array_sum(array_column($balances,'fcamt_opening_debitbalance'));


            array_push(    $alltotals,  $fcamt_total_opening_debitbalance);

            $fcamt_total_opening_creditbalance=array_sum(array_column($balances,'fcamt_opening_creditbalance'));

            array_push(    $alltotals, $fcamt_total_opening_creditbalance);

            $fcamt_total_opening_debitcredit_diff=$fcamt_total_opening_debitbalance-$fcamt_total_opening_creditbalance;

            $fcamt_total_total_debit=array_sum(array_column($balances,'fcamt_total_debit'));

            array_push(    $alltotals,  $fcamt_total_total_debit);

            $fcamt_total_total_credit=array_sum(array_column($balances,'fcamt_total_credit'));

            
            array_push(    $alltotals,    $fcamt_total_total_credit);

            $fcamt_total_total_debit_credit_diff=   $fcamt_total_total_debit-  $fcamt_total_total_credit;


            $fcamt_total_closing_debit_balance=array_sum(array_column($balances,'fcamt_closing_debit_balance'));
 
            array_push(    $alltotals,    $fcamt_total_closing_debit_balance); 

            $fcamt_total_closing_credit_balance=array_sum(array_column($balances,'fcamt_closing_credit_balance'));
            
            array_push(    $alltotals,   $fcamt_total_closing_credit_balance); 

            $fcamt_total_closing_debit_credit_balance_diff=    $fcamt_total_closing_debit_balance- $fcamt_total_closing_credit_balance;
 
            if(!empty($selected_account_level)){
              $this->reportservice->account_level= $selected_account_level; 
              $this->reportservice->parent_account_ids=  $firstlevel_accountids;
              $child_accounts= $this->reportservice->getAccountLevelAccountIds(); 
               $this->reportservice->account_ids=   $child_accounts; 
            }
            else{
             
              $this->reportservice->account_ids= array();
            }
             

            $child_accounts=$this->reportservice->getAccountsSequenctially();

            $selected_accounts=array();

            $child_balances=array();

            foreach(  $child_accounts as   $child_account){

              $this->reportservice->account_id= $child_account;
              $result= $this->reportservice->getAccountTotals();

                if(   $showzeros==0 && $result['opening_debitbalance']==0 && $result['opening_creditbalance']==0 && $result['total_debit']==0 && $result['total_credit']==0 && $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
                  continue;
                } 

                $child_balances[ $child_account]=$result; 

                array_push($selected_accounts,$child_account);

            }
            $account_tree_data=$this->reportservice->account_tree_data;

            $account_level=1;

            if(!empty(Auth::user()->id."_trial_balances_input")){
              Cache::forget(Auth::user()->id."_trial_balances_input");
            }

            $treestyle_trial_balances_input=array("all_accounts"=>$selected_accounts,"all_balances"=>$child_balances  ,'start_date'=> $start_date_string,'end_date'=>$end_date_string,'cost_center'=>$cost_center,'division'=>$division,'show_foreign_currency'=> $show_foreigncurrency  ,
            'show_zeros'=>$showzeros,
            'total_opening_debitcredit_diff'=>$total_opening_debitcredit_diff ,
            'total_total_debit_credit_diff'=>$total_total_debit_credit_diff ,
            'total_closing_debit_credit_balance_diff'=>$total_closing_debit_credit_balance_diff,
            'fcamt_total_opening_debitcredit_diff'=>$fcamt_total_opening_debitcredit_diff ,
            'fcamt_total_total_debit_credit_diff'=>$fcamt_total_total_debit_credit_diff ,
            'fcamt_total_closing_debit_credit_balance_diff'=>$fcamt_total_closing_debit_credit_balance_diff ,
            'selected_account_level'=>$selected_account_level
            ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year ,

            'all_totals'=>  $alltotals
            );

            
            Cache::put(Auth::user()->id."_trial_balances_input",json_encode($treestyle_trial_balances_input));


            }
            else if(!empty($account_id) && !empty($level_no)){
 
              $single_account_name= Account::where('Id',$account_id)->value('ACName');
       
               $start_date_string="";
       
               $end_date_string="";
       
               $selected_accounts=array(); 
        
               $this->reportservice->add_subtotals=false; 
               $this->reportservice->show_foreign_currency=false; 
       
              $trial_balance_inputs=  json_decode( Cache::get(Auth::user()->id."_trial_balances_input"),true);
              $startdate_string=    $trial_balance_inputs['start_date'];
              $enddate_string=   $trial_balance_inputs['end_date'];
       
              $startdate_array=explode("-",  $startdate_string);
              $enddate_array=explode("-",    $enddate_string); 
              $startdate=implode("-",array_reverse($startdate_array));
              $enddate=implode("-",array_reverse($enddate_array));
         
              $this->reportservice->start_date=    $startdate;
              $this->reportservice->end_date=  $enddate; 
               $this->reportservice->account_id= $account_id;
               $this->reportservice->company_name= $companyname;
               $this->reportservice->user_id=Auth::user()->id; 
               $this->reportservice->setAccountTreeData();
               // $this->reportservice->calculateAccountWiseTotalsByQuery();
             
               $this->reportservice->getAllAccountTotals(); 
               
               $selected_accounts=   $this->reportservice->getChildAccountIds();
 
               $open_childaccounts_balances=array();

               $balances=array();
               
               foreach(  $selected_accounts as   $selected_account){
        
                 $this->reportservice->account_id= $selected_account;
       
                 $result= $this->reportservice->getAccountTotals();
       
                 $open_childaccounts_balances[$selected_account]=  $result; 
 
       
                 array_push($balances,   $result);
               }
 
               $account_level=$level_no+1;
        
               $total_opening_debitbalance=array_sum(array_column($balances,'opening_debitbalance'));
       
               array_push( $alltotals, $total_opening_debitbalance );
               
       
               $total_opening_creditbalance=array_sum(array_column($balances,'opening_creditbalance'));
       
       
               array_push( $alltotals,  $total_opening_creditbalance );
               
       
               $total_opening_debitcredit_diff=round($total_opening_debitbalance-$total_opening_creditbalance,2);
       
               $total_total_debit=array_sum(array_column($balances,'total_debit'));
       
       
               array_push( $alltotals, $total_total_debit);
               
               
               $total_total_credit=array_sum(array_column($balances,'total_credit'));
        
               array_push( $alltotals,    $total_total_credit);
        
               $total_total_debit_credit_diff= round(  $total_total_debit-  $total_total_credit,2);
        
       
               $total_closing_debit_balance=array_sum(array_column($balances,'closing_debit_balance'));
       
               
               array_push( $alltotals,  $total_closing_debit_balance);
       
               $total_closing_credit_balance=array_sum(array_column($balances,'closing_credit_balance'));
               
               array_push( $alltotals,  $total_closing_credit_balance);
       
               $total_closing_debit_credit_balance_diff=  round( $total_closing_debit_balance- $total_closing_credit_balance,2);
        
               $fcamt_total_opening_debitbalance=array_sum(array_column($balances,'fcamt_opening_debitbalance'));
               
       
               array_push( $alltotals,    $fcamt_total_opening_debitbalance);
       
               $fcamt_total_opening_creditbalance=array_sum(array_column($balances,'fcamt_opening_creditbalance'));
       
       
               array_push( $alltotals,    $fcamt_total_opening_creditbalance);
       
               $fcamt_total_opening_debitcredit_diff=round($fcamt_total_opening_debitbalance-$fcamt_total_opening_creditbalance,2);
       
               $fcamt_total_total_debit=array_sum(array_column($balances,'fcamt_total_debit'));
       
               array_push( $alltotals,    $fcamt_total_total_debit);
               
               $fcamt_total_total_credit=array_sum(array_column($balances,'fcamt_total_credit'));
       
               
               array_push( $alltotals,  $fcamt_total_total_credit);
       
               $fcamt_total_total_debit_credit_diff=  round( $fcamt_total_total_debit-  $fcamt_total_total_credit,2);
        
       
               $fcamt_total_closing_debit_balance=array_sum(array_column($balances,'fcamt_closing_debit_balance'));
       
               
               array_push( $alltotals,   $fcamt_total_closing_debit_balance);
               
               $fcamt_total_closing_credit_balance=array_sum(array_column($balances,'fcamt_closing_credit_balance'));
               
               array_push( $alltotals,    $fcamt_total_closing_credit_balance);
       
               $fcamt_total_closing_debit_credit_balance_diff=    round($fcamt_total_closing_debit_balance- $fcamt_total_closing_credit_balance,2);
        
               $open_childaccounts=true; 
         
               if(!empty(Cache::get(Auth::user()->id."_trial_balances_open_childaccounts") ) ){
                 Cache::forget(Auth::user()->id."_trial_balances_open_childaccounts");
               }
        
 
               Cache::put(Auth::user()->id."_trial_balances_open_childaccounts",json_encode( array( 'account_ids'=> $selected_accounts ,'parent_account_name'=>  $single_account_name ,'all_balances'=> $open_childaccounts_balances,'all_totals'=> $alltotals,    
                   'total_opening_debitcredit_diff'=>$total_opening_debitcredit_diff ,
               'total_total_debit_credit_diff'=>$total_total_debit_credit_diff ,
               'total_closing_debit_credit_balance_diff'=>$total_closing_debit_credit_balance_diff,
               'fcamt_total_opening_debitcredit_diff'=>$fcamt_total_opening_debitcredit_diff ,
               'fcamt_total_total_debit_credit_diff'=>$fcamt_total_total_debit_credit_diff ,
               'fcamt_total_closing_debit_credit_balance_diff'=>$fcamt_total_closing_debit_credit_balance_diff ) ));
          
       
             }
            else if(!empty( Cache::get(Auth::user()->id."_trial_balances_input"))){

            $tree_style_trial_balances_inputs_json= Cache::get(Auth::user()->id."_trial_balances_input");
            $tree_style_trial_balances_inputs=json_decode($tree_style_trial_balances_inputs_json,true);

              $division=  $tree_style_trial_balances_inputs['division'];
              $account_level=1;
              
              $start_date_string=$tree_style_trial_balances_inputs['start_date'];

              $end_date_string=$tree_style_trial_balances_inputs['end_date'];

              $start_date_array=explode('-',$tree_style_trial_balances_inputs['start_date']);

              $end_date_array=explode('-',$tree_style_trial_balances_inputs['end_date']);
                      
              $date_start= implode('-',array_reverse($start_date_array));

              $date_end=implode('-',array_reverse($end_date_array)); 

              $this->reportservice->start_date=  $date_start;
              $this->reportservice->end_date=  $date_end;
              $this->reportservice->cost_center= $cost_center;
              $this->reportservice->division=   $division; 
              $this->reportservice->add_subtotals=false;
              $this->reportservice->show_foreign_currency=  $show_foreigncurrency; 
              $this->reportservice->company_name= $companyname; 
              $this->reportservice->user_id=Auth::user()->id; 

              $selected_accounts=array();

              $child_balances=array();
            
            $total_opening_debitcredit_diff=   $tree_style_trial_balances_inputs['total_opening_debitcredit_diff'];

            $total_total_debit_credit_diff=   $tree_style_trial_balances_inputs['total_total_debit_credit_diff'];

            $total_closing_debit_credit_balance_diff=  $tree_style_trial_balances_inputs['total_closing_debit_credit_balance_diff'];

            $fcamt_total_opening_debitcredit_diff=$tree_style_trial_balances_inputs['fcamt_total_opening_debitcredit_diff'];

            $fcamt_total_total_debit_credit_diff=$tree_style_trial_balances_inputs['fcamt_total_total_debit_credit_diff'];

            $fcamt_total_closing_debit_credit_balance_diff= $tree_style_trial_balances_inputs['fcamt_total_closing_debit_credit_balance_diff'];
            $selected_account_level=$tree_style_trial_balances_inputs['selected_account_level'];

            $selected_accounts    = $tree_style_trial_balances_inputs["all_accounts"];

            $show_foreigncurrency = $tree_style_trial_balances_inputs["show_foreign_currency"];; 
            $showzeros= $tree_style_trial_balances_inputs["show_zeros"];; 
            
            }
            else{
                    
            $start_date_string= date("d-m-Y",strtotime($startEndDate->fs_date));

            $end_date_string=date("d-m-Y",strtotime($startEndDate->fe_date));;

            $selected_accounts=array();

            $balances=array();


            $total_opening_debitcredit_diff=0;
            $total_total_debit_credit_diff=0; 
            $total_closing_debit_credit_balance_diff=0;
            $account_level="";

            $fcamt_total_opening_debitcredit_diff=0;
            $fcamt_total_total_debit_credit_diff=0; 
            $fcamt_total_closing_debit_credit_balance_diff=0;

            }

            $selected_accounts_collection = collect( $selected_accounts); 
          
            if($open_childaccounts==false){
              $selected_accounts_data= $this->reportservice->paginate(['company_name'=>$companyname],'company.treestyle-trial-balances',$selected_accounts_collection, 10 );

            }
            else{
              $selected_accounts_data=        $selected_accounts_collection;
            }

         
            $report_name="Trial Balances";
            
            return view('reports.treestyle-trial-balances',compact('accounts' ,'companyname','costcenters' ,'start_date_string','end_date_string','balances','total_opening_debitcredit_diff','total_total_debit_credit_diff','total_closing_debit_credit_balance_diff','showzeros','account_level','show_foreigncurrency','fcamt_total_opening_debitcredit_diff','fcamt_total_total_debit_credit_diff','fcamt_total_closing_debit_credit_balance_diff','open_childaccounts','account_id','single_account_name','account_level','divisions','division','account_tree_data','child_balances','report_name','selected_account_level','selected_accounts_data' ,'alltotals' ));



  }
 
  
  public function reportTreeStylePandL(Request $request ,$company_name,$report_type='vertical',$report_for='vertical' ){
 
    $accounts=Account::where('Parent2',0)->whereIn('Id',array(3,4))->orderby('ACName','asc')->select('ACName as account_name','Id as id','G-A as ga')->get();

    $costcenters=CostCentre::all(); 
     
     $divisions=Division::orderby('division','asc')->pluck('division','Id')->toArray(); 
 
    $showzeros=(empty($request->showzeros)?0:1);
    
    $show_foreigncurrency=(empty($request->showforeigncurrency)?0:1);
    
    $selected_account_level=""  ;
     
    
    $cost_center= (isset($request->cost_center)?$request->cost_center:NULL);  
    $division= (isset($request->division)?$request->division:NULL);
    $companyname=Session::get('company_name');
    $start_date_string="";
    $end_date_string="";   

    $startEndDate = Company::getStartEndDate($companyname);
 
    $report_name="Tree Style P and l";

    $total_expenses=0;
    $total_fcamt_expenses=0;
    $total_incomes=0; 
    $total_fcamt_incomes=0;
    $balances=array();
    
    $child_balances=array();
    
    $selected_accounts=array(); 
    $income_accounts=array();
    $expense_accounts=array();
    $user_id=Auth::user()->id; 

    $name_of_company= $startEndDate ->comp_name;

    $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ".date('d/m/Y',strtotime($startEndDate->fe_date));

   
    if($request->method()=="POST") {


      $start_date_string=$request->start_date;

      $end_date_string=$request->end_date;
      
      $division=$request->division;
      
      $start_date_array=explode('-', $start_date_string);
      
      $end_date_array=explode('-',$end_date_string);
      
      
      $date_start= implode('-',array_reverse($start_date_array));
      
      $date_end=implode('-',array_reverse($end_date_array)); 
      $this->reportservice->start_date=  $date_start;
      $this->reportservice->end_date=  $date_end;
      $this->reportservice->cost_center= $cost_center;
      $this->reportservice->division=   $division;   
      $selected_account_level=$request->selected_account_level  ;
      $this->reportservice->add_subtotals=true;
      $this->reportservice->show_foreign_currency=  $show_foreigncurrency; 
      $this->reportservice->company_name= $companyname;
      $this->reportservice->user_id=   $user_id;
      $this->reportservice->setAccountTreeData();
      $this->reportservice->calculateAccountWiseTotalsByQuery();

      $this->reportservice->getAllAccountTotals();  
      $firstlevel_accountids=array(3,4); 
      
      foreach(   $firstlevel_accountids as   $selected_account){

        $this->reportservice->account_id= $selected_account;
        $result= $this->reportservice->getAccountTotals();

          if(   $showzeros==0 &&   $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
            continue;
          }  

          if( $selected_account==4){
            $result_amt=$result['closing_debit_balance']-$result['closing_credit_balance'];
            $result_fc_amt=  $result['fcamt_closing_debit_balance']-$result['fcamt_closing_credit_balance'];
          }
          else{
            $result_amt=$result['closing_credit_balance']-$result['closing_debit_balance'];
            $result_fc_amt=$result['fcamt_closing_credit_balance']-$result['fcamt_closing_debit_balance'];
          }
 
          $balance_result=array( 'account_id'=>$result['account_id'],'account_name'=> $result['account_name'], 'account_type'=>  $result['account_type'],'parent_name'=>  $result['parent_name'] , 'amount'=>$result_amt,'fc_amount'=>$result_fc_amt);
       
          $balances[$selected_account]=    $balance_result;
          // array_push(   $balances,$result); 
      }


      $total_expenses=  $balances[4]['amount'];
      $total_fcamt_expenses=$balances[4]['fc_amount'];;
      $total_incomes=$balances[3]['amount'];; 
      $total_fcamt_incomes=$balances[3]['fc_amount'];;;

            
      if(!empty($selected_account_level)){
        $this->reportservice->account_level= $selected_account_level;  
      }
      else{
        $this->reportservice->account_level= 45; 

      }

      $this->reportservice->parent_account_ids=array(3);
      $income_accounts= $this->reportservice->getAccountLevelAccountIds(); 
      $this->reportservice->account_ids=  $income_accounts; 
 
      $income_accounts=$this->reportservice->getAccountsSequenctially();
   

      foreach( $income_accounts as  $income_account){
 
          $this->reportservice->account_id=  $income_account;

          $result= $this->reportservice->getAccountTotals();
 

            if(   $showzeros==0 &&   $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
              continue;
            }

            $result_amt=$result['closing_credit_balance']-$result['closing_debit_balance'];
            $result_fc_amt=$result['fcamt_closing_credit_balance']-$result['fcamt_closing_debit_balance']; 

            $balance_result=array( 'account_id'=>$result['account_id'], 'account_name'=> $result['account_name'],'account_type'=>$result['account_type']  ,'parent_name'=>  $result['parent_name'] , 'amount'=>$result_amt,'fc_amount'=>$result_fc_amt);
        
            $child_balances[$income_account]=$balance_result;  
            array_push( $selected_accounts, $income_account); 
      }



      $this->reportservice->parent_account_ids=array(4);
      $expense_accounts= $this->reportservice->getAccountLevelAccountIds(); 
      $this->reportservice->account_ids=  $expense_accounts; 
 
      $expense_accounts=$this->reportservice->getAccountsSequenctially();
  

      foreach( $expense_accounts as  $expense_account){
 
          $this->reportservice->account_id=  $expense_account;

          $result= $this->reportservice->getAccountTotals();

            if(   $showzeros==0 &&   $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
              continue;
            }

            $result_amt=$result['closing_debit_balance']-$result['closing_credit_balance'];
            $result_fc_amt=$result['fcamt_closing_debit_balance']-$result['fcamt_closing_credit_balance'] ; 

            $balance_result=array('account_id'=>$result['account_id'], 'account_name'=> $result['account_name'],'account_type'=>$result['account_type'] ,'parent_name'=>  $result['parent_name'] , 'amount'=>$result_amt,'fc_amount'=>$result_fc_amt);
        
            $child_balances[$expense_account]=$balance_result; 
            array_push( $selected_accounts,  $expense_account);
 
 

      }


      if(!empty(Cache::get( $user_id."_p_and_l_report"))){
        Cache::forget( $user_id."_p_and_l_report");
      }
       
      

        $p_and_l_report_inputs=array( 'start_date'=>$start_date_string ,'end_date'=>$end_date_string ,  'show_foreigncurrency'=>$show_foreigncurrency,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,
        'show_zeros'=>$showzeros,"all_balances"=>$child_balances,"all_accounts"=> $selected_accounts,'main_balances'=>$balances ,'expense_accounts'=>$expense_accounts,'income_accounts'=>$income_accounts,
      'total_expenses'=> $total_expenses ,'total_fcamt_expenses'=>  $total_fcamt_expenses,'total_incomes'=>     $total_incomes,'total_fcamt_incomes'=>      $total_fcamt_incomes,'selected_account_level'=> $selected_account_level,'cost_center'=>$cost_center,'division'=>$division
      
      ); 

        $p_and_l_report_inputs_json=json_encode(  $p_and_l_report_inputs);
      
        Cache::put( $user_id."_p_and_l_report",$p_and_l_report_inputs_json);

      } 
      else if(  !empty(Cache::get( $user_id."_p_and_l_report") ) ){

        $p_and_l_report_inputs= json_decode(Cache::get( $user_id."_p_and_l_report"),true);

        $start_date_string=$p_and_l_report_inputs['start_date'];
        $end_date_string =$p_and_l_report_inputs['end_date'];
        $show_foreigncurrency=$p_and_l_report_inputs['show_foreigncurrency'];
        $showzeros=$p_and_l_report_inputs['show_zeros'];
        $selected_accounts=$p_and_l_report_inputs['all_accounts']; 
        $balances=$p_and_l_report_inputs['main_balances'];
        $expense_accounts=$p_and_l_report_inputs['expense_accounts'];
        $income_accounts=$p_and_l_report_inputs['income_accounts'];
        $selected_account_level=$p_and_l_report_inputs['selected_account_level'];
        $cost_center=$p_and_l_report_inputs['cost_center'];
        $division=$p_and_l_report_inputs['division'];
        $total_expenses =$p_and_l_report_inputs['total_expenses'];
        $total_fcamt_expenses=$p_and_l_report_inputs['total_fcamt_expenses'];
        $total_incomes=$p_and_l_report_inputs['total_incomes'];
        $total_fcamt_incomes=$p_and_l_report_inputs['total_fcamt_incomes'];
 
      }
      else{
        $start_date_string= date("d-m-Y",strtotime($startEndDate->fs_date));

        $end_date_string=date("d-m-Y",strtotime($startEndDate->fe_date));;
      }

      $selected_accounts_collection = collect( $selected_accounts);  

      $expense_accounts_collection=collect( $expense_accounts);

  
      $income_accounts_collection=collect($income_accounts);
    
      $selected_accounts_data_vertical= $this->reportservice->paginate(['company_name'=>$companyname,'report_type'=>'vertical','report_for'=>'vertical'] ,'company.treestyle-p-and-l',$selected_accounts_collection, 10);

      $expense_accounts_horizontal= $this->reportservice->paginate(['company_name'=>$companyname,'report_type'=>'horizontal','report_for'=>'expenses'] ,'company.treestyle-p-and-l',$expense_accounts_collection, 10);
  
      $income_accounts_horizontal= $this->reportservice->paginate(['company_name'=>$companyname,'report_type'=>'horizontal','report_for'=>'incomes'] ,'company.treestyle-p-and-l', $income_accounts_collection, 10);
   
    return view('reports.treestyle-p-and-l',compact('accounts' ,'companyname','costcenters' ,'start_date_string','end_date_string'   ,'showzeros', 'show_foreigncurrency'   ,'divisions'    ,'selected_account_level' ,'report_name' ,'total_expenses','total_fcamt_expenses','total_incomes','total_fcamt_incomes','cost_center','division','balances','selected_accounts_data_vertical','expense_accounts_horizontal','report_type','income_accounts_horizontal'));

  }



  public function testSequential(){

    $this->reportservice->getAllAccountTotals();  
        
   
    $this->reportservice->account_id= 7719;
    $result= $this->reportservice->getAccountTotals();

 
 

  }


  public function downloadTreeStyleTrialBalancesReport($companyname,$format="xlsx"){

    $tree_style_trial_balances_inputs_json= Cache::get(Auth::user()->id."_tree_style_trial_balances_input");
    $tree_style_trial_balances_inputs=json_decode($tree_style_trial_balances_inputs_json,true);

      $selected_accounts    = $tree_style_trial_balances_inputs["all_accounts"];

      $show_foreigncurrency = $tree_style_trial_balances_inputs["show_foreign_currency"];; 

      $name_of_company=$tree_style_trial_balances_inputs["name_of_company"];

      $financial_year=$tree_style_trial_balances_inputs["financial_year"];
      $start_date=$tree_style_trial_balances_inputs["start_date"];
      $end_date=$tree_style_trial_balances_inputs["end_date"];
      $selected_account_level=$tree_style_trial_balances_inputs["selected_account_level"];
      
      $alltotals=$tree_style_trial_balances_inputs["alltotals"];

      $all_balances=$tree_style_trial_balances_inputs["all_balances"];

            //  return view('reports.downloadformats.tree_style_trial_balances_format',[ 'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Tree Style Trial Balance Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
      //       'total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["total_opening_debitcredit_diff"] ,
      //       'total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"],
      //       'total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["total_total_debit_credit_diff"] ,
      //       'fcamt_total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
      //       'fcamt_total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
      //       'fcamt_total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"] ,
      //       'alltotals'=>$alltotals
      //       ]);
       
      $downloadfilename=  "treestyle-trial-balances-".date("Ymd",strtotime($tree_style_trial_balances_inputs['start_date']))."-".date("Ymd",strtotime($tree_style_trial_balances_inputs['end_date']));
             
       
 
      if(count( $selected_accounts)<=12000){


      if($format=="pdf"){ 

        $datas=array( 
          'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Tree Style Trial Balance Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
            'total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["total_opening_debitcredit_diff"] ,
            'total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"],
            'total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["total_total_debit_credit_diff"] ,
            'fcamt_total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
            'fcamt_total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
            'fcamt_total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"] ,
            'alltotals'=>$tree_style_trial_balances_inputs["alltotals"] ,'open_childaccounts'=>false,'all_balances'=>$all_balances
         
        );
        $pdf = PDF::loadView('reports.downloadformats.tree_style_trial_balances_format', $datas)->setPaper('a3')->setOrientation('landscape');
       
      
        $pdf->setTimeout(2*60*60);
        return $pdf->download(       $downloadfilename.'.pdf');
        }
 
        if($format=="xlsx" || $format=="csv" ){
          return  Excel::download(new TreeStyleTrialBalanceView( 'Tree Style Trial Balance Report',$name_of_company , $financial_year ,$start_date,$end_date, $selected_account_level ,$selected_accounts,$show_foreigncurrency,
          $tree_style_trial_balances_inputs["total_opening_debitcredit_diff"]  ,
          $tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"] ,
          $tree_style_trial_balances_inputs["total_total_debit_credit_diff"]  ,
          $tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
          $tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
          $tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"]  ,
          $tree_style_trial_balances_inputs["all_balances"] ,
           $tree_style_trial_balances_inputs["alltotals"] 
        ),        $downloadfilename.'.'.strtolower($format));
      }
   
    }
    else{

      $zipfolder=time()."-".Auth::user()->id;
      // create files and download in download_reports folder  and make zip give downloadable file to user
      File::makeDirectory(storage_path('app/public/download_reports/'.$zipfolder));

      $selected_accounts_chunks=array_chunk( $selected_accounts,12000);


      $chunk_no=1;
      foreach( $selected_accounts_chunks as  $selected_accounts_chunk){

        // if format is pdf then download in folder

        if(strtolower($format)=="pdf"){
          
            $datas=array( 
              'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Tree Style Trial Balance Report','accounts_data'=> $selected_accounts_chunk,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
                'total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["total_opening_debitcredit_diff"] ,
                'total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"],
                'total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["total_total_debit_credit_diff"] ,
                'fcamt_total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
                'fcamt_total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
                'fcamt_total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"] ,
                'alltotals'=>$tree_style_trial_balances_inputs["alltotals"] ,'open_childaccounts'=>false,'all_balances'=>$tree_style_trial_balances_inputs["all_balances"] 
            
            );
            $pdf = PDF::loadView('reports.downloadformats.tree_style_trial_balances_format', $datas)->setPaper('a3')->setOrientation('landscape');
            $pdf->save(storage_path('app/public/download_reports/'.$zipfolder)."/".$downloadfilename."_". $chunk_no.".pdf");

          }
          else{
            // in case of xlsx or csv run below


               Excel::store(new TreeStyleTrialBalanceView( 'Tree Style Trial Balance Report',$name_of_company , $financial_year ,$start_date,$end_date, $selected_account_level ,$selected_accounts_chunk,$show_foreigncurrency,
                  $tree_style_trial_balances_inputs["total_opening_debitcredit_diff"]  ,
                  $tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"] ,
                  $tree_style_trial_balances_inputs["total_total_debit_credit_diff"]  ,
                  $tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
                  $tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
                  $tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"]  ,
                  $tree_style_trial_balances_inputs["all_balances"] ,
                  $tree_style_trial_balances_inputs["alltotals"] 
                ),  '/download_reports/'.$zipfolder.'/'.$downloadfilename.'_'.$chunk_no.'.'.strtolower($format) ,'public');


          }
 
        $chunk_no++;

      }
 
 
      $zip = new ZipArchive;
    
 
      if ($zip->open(storage_path('app/public/download_reports/'.$zipfolder.".zip"), ZipArchive::CREATE) === TRUE)
      {
          $files = File::files(storage_path('app/public/download_reports/'.$zipfolder) );
 
          foreach ($files as $key => $value) {
              $relativeNameInZipFile = basename($value);
              $zip->addFile($value, $relativeNameInZipFile);
          }
           
          $zip->close();
      }
  
      return response()->download(storage_path('app/public/download_reports/'.$zipfolder.".zip"),$downloadfilename."_".strtolower($format).".zip");

 
    }




  }




  

  public function downloadTrialBalancesReport($companyname,$format="xlsx"){

    $tree_style_trial_balances_inputs_json= Cache::get(Auth::user()->id."_trial_balances_input");
    $tree_style_trial_balances_inputs=json_decode($tree_style_trial_balances_inputs_json,true);

      $selected_accounts    = $tree_style_trial_balances_inputs["all_accounts"];

      $show_foreigncurrency = $tree_style_trial_balances_inputs["show_foreign_currency"];; 

      $name_of_company=$tree_style_trial_balances_inputs["name_of_company"];

      $financial_year=$tree_style_trial_balances_inputs["financial_year"];
      $start_date=$tree_style_trial_balances_inputs["start_date"];
      $end_date=$tree_style_trial_balances_inputs["end_date"];
      $selected_account_level=$tree_style_trial_balances_inputs["selected_account_level"];

      $all_totals=$tree_style_trial_balances_inputs["all_totals"];
      $all_balances=$tree_style_trial_balances_inputs["all_balances"];
 
      $datas=array( 
        'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Trial Balance Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
          'total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["total_opening_debitcredit_diff"] ,
          'total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"],
          'total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["total_total_debit_credit_diff"] ,
          'fcamt_total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
          'fcamt_total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
          'fcamt_total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"]  ,
           'alltotals'=>    $all_totals ,'open_childaccounts'=>false ,'all_balances'=>$all_balances
      );

      //  return view('reports.downloadformats.tree_style_trial_balances_format',  $datas);

    
      $downloadfilename=  "trialbalances-".str_replace("-","",$tree_style_trial_balances_inputs['start_date'])."-".str_replace("-","",$tree_style_trial_balances_inputs['end_date']);
             
  if(count( $selected_accounts)<=6000){
       
      if($format=="pdf"){ 

        $pdf = PDF::loadView('reports.downloadformats.tree_style_trial_balances_format', $datas)->setPaper('a3')->setOrientation('landscape');
       
      
        $pdf->setTimeout(2*60*60);
        return $pdf->download(       $downloadfilename.'.pdf');
        }
 
        if($format=="xlsx" || $format=="csv" ){
 
          return  Excel::download(new TreeStyleTrialBalanceView( 'Trial Balance Report',$name_of_company , $financial_year ,$start_date,$end_date, $selected_account_level ,$selected_accounts,$show_foreigncurrency,
          $tree_style_trial_balances_inputs["total_opening_debitcredit_diff"]  ,
          $tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"] ,
          $tree_style_trial_balances_inputs["total_total_debit_credit_diff"]  ,
          $tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
          $tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
          $tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"] ,
          $tree_style_trial_balances_inputs["all_balances"] ,
          $tree_style_trial_balances_inputs["all_totals"] 
        ),        $downloadfilename.'.'.strtolower($format));
      }
    }
    else{
           
          $zipfolder=time()."-".Auth::user()->id;
          // create files and download in download_reports folder  and make zip give downloadable file to user
          File::makeDirectory(storage_path('app/public/download_reports/'.$zipfolder));

          $selected_accounts_chunks=array_chunk( $selected_accounts,6000 );
 
          $chunk_no=1;
          foreach( $selected_accounts_chunks as  $selected_accounts_chunk){

                if(strtolower($format)=="pdf"){ 

                        $datas=array( 
                          'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Trial Balance Report','accounts_data'=> $selected_accounts_chunk,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
                            'total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["total_opening_debitcredit_diff"] ,
                            'total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"],
                            'total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["total_total_debit_credit_diff"] ,
                            'fcamt_total_opening_debitcredit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
                            'fcamt_total_total_debit_credit_diff'=>$tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
                            'fcamt_total_closing_debit_credit_balance_diff'=>$tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"]  ,
                            'alltotals'=>    $all_totals ,'open_childaccounts'=>false,'all_balances'=>$all_balances
                        );

                        $pdf = PDF::loadView('reports.downloadformats.tree_style_trial_balances_format', $datas)->setPaper('a3')->setOrientation('landscape');
       
                        $pdf->save(storage_path('app/public/download_reports/'.$zipfolder)."/".$downloadfilename."_". $chunk_no.".pdf");


                }
                else{

                            Excel::store(new TreeStyleTrialBalanceView( 'Trial Balance Report',$name_of_company , $financial_year ,$start_date,$end_date, $selected_account_level ,$selected_accounts_chunk,$show_foreigncurrency,
                    $tree_style_trial_balances_inputs["total_opening_debitcredit_diff"]  ,
                    $tree_style_trial_balances_inputs["total_closing_debit_credit_balance_diff"] ,
                    $tree_style_trial_balances_inputs["total_total_debit_credit_diff"]  ,
                    $tree_style_trial_balances_inputs["fcamt_total_opening_debitcredit_diff"] ,
                    $tree_style_trial_balances_inputs["fcamt_total_total_debit_credit_diff"] ,
                    $tree_style_trial_balances_inputs["fcamt_total_closing_debit_credit_balance_diff"] ,
                    $tree_style_trial_balances_inputs["all_balances"] ,
                    $tree_style_trial_balances_inputs["all_totals"] 
                  ), '/download_reports/'.$zipfolder.'/'.$downloadfilename.'_'.$chunk_no.'.'.strtolower($format) ,'public');
                  
                }


                $chunk_no++;
          }
 
            $zip = new ZipArchive;


            if ($zip->open(storage_path('app/public/download_reports/'.$zipfolder.".zip"), ZipArchive::CREATE) === TRUE)
            {
                $files = File::files(storage_path('app/public/download_reports/'.$zipfolder) );

                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                
                $zip->close();
            }

            return response()->download(storage_path('app/public/download_reports/'.$zipfolder.".zip"),$downloadfilename."_".strtolower($format).".zip");

    }


  }


  public function getUrlForEditTransactionDataByBillNo($company_name,$bill_no){
    
     $tblauditdata=  TblAuditData::where('docno','like',$bill_no)->select('table_name','base_id')->first();
 
     $tableid=TableMaster::where('Table_Name',    $tblauditdata->table_name)->value('Id');

     $url=  url("/")."/".$company_name."/edit-transaction-table-single-data/".trim($tblauditdata->table_name)."/".trim($tableid)."/".trim( $tblauditdata->base_id);
 
     return response()->json(['url'=> $url]);
  }

 public function downloadTreestyleDrilldownReport($companyname,$format="xlsx"){

   $open_childaccounts_json= Cache::get(Auth::user()->id."_tree_style_trial_balances_open_childaccounts");

   $open_childaccounts_data_array=json_decode(  $open_childaccounts_json,true);
 
 
  $tree_style_trial_balances_inputs_json= Cache::get(Auth::user()->id."_tree_style_trial_balances_input");
  $tree_style_trial_balances_inputs=json_decode($tree_style_trial_balances_inputs_json,true);

    $selected_accounts    = $open_childaccounts_data_array["account_ids"];

    $show_foreigncurrency = $tree_style_trial_balances_inputs["show_foreign_currency"];; 

    $name_of_company=$tree_style_trial_balances_inputs["name_of_company"];
    $selected_account_level=1;

    $financial_year=$tree_style_trial_balances_inputs["financial_year"];
    $start_date=$tree_style_trial_balances_inputs["start_date"];
    $end_date=$tree_style_trial_balances_inputs["end_date"]; 
    $parent_account_name=$open_childaccounts_data_array['parent_account_name'];
     
    $alltotals=$open_childaccounts_data_array["all_totals"]; 

    $all_balances=$open_childaccounts_data_array["all_balances"]; 
 
    //  return view('reports.downloadformats.tree_style_trial_balances_format',[ 'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Tree Style Trial Balance Drilldown Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
    //       'total_opening_debitcredit_diff'=>$open_childaccounts_data_array["total_opening_debitcredit_diff"] ,
    //       'total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["total_closing_debit_credit_balance_diff"],
    //       'total_total_debit_credit_diff'=>$open_childaccounts_data_array["total_total_debit_credit_diff"] ,
    //       'fcamt_total_opening_debitcredit_diff'=>$open_childaccounts_data_array["fcamt_total_opening_debitcredit_diff"] ,
    //       'fcamt_total_total_debit_credit_diff'=>$open_childaccounts_data_array["fcamt_total_total_debit_credit_diff"] ,
    //       'fcamt_total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["fcamt_total_closing_debit_credit_balance_diff"] ,
    //       'alltotals'=>$alltotals,'open_childaccounts'=>true,'parent_account_name'=>   $open_childaccounts_data_array['parent_account_name']
    //       ]);


          $downloadfilename=  "treestyle-trial-balances-drilldown-".date("Ymd",strtotime($tree_style_trial_balances_inputs['start_date']))."-".date("Ymd",strtotime($tree_style_trial_balances_inputs['end_date']));
             
       
          if($format=="pdf"){ 
    
            $datas=array( 
              'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Tree Style Trial Balance Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
                'total_opening_debitcredit_diff'=>$open_childaccounts_data_array["total_opening_debitcredit_diff"] ,
                'total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["total_closing_debit_credit_balance_diff"],
                'total_total_debit_credit_diff'=>$open_childaccounts_data_array["total_total_debit_credit_diff"] ,
                'fcamt_total_opening_debitcredit_diff'=>$open_childaccounts_data_array["fcamt_total_opening_debitcredit_diff"] ,
                'fcamt_total_total_debit_credit_diff'=>$open_childaccounts_data_array["fcamt_total_total_debit_credit_diff"] ,
                'fcamt_total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["fcamt_total_closing_debit_credit_balance_diff"] ,
                'alltotals'=>$alltotals ,'open_childaccounts'=>true,'parent_account_name'=>   $open_childaccounts_data_array['parent_account_name'] ,
                'all_balances'=>$open_childaccounts_data_array["all_balances"]
             
            );
            $pdf = PDF::loadView('reports.downloadformats.tree_style_trial_balances_format', $datas)->setPaper('a3')->setOrientation('landscape');
           
          
            $pdf->setTimeout(2*60*60);
            return $pdf->download(       $downloadfilename.'.pdf');
            }
     
            if($format=="xlsx" || $format=="csv" ){
              return  Excel::download(new TreeStyleTrialBalanceView( 'Tree Style Trial Balance Report',$name_of_company , $financial_year ,$start_date,$end_date, $selected_account_level ,$selected_accounts,$show_foreigncurrency,
              $open_childaccounts_data_array["total_opening_debitcredit_diff"]  ,
              $open_childaccounts_data_array["total_closing_debit_credit_balance_diff"] ,
              $open_childaccounts_data_array["total_total_debit_credit_diff"]  ,
              $open_childaccounts_data_array["fcamt_total_opening_debitcredit_diff"] ,
              $open_childaccounts_data_array["fcamt_total_total_debit_credit_diff"] ,
              $open_childaccounts_data_array["fcamt_total_closing_debit_credit_balance_diff"]  ,
              $open_childaccounts_data_array["all_balances"],
               $open_childaccounts_data_array["all_totals"] , true, $open_childaccounts_data_array['parent_account_name']
            ),        $downloadfilename.'.'.strtolower($format));
          }
     
 }

 public function downloadTrialBalancesDrilldownReport($companyname,$format="xlsx"){
 
  $open_childaccounts_json= Cache::get(Auth::user()->id."_trial_balances_open_childaccounts");

   $open_childaccounts_data_array=json_decode(  $open_childaccounts_json,true);
 
 
  $tree_style_trial_balances_inputs_json= Cache::get(Auth::user()->id."_trial_balances_input");
  $tree_style_trial_balances_inputs=json_decode($tree_style_trial_balances_inputs_json,true);

    $selected_accounts    = $open_childaccounts_data_array["account_ids"];

    $show_foreigncurrency = $tree_style_trial_balances_inputs["show_foreign_currency"];; 

    $name_of_company=$tree_style_trial_balances_inputs["name_of_company"];
    $selected_account_level=1;

    $financial_year=$tree_style_trial_balances_inputs["financial_year"];
    $start_date=$tree_style_trial_balances_inputs["start_date"];
    $end_date=$tree_style_trial_balances_inputs["end_date"]; 
    $parent_account_name=$open_childaccounts_data_array['parent_account_name'];
     
    $alltotals=$open_childaccounts_data_array["all_totals"]; 
    $all_balances=$open_childaccounts_data_array["all_balances"]; 

    //  return view('reports.downloadformats.tree_style_trial_balances_format',[ 'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Trial Balance Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
    //       'total_opening_debitcredit_diff'=>$open_childaccounts_data_array["total_opening_debitcredit_diff"] ,
    //       'total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["total_closing_debit_credit_balance_diff"],
    //       'total_total_debit_credit_diff'=>$open_childaccounts_data_array["total_total_debit_credit_diff"] ,
    //       'fcamt_total_opening_debitcredit_diff'=>$open_childaccounts_data_array["fcamt_total_opening_debitcredit_diff"] ,
    //       'fcamt_total_total_debit_credit_diff'=>$open_childaccounts_data_array["fcamt_total_total_debit_credit_diff"] ,
    //       'fcamt_total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["fcamt_total_closing_debit_credit_balance_diff"] ,
    //       'alltotals'=>$alltotals,'open_childaccounts'=>true,'parent_account_name'=>   $open_childaccounts_data_array['parent_account_name']
    //       ]);


          $downloadfilename=  "treestyle-trial-balances-drilldown-".date("Ymd",strtotime($tree_style_trial_balances_inputs['start_date']))."-".date("Ymd",strtotime($tree_style_trial_balances_inputs['end_date']));
             
       
          if($format=="pdf"){ 
    
            $datas=array( 
              'name_of_company'=>$name_of_company,'financial_year'=>$financial_year, 'report_name'=>'Trial Balance Report','accounts_data'=> $selected_accounts ,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=>$start_date,'end_date'=>$end_date,'account_level'=>    $selected_account_level ,
                'total_opening_debitcredit_diff'=>$open_childaccounts_data_array["total_opening_debitcredit_diff"] ,
                'total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["total_closing_debit_credit_balance_diff"],
                'total_total_debit_credit_diff'=>$open_childaccounts_data_array["total_total_debit_credit_diff"] ,
                'fcamt_total_opening_debitcredit_diff'=>$open_childaccounts_data_array["fcamt_total_opening_debitcredit_diff"] ,
                'fcamt_total_total_debit_credit_diff'=>$open_childaccounts_data_array["fcamt_total_total_debit_credit_diff"] ,
                'fcamt_total_closing_debit_credit_balance_diff'=>$open_childaccounts_data_array["fcamt_total_closing_debit_credit_balance_diff"] ,
                'alltotals'=>$alltotals ,'open_childaccounts'=>true,'parent_account_name'=>   $open_childaccounts_data_array['parent_account_name'] ,
                'all_balances'=> $all_balances
             
            );
            $pdf = PDF::loadView('reports.downloadformats.tree_style_trial_balances_format', $datas)->setPaper('a3')->setOrientation('landscape');
           
          
            $pdf->setTimeout(2*60*60);
            return $pdf->download(       $downloadfilename.'.pdf');
            }
     
            if($format=="xlsx" || $format=="csv" ){
              return  Excel::download(new TreeStyleTrialBalanceView( 'Trial Balance Report',$name_of_company , $financial_year ,$start_date,$end_date, $selected_account_level ,$selected_accounts,$show_foreigncurrency,
              $open_childaccounts_data_array["total_opening_debitcredit_diff"]  ,
              $open_childaccounts_data_array["total_closing_debit_credit_balance_diff"] ,
              $open_childaccounts_data_array["total_total_debit_credit_diff"]  ,
              $open_childaccounts_data_array["fcamt_total_opening_debitcredit_diff"] ,
              $open_childaccounts_data_array["fcamt_total_total_debit_credit_diff"] ,
              $open_childaccounts_data_array["fcamt_total_closing_debit_credit_balance_diff"]  ,
              $open_childaccounts_data_array["all_balances"]  ,
              $open_childaccounts_data_array['all_totals'] , true, $open_childaccounts_data_array['parent_account_name']
            ),        $downloadfilename.'.'.strtolower($format));
          } 
          }
  
 public function downloadTreeStylePandLReport($companyname,$report_type="vertical",$format="xlsx"){ 
     $user_id=Auth::user()->id;
     $p_and_l_report_inputs_json= Cache::get( $user_id."_p_and_l_report");
     $p_and_l_report_inputs_array=json_decode(    $p_and_l_report_inputs_json,true);

     $name_of_company= $p_and_l_report_inputs_array['name_of_company'];
     $financial_year=$p_and_l_report_inputs_array['financial_year'];
     $show_foreigncurrency=$p_and_l_report_inputs_array['show_foreigncurrency'];
     $start_date_string =$p_and_l_report_inputs_array['start_date'];
     $end_date_string=$p_and_l_report_inputs_array['end_date'];
     $all_accounts=$p_and_l_report_inputs_array['all_accounts'];
     $total_expenses=$p_and_l_report_inputs_array['total_expenses'];
     $total_fcamt_expenses=$p_and_l_report_inputs_array['total_fcamt_expenses'];
     $total_incomes=$p_and_l_report_inputs_array['total_incomes'];
     $total_fcamt_incomes=$p_and_l_report_inputs_array['total_fcamt_incomes'];

     $income_accounts=$p_and_l_report_inputs_array['income_accounts'];
     $expense_accounts=$p_and_l_report_inputs_array['expense_accounts'];
     $all_balances=$p_and_l_report_inputs_array['all_balances'];
  
    // return view("reports.downloadformats.treestyle_pandl_format",[  'all_accounts'=>$all_accounts,'name_of_company'=>   $name_of_company ,'financial_year'=>$financial_year,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=> $start_date_string ,'end_date'=> $end_date_string,'report_name'=>'P and L Report','total_expenses'=>$total_expenses,'total_fcamt_expenses'=> $total_fcamt_expenses,'total_incomes'=>$total_incomes,'total_fcamt_incomes'=>$total_fcamt_incomes,'report_type'=>$report_type,'income_accounts'=>$income_accounts,'expense_accounts'=>$expense_accounts]);
  
    
     $downloadfilename="p_and_l_report-".$report_type."-".formatDateInYmd($start_date_string )."-".formatDateInYmd($end_date_string );
     
     if($format=="pdf"){ 
     
      $datas=array( 
        'all_accounts'=>$all_accounts,'name_of_company'=>   $name_of_company ,'financial_year'=>$financial_year,'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=> $start_date_string ,'end_date'=> $end_date_string,'report_name'=>'P and L Report','total_expenses'=>$total_expenses,'total_fcamt_expenses'=> $total_fcamt_expenses,'total_incomes'=>$total_incomes,'total_fcamt_incomes'=>$total_fcamt_incomes,'report_type'=>$report_type ,'income_accounts'=>$income_accounts,'expense_accounts'=>$expense_accounts,'all_balances'=>$all_balances
      );
      $pdf = PDF::loadView('reports.downloadformats.treestyle_pandl_format', $datas)->setPaper('a3')->setOrientation('landscape');
      
      $pdf->setTimeout(2*60*60);
      return $pdf->download(       $downloadfilename.'.pdf');
      } 
   
      if($format=="xlsx" || $format=="csv" ){
 
        return  Excel::download(new PandLReportView( $all_accounts,  $name_of_company , $financial_year, $show_foreigncurrency, $start_date_string , $end_date_string, 'P and L Report',$total_expenses, $total_fcamt_expenses, $total_incomes, $total_fcamt_incomes, $report_type,$all_balances ,$income_accounts,$expense_accounts ),        $downloadfilename.'.'.strtolower($format));
    }
 

 }
 
    public function generateTemperaryUrl(){

         $this->reportservice->deleteAllCreatedFilesAtFolderAfter24Hour('send_docs');

            

    
      }

      public function processQueueJobs(){

     
        \Artisan::call("queue:work --stop-when-empty");

        dump("Queue Work Executed");

       

      
    }
    

    public function deleteCreatedFilesAfter24Hours(){

      $this->reportservice->deleteAllCreatedFilesAtFolderAfter24Hour('send_docs/sub_ledgers');
        
      dump("send docs subledgers deleted"); 

      $this->reportservice->deleteAllCreatedFilesAtFolderAfter24Hour('invoices_pdf');

      dump("invoices pdf deleted"); 

      $this->reportservice->deleteAllCreatedFilesAtFolderAfter24Hour('send_docs');
      
      dump("send_docs deleted"); 

      

      $this->reportservice->deleteAllCreatedFilesAtFolderAfter24Hour('download_reports');

      
      dump("download_reports deleted"); 


    }


      public function setTreestyleTrialBalanceDrilldownSettingsFromReport(Request $request){
              
            $user_id=Auth::user()->id;

            $from_report_name=$request->from_report; 

            $input_json_string=Cache::get($user_id.$from_report_name);
 

            if(empty($input_json_string)){

              return response()->json(['status'=>'failure','message'=>'input settings not given']);
            }

            if(!empty(Cache::get($user_id."_tree_style_trial_balances_drilldown_inputs"))){
              Cache::forget($user_id."_tree_style_trial_balances_drilldown_inputs");
            } 

            $input_array=json_decode(      $input_json_string,true);
           

            $result_array=array('start_date'=> $input_array['start_date'],'end_date'=> $input_array['end_date']);

            $json_string=json_encode( $result_array);

            Cache::put($user_id."_tree_style_trial_balances_drilldown_inputs", $json_string,10800);

            return response()->json(['status'=>'success']);
 
      }


      public function openBalanceSheet(Request $request,$company_name,$report_type='vertical',$report_for='vertical'){

        $accounts=Account::where('Parent2',0)->whereIn('Id',array(1,2))->orderby('ACName','asc')->select('ACName as account_name','Id as id','G-A as ga')->get();

        $costcenters=CostCentre::all(); 
         
         $divisions=Division::orderby('division','asc')->pluck('division','Id')->toArray(); 
        
        $showzeros=(empty($request->showzeros)?0:1);
        
        $show_foreigncurrency=(empty($request->showforeigncurrency)?0:1);
        
        $selected_account_level=""  ;
         
        $showdetails=(empty($request->showdetails)?0:1);
        
        $cost_center= (isset($request->cost_center)?$request->cost_center:NULL);  
        $division= (isset($request->division)?$request->division:NULL);
        $companyname=Session::get('company_name');
        $start_date_string="";
        $end_date_string="";   
        
        $startEndDate = Company::getStartEndDate($companyname);
        
        $report_name="Balance Sheet";
        
        $total_liabilities=0;
        $total_fcamt_liabilities=0;
        $total_assets=0; 
        $total_fcamt_assets=0;
        $balances=array();
        
        $child_balances=array();
        
        $selected_accounts=array(); 
        $liabilities_accounts=array();
        $assets_accounts=array();
        $user_id=Auth::user()->id; 
        
        $name_of_company= $startEndDate ->comp_name;
        
        $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ".date('d/m/Y',strtotime($startEndDate->fe_date));
        
        $profit_loss_detail=array();

        $total_opening_debitbalance=0;
        $total_opening_creditbalance=0;
        $total_total_debit=0;
        $total_total_credit=0;
        $total_closing_debit_balance=0;
        $total_closing_credit_balance=0;
        $total_fcamt_opening_debitbalance=0;
        $total_fcamt_opening_creditbalance= 0;
        $total_fcamt_total_debit=0;
        $total_fcamt_total_credit=0;
        $total_fcamt_closing_debit_balance=0;
        $total_fcamt_closing_credit_balance=0;
        			
        $total_opening_debitcredit_diff=0;
        $total_total_debit_credit_diff=0; 
        $total_closing_debit_credit_balance_diff= 0; 
        $fcamt_total_opening_debitcredit_diff=0; 
        $fcamt_total_total_debit_credit_diff=0;
        $fcamt_total_closing_debit_credit_balance_diff=0;
  
         

        if($request->method()=="POST") {
        
        
          $start_date_string=$request->start_date;
        
          $end_date_string=$request->end_date;
          
          $division=$request->division;
          
          $start_date_array=explode('-', $start_date_string);
          
          $end_date_array=explode('-',$end_date_string);
          
          
          $date_start= implode('-',array_reverse($start_date_array));
          
          $date_end=implode('-',array_reverse($end_date_array)); 
          $this->reportservice->start_date=  $date_start;
          $this->reportservice->end_date=  $date_end;
          $this->reportservice->cost_center= $cost_center;
          $this->reportservice->division=   $division;   
          $selected_account_level=$request->selected_account_level  ;
          $this->reportservice->add_subtotals=true;
          $this->reportservice->show_foreign_currency=  $show_foreigncurrency; 
          $this->reportservice->company_name= $companyname;
          $this->reportservice->user_id=   $user_id;
          $this->reportservice->setAccountTreeData();
          $this->reportservice->calculateAccountWiseTotalsByQuery();
       
          $this->reportservice->getAllAccountTotals();  
          $firstlevel_accountids=array(2,1); 

          $profit_loss_detail= $this->reportservice->getProfitLossUsingExpenseAndIncome();

          
          foreach(   $firstlevel_accountids as   $selected_account){
        
            $this->reportservice->account_id= $selected_account;
            $result= $this->reportservice->getAccountTotals();
        
              if(   $showzeros==0 &&   $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
                continue;
              }  
        
              if( $selected_account==2){
                $result_amt=$result['closing_credit_balance']-$result['closing_debit_balance'];
                $result_fc_amt= $result['fcamt_closing_credit_balance']- $result['fcamt_closing_debit_balance'];
              }
              else{
                $result_amt=$result['closing_debit_balance']-$result['closing_credit_balance'];
                $result_fc_amt=$result['fcamt_closing_debit_balance']-$result['fcamt_closing_credit_balance'];
              }
        
              $balance_result=array( 'account_id'=>$result['account_id'],'account_name'=> $result['account_name'], 'account_type'=>  $result['account_type'],'parent_name'=>  $result['parent_name'] , 'amount'=>$result_amt,'fc_amount'=>$result_fc_amt ,
              'showdetails'=> $showdetails,
            'opening_debitbalance'=>$result['opening_debitbalance'],
            'opening_creditbalance'=>$result['opening_creditbalance'],
            'total_debit'=>$result['total_debit'],
            'total_credit'=>$result['total_credit'],
            'closing_debit_balance'=>$result['closing_debit_balance'],
            'closing_credit_balance'=>$result['closing_credit_balance'],
            'fcamt_opening_debitbalance'=>$result['fcamt_opening_debitbalance'],
            'fcamt_opening_creditbalance'=>$result['fcamt_opening_creditbalance'],
            'fcamt_total_debit'=> $result['fcamt_total_debit'],
            'fcamt_total_credit'=>$result['fcamt_total_credit'],
            'fcamt_closing_debit_balance'=>$result['fcamt_closing_debit_balance'],
            'fcamt_closing_credit_balance'=> $result['fcamt_closing_credit_balance']
            );
           
              $balances[$selected_account]=    $balance_result;
              // array_push(   $balances,$result); 
          }
        
        
          $total_liabilities=  $balances[2]['amount']+   $profit_loss_detail['profit_loss'];
          $total_fcamt_liabilities=$balances[2]['fc_amount']+   $profit_loss_detail['fc_profit_loss'];
          $total_assets=$balances[1]['amount'];; 
          $total_fcamt_assets=$balances[1]['fc_amount'];;;
          $total_opening_debitbalance=$balances[1]['opening_debitbalance']+$balances[2]['opening_debitbalance'];
          $total_opening_creditbalance=$balances[1]['opening_creditbalance']+$balances[2]['opening_creditbalance'];
          $total_total_debit=$balances[1]['total_debit']+$balances[2]['total_debit'];
          $total_total_credit=$balances[1]['total_credit']+$balances[2]['total_credit'];
          $total_closing_debit_balance=$balances[1]['closing_debit_balance']+$balances[2]['closing_debit_balance'];
          $total_closing_credit_balance=$balances[1]['closing_credit_balance']+$balances[2]['closing_credit_balance'];
          $total_fcamt_opening_debitbalance=$balances[1]['fcamt_opening_debitbalance']+$balances[2]['fcamt_opening_debitbalance']; 
          $total_fcamt_opening_creditbalance= $balances[1]['fcamt_opening_creditbalance']+$balances[2]['fcamt_opening_creditbalance']; 
          $total_fcamt_total_debit=$balances[1]['fcamt_total_debit']+$balances[2]['fcamt_total_debit']; 
          $total_fcamt_total_credit=$balances[1]['fcamt_total_credit']+$balances[2]['fcamt_total_credit']; 
          $total_fcamt_closing_debit_balance=$balances[1]['fcamt_closing_debit_balance']+$balances[2]['fcamt_closing_debit_balance']; 
          $total_fcamt_closing_credit_balance=$balances[1]['fcamt_closing_credit_balance']+$balances[2]['fcamt_closing_credit_balance']; 
        
          $total_opening_debitcredit_diff=$total_opening_debitbalance-$total_opening_creditbalance;
            $total_total_debit_credit_diff=   $total_total_debit-  $total_total_credit;
         $total_closing_debit_credit_balance_diff=    $total_closing_debit_balance- $total_closing_credit_balance;
         $fcamt_total_opening_debitcredit_diff=  $total_fcamt_opening_debitbalance- $total_fcamt_opening_creditbalance;
           $fcamt_total_total_debit_credit_diff= $total_fcamt_total_debit-$total_fcamt_total_credit;
        $fcamt_total_closing_debit_credit_balance_diff=   $total_fcamt_closing_debit_balance- $total_fcamt_closing_credit_balance;

        
          if(!empty($selected_account_level)){
            $this->reportservice->account_level= $selected_account_level;  
          }
          else{
            $this->reportservice->account_level= 45; 
        
          }
         
          $this->reportservice->parent_account_ids=array(2);
          $liabilities_accounts= $this->reportservice->getAccountLevelAccountIds(); 
          $this->reportservice->account_ids=  $liabilities_accounts; 
        
          $liabilities_accounts=$this->reportservice->getAccountsSequenctially();
        
        
          foreach( $liabilities_accounts as  $liabilities_account){
        
              $this->reportservice->account_id= $liabilities_account;
        
              $result= $this->reportservice->getAccountTotals();
        
                if(   $showzeros==0 &&   $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
                  continue;
                }
        
                $result_amt=$result['closing_credit_balance']-$result['closing_debit_balance'];
                $result_fc_amt=$result['fcamt_closing_credit_balance']-$result['fcamt_closing_debit_balance'] ; 
        
                $balance_result=array('account_id'=>$result['account_id'], 'account_name'=> $result['account_name'],'account_type'=>$result['account_type'] ,'parent_name'=>  $result['parent_name'] , 'amount'=>$result_amt,'fc_amount'=>$result_fc_amt ,
              'opening_debitbalance'=>$result['opening_debitbalance'] ,
              'opening_creditbalance'=>$result['opening_creditbalance'],
              'total_debit'=>$result['total_debit'],
              'total_credit'=>$result['total_credit'] ,
              'closing_debit_balance'=>$result['closing_debit_balance'] ,
              'closing_credit_balance'=>$result['closing_credit_balance'] ,
              'fcamt_opening_debitbalance'=>$result['fcamt_opening_debitbalance'],
              'fcamt_opening_creditbalance'=>$result['fcamt_opening_creditbalance'],
              'fcamt_total_debit'=>$result['fcamt_total_debit'],
              'fcamt_total_credit'=>$result['fcamt_total_credit'],
              'fcamt_closing_debit_balance'=>$result['fcamt_closing_debit_balance'],
              'fcamt_closing_credit_balance'=>$result['fcamt_closing_credit_balance'] 

              );
            
                $child_balances[ $liabilities_account]=$balance_result; 
                array_push( $selected_accounts,  $liabilities_account);
         
          }



          $this->reportservice->parent_account_ids=array(1);
          $assets_accounts= $this->reportservice->getAccountLevelAccountIds(); 
          $this->reportservice->account_ids=  $assets_accounts; 
        
          $assets_accounts=$this->reportservice->getAccountsSequenctially();
        
        
          foreach( $assets_accounts as  $assets_account){
        
              $this->reportservice->account_id=  $assets_account;
        
              $result= $this->reportservice->getAccountTotals();
        
        
                if(   $showzeros==0 &&   $result['closing_debit_balance']==0  && $result['closing_credit_balance']==0 ){
                  continue;
                }
        
                $result_amt=$result['closing_debit_balance']-$result['closing_credit_balance'];
                $result_fc_amt=$result['fcamt_closing_debit_balance']-$result['fcamt_closing_credit_balance']; 
        
                $balance_result=array( 'account_id'=>$result['account_id'], 'account_name'=> $result['account_name'],'account_type'=>$result['account_type']  ,'parent_name'=>  $result['parent_name'] , 'amount'=>$result_amt,'fc_amount'=>$result_fc_amt ,
                'opening_debitbalance'=>$result['opening_debitbalance'] ,
                'opening_creditbalance'=>$result['opening_creditbalance'],
                'total_debit'=>$result['total_debit'],
                'total_credit'=>$result['total_credit'] ,
                'closing_debit_balance'=>$result['closing_debit_balance'] ,
                'closing_credit_balance'=>$result['closing_credit_balance'] ,
                'fcamt_opening_debitbalance'=>$result['fcamt_opening_debitbalance'],
                'fcamt_opening_creditbalance'=>$result['fcamt_opening_creditbalance'],
                'fcamt_total_debit'=>$result['fcamt_total_debit'],
                'fcamt_total_credit'=>$result['fcamt_total_credit'],
                'fcamt_closing_debit_balance'=>$result['fcamt_closing_debit_balance'],
                'fcamt_closing_credit_balance'=>$result['fcamt_closing_credit_balance'] 
              
              );
            
                $child_balances[$assets_account]=$balance_result;  
                array_push( $selected_accounts,$assets_account); 
          }
        
        
          if(!empty(Cache::get( $user_id."_balance_report"))){
            Cache::forget( $user_id."_balance_report");
          }
            
            $balance_report_inputs=array( 'start_date'=>$start_date_string ,'end_date'=>$end_date_string ,  'show_foreigncurrency'=>$show_foreigncurrency,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,'show_details'=> $showdetails ,
            'show_zeros'=>$showzeros,"all_balances"=>$child_balances,"all_accounts"=> $selected_accounts,'main_balances'=>$balances ,'assets_accounts'=>$assets_accounts,'liabilities_accounts'=>$liabilities_accounts,
          'total_assets'=> $total_assets ,'total_fcamt_assets'=>  $total_fcamt_assets,'total_liabilities'=>     $total_liabilities,'total_fcamt_liabilities'=>      $total_fcamt_liabilities,'selected_account_level'=> $selected_account_level,'cost_center'=>$cost_center,'division'=>$division ,'profit_loss_amt'=>  $profit_loss_detail['profit_loss'],'profit_loss_fc_amt'=>  $profit_loss_detail['fc_profit_loss'],
          'total_opening_debitbalance'=> $total_opening_debitbalance ,
          'total_opening_creditbalance'=>$total_opening_creditbalance,
          'total_total_debit'=>$total_total_debit,
          'total_total_credit'=>$total_total_credit,
          'total_closing_debit_balance'=>$total_closing_debit_balance,
          'total_closing_credit_balance'=>$total_closing_credit_balance,
          'total_fcamt_opening_debitbalance'=>$total_fcamt_opening_debitbalance,
          'total_fcamt_opening_creditbalance'=>$total_fcamt_opening_creditbalance,
          'total_fcamt_total_debit'=>$total_fcamt_total_debit,
          'total_fcamt_total_credit'=>$total_fcamt_total_credit,
          'total_fcamt_closing_debit_balance'=>$total_fcamt_closing_debit_balance,
           'total_fcamt_closing_credit_balance'=>$total_fcamt_closing_credit_balance ,
           'total_opening_debitcredit_diff'=>  $total_opening_debitcredit_diff ,
           'total_total_debit_credit_diff'=>  $total_total_debit_credit_diff ,
           'total_closing_debit_credit_balance_diff'=>$total_closing_debit_credit_balance_diff ,
           'fcamt_total_opening_debitcredit_diff'=>$fcamt_total_opening_debitcredit_diff ,
           'fcamt_total_total_debit_credit_diff'=>$fcamt_total_total_debit_credit_diff ,
           'fcamt_total_closing_debit_credit_balance_diff'=>$fcamt_total_closing_debit_credit_balance_diff
          
          ); 
        
            $balance_report_inputs_json=json_encode(  $balance_report_inputs);
          
            Cache::put( $user_id."_balance_report",$balance_report_inputs_json);
        
          } 
          else if(  !empty(Cache::get( $user_id."_balance_report") ) ){
        
            $balance_report_inputs= json_decode(Cache::get( $user_id."_balance_report"),true);
           
            $start_date_string=$balance_report_inputs['start_date'];
            $end_date_string =$balance_report_inputs['end_date'];
            $show_foreigncurrency=$balance_report_inputs['show_foreigncurrency'];
            $showzeros=$balance_report_inputs['show_zeros'];
            $selected_accounts=$balance_report_inputs['all_accounts']; 
            $balances=$balance_report_inputs['main_balances'];
            $assets_accounts=$balance_report_inputs['assets_accounts'];
            $liabilities_accounts=$balance_report_inputs['liabilities_accounts'];
            $selected_account_level=$balance_report_inputs['selected_account_level'];
            $cost_center=$balance_report_inputs['cost_center'];
            $division=$balance_report_inputs['division'];
            $total_assets =$balance_report_inputs['total_assets'];
            $total_fcamt_assets=$balance_report_inputs['total_fcamt_assets'];
            $total_liabilities=$balance_report_inputs['total_liabilities'];
            $total_fcamt_liabilities=$balance_report_inputs['total_fcamt_liabilities'];
            $profit_loss_detail['profit_loss']=$balance_report_inputs['profit_loss_amt'];
            $profit_loss_detail['fc_profit_loss']=$balance_report_inputs['profit_loss_fc_amt'];

            $total_opening_debitbalance=$balance_report_inputs['total_opening_debitbalance'];
            $total_opening_creditbalance=$balance_report_inputs['total_opening_creditbalance'];
            $total_total_debit=$balance_report_inputs['total_total_debit'];
            $total_total_credit=$balance_report_inputs['total_total_credit'];
            $total_closing_debit_balance=$balance_report_inputs['total_closing_debit_balance'];;
            $total_closing_credit_balance=$balance_report_inputs['total_closing_credit_balance'];;
            $total_fcamt_opening_debitbalance=$balance_report_inputs['total_fcamt_opening_debitbalance'];;
            $total_fcamt_opening_creditbalance=$balance_report_inputs['total_fcamt_opening_creditbalance'];;
            $total_fcamt_total_debit=$balance_report_inputs['total_fcamt_total_debit'];;
            $total_fcamt_total_credit=$balance_report_inputs['total_fcamt_total_credit'];;
            $total_fcamt_closing_debit_balance=$balance_report_inputs['total_fcamt_closing_debit_balance'];;
            $total_fcamt_closing_credit_balance=$balance_report_inputs['total_fcamt_closing_credit_balance'];;
            $showdetails=$balance_report_inputs['show_details'];;
            $total_opening_debitcredit_diff=$balance_report_inputs['total_opening_debitcredit_diff'];;
            $total_total_debit_credit_diff=$balance_report_inputs['total_total_debit_credit_diff'];;
            $total_closing_debit_credit_balance_diff=$balance_report_inputs['total_closing_debit_credit_balance_diff'];;
            $fcamt_total_opening_debitcredit_diff=$balance_report_inputs['fcamt_total_opening_debitcredit_diff'];
            $fcamt_total_total_debit_credit_diff =$balance_report_inputs['fcamt_total_total_debit_credit_diff'];
             $fcamt_total_closing_debit_credit_balance_diff=$balance_report_inputs['fcamt_total_closing_debit_credit_balance_diff'];
          }
          else{
            $start_date_string= date("d-m-Y",strtotime($startEndDate->fs_date));
        
            $end_date_string=date("d-m-Y",strtotime($startEndDate->fe_date));;
          }
        
          $selected_accounts_collection = collect( $selected_accounts);  
        
          $assets_accounts_collection=collect( $assets_accounts);
        
        
          $liabilities_accounts_collection=collect($liabilities_accounts);
        
          $selected_accounts_data_vertical= $this->reportservice->paginate(['company_name'=>$companyname,'report_type'=>'vertical','report_for'=>'vertical'] ,'company.balance_sheet',$selected_accounts_collection, 10);
        
          $assets_accounts_horizontal= $this->reportservice->paginate(['company_name'=>$companyname,'report_type'=>'horizontal','report_for'=>'expenses'] ,'company.balance_sheet',$assets_accounts_collection, 10);
        
          $liabilities_accounts_horizontal= $this->reportservice->paginate(['company_name'=>$companyname,'report_type'=>'horizontal','report_for'=>'incomes'] ,'company.balance_sheet', $liabilities_accounts_collection, 10);
        
        return view('reports.balance_sheet',compact('accounts' ,'companyname','costcenters' ,'start_date_string','end_date_string'   ,'showzeros', 'show_foreigncurrency'   ,'divisions'    ,'selected_account_level' ,'report_name' ,'total_assets','total_fcamt_assets','total_liabilities','total_fcamt_liabilities','cost_center','division','balances','selected_accounts_data_vertical','assets_accounts_horizontal','report_type','liabilities_accounts_horizontal','profit_loss_detail',
      'total_opening_debitbalance','total_opening_creditbalance','total_total_debit','total_total_credit','total_closing_debit_balance','total_closing_credit_balance','total_fcamt_opening_debitbalance','total_fcamt_opening_creditbalance',
      'total_fcamt_total_debit','total_fcamt_total_credit','total_fcamt_closing_debit_balance','total_fcamt_closing_credit_balance','showdetails',
      'total_opening_debitcredit_diff','total_total_debit_credit_diff','total_closing_debit_credit_balance_diff' ,'fcamt_total_opening_debitcredit_diff','fcamt_total_total_debit_credit_diff',
      'fcamt_total_closing_debit_credit_balance_diff'
      ));
        

      }



      public function downloadBalanceSheetReport($companyname,$report_type="vertical",$format="xlsx"){

        $user_id=Auth::user()->id; 
         $balance_sheet_inputs_json=Cache::get( $user_id."_balance_report");

         $balance_sheet_inputs_array=json_decode( $balance_sheet_inputs_json,true);

         $start_date_string=  $balance_sheet_inputs_array['start_date'];
         $end_date_string =  $balance_sheet_inputs_array['end_date']; 
         $showdetails =$balance_sheet_inputs_array['show_details'];  

         $show_foreigncurrency=$balance_sheet_inputs_array['show_foreigncurrency'];
         $name_of_company=$balance_sheet_inputs_array['name_of_company'];
         $financial_year=$balance_sheet_inputs_array['financial_year'];
         $showzeros=$balance_sheet_inputs_array['show_zeros'];
         $child_balances=$balance_sheet_inputs_array['all_balances'];
         $selected_accounts=$balance_sheet_inputs_array['all_accounts'];
         $balances=$balance_sheet_inputs_array['main_balances'];
         $assets_accounts=$balance_sheet_inputs_array['assets_accounts'];

         $liabilities_accounts=$balance_sheet_inputs_array['liabilities_accounts'];
         $total_assets=$balance_sheet_inputs_array['total_assets'];
         $total_fcamt_assets=$balance_sheet_inputs_array['total_fcamt_assets'];
         $total_liabilities=$balance_sheet_inputs_array['total_liabilities'];
         $total_fcamt_liabilities=$balance_sheet_inputs_array['total_fcamt_liabilities'];
         $selected_account_level=$balance_sheet_inputs_array['selected_account_level'];
         $cost_center=$balance_sheet_inputs_array['cost_center'];
         $division=$balance_sheet_inputs_array['division'];

         $profit_loss_detail=array();

         $profit_loss_detail['profit_loss']=$balance_sheet_inputs_array['profit_loss_amt'];

         $profit_loss_detail['fc_profit_loss']=$balance_sheet_inputs_array['profit_loss_fc_amt'];

         $total_opening_debitbalance=$balance_sheet_inputs_array['total_opening_debitbalance'];

         $total_opening_creditbalance=$balance_sheet_inputs_array['total_opening_creditbalance'];

         $total_total_debit=$balance_sheet_inputs_array['total_total_debit'];

         $total_total_credit=$balance_sheet_inputs_array['total_total_credit'];



         $total_closing_debit_balance=$balance_sheet_inputs_array['total_closing_debit_balance'];

         $total_closing_credit_balance=$balance_sheet_inputs_array['total_closing_credit_balance'];

         $total_fcamt_opening_debitbalance=$balance_sheet_inputs_array['total_fcamt_opening_debitbalance'];

         $total_fcamt_opening_creditbalance=$balance_sheet_inputs_array['total_fcamt_opening_creditbalance'];

          

         $total_fcamt_total_debit=$balance_sheet_inputs_array['total_fcamt_total_debit'];

         $total_fcamt_total_credit=$balance_sheet_inputs_array['total_fcamt_total_credit'];

         $total_fcamt_closing_debit_balance=$balance_sheet_inputs_array['total_fcamt_closing_debit_balance'];

         $total_fcamt_closing_credit_balance =$balance_sheet_inputs_array['total_fcamt_closing_credit_balance'];

       
         $total_opening_debitcredit_diff=$balance_sheet_inputs_array['total_opening_debitcredit_diff'];

         $total_total_debit_credit_diff=$balance_sheet_inputs_array['total_total_debit_credit_diff'];

         $total_closing_debit_credit_balance_diff =$balance_sheet_inputs_array['total_closing_debit_credit_balance_diff'];

         $fcamt_total_opening_debitcredit_diff=$balance_sheet_inputs_array['fcamt_total_opening_debitcredit_diff'];

  
 
         $fcamt_total_total_debit_credit_diff =$balance_sheet_inputs_array['fcamt_total_total_debit_credit_diff'];
 
         $fcamt_total_closing_debit_credit_balance_diff=$balance_sheet_inputs_array['fcamt_total_closing_debit_credit_balance_diff'];


         $datas=array( 
          'all_accounts'=>  $selected_accounts,'name_of_company'=>   $name_of_company ,'financial_year'=>$financial_year,
          'show_foreigncurrency'=>$show_foreigncurrency,'start_date'=> $start_date_string ,'end_date'=> $end_date_string,'report_name'=>'Balance Sheet Report','total_assets'=>$total_assets,'total_fcamt_assets'=> $total_fcamt_assets,'total_liabilities'=>$total_liabilities,'total_fcamt_liabilities'=>$total_fcamt_liabilities,'report_type'=>$report_type ,'assets_accounts'=> $assets_accounts,'liabilities_accounts'=>$liabilities_accounts ,
          'profit_loss_detail'=>  $profit_loss_detail ,
          'total_opening_debitbalance'=>$total_opening_debitbalance,
          'total_opening_creditbalance'=>$total_opening_creditbalance,
          'total_total_debit'=>$total_total_debit,
          'total_total_credit'=>$total_total_credit,
          'total_closing_debit_balance'=>$total_closing_debit_balance,
          'total_closing_credit_balance'=>$total_closing_credit_balance,
          'total_fcamt_opening_debitbalance'=>$total_fcamt_opening_debitbalance,
          'total_fcamt_opening_creditbalance'=>$total_fcamt_opening_creditbalance ,
          'total_fcamt_total_debit'=> $total_fcamt_total_debit ,
          'total_fcamt_total_credit'=>$total_fcamt_total_credit,
          'total_fcamt_closing_debit_balance'=>$total_fcamt_closing_debit_balance,
          'total_fcamt_closing_credit_balance'=>$total_fcamt_closing_credit_balance,
          'total_opening_debitcredit_diff'=>$total_opening_debitcredit_diff ,
          'total_total_debit_credit_diff'=>$total_total_debit_credit_diff ,
          'total_closing_debit_credit_balance_diff'=> $total_closing_debit_credit_balance_diff ,
          'fcamt_total_opening_debitcredit_diff'=>$fcamt_total_opening_debitcredit_diff ,
          'fcamt_total_total_debit_credit_diff'=> $fcamt_total_total_debit_credit_diff  ,
          'fcamt_total_closing_debit_credit_balance_diff'=>$fcamt_total_closing_debit_credit_balance_diff ,
          'show_details'=> $showdetails ,
          'all_balances'=> $child_balances

        );

      
          // return view('reports.downloadformats.balance_sheet_format',    $datas );

          $downloadfilename="balance_report-".$report_type."-".formatDateInYmd($start_date_string )."-".formatDateInYmd($end_date_string );
            
                      
            if($format=="pdf"){  

                  $pdf = PDF::loadView('reports.downloadformats.balance_sheet_format', $datas)->setPaper('a3')->setOrientation('landscape');
                  
                  $pdf->setTimeout(2*60*60);
                  return $pdf->download(       $downloadfilename.'.pdf');

                  } 
                         
              if($format=="xlsx" || $format=="csv" ){
        
                    return  Excel::download(new BalanceSheetView(   $datas ),        $downloadfilename.'.'.strtolower($format));
                }

            

      }

}
