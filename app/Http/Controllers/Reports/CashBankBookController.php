<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Costcentre;
use App\Models\Division;
use App\Models\Company;
use Session;
use Illuminate\Support\Collection;
use DB;  
use App\Http\Controllers\Services\ReportService; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use PDF;
use Excel;
use App\Exports\GeneralLedgerView;

class CashBankBookController extends Controller
{

    protected $reportservice;

    public function __construct(ReportService $rservice){

     $this->reportservice= $rservice;


    }
     
  
    public function openCashBook(Request $request, $id){
 
        $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get(); 
        $divisions=Division::pluck('division','Id')->toArray();

        $startEndDate = Company::getStartEndDate($id);

        $name_of_company=  $startEndDate->comp_name;

        $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ". date('d/m/Y',strtotime($startEndDate->fe_date));

        $accounts=Account::where("ACName","Cash Balance")->select('ACName as account_name','Id as id','G-A as ga')->get();
        $companyname=Session::get('company_name');
        $selected_costcenter="";
        $selected_division ="";

        $ChequeNo ="";
        $ChequeStatus = "";
        $ClearingDate = "";
        $CostCentre ="";
        $division ="";
        $Executive ="";
        $Project = "";
        $ForeignCurrency ="";
        $arraydata = array(); 

        $user_id=Auth::user()->id;

        $no_of_additional_columns=0;

       $account_ids = []; 

       if($request->method()=="POST"){

           $fromdate = $request->selectVchFromDate;
           $todate = $request->selectVchToDate;

           $selected_costcenter=$request->costId; 
           $CostCentre = $request->CostCentre ;

           $selected_division = $request->select_division;
           
           $startEndDate =new Collection( );
           $startEndDate->fs_date= $fromdate ;
           $startEndDate->fe_date=$todate;
           
              $ChequeNo = $request->ChequeNo;
               $ChequeStatus = $request->ChequeStatus;
               $ClearingDate = $request->ClearingDate; 
                $division=$request->division;
               $Executive = $request->Executive; 
               $ForeignCurrency = $request->ForeignCurrency;


               if(!empty(    $ChequeNo)){
                  $no_of_additional_columns++;
               }

               
               if(!empty(      $ChequeStatus )){
                   $no_of_additional_columns++;
                }
                
                    
               if(!empty(    $ClearingDate)){
                   $no_of_additional_columns++;
                }

                    
               if(!empty(    $CostCentre )){
                   $no_of_additional_columns++;
                }

                if(!empty(       $division )){
                   $no_of_additional_columns++;
                }

                if(!empty(      $Executive )){
                   $no_of_additional_columns++;
                }

              $selected_accounts= $request->selected_accounts;

              $all_accounts=array(); 
              if($selected_accounts==null){ 

               goto line1098;
              }

              $this->reportservice->company_name=Session::get('company_name');
              $this->reportservice->setAccountTreeData();
       
              $account_tree_data= $this->reportservice->account_tree_data;
         
               foreach($selected_accounts as $single_account){

                   if(!in_array($single_account,$all_accounts)){
                       
                       array_push($all_accounts,$single_account);

                       $this->reportservice->account_id=$single_account;

                       $child_accounts=  $this->reportservice->getChildrenAccountIds();

                       $all_accounts= array_merge($all_accounts, $child_accounts); 

                   } 
                   }

               $all_accounts= array_merge($all_accounts,  $selected_accounts );
               $all_accounts= array_unique($all_accounts);

                   line1098:  

               $general_ledger_inputs=array('all_accounts'=>$all_accounts,
               'fromdate'=>$fromdate,'todate'=>$todate,
               'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division,
               'chequeno'=>  $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=>$ClearingDate,
               'costcentre'=> $CostCentre,
                   'division'=>   $division,'executive'=>   $Executive   ,'foreignCurrency'=> $ForeignCurrency,
                   'no_of_additional_columns'=>$no_of_additional_columns ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year
                   );



                   $account_ids=$all_accounts;

               $general_ledger_inputs_json=json_encode( $general_ledger_inputs);

               if(!empty(Cache::get($user_id."_cash_book_inputs"))){
                   Cache::forget($user_id."_cash_book_inputs"); 
               }

               Cache::put($user_id."_cash_book_inputs", $general_ledger_inputs_json,108000);


           }
           else if(!empty(Cache::get($user_id."_cash_book_inputs"))){

               $general_ledger_inputs=json_decode(Cache::get($user_id."_cash_book_inputs") ,true);

               $account_ids=  $general_ledger_inputs['all_accounts'];

               $startEndDate =new Collection( );
               $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
               $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

               $selected_costcenter=$general_ledger_inputs['selected_costcenter'] ;
               $selected_division=$general_ledger_inputs['selected_division'] ;
                
                  $ChequeNo = $general_ledger_inputs['chequeno'];
                   $ChequeStatus =$general_ledger_inputs['chequestatus'];
                   $ClearingDate =$general_ledger_inputs['clearingdate'];
                   $CostCentre = $general_ledger_inputs['costcentre'];
                   $division = $general_ledger_inputs['division'];
                   $Executive = $general_ledger_inputs['executive']; 
                   $ForeignCurrency = $general_ledger_inputs['foreignCurrency'];
                   $no_of_additional_columns= $general_ledger_inputs['no_of_additional_columns'];
                   $name_of_company=$general_ledger_inputs['name_of_company'];
                   $financial_year=$general_ledger_inputs['financial_year'];


           }

             
       $accounts_collection = collect($account_ids);
  
       $accounts_data = $this->reportservice->paginate(Session::get('company_name'),'company.cash_book',$accounts_collection,3);
 
        $report_name="Cash Book";

        $general_ledger_submit_url=route('company.cash_book_submit' ,['company_name'=>$companyname]);

        $general_ledger_url=  route('company.cash_book_submit' ,['company_name'=>$companyname]);

        $general_ledger_download_url=  route('company.download_cash_book_report',['company_name'=>$companyname]);
        
        $general_ledger_cancel_inputs_url= route('company.cancel_cache_report_inputs',['company_name'=>$companyname,'reportname'=>'cash-book']);

        return view('Company.general_ledger_new', ['id' => $id ,  'costdata' => $costdata , 'companyDates' => $startEndDate,'accounts'=>$accounts,'companyname'=>$companyname ,
        "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
        'division'=>$division ,'executive'=>$Executive,'project'=>$Project,'foreigncurrency'=>$ForeignCurrency,'arraydata'=>$arraydata,'accounts_data'=> $accounts_data,'no_of_additional_columns'=>$no_of_additional_columns  ,
       'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,'divisions'=>  $divisions  ,'report_name'=>$report_name,'general_ledger_submit_url'=>  $general_ledger_submit_url ,
       'general_ledger_url'=> $general_ledger_url,'general_ledger_download_url'=>$general_ledger_download_url,'general_ledger_cancel_inputs_url'=>$general_ledger_cancel_inputs_url
       ]);

   }
 

   public function downloadCashBookReport($companyname,$format="xlsx"){
 
    $user_id=Auth::user()->id;

    if(empty(Cache::get($user_id."_cash_book_inputs"))){
        echo "Please seacrh before export";

        exit();
    }

    
    $general_ledger_inputs=json_decode(Cache::get($user_id."_cash_book_inputs") ,true); 
    $account_ids=  $general_ledger_inputs['all_accounts'];    
    $startEndDate =new Collection( );
    $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
    $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

    
    $downloadfilename=  "cashbook-".str_replace("-","",$general_ledger_inputs['fromdate'])."-".str_replace("-","",$general_ledger_inputs['todate']);
         
       $ChequeNo = $general_ledger_inputs['chequeno'];
        $ChequeStatus =$general_ledger_inputs['chequestatus'];
        $ClearingDate =$general_ledger_inputs['clearingdate'];
        $CostCentre = $general_ledger_inputs['costcentre'];
        $division = $general_ledger_inputs['division'];
        $Executive = $general_ledger_inputs['executive']; 
        $ForeignCurrency = $general_ledger_inputs['foreignCurrency'];
        $no_of_additional_columns= $general_ledger_inputs['no_of_additional_columns'];

        $selected_costcenter=$general_ledger_inputs['selected_costcenter'];
        $selected_division=$general_ledger_inputs['selected_division'];
        $name_of_company=$general_ledger_inputs['name_of_company'];
        $financial_year=$general_ledger_inputs['financial_year'];

//      return view('reports.downloadformats.general_ledger_format', [      'companyDates' => $startEndDate,
//      "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
//      'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
//    'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'General Ledger','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year

// ]);


    if($format=="xlsx"){
       return  Excel::download(new GeneralLedgerView( $name_of_company ,$financial_year, $startEndDate,  $selected_costcenter ,  $selected_division,$ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,"Cash Book"), $downloadfilename.'.xlsx');

    }

    if($format=="pdf"){ 
    $datas=array(   'companyDates' => $startEndDate,
    "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
    'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
  'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'Cash Book','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year);
   
     $pdf = PDF::loadView('reports.downloadformats.general_ledger_format', $datas)->setPaper('a3')->setOrientation('landscape');
    $pdf->setTimeout(2*60*60);
    return $pdf->download($downloadfilename.'.pdf');
    }

    if($format=="csv"){
        return  Excel::download(new GeneralLedgerView($name_of_company , $financial_year , $startEndDate,$selected_costcenter ,  $selected_division, $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,"Cash Book"), $downloadfilename.'.csv' );

    }
     


}



public function openBankBook(Request $request, $id){
 
    $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get(); 
    $divisions=Division::pluck('division','Id')->toArray();

    $startEndDate = Company::getStartEndDate($id);

    $name_of_company=  $startEndDate->comp_name;

    $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ". date('d/m/Y',strtotime($startEndDate->fe_date));

    $accounts=Account::where("ACName","Bank Balance")->select('ACName as account_name','Id as id','G-A as ga')->get();
    $companyname=Session::get('company_name');
    $selected_costcenter="";
    $selected_division ="";

    $ChequeNo ="";
    $ChequeStatus = "";
    $ClearingDate = "";
    $CostCentre ="";
    $division ="";
    $Executive ="";
    $Project = "";
    $ForeignCurrency ="";
    $arraydata = array(); 

    $user_id=Auth::user()->id;

    $no_of_additional_columns=0;

   $account_ids = []; 

   if($request->method()=="POST"){

       $fromdate = $request->selectVchFromDate;
       $todate = $request->selectVchToDate;

       $selected_costcenter=$request->costId; 
       $CostCentre = $request->CostCentre ;

       $selected_division = $request->select_division;
       
       $startEndDate =new Collection( );
       $startEndDate->fs_date= $fromdate ;
       $startEndDate->fe_date=$todate;
       
          $ChequeNo = $request->ChequeNo;
           $ChequeStatus = $request->ChequeStatus;
           $ClearingDate = $request->ClearingDate; 
            $division=$request->division;
           $Executive = $request->Executive; 
           $ForeignCurrency = $request->ForeignCurrency;


           if(!empty(    $ChequeNo)){
              $no_of_additional_columns++;
           }

           
           if(!empty(      $ChequeStatus )){
               $no_of_additional_columns++;
            }
            
                
           if(!empty(    $ClearingDate)){
               $no_of_additional_columns++;
            }

                
           if(!empty(    $CostCentre )){
               $no_of_additional_columns++;
            }

            if(!empty(       $division )){
               $no_of_additional_columns++;
            }

            if(!empty(      $Executive )){
               $no_of_additional_columns++;
            }

          $selected_accounts= $request->selected_accounts;

          $all_accounts=array(); 
          if($selected_accounts==null){ 

           goto line1098;
          }

          $this->reportservice->company_name=Session::get('company_name');
          $this->reportservice->setAccountTreeData();
   
          $account_tree_data= $this->reportservice->account_tree_data;
     
           foreach($selected_accounts as $single_account){

               if(!in_array($single_account,$all_accounts)){
                   
                   array_push($all_accounts,$single_account);

                   $this->reportservice->account_id=$single_account;

                   $child_accounts=  $this->reportservice->getChildrenAccountIds();

                   $all_accounts= array_merge($all_accounts, $child_accounts); 

               } 
               }

           $all_accounts= array_merge($all_accounts,  $selected_accounts );
           $all_accounts= array_unique($all_accounts);

               line1098:  

           $general_ledger_inputs=array('all_accounts'=>$all_accounts,
           'fromdate'=>$fromdate,'todate'=>$todate,
           'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division,
           'chequeno'=>  $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=>$ClearingDate,
           'costcentre'=> $CostCentre,
               'division'=>   $division,'executive'=>   $Executive   ,'foreignCurrency'=> $ForeignCurrency,
               'no_of_additional_columns'=>$no_of_additional_columns ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year
               );



               $account_ids=$all_accounts;

           $general_ledger_inputs_json=json_encode( $general_ledger_inputs);

           if(!empty(Cache::get($user_id."_bank_book_inputs"))){
               Cache::forget($user_id."_bank_book_inputs"); 
           }

           Cache::put($user_id."_bank_book_inputs", $general_ledger_inputs_json,108000);


       }
       else if(!empty(Cache::get($user_id."_bank_book_inputs"))){

           $general_ledger_inputs=json_decode(Cache::get($user_id."_bank_book_inputs") ,true);

           $account_ids=  $general_ledger_inputs['all_accounts'];

           $startEndDate =new Collection( );
           $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
           $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

           $selected_costcenter=$general_ledger_inputs['selected_costcenter'] ;
           $selected_division=$general_ledger_inputs['selected_division'] ;
            
              $ChequeNo = $general_ledger_inputs['chequeno'];
               $ChequeStatus =$general_ledger_inputs['chequestatus'];
               $ClearingDate =$general_ledger_inputs['clearingdate'];
               $CostCentre = $general_ledger_inputs['costcentre'];
               $division = $general_ledger_inputs['division'];
               $Executive = $general_ledger_inputs['executive']; 
               $ForeignCurrency = $general_ledger_inputs['foreignCurrency'];
               $no_of_additional_columns= $general_ledger_inputs['no_of_additional_columns'];
               $name_of_company=$general_ledger_inputs['name_of_company'];
               $financial_year=$general_ledger_inputs['financial_year'];


       }

         
   $accounts_collection = collect($account_ids);

   $accounts_data = $this->reportservice->paginate(Session::get('company_name'),'company.bank_book',$accounts_collection,3);

    $report_name="Bank Book";

    $general_ledger_submit_url=route('company.bank_book_submit' ,['company_name'=>$companyname]);

    $general_ledger_url=  route('company.bank_book' ,['company_name'=>$companyname]);

    $general_ledger_download_url=  route('company.download_bank_book_report',['company_name'=>$companyname]);
    
    $general_ledger_cancel_inputs_url= route('company.cancel_cache_report_inputs',['company_name'=>$companyname,'reportname'=>'bank-book']);

    return view('Company.general_ledger_new', ['id' => $id ,  'costdata' => $costdata , 'companyDates' => $startEndDate,'accounts'=>$accounts,'companyname'=>$companyname ,
    "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
    'division'=>$division ,'executive'=>$Executive,'project'=>$Project,'foreigncurrency'=>$ForeignCurrency,'arraydata'=>$arraydata,'accounts_data'=> $accounts_data,'no_of_additional_columns'=>$no_of_additional_columns  ,
   'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,'divisions'=>  $divisions  ,'report_name'=>$report_name,'general_ledger_submit_url'=>  $general_ledger_submit_url ,
   'general_ledger_url'=> $general_ledger_url,'general_ledger_download_url'=>$general_ledger_download_url,'general_ledger_cancel_inputs_url'=>$general_ledger_cancel_inputs_url
   ]);

}



public function downloadBankBookReport($companyname,$format="xlsx"){
 
    $user_id=Auth::user()->id;

    if(empty(Cache::get($user_id."_bank_book_inputs"))){
        echo "Please seacrh before export";

        exit();
    }

    
    $general_ledger_inputs=json_decode(Cache::get($user_id."_bank_book_inputs") ,true); 
    $account_ids=  $general_ledger_inputs['all_accounts'];    
    $startEndDate =new Collection( );
    $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
    $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

    
    $downloadfilename=  "bankbook-".str_replace("-","",$general_ledger_inputs['fromdate'])."-".str_replace("-","",$general_ledger_inputs['todate']);
         
       $ChequeNo = $general_ledger_inputs['chequeno'];
        $ChequeStatus =$general_ledger_inputs['chequestatus'];
        $ClearingDate =$general_ledger_inputs['clearingdate'];
        $CostCentre = $general_ledger_inputs['costcentre'];
        $division = $general_ledger_inputs['division'];
        $Executive = $general_ledger_inputs['executive']; 
        $ForeignCurrency = $general_ledger_inputs['foreignCurrency'];
        $no_of_additional_columns= $general_ledger_inputs['no_of_additional_columns'];

        $selected_costcenter=$general_ledger_inputs['selected_costcenter'];
        $selected_division=$general_ledger_inputs['selected_division'];
        $name_of_company=$general_ledger_inputs['name_of_company'];
        $financial_year=$general_ledger_inputs['financial_year'];

//      return view('reports.downloadformats.general_ledger_format', [      'companyDates' => $startEndDate,
//      "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
//      'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
//    'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'General Ledger','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year

// ]);


    if($format=="xlsx"){
       return  Excel::download(new GeneralLedgerView( $name_of_company ,$financial_year, $startEndDate,  $selected_costcenter ,  $selected_division,$ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,"Bank Book"), $downloadfilename.'.xlsx');

    }

    if($format=="pdf"){ 
    $datas=array(   'companyDates' => $startEndDate,
    "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
    'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
  'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'Bank Book','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year);
   
     $pdf = PDF::loadView('reports.downloadformats.general_ledger_format', $datas)->setPaper('a3')->setOrientation('landscape');
    $pdf->setTimeout(2*60*60);
    return $pdf->download($downloadfilename.'.pdf');
    }

    if($format=="csv"){
        return  Excel::download(new GeneralLedgerView($name_of_company , $financial_year , $startEndDate,$selected_costcenter ,  $selected_division, $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,"Bank Book"), $downloadfilename.'.csv' );

    } 

}


public function openPettyCashBook(Request $request, $id){ 

    $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get(); 
    $divisions=Division::pluck('division','Id')->toArray();

    $startEndDate = Company::getStartEndDate($id);

    $name_of_company=  $startEndDate->comp_name;

    $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ". date('d/m/Y',strtotime($startEndDate->fe_date));

    $accounts=Account::where("ACName", 'LIKE',"%Petty%")->select('ACName as account_name','Id as id','G-A as ga')->get();
    $companyname=Session::get('company_name');
    $selected_costcenter="";
    $selected_division ="";

    $ChequeNo ="";
    $ChequeStatus = "";
    $ClearingDate = "";
    $CostCentre ="";
    $division ="";
    $Executive ="";
    $Project = "";
    $ForeignCurrency ="";
    $arraydata = array(); 

    $user_id=Auth::user()->id;

    $no_of_additional_columns=0;

   $account_ids = []; 

   if($request->method()=="POST"){

       $fromdate = $request->selectVchFromDate;
       $todate = $request->selectVchToDate;

       $selected_costcenter=$request->costId; 
       $CostCentre = $request->CostCentre ;

       $selected_division = $request->select_division;
       
       $startEndDate =new Collection( );
       $startEndDate->fs_date= $fromdate ;
       $startEndDate->fe_date=$todate;
       
          $ChequeNo = $request->ChequeNo;
           $ChequeStatus = $request->ChequeStatus;
           $ClearingDate = $request->ClearingDate; 
            $division=$request->division;
           $Executive = $request->Executive; 
           $ForeignCurrency = $request->ForeignCurrency;


           if(!empty(    $ChequeNo)){
              $no_of_additional_columns++;
           }

           
           if(!empty(      $ChequeStatus )){
               $no_of_additional_columns++;
            }
            
                
           if(!empty(    $ClearingDate)){
               $no_of_additional_columns++;
            }

                
           if(!empty(    $CostCentre )){
               $no_of_additional_columns++;
            }

            if(!empty(       $division )){
               $no_of_additional_columns++;
            }

            if(!empty(      $Executive )){
               $no_of_additional_columns++;
            }

          $selected_accounts= $request->selected_accounts;

          $all_accounts=array(); 
          if($selected_accounts==null){ 

           goto line1098;
          }

          $this->reportservice->company_name=Session::get('company_name');
          $this->reportservice->setAccountTreeData();
   
          $account_tree_data= $this->reportservice->account_tree_data;
     
           foreach($selected_accounts as $single_account){

               if(!in_array($single_account,$all_accounts)){
                   
                   array_push($all_accounts,$single_account);

                   $this->reportservice->account_id=$single_account;

                   $child_accounts=  $this->reportservice->getChildrenAccountIds();

                   $all_accounts= array_merge($all_accounts, $child_accounts); 

               } 
               }

           $all_accounts= array_merge($all_accounts,  $selected_accounts );
           $all_accounts= array_unique($all_accounts);

               line1098:  

           $general_ledger_inputs=array('all_accounts'=>$all_accounts,
           'fromdate'=>$fromdate,'todate'=>$todate,
           'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division,
           'chequeno'=>  $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=>$ClearingDate,
           'costcentre'=> $CostCentre,
               'division'=>   $division,'executive'=>   $Executive   ,'foreignCurrency'=> $ForeignCurrency,
               'no_of_additional_columns'=>$no_of_additional_columns ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year
               );



               $account_ids=$all_accounts;

           $general_ledger_inputs_json=json_encode( $general_ledger_inputs);
 

           if(!empty(Cache::get($user_id."_petty_cash_book_inputs"))){
               Cache::forget($user_id."_petty_cash_book_inputs"); 
           }

           Cache::put($user_id."_petty_cash_book_inputs", $general_ledger_inputs_json,108000);


       }
       else if(!empty(Cache::get($user_id."_petty_cash_book_inputs"))){

           $general_ledger_inputs=json_decode(Cache::get($user_id."_petty_cash_book_inputs") ,true);

           $account_ids=  $general_ledger_inputs['all_accounts'];

           $startEndDate =new Collection( );
           $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
           $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

           $selected_costcenter=$general_ledger_inputs['selected_costcenter'] ;
           $selected_division=$general_ledger_inputs['selected_division'] ;
            
              $ChequeNo = $general_ledger_inputs['chequeno'];
               $ChequeStatus =$general_ledger_inputs['chequestatus'];
               $ClearingDate =$general_ledger_inputs['clearingdate'];
               $CostCentre = $general_ledger_inputs['costcentre'];
               $division = $general_ledger_inputs['division'];
               $Executive = $general_ledger_inputs['executive']; 
               $ForeignCurrency = $general_ledger_inputs['foreignCurrency'];
               $no_of_additional_columns= $general_ledger_inputs['no_of_additional_columns'];
               $name_of_company=$general_ledger_inputs['name_of_company'];
               $financial_year=$general_ledger_inputs['financial_year'];


       }

         
   $accounts_collection = collect($account_ids);

   $accounts_data = $this->reportservice->paginate(Session::get('company_name'),'company.petty_cash_book',$accounts_collection,3);

    $report_name="Petty Cash Book";

    $general_ledger_submit_url=route('company.petty_cash_book_submit' ,['company_name'=>$companyname]);

    $general_ledger_url=  route('company.petty_cash_book' ,['company_name'=>$companyname]);

    $general_ledger_download_url=  route('company.download_petty_cash_book_report',['company_name'=>$companyname]);
    
    $general_ledger_cancel_inputs_url= route('company.cancel_cache_report_inputs',['company_name'=>$companyname,'reportname'=>'petty-cash-book']);

    return view('Company.general_ledger_new', ['id' => $id ,  'costdata' => $costdata , 'companyDates' => $startEndDate,'accounts'=>$accounts,'companyname'=>$companyname ,
    "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
    'division'=>$division ,'executive'=>$Executive,'project'=>$Project,'foreigncurrency'=>$ForeignCurrency,'arraydata'=>$arraydata,'accounts_data'=> $accounts_data,'no_of_additional_columns'=>$no_of_additional_columns  ,
   'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,'divisions'=>  $divisions  ,'report_name'=>$report_name,'general_ledger_submit_url'=>  $general_ledger_submit_url ,
   'general_ledger_url'=> $general_ledger_url,'general_ledger_download_url'=>$general_ledger_download_url,'general_ledger_cancel_inputs_url'=>$general_ledger_cancel_inputs_url
   ]);

}




public function downloadPettyCashBookReport($companyname,$format="xlsx"){
 
    $user_id=Auth::user()->id;

    if(empty(Cache::get($user_id."_petty_cash_book_inputs"))){
        echo "Please seacrh before export";

        exit();
    }

    
    $general_ledger_inputs=json_decode(Cache::get($user_id."_petty_cash_book_inputs") ,true); 
    $account_ids=  $general_ledger_inputs['all_accounts'];    
    $startEndDate =new Collection( );
    $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
    $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

    
    $downloadfilename=  "pettycashbook-".str_replace("-","",$general_ledger_inputs['fromdate'])."-".str_replace("-","",$general_ledger_inputs['todate']);
         
       $ChequeNo = $general_ledger_inputs['chequeno'];
        $ChequeStatus =$general_ledger_inputs['chequestatus'];
        $ClearingDate =$general_ledger_inputs['clearingdate'];
        $CostCentre = $general_ledger_inputs['costcentre'];
        $division = $general_ledger_inputs['division'];
        $Executive = $general_ledger_inputs['executive']; 
        $ForeignCurrency = $general_ledger_inputs['foreignCurrency'];
        $no_of_additional_columns= $general_ledger_inputs['no_of_additional_columns'];

        $selected_costcenter=$general_ledger_inputs['selected_costcenter'];
        $selected_division=$general_ledger_inputs['selected_division'];
        $name_of_company=$general_ledger_inputs['name_of_company'];
        $financial_year=$general_ledger_inputs['financial_year'];

//      return view('reports.downloadformats.general_ledger_format', [      'companyDates' => $startEndDate,
//      "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
//      'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
//    'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'General Ledger','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year

// ]);


    if($format=="xlsx"){
       return  Excel::download(new GeneralLedgerView( $name_of_company ,$financial_year, $startEndDate,  $selected_costcenter ,  $selected_division,$ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,"Petty Cash Book"), $downloadfilename.'.xlsx');

    }

    if($format=="pdf"){ 
    $datas=array(   'companyDates' => $startEndDate,
    "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
    'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
  'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'Petty Cash Book','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year);
   
     $pdf = PDF::loadView('reports.downloadformats.general_ledger_format', $datas)->setPaper('a3')->setOrientation('landscape');
    $pdf->setTimeout(2*60*60);
    return $pdf->download($downloadfilename.'.pdf');
    }

    if($format=="csv"){
        return  Excel::download(new GeneralLedgerView($name_of_company , $financial_year , $startEndDate,$selected_costcenter ,  $selected_division, $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,"Petty Cash Book"), $downloadfilename.'.csv' );

    } 

}





}
