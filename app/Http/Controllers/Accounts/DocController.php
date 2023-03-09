<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Code;
use App\Models\Costcentre;
use App\Models\Department;
use App\Models\VchDet;
use App\Models\VchMain;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Division;
use stdClass; 
use Illuminate\Support\Collection;
use DB;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use PHPUnit\TextUI\XmlConfiguration\Php;
use Illuminate\Contracts\Session\Session as SessionSession;
use Session;
use App\Http\Controllers\Services\ReportService;
use PDF;
use App\Exports\ExportFromView;
use Excel;
use App\Exports\GeneralLedgerView;
use App\Models\Project; 
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Storage;
use App\Helper\Helper;
use App\Http\Controllers\Services\WhatsAppService;
 
class DocController extends Controller
{

    protected $reportservice;

    protected $whatsappservice;

    public function __construct(ReportService $rservice,WhatsAppService $wapservice){

     $this->reportservice= $rservice;

     $this->whatsappservice=$wapservice;


    }


    public function ViewSubLedger(Request $request,$companyname, $id = null)
    {
        $acdata = Account::select('Id', 'ACName')->orderBy('ACName', 'ASC')->get();
        $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get();
 
        $divisions = Division::pluck('division','Id')->toArray();
 
        $companyname = Session::get('company_name');
    
        $startEndDate = Company::getStartEndDate($companyname);

        $name_of_company= $startEndDate ->comp_name;

        $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ".date('d/m/Y',strtotime($startEndDate->fe_date));
  
        $account_id="";

        $user_id=Auth::user()->id;
  
        $selected_costcenter=NULL; 

        $no_of_additional_columns=0;

        $account_name=""; 

        $chequeno=NULL;

        $chequestatus=NULL;

        $clearingdate=NULL; 

        $costcenter=NULL;

        $department=NULL;

        $executive=NULL;

        $division=NULL;
 

        $foreigncurrency=NULL;
        $selected_division="";


        if($request->method()=="POST"){
 
            $account_id=$request->accountId;
            $account_name = Account::where('Id',$account_id)->value('ACName');
            $fromdate=$request->selectVchFromDate; 
            $todate=$request->selectVchToDate;  
            $selected_costcenter=$request->costId;
            $selected_division=$request->selected_division; 
            $startEndDate->fs_date=   $fromdate;
            $startEndDate->fe_date=  $todate;
            
            $financial_start= $fromdate;
            $financial_end= $todate; 

            $chequeno=$request->ChequeNo;

            $chequestatus=$request->ChequeStatus;

            $clearingdate=$request->ClearingDate; 

            $costcenter=$request->CostCentre;
 
            $executive=$request->Executive;

            $division=$request->division;

            $foreigncurrency=$request->ForeignCurrency;

            
            if(!empty(    $chequeno)){
                $no_of_additional_columns++;
             }

             
             if(!empty(      $chequestatus )){
                 $no_of_additional_columns++;
              }
              
                  
             if(!empty(    $clearingdate)){
                 $no_of_additional_columns++;
              }

                  
             if(!empty(   $showcostcenter)){
                 $no_of_additional_columns++;
              }

         

              if(!empty(  $executive )){
                 $no_of_additional_columns++;
              }

              if(!empty(  $division )){
                 $no_of_additional_columns++;
              }


              if(!empty(Cache::get( $user_id."_sub_ledger_inputs"))){
                Cache::forget( $user_id."_sub_ledger_inputs");
              }
 
            $sub_ledger_inputs=array('all_accounts'=>array(  $account_id),
            'fromdate'=>$fromdate,'todate'=>$todate, 'selected_costcenter'=>$selected_costcenter, 'selected_division'=>$selected_division    ,'chequeno'=>  $chequeno,'chequestatus'=>$chequestatus,'clearingdate'=>$clearingdate,
            'costcentre'=>   $costcenter,'executive'=> $executive,'division'=>$division,'foreignCurrency'=>$foreigncurrency ,'no_of_additional_columns'=>
                $no_of_additional_columns ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year
                );

            $sub_ledger_inputs_json=json_encode( $sub_ledger_inputs);

            if(!empty( Cache::get(  $user_id."_sub_ledger_inputs") )){
                Cache::forget(  $user_id."_sub_ledger_inputs") ;
            }
    
            Cache::put(  $user_id."_sub_ledger_inputs", $sub_ledger_inputs_json,108000);
 
   
        }
        else if(!empty( Cache::get(  $user_id."_sub_ledger_inputs"))){

           
            $sub_ledger_inputs=json_decode(Cache::get(  $user_id."_sub_ledger_inputs"),true);
 
            
            $account_id=  $sub_ledger_inputs['all_accounts'][0];
            $account_name = Account::where('Id',$account_id)->value('ACName');
            $fromdate=$sub_ledger_inputs['fromdate']; 
            $todate=$sub_ledger_inputs['todate']; 
            $selected_costcenter= $sub_ledger_inputs['selected_costcenter'];
            
            $selected_division= $sub_ledger_inputs['selected_division']; 
            $startEndDate->fs_date=   $fromdate;
            $startEndDate->fe_date=  $todate; 
            $financial_start= $fromdate;
            $financial_end= $todate;  
            $chequeno= $sub_ledger_inputs['chequeno'];

            $chequestatus=$sub_ledger_inputs['chequestatus'];

            $clearingdate=$sub_ledger_inputs['clearingdate'];

            $costcenter=$sub_ledger_inputs['costcentre'];
 
            $executive=$sub_ledger_inputs['executive'];

            $division=$sub_ledger_inputs['division'];

            $foreigncurrency=$sub_ledger_inputs['foreignCurrency'];

            
            if(!empty(    $chequeno)){
                $no_of_additional_columns++;
             }

             
             if(!empty(      $chequestatus )){
                 $no_of_additional_columns++;
              }
              
                  
             if(!empty(    $clearingdate)){
                 $no_of_additional_columns++;
              }

                  
             if(!empty(   $showcostcenter)){
                 $no_of_additional_columns++;
              }

         

              if(!empty(  $executive )){
                 $no_of_additional_columns++;
              }

              if(!empty(  $division )){
                 $no_of_additional_columns++;
              }
 
        }
        else if($request->method()=="GET"){
        if (!empty($id)) {
            $account_name = Account::where('Id', $id)->value('ACName');
            
             $company_detail=  Company::where('db_name',$companyname)->select('comp_name','fs_date','fe_date')->first();

             $company_name= $company_detail->comp_name;

             $financial_start=date("d/m/Y",strtotime($company_detail->fs_date));

             $financial_end=date("d/m/Y",strtotime($company_detail->fe_date));

        } else {
            $account_name = '';
            $company_name= '';
            $financial_start='';
            $financial_end='';
        }

    } 
 
 
        return view('Company.subledger', ['id' => $id, 'account_name' => $account_name, 'acdata' => $acdata,  'costdata' => $costdata , 'companyDates' => $startEndDate, 'companyname' => $companyname, 'free_style_array' => array() ,'financial_start'=>$financial_start,'financial_end'=>$financial_end,'account_id'=>$account_id ,'costcentre'=>  $costcenter , 'division'=>    $division,
       'chequeno'=>   $chequeno , 'chequestatus'=> $chequestatus ,'clearingdate'=>$clearingdate  ,'executive'=>$executive ,'foreigncurrency'=>$foreigncurrency,
         'no_of_additional_columns'=>$no_of_additional_columns , 'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year ,'divisions'=>$divisions]);


    }

    public function GetSubLedger(Request $request)
    {
        // echo '<pre>';print_r($request->sEcho);exit; 
        $draw = $request->sEcho;
        $row = $request->iDisplayStart;
        $rowperpage = $request->iDisplayLength; // Rows display per page
        $indexColumn = $request->iSortCol_0;
        $columnName = $request->mDataProp . '_' . $indexColumn; // Column name
        $columnSortOrder = $request->sSortDir_0; // asc or desc
        $accountid = $request->accountId;
        $fromdate = $request->selectVchFromDate;
        $todate = $request->selectVchToDate;
        $costCenter = $request->costCenter;
        $department = $request->department;
        $sSearch = $request->sSearch;
        $dropval = $request->selectDbField;
        $pageNo = $row / $rowperpage + 1;
        // echo "<pre>"; print_r($dropval);


        $sub = VchMain::getSubLedger($columnName, $columnSortOrder, $draw, $row, $rowperpage, $accountid, $fromdate, $todate, $department, $costCenter, $sSearch, $dropval);

        $opening = VchMain::getOpening($accountid, $fromdate);

        $closingBal = VchMain::getClosing($accountid, $fromdate, $todate);

        // dd($closingBal['ClosingFCbalance']);

        $company_name=Session::get('company_name');

        if(!empty(Cache::get($company_name."_subledger_inputs"))){
            Cache::forget($company_name."_subledger_inputs");
        }

        Cache::put($company_name."_subledger_inputs",json_encode(array('accountid'=>$accountid,'fromdate'=>$fromdate,'todate'=>$todate,'department'=>$department,'costcenter'=>$costCenter,'ssearch'=>$sSearch,'dropval'=> $dropval)),36000);

        $subcount = VchMain::getSubLedgerCount($accountid, $fromdate, $todate, $department, $costCenter, $sSearch, $dropval);

        $arraydata = array();

        if ($pageNo == 1) {
            $balance = 0;
            $totalCredit = 0;
            $totalDebit = 0;
            $FCbalance = 0;
            $totalFCCredit = 0;
            $totalFCDebit = 0;
        }else{
            $balance = Session::get('balance_session');
            $FCbalance = Session::get('fcbalance_session');
            $totalCredit = Session::get('totalCredit_session');
            $totalFCCredit = Session::get('fctotalCredit_session');
            $totalDebit = Session::get('totalDebit_session');
            $totalFCDebit = Session::get('totalFCDebit_session');
        }

        // $debit = 0;
        // $creadit = 0;

        foreach ($sub as $key => $value) {


            $vdate =  date('d-m-Y', strtotime($value->VchDate));

            $value->VchDate = $vdate;
            // dd($vdate);

            // check if field having value or not
            if (!isset($value->Narration)) {
                $value->Narration = '';
            }
            if (!isset($value->costName)) {
                $value->costName = '';
            }
            if (!isset($value->DDeptName)) {
                $value->DDeptName = '';
            }
            if (!isset($value->exeName)) {
                $value->exeName = '';
            }

            // get perticulars details
            $value->perticulars = VchDet::getPerticulars($value->Id, $accountid);

            $creadit = ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
            $debit = ($value->vcAmount > 0) ? $value->vcAmount : "0.00";

            if (isset($balance) && $balance == 0 && $key == 0 && $pageNo == 1) {
                if (isset($opening['openingbalance']) && $opening['openingbalance'] > 0) {
                    $balance = $opening['openingbalance'];
                } else if (isset($opening['openingbalance']) && $opening['openingbalance'] < 0) {
                    $balance = $opening['openingbalance'];
                }
            }


            $fccreadit = ($value->FcFCamt < 0) ? $value->FcFCamt : "0.00";
            $fcdebit = ($value->FcFCamt > 0) ? $value->FcFCamt : "0.00";

            $balance = (float)$balance + (float)$debit - abs((float)$creadit);

            
            // $balance = (float)$balance - abs((float)$creadit);
            
            if ($balance < 0) {
                $conbalance = round(abs($balance), 2) . " CR.";
            } else {
                $conbalance = round($balance, 2) . " DR.";
            }
            
            $FCbalance = (float)$FCbalance + (float)$fcdebit - abs((float)$fccreadit);
            // $FCbalance = (float)$FCbalance - abs((float)$fccreadit);
            

            if ($FCbalance < 0) {
                $conFCbalance = round(abs($FCbalance), 2) . " CR.";
            } else {
                $conFCbalance = round($FCbalance, 2) . " DR.";
            }

            $totalCredit += (float) $creadit;
            $totalDebit += (float) $debit;

            $totalFCCredit += (float) $fccreadit;
            $totalFCDebit += (float) $fcdebit;


            Session::put('balance_session', $balance);
            Session::put('fcbalance_session', $FCbalance);
            Session::put('totalCredit_session', $totalCredit);
            Session::put('fctotalCredit_session', $totalFCCredit);
            Session::put('totalDebit_session', $totalDebit);
            Session::put('totalFCDebit_session', $totalFCDebit);

            $arraydata[] = [
                "key" => $key + 1,
                "data" => $value,
                "credit" => abs($creadit),
                "debit" => $debit,

                "fccreadit" => abs($fccreadit),
                "fcdebit" => $fcdebit,

                "balance" => $conbalance,
                "FCbalance" => $conFCbalance,

                "Openingbalance" => $opening['openingbalance'],
                "OpeningFCbalance" => $opening['OpeningFCbalance'],

                // "Closingbalance" => $closingBal['Closingbalance'],
                "Closingbalance" => $balance,
                // "ClosingFCbalance" => $closingBal['ClosingFCbalance'],
                "ClosingFCbalance" => $FCbalance,


                // "totalCredit"=>round($totalCredit, 2),
                // "totalDebit"=>round($totalDebit, 2),

                // "totalFCCredit"=>round($totalFCCredit, 2),
                // "totalFCDebit"=>round($totalFCDebit, 2),
            ];
        }
        // dd($arraydata);
        // echo '<pre>';print_r($arraydata);
        // exit;

        return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $subcount, "iTotalDisplayRecords" => $subcount, "aaData" => $arraydata]);
        // return response()->json(["data" => $arraydata]);
    }

    // General Ledger
    public function ViewGeneralLedger(Request $request,$id)
    { 
        // phpinfo();
        $acdata = Account::select('Id', 'ACName')->orderBy('ACName', 'ASC')->get();
        // dd($acdata);
        $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get();
        $deptdata = Department::select('Id', 'DeptName')->orderBy('DeptName', 'ASC')->get();

        $startEndDate = Company::getStartEndDate($id);
        $accounts=Account::where(DB::raw("trim(accounts.Parent2)"),'0')->orderby('ACName','asc')->select('ACName as account_name','Id as id','G-A as ga')->get();
        $companyname=Session::get('company_name');
        $costCenter="";
        $department ="";

        $ChequeNo ="";
        $ChequeStatus = "";
        $ClearingDate = "";
        $CostCentre ="";
        $Department ="";
        $Executive ="";
        $Project = "";
        $ForeignCurrency ="";
        $arraydata = array();
        

        if($request->method()=="POST"){

            $fromdate = $request->selectVchFromDate;
            $todate = $request->selectVchToDate;
            $costCenter = $request->costCenter;
            $department = $request->department;

            $startEndDate =new Collection( );
            $startEndDate->fs_date= $fromdate ;
            $startEndDate->fe_date=$todate;
 
               $ChequeNo = $request->ChequeNo;
                $ChequeStatus = $request->ChequeStatus;
                $ClearingDate = $request->ClearingDate;
                $CostCentre = $request->CostCentre;
                $Department = $request->Department;
                $Executive = $request->Executive;
                $Project = $request->Project;
                $ForeignCurrency = $request->ForeignCurrency;
                
                 $selected_accounts= $request->selected_accounts;
            
               if($selected_accounts==null){
                   goto line410;
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

                    $general_ledger_inputs=array('all_accounts'=>$all_accounts,
                    'fromdate'=>$fromdate,'todate'=>$todate,'chequeno'=>  $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=>$ClearingDate,
                    'costcentre'=> $CostCentre,
                        'department'=>   $Department,'executive'=>   $Executive ,'project'=>    $Project ,'foreignCurrency'=> $ForeignCurrency
                        );

                    $general_ledger_inputs_json=json_encode( $general_ledger_inputs);
            
                    Cache::put($companyname."_general_ledger_inputs", $general_ledger_inputs_json,108000);
 
                    $opening_balances=$this->reportservice->getOpeningBalances( $all_accounts, $fromdate);

                    $closing_balances=$this->reportservice->getClosingBalances($all_accounts, $fromdate, $todate);

                    foreach ( $all_accounts as $key => $checkValue) {


                        $general_ledger_result=$this->reportservice->getGeneralLedger($checkValue, $fromdate, $todate, $costCenter, $department);
            
                        $generalData[$checkValue] =     $general_ledger_result['data']; 
                        $opening =  $opening_balances[$checkValue];  
                        $closingBal =  $closing_balances[$checkValue];

                        $arrayResponseData[$checkValue] = array('startDate' => $fromdate, 'toDate' => $todate, 'account_id' => $checkValue, "Openingbalance" => $opening['openingbalance'], "OpeningFCbalance" => $opening['OpeningFCbalance'], "Closingbalance" => $closingBal['Closingbalance'], "ClosingFCbalance" => $closingBal['ClosingFCbalance']);
 
                        $generalDatacount[$checkValue] =  $general_ledger_result['count'];
 
                        $balance = 0;
                        $totalCredit = 0;
                        $totalDebit = 0;
                        $FCbalance = 0;
                        $totalFCCredit = 0;
                        $totalFCDebit = 0;

                        // $debit = 0;
                        // $creadit = 0;

                        foreach ($generalData[$checkValue] as $key => $value) {
            
                            $vdate =  date('d-m-Y', strtotime($value->VchDate));

                            $arrayResponseData[$checkValue]['accountName'] =  $value->ACName;

                            $value->VchDate = $vdate;
                            // dd($vdate);

                            // check if field having value or not
                            if (!isset($value->Narration)) {
                                $value->Narration = '';
                            }
                            if (!isset($value->costName)) {
                                $value->costName = '';
                            }
                            if (!isset($value->DDeptName)) {
                                $value->DDeptName = '';
                            }
                            if (!isset($value->exeName)) {
                                $value->exeName = '';
                            }

                            $value->perticulars = (array_key_exists($value->Id,$general_ledger_result['particulars'])?$general_ledger_result['particulars'][$value->Id]:"");
            

                            $creadit = ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
                            $debit = ($value->vcAmount > 0) ? $value->vcAmount : "0.00";

                            if ($balance == 0 && $key == 0) {
                                if (isset($opening['openingbalance']) && $opening['openingbalance'] > 0) {
                                    $balance = $opening['openingbalance'];
                                } else if (isset($opening['openingbalance']) && $opening['openingbalance'] < 0) {
                                    $balance = $opening['openingbalance'];
                                }
                            }

                            $fccreadit = ($value->FcFCamt < 0) ? $value->FcFCamt : "0.00";
                            $fcdebit = ($value->FcFCamt > 0) ? $value->FcFCamt : "0.00";

                            $balance = (float)$balance + (float)$debit - abs((float)$creadit);
            
                            if ($balance < 0) {
                                $conbalance = round(abs($balance), 2) . " CR.";
                            } else {
                                $conbalance = round($balance, 2) . " DR.";
                            }

                            $FCbalance = (float)$FCbalance + (float)$fcdebit - abs((float)$fccreadit);
                    
                            if ($FCbalance < 0) {
                                $conFCbalance = round(abs($FCbalance), 2) . " CR.";
                            } else {
                                $conFCbalance = round($FCbalance, 2) . " DR.";
                            }

                            $totalCredit += (float) $creadit;
                            $totalDebit += (float) $debit;

                            $totalFCCredit += (float) $fccreadit;
                            $totalFCDebit += (float) $fcdebit;

                            $arrayResponseData[$checkValue]['totalCredit'] =  $totalCredit;
                            $arrayResponseData[$checkValue]['totalDebit'] =  $totalDebit;
                            $arrayResponseData[$checkValue]['totalFCCredit'] =  $totalFCCredit;
                            $arrayResponseData[$checkValue]['totalFCDebit'] =  $totalFCDebit;

                            $arrayResponseData[$checkValue][] = [
                                "key" => $key + 1,
                                "data" => $value,
                                "credit" => abs($creadit),
                                "debit" => $debit,

                                "fccreadit" => abs($fccreadit),
                                "fcdebit" => $fcdebit,

                                "balance" => $conbalance,
                                "FCbalance" => $conFCbalance,
            
                            ];
                        }
                    
                    }

                    $arraydata = array_filter($arrayResponseData);

                    line410:

        } 
  
        return view('Company.general_ledger', ['id' => $id, 'acdata' => $acdata,  'costdata' => $costdata, 'deptdata' => $deptdata, 'companyDates' => $startEndDate,'accounts'=>$accounts,'companyname'=>$companyname,'costcenter'=>    $costCenter,'department'=>      $department ,
        "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
        'department'=>$Department ,'executive'=>$Executive,'project'=>$Project,'foreigncurrency'=>$ForeignCurrency,'arraydata'=>$arraydata]);
    }

    public function ViewTrialBalance($id)
    {
        $acdata = Account::select('Id', 'ACName')->orderBy('ACName', 'ASC')->get();
        $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get();
        $deptdata = Department::select('Id', 'DeptName')->orderBy('DeptName', 'ASC')->get();
        $startEndDate = Company::getStartEndDate($id);
        return view('Company.trialbalance', ['id' => $id, 'acdata' => $acdata,  'costdata' => $costdata, 'deptdata' => $deptdata, 'companyDates' => $startEndDate]);
    }


    // General Ledger
    public function getAccountsTree()
    {
        $responseData = Account::select('Id', 'ACName as text', DB::raw("TRIM(Parent2) as parent_id"))->where("Parent2", '!=', '')->where("Parent2", '!=', NULL)->orderBy("Parent2", 'ASC')->get();

        // $data = $responseData->toArray();

        $data = $responseData->toArray();
        // echo '<prE>';print_r($data);exit;
        $tmp = array();

        foreach ($data as $key => &$item) {
            $data[$key]['Id'] = $item['Id'];
            $data[$key]['text'] = $item['text'];
            $data[$key]['parent_id'] = (int)$item['parent_id'];

            $tmp[] = $data[$key]['parent_id'];
        }

        // echo '<prE>';print_r($data);exit;

        $itemsByReference = array();
        // Build array of item references:
        foreach ($data as $key => &$item) {
            // if (in_array($item['Id'], $tmp)) {
            $itemsByReference[$item['Id']] = &$item;
            // Children array:
            $itemsByReference[$item['Id']]['children'] = array();
            // Empty data class (so that json_encode adds "data: {}" ) 
            $itemsByReference[$item['Id']]['data'] = new StdClass();
            // }
        }

        // echo '<pre>';print_r($itemsByReference);exit;
        // Set items as children of the relevant parent item.
        foreach ($data as $key => &$item) {
            // if (in_array($item['Id'], $tmp)) {
            if ($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
                $itemsByReference[$item['parent_id']]['children'][] = &$item;
            }
            // }
        }

        // echo '<pre>';print_r($itemsByReference);exit;

        // Remove items that were added to parents elsewhere:
        foreach ($data as $key => &$item) {
            if (trim($item['parent_id']) && isset($itemsByReference[$item['parent_id']])) {
                unset($data[$key]);
            }
        }

        foreach ($data as $key => &$item) {
            if (!in_array($item['Id'], $tmp)) {
                unset($data[$key]);
            }
        }
        // echo '<prE>';print_r($itemsByReference);die;

        // echo '<pre>';
        // print_r(array_values($data));
        // exit;
        // Encode:
        echo json_encode(array_values($data));
        exit;

        // $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get();
        // $deptdata = Department::select('Id', 'DeptName')->orderBy('DeptName', 'ASC')->get();
        // $startEndDate = Company::getStartEndDate($id);
        // return view('Company.general_ledger', ['id' => $id, 'acdata' => $acdata,  'costdata' => $costdata, 'deptdata' => $deptdata, 'companyDates' => $startEndDate]);
    }

    public function showGeneralLedger(Request $request )
    {
        // echo '<pre>';print_r($id);exit;
        $selected_accounts= $request->selected_accounts;
        $this->reportservice->company_name=Session::get('company_name');
        $this->reportservice->setAccountTreeData();
 

        $account_tree_data= $this->reportservice->account_tree_data;
        $all_accounts=array();
        foreach($selected_accounts as $single_account){

            if(!in_array($single_account,$all_accounts)){
                 
                array_push($all_accounts,$single_account);

                $this->reportservice->account_id=$single_account;

                $child_accounts=  $this->reportservice->getChildrenAccountIds();

                $all_accounts= array_merge($all_accounts, $child_accounts); 

            } 
        }

        $all_accounts= array_merge($all_accounts,  $selected_accounts );
  
       
        $fromdate = $request->selectVchFromDate;
        $todate = $request->selectVchToDate;
        $costCenter = $request->costCenter;
        $department = $request->department; 

        $opening_balances=$this->reportservice->getOpeningBalances( $all_accounts, $fromdate);

        $closing_balances=$this->reportservice->getClosingBalances($all_accounts, $fromdate, $todate);

        foreach ( $all_accounts as $key => $checkValue) {


            $general_ledger_result=$this->reportservice->getGeneralLedger($checkValue, $fromdate, $todate, $costCenter, $department);
 
            $generalData[$checkValue] =     $general_ledger_result['data'];

     
            $opening =  $opening_balances[$checkValue];
            // VchMain::getClosing($checkValue, $fromdate, $todate)  
            $closingBal =  $closing_balances[$checkValue];

            $arrayResponseData[$checkValue] = array('startDate' => $fromdate, 'toDate' => $todate, 'account_id' => $checkValue, "Openingbalance" => $opening['openingbalance'], "OpeningFCbalance" => $opening['OpeningFCbalance'], "Closingbalance" => $closingBal['Closingbalance'], "ClosingFCbalance" => $closingBal['ClosingFCbalance']);

            // dd($closingBal['ClosingFCbalance']);

            $generalDatacount[$checkValue] =  $general_ledger_result['count'];

            // $arraydata = array();

            $balance = 0;
            $totalCredit = 0;
            $totalDebit = 0;
            $FCbalance = 0;
            $totalFCCredit = 0;
            $totalFCDebit = 0;

            // $debit = 0;
            // $creadit = 0;

            foreach ($generalData[$checkValue] as $key => $value) {
 
                $vdate =  date('d-m-Y', strtotime($value->VchDate));

                $arrayResponseData[$checkValue]['accountName'] =  $value->ACName;

                $value->VchDate = $vdate;
                // dd($vdate);

                // check if field having value or not
                if (!isset($value->Narration)) {
                    $value->Narration = '';
                }
                if (!isset($value->costName)) {
                    $value->costName = '';
                }
                if (!isset($value->DDeptName)) {
                    $value->DDeptName = '';
                }
                if (!isset($value->exeName)) {
                    $value->exeName = '';
                }

                 $value->perticulars = (array_key_exists($value->Id,$general_ledger_result['particulars'])?$general_ledger_result['particulars'][$value->Id]:"");
 

                $creadit = ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
                $debit = ($value->vcAmount > 0) ? $value->vcAmount : "0.00";

                if ($balance == 0 && $key == 0) {
                    if (isset($opening['openingbalance']) && $opening['openingbalance'] > 0) {
                        $balance = $opening['openingbalance'];
                    } else if (isset($opening['openingbalance']) && $opening['openingbalance'] < 0) {
                        $balance = $opening['openingbalance'];
                    }
                }

                $fccreadit = ($value->FcFCamt < 0) ? $value->FcFCamt : "0.00";
                $fcdebit = ($value->FcFCamt > 0) ? $value->FcFCamt : "0.00";

                $balance = (float)$balance + (float)$debit - abs((float)$creadit);
 
                if ($balance < 0) {
                    $conbalance = round(abs($balance), 2) . " CR.";
                } else {
                    $conbalance = round($balance, 2) . " DR.";
                }

                $FCbalance = (float)$FCbalance + (float)$fcdebit - abs((float)$fccreadit);
         
                if ($FCbalance < 0) {
                    $conFCbalance = round(abs($FCbalance), 2) . " CR.";
                } else {
                    $conFCbalance = round($FCbalance, 2) . " DR.";
                }

                $totalCredit += (float) $creadit;
                $totalDebit += (float) $debit;

                $totalFCCredit += (float) $fccreadit;
                $totalFCDebit += (float) $fcdebit;

                $arrayResponseData[$checkValue]['totalCredit'] =  $totalCredit;
                $arrayResponseData[$checkValue]['totalDebit'] =  $totalDebit;
                $arrayResponseData[$checkValue]['totalFCCredit'] =  $totalFCCredit;
                $arrayResponseData[$checkValue]['totalFCDebit'] =  $totalFCDebit;

                $arrayResponseData[$checkValue][] = [
                    "key" => $key + 1,
                    "data" => $value,
                    "credit" => abs($creadit),
                    "debit" => $debit,

                    "fccreadit" => abs($fccreadit),
                    "fcdebit" => $fcdebit,

                    "balance" => $conbalance,
                    "FCbalance" => $conFCbalance,
 
                ];
            }
        
        }

      
        $ChequeNo = $request->ChequeNo;
        $ChequeStatus = $request->ChequeStatus;
        $ClearingDate = $request->ClearingDate;
        $CostCentre = $request->CostCentre;
        $Department = $request->Department;
        $Executive = $request->Executive;
        $Project = $request->Project;
        $ForeignCurrency = $request->ForeignCurrency;
        $arraydata = array_filter($arrayResponseData);
        return view("company.general-ledger-list", compact( 'arraydata', 'ChequeNo', 'ChequeStatus', 'ClearingDate', 'CostCentre', 'Department', 'Executive', 'Project', 'ForeignCurrency'));
        // return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $subcount, "iTotalDisplayRecords" => $subcount, "aaData" => array_filter($arraydata)]);

        // return response()->json(["data" => $arraydata]);
    }


    public function showTrialBalance(Request $request)
    {
        // echo '<pre>';print_r($request->all());exit;
        $checkBoxs = $request->myCheckboxField;
        $myCheckboxFieldText = $request->myCheckboxFieldText;
        // $draw = $request->sEcho;
        // $row = $request->iDisplayStart;
        // $rowperpage = $request->iDisplayLength; // Rows display per page
        // $indexColumn = $request->iSortCol_0;
        // $columnName = $request->mDataProp . '_' . $indexColumn; // Column name
        // $columnSortOrder = $request->sSortDir_0; // asc or desc
        // $accountid = $request->accountId;
        $fromdate = $request->selectVchFromDate;
        $todate = $request->selectVchToDate;
        $costCenter = $request->costCenter;
        $department = $request->department;
        // $sSearch = $request->sSearch;
        // $dropval = $request->selectDbField;
        // echo "<pre>";
        // print_r($myCheckboxFieldText);
        // exit;
        // foreach ($checkBoxs as $key => $checkValue) {
        $elements = array();
        $AllAccounts = Account::getAllAccIds($myCheckboxFieldText);
        $parentsChildNodes = Account::buildTree($myCheckboxFieldText);

        // echo '<pre>';print_r($parentsChildNodes);exit;

        // $tasks[] = array("id" => 1, "parent_id" => 0, "title" => 'task 1');
        // $tasks[] = array("id" => 2, "parent_id" => 1, "title" => 'sub task 1');
        // $tasks[] = array("id" => 3, "parent_id" => 1, "title" => 'sub task 2');
        // $tasks[] = array("id" => 5, "parent_id" => 2, "title" => 'task 2');
        // $tasks[] = array("id" => 4, "parent_id" => 2, "title" => 'sub sub task 1');
        // $tasks[] = array("id" => 6, "parent_id" => 2, "title" => 'sub task 3');
        // $tasks[] = array("id" => 7, "parent_id" => 6, "title" => 'sub task of 6');
        // $branch = array();

        // echo '<prE>';print_r($AllAccounts);exit;
        // echo '<prE>';print_r($tasks);exit;

        // foreach($parents[$checkValue] as $k => $parentVal){
        // echo '<pre>';
        // print_r($parentsChildNodes);exit;
        // }
        // $reverseParent[$checkValue] = array_reverse($parents[$checkValue]);


        // $keys = array_column($parents[$checkValue], 'Parent2');
        // array_multisort($keys, SORT_ASC, $parents[$checkValue]);

        // $parents_sort[$checkValue] = Account::array_sort_by_column($parents,'Parent2');
        // $finalParentArry = array_reverse($parents);


        // }

        // exit;


        foreach ($parentsChildNodes as $key => $checkValue) {
            $elements = array();

            $checkBoxId = $checkValue['Id'];
            // echo '<br>';
            // echo "acc id==".$checkValue;
            // echo '<br>';
            # code...
            $trialBalanceData[$checkBoxId] = VchMain::getTrialBalance($checkBoxId, $fromdate, $todate, $costCenter, $department);

            // if ($checkValue == 8815) {
            //     echo "<pre>";
            //     // print_r(array_filter($trialBalanceData));
            //     print_r($trialBalanceData[$checkValue]);
            //     exit;
            // }

            $opening = VchMain::getOpening($checkBoxId, $fromdate);

            $closingBal = VchMain::getClosing($checkBoxId, $fromdate, $todate);

            $arrayResponseData[$checkBoxId] = array('startDate' => $fromdate, 'toDate' => $todate, 'account_id' => $checkBoxId, "Openingbalance" => $opening['openingbalance'], "OpeningFCbalance" => $opening['OpeningFCbalance'], "Closingbalance" => $closingBal['Closingbalance'], "ClosingFCbalance" => $closingBal['ClosingFCbalance']);

            // dd($closingBal['ClosingFCbalance']);

            $generalDatacount[$checkBoxId] = VchMain::getGeneralLedgerCount($checkBoxId, $fromdate, $todate, $costCenter, $department);

            // $arraydata = array();

            $balance = 0;
            $totalCredit = 0;
            $totalDebit = 0;
            $FCbalance = 0;
            $totalFCCredit = 0;
            $totalFCDebit = 0;

            $arrayResponseData[$checkBoxId]['accountName'] =  $checkValue['ACName'];
            $arrayResponseData[$checkBoxId]['totalCredit'] =  0;
            $arrayResponseData[$checkBoxId]['totalDebit'] =  0;
            $arrayResponseData[$checkBoxId]['totalFCCredit'] =  0;
            $arrayResponseData[$checkBoxId]['totalFCDebit'] =  0;
            $arrayResponseData[$checkBoxId]['ClosingCreditbalance'] =  0;
            $arrayResponseData[$checkBoxId]['ClosingDebitbalance'] =  0;

            foreach ($trialBalanceData[$checkBoxId] as $key => $value) {

                $vdate =  date('d-m-Y', strtotime($value->VchDate));

                $value->VchDate = $vdate;
                // dd($vdate);

                // check if field having value or not
                if (!isset($value->Narration)) {
                    $value->Narration = '';
                }
                if (!isset($value->costName)) {
                    $value->costName = '';
                }
                if (!isset($value->DDeptName)) {
                    $value->DDeptName = '';
                }
                if (!isset($value->exeName)) {
                    $value->exeName = '';
                }

                // get perticulars details
                $value->perticulars = Vchdet::getPerticulars($value->Id, $checkBoxId);

                $creadit = ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
                $debit = ($value->vcAmount > 0) ? $value->vcAmount : "0.00";

                $fccreadit = ($value->FcFCamt < 0) ? $value->FcFCamt : "0.00";
                $fcdebit = ($value->FcFCamt > 0) ? $value->FcFCamt : "0.00";

                $balance = (float)$balance + (float)$debit;

                $balance = (float)$balance - abs((float)$creadit);

                if ($balance < 0) {
                    $conbalance = round(abs($balance), 2) . " CR.";
                } else {
                    $conbalance = round($balance, 2) . " DR.";
                }

                $FCbalance = (float)$FCbalance + (float)$fcdebit;
                $FCbalance = (float)$FCbalance - abs((float)$fccreadit);

                if ($FCbalance < 0) {
                    $conFCbalance = round(abs($FCbalance), 2) . " CR.";
                } else {
                    $conFCbalance = round($FCbalance, 2) . " DR.";
                }

                $totalCredit += (float) $creadit;
                $totalDebit += (float) $debit;

                $totalFCCredit += (float) $fccreadit;
                $totalFCDebit += (float) $fcdebit;

                $arrayResponseData[$checkBoxId]['totalCredit'] =  $totalCredit;
                $arrayResponseData[$checkBoxId]['totalDebit'] =  $totalDebit;
                $arrayResponseData[$checkBoxId]['totalFCCredit'] =  $totalFCCredit;
                $arrayResponseData[$checkBoxId]['totalFCDebit'] =  $totalFCDebit;
                $arrayResponseData[$checkBoxId]['ClosingCreditbalance'] =  $closingBal['ClosingCreditbalance'];
                $arrayResponseData[$checkBoxId]['ClosingDebitbalance'] =  $closingBal['ClosingDebitbalance'];

                $arrayResponseData[$checkBoxId]['key'] = $key + 1;
                // $arrayResponseData[$checkBoxId]['data'] = $value;
                $arrayResponseData[$checkBoxId]['credit'] = abs($creadit);
                $arrayResponseData[$checkBoxId]['debit'] = $debit;
                $arrayResponseData[$checkBoxId]['fccreadit'] = abs($fccreadit);
                $arrayResponseData[$checkBoxId]['fcdebit'] = $fcdebit;
                $arrayResponseData[$checkBoxId]['balance'] = $conbalance;
                $arrayResponseData[$checkBoxId]['FCbalance'] = $conFCbalance;
                // [
                //     "key" => $key + 1,
                //     "data" => $value,
                //     "credit" => abs($creadit),
                //     "debit" => $debit,

                //     "fccreadit" => abs($fccreadit),
                //     "fcdebit" => $fcdebit,

                //     "balance" => $conbalance,
                //     "FCbalance" => $conFCbalance,

                //     // "Openingbalance" => $opening['openingbalance'],
                //     // "OpeningFCbalance" => $opening['OpeningFCbalance'],

                //     // "Closingbalance" => $closingBal['Closingbalance'],
                //     // "ClosingFCbalance" => $closingBal['ClosingFCbalance'],


                //     // "totalCredit"=>round($totalCredit, 2),
                //     // "totalDebit"=>round($totalDebit, 2),

                //     // "totalFCCredit"=>round($totalFCCredit, 2),
                //     // "totalFCDebit"=>round($totalFCDebit, 2),

                // ];
            }
            // echo '<pre>';
            // print_r($arraydata);
            // exit;
        }


        foreach ($parentsChildNodes as $pcKey => $parentChildVals) {
            if ($parentChildVals['Id'] == $arrayResponseData[$parentChildVals['Id']]['account_id']) {
                $arrayResponseData[$parentChildVals['Id']]['Parent2'] = (int)$parentChildVals['Parent2'];
            }
        }

        // dd($arraydata);
        // echo '<pre>';
        // print_r(array_filter($arrayResponseData));
        // print_r($parentsChildNodes);
        // exit;
        $ChequeNo = $request->ChequeNo;
        $ChequeStatus = $request->ChequeStatus;
        $ClearingDate = $request->ClearingDate;
        $CostCentre = $request->CostCentre;
        $Department = $request->Department;
        $Executive = $request->Executive;
        $Project = $request->Project;
        $ForeignCurrency = $request->ForeignCurrency;
        $arraydata = array_filter($arrayResponseData);
        // $arraydata = array();
        return view("company.trial-balance-list", compact('parentsChildNodes', 'arraydata', 'ChequeNo', 'ChequeStatus', 'ClearingDate', 'CostCentre', 'Department', 'Executive', 'Project', 'ForeignCurrency'));
        // return response()->json(["sEcho" => intval($draw), "iTotalRecords" => $subcount, "iTotalDisplayRecords" => $subcount, "aaData" => array_filter($arraydata)]);

        // return response()->json(["data" => $arraydata]);
    }


    public function generalledgerlist_xls(Request $request)
    {
    }



    public function testCollectionPaging(){

         $test_array=array(56,78,34,56,789,23,45,76,101,234,156,178,345,234,256,278,289);

         $test_collection=new Collection(   $test_array);

         $test_page=   $test_collection->paginate(2);

         dd($test_page);



    }

    public function downloadSubledgerExcel(){

        dd("download sub ledger excel");
    }


    public function testDownloadPdf(){

        $names=array(['fname'=>'rohit','lname'=>'johri'] , ['fname'=>'ram','lname'=>'singh']);

        $datas=array('names'=>     $names);
        $pdf = PDF::loadView('reports.downloadformats.downloadpdf', $datas);
        return $pdf->download('invoice.pdf');
    }

    public function testDownloadExcel(){
        // return (new ExportFromView)->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);

             $names=new Collection([
            ['fname'=>'Rohit','lname'=>'Kumar'],
            ['fname'=>'Akshay','lname'=>'Kumar']
        ]);

        return  Excel::download(new ExportFromView(    $names), 'invoices.xlsx');

    //    return  Excel::download(new ExportFromView, 'invoices.csv' );

        // return (new InvoicesExport)->download('invoices.csv', \Maatwebsite\Excel\Excel::CSV);

    }


    public function openGeneralLedger(Request $request, $id){

         // phpinfo();
         $acdata = Account::select('Id', 'ACName')->orderBy('ACName', 'ASC')->get();
         // dd($acdata);
         $costdata = Costcentre::select('Id', 'Name')->orderBy('Name', 'ASC')->get(); 
         $divisions=Division::pluck('division','Id')->toArray();
 
         $startEndDate = Company::getStartEndDate($id);

         $name_of_company=  $startEndDate->comp_name;

         $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ". date('d/m/Y',strtotime($startEndDate->fe_date));

         $accounts=Account::where("accounts.Parent2",'0')->orderby('ACName','asc')->select('ACName as account_name','Id as id','G-A as ga')->get();
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
               $this->reportservice->user_id=Auth::user()->id;
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
                $all_accounts= array_unique( $all_accounts);
  
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
 
                if(!empty(Cache::get($user_id."_general_ledger_inputs"))){
                    Cache::forget($user_id."_general_ledger_inputs"); 
                }

                Cache::put($user_id."_general_ledger_inputs", $general_ledger_inputs_json,108000);
 
 
            }
            else if(!empty(Cache::get($user_id."_general_ledger_inputs"))){

                $general_ledger_inputs=json_decode(Cache::get($user_id."_general_ledger_inputs") ,true);
 
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
   
        $accounts_data = $this->reportservice->paginate(Session::get('company_name'),'company.general_ledger_new',$accounts_collection,3);
 
 
      $show_account_names= Account::whereIn('Id',  $accounts_data->toArray()['data'] )->pluck('ACName','Id');

         $report_name="General Ledger";

         $general_ledger_submit_url=route('company.general_ledger_new_submit' ,['company_name'=>$companyname]);

         $general_ledger_url=  route('company.general_ledger_new' ,['company_name'=>$companyname]);

         $general_ledger_download_url=  route('company.download_general_ledger',['company_name'=>$companyname]);
         
         $general_ledger_cancel_inputs_url= route('company.cancel_cache_report_inputs',['company_name'=>$companyname,'reportname'=>'general-ledger']);
 
         return view('Company.general_ledger_new', ['id' => $id, 'acdata' => $acdata,  'costdata' => $costdata , 'companyDates' => $startEndDate,'accounts'=>$accounts,'companyname'=>$companyname ,
         "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
         'division'=>$division ,'executive'=>$Executive,'project'=>$Project,'foreigncurrency'=>$ForeignCurrency,'arraydata'=>$arraydata,'accounts_data'=> $accounts_data,'no_of_additional_columns'=>$no_of_additional_columns,'show_account_names'=>$show_account_names ,
        'selected_costcenter'=>$selected_costcenter,'selected_division'=>$selected_division ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year,'divisions'=>  $divisions  ,'report_name'=>$report_name,'general_ledger_submit_url'=>  $general_ledger_submit_url ,
        'general_ledger_url'=> $general_ledger_url,'general_ledger_download_url'=>$general_ledger_download_url,'general_ledger_cancel_inputs_url'=>$general_ledger_cancel_inputs_url
        ]);
 
    }


    public function downloadGeneralLedgerByFormat($companyname,$format="xlsx"){
 
        $user_id=Auth::user()->id;

        if(empty(Cache::get($user_id."_general_ledger_inputs"))){
            echo "Please seacrh before export";

            exit();
        }

        
        $general_ledger_inputs=json_decode(Cache::get($user_id."_general_ledger_inputs") ,true); 
        $account_ids=  $general_ledger_inputs['all_accounts'];    
        $startEndDate =new Collection( );
        $startEndDate->fs_date=   $general_ledger_inputs['fromdate'] ;
        $startEndDate->fe_date=$general_ledger_inputs['todate'] ;

        
        $downloadfilename=  "generalledger-".str_replace("-","",$general_ledger_inputs['fromdate'])."-".str_replace("-","",$general_ledger_inputs['todate']);
             
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
           return  Excel::download(new GeneralLedgerView( $name_of_company ,$financial_year, $startEndDate,  $selected_costcenter ,  $selected_division,$ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns), $downloadfilename.'.xlsx');
  
        }

        if($format=="pdf"){ 
        $datas=array(   'companyDates' => $startEndDate,
        "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
        'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
      'selected_costcenter'=>$selected_costcenter ,'selected_division'=>  $selected_division ,'report_name'=>'General Ledger','name_of_company'=>$name_of_company ,'financial_year'=>$financial_year);
       
         $pdf = PDF::loadView('reports.downloadformats.general_ledger_format', $datas)->setPaper('a3')->setOrientation('landscape');
        $pdf->setTimeout(2*60*60);
        return $pdf->download($downloadfilename.'.pdf');
        }

        if($format=="csv"){
            return  Excel::download(new GeneralLedgerView($name_of_company , $financial_year , $startEndDate,$selected_costcenter ,  $selected_division, $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns), $downloadfilename.'.csv' );
 
        }
         
    

    }

        public function cancelCacheReportInputsByName($companyname,$reportname){

            $user_id=Auth::user()->id;

            if($reportname=="general-ledger"){

                Cache::forget($user_id."_general_ledger_inputs"); 

            }
            else  if($reportname=="sub-ledger"){

                Cache::forget($user_id."_sub_ledger_inputs"); 
            }
            else if($reportname=="tree-style-trial-balances"){

                Cache::forget($user_id."_tree_style_trial_balances_input");  

                Cache::forget( $user_id."_trial_balances_input"); 

                Cache::forget( $user_id."_tree_style_trial_balances_open_childaccounts"); 
                
                Cache::forget($user_id."_account_totals_data");

                Cache::forget( $user_id."_tree_style_trial_balances_drilldown_inputs"); 
            }
            else if($reportname=="account-totals-data"){
    
                Cache::forget($user_id."_account_totals_data"); 


            }
            else if($reportname=="trial-balances"){

                Cache::forget($user_id."_trial_balances_input"); 

                Cache::forget($user_id."_account_totals_data");

            }
            else if($reportname=="treestyle-p-and-l-report"){

                Cache::forget($user_id."_p_and_l_report"); 
            }
            else if($reportname=="balance-report"){

                Cache::forget($user_id."_balance_report"); 
            }
            else if($reportname=="cash-book"){

                Cache::forget($user_id."_cash_book_inputs"); 
            }
            else if($reportname=="bank-book"){

                Cache::forget($user_id."_bank_book_inputs"); 
            }
            return response()->json(['status'=>'success']);

        }



        public function downloadSubledger($companyname,$format){


            $user_id=Auth::user()->id;
      
            if(empty(Cache::get($user_id."_sub_ledger_inputs"))){
                echo "Please seacrh before export";
    
                exit();
            }
    
            
            $sub_ledger_inputs=json_decode(Cache::get($user_id."_sub_ledger_inputs") ,true); 
            $account_ids=  $sub_ledger_inputs['all_accounts']; 

            // $account_ids= array(7719);
     
            $startEndDate =new Collection( );
            $startEndDate->fs_date=   $sub_ledger_inputs['fromdate'] ;
            $startEndDate->fe_date=$sub_ledger_inputs['todate'] ;

            $downloadfilename=Account::where('Id',   $account_ids['0'])->value('ACName');  

            $downloadfilename= $downloadfilename."-".str_replace("-","",$sub_ledger_inputs['fromdate'])."-".str_replace("-","",$sub_ledger_inputs['todate']);
            
            
               $ChequeNo = $sub_ledger_inputs['chequeno'];
                $ChequeStatus =$sub_ledger_inputs['chequestatus'];
                $ClearingDate =$sub_ledger_inputs['clearingdate'];
                $CostCentre = $sub_ledger_inputs['costcentre'];
                $division = $sub_ledger_inputs['division'];
                $Executive = $sub_ledger_inputs['executive']; 
                $ForeignCurrency = $sub_ledger_inputs['foreignCurrency'];
                $no_of_additional_columns= $sub_ledger_inputs['no_of_additional_columns'];
                $selected_costcenter= $sub_ledger_inputs['selected_costcenter'];
                $selected_division=$sub_ledger_inputs['selected_division'];
                $name_of_company=$sub_ledger_inputs['name_of_company'];
                $financial_year=$sub_ledger_inputs['financial_year'];
     
        //      return view('reports.downloadformats.general_ledger_format', [ 'name_of_company'=>$name_of_company, 'financial_year'=>$financial_year,    'companyDates' => $startEndDate,
        //      "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
        //      'division'=>$division ,'executive'=>$Executive,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns ,
        //    'selected_costcenter'=>$selected_costcenter ,  'selected_division'=>$selected_division,'report_name'=>'Sub Ledger'
        // ]);
     
    
            if($format=="xlsx"){
                return  Excel::download(new GeneralLedgerView( $name_of_company , $financial_year,$startEndDate,$selected_costcenter ,  $selected_division , $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,'Sub Ledger'),        $downloadfilename.'.xlsx');
            }
    
            if($format=="pdf"){ 

            $datas=array( 'name_of_company'=>$name_of_company ,'financial_year'=>$financial_year ,   'companyDates' => $startEndDate,'selected_costcenter'=>$selected_costcenter ,'selected_division'=>$selected_division,
             "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
             'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns,'report_name'=>'Sub Ledger' );
            $pdf = PDF::loadView('reports.downloadformats.general_ledger_format', $datas)->setPaper('a3')->setOrientation('landscape');
           
          
            $pdf->setTimeout(2*60*60);
            return $pdf->download(       $downloadfilename.'.pdf');
            }
    
            if($format=="csv"){
                 return  Excel::download(new GeneralLedgerView($name_of_company , $financial_year ,$startEndDate, $selected_costcenter,$selected_division, $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,'Sub Ledger'),        $downloadfilename.'.csv' );
               
            }
          


        }


        public function sendSubledgerEmailWhatsapp(Request $request){
 
            $user_id=Auth::user()->id;

 
            $format=$request->reportformat;

            $report_mode=$request->report_mode;
 
             
            $user_id=Auth::user()->id;
      
            if(empty(Cache::get($user_id."_sub_ledger_inputs"))){
                
                return response()->json(['status'=>'no ledger input found']);

            }
 
            $sub_ledger_inputs=json_decode(Cache::get($user_id."_sub_ledger_inputs") ,true); 
            $account_ids=  $sub_ledger_inputs['all_accounts']; 

            // $account_ids= array(7719);
     
            $startEndDate =new Collection( );
            $startEndDate->fs_date=   $sub_ledger_inputs['fromdate'] ;
            $startEndDate->fe_date=$sub_ledger_inputs['todate'] ;

            $account_name=Account::where('Id',   $account_ids['0'])->value('ACName'); 
   
            $downloadfilename= $account_name."-".str_replace("-","",$sub_ledger_inputs['fromdate'])."-".str_replace("-","",$sub_ledger_inputs['todate']).".".$format;
              
            // if file already exists the delete it

            $exists = Storage::disk('public')->exists("send_docs/sub_ledgers/".$downloadfilename);


            if(  $exists==true){
                
                Storage::disk('public')->delete("send_docs/sub_ledgers/".$downloadfilename); 
            }
         
 
            $ChequeNo = $sub_ledger_inputs['chequeno'];
                $ChequeStatus =$sub_ledger_inputs['chequestatus'];
                $ClearingDate =$sub_ledger_inputs['clearingdate'];
                $CostCentre = $sub_ledger_inputs['costcentre'];
                $division = $sub_ledger_inputs['division'];
                $Executive = $sub_ledger_inputs['executive']; 
                $ForeignCurrency = $sub_ledger_inputs['foreignCurrency'];
                $no_of_additional_columns= $sub_ledger_inputs['no_of_additional_columns'];
                $selected_costcenter= $sub_ledger_inputs['selected_costcenter'];
                $selected_division=$sub_ledger_inputs['selected_division'];
                $name_of_company=$sub_ledger_inputs['name_of_company'];
                $financial_year=$sub_ledger_inputs['financial_year'];
                $filepath='public/send_docs/sub_ledgers/'. $downloadfilename;


    
            if($format=="xlsx"){
            
               Excel::store(new GeneralLedgerView( $name_of_company , $financial_year,$startEndDate,$selected_costcenter ,  $selected_division , $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,'Sub Ledger'),     $filepath, 'local');
            }
    
            if($format=="pdf"){ 

            $datas=array( 'name_of_company'=>$name_of_company ,'financial_year'=>$financial_year ,   'companyDates' => $startEndDate,'selected_costcenter'=>$selected_costcenter ,'selected_division'=>$selected_division,
             "chequeno"=>   $ChequeNo,'chequestatus'=>$ChequeStatus,'clearingdate'=> $ClearingDate,'costcentre'=>$CostCentre ,
             'division'=>$division ,'executive'=>$Executive ,'foreigncurrency'=>$ForeignCurrency, 'accounts_data'=>  $account_ids,'no_of_additional_columns'=>$no_of_additional_columns,'report_name'=>'Sub Ledger' );
            $pdf = PDF::loadView('reports.downloadformats.general_ledger_format', $datas)->setPaper('a3')->setOrientation('landscape');
          
            //    $pdf->save(storage_path('app/'.$filepath));
                      
               $pdf->save(storage_path('app/'.$filepath));
           
            }
 
            if($format=="csv"){
                // return  Excel::download(new GeneralLedgerView($name_of_company , $financial_year ,$startEndDate, $selected_costcenter,$selected_division, $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,'Sub Ledger'),        $downloadfilename.'.csv' );
                Excel::store(new GeneralLedgerView( $name_of_company , $financial_year,$startEndDate,$selected_costcenter ,  $selected_division , $ChequeNo,$ChequeStatus, $ClearingDate,$CostCentre ,$division,$Executive ,$ForeignCurrency, $account_ids,$no_of_additional_columns,'Sub Ledger'), $filepath, 'local');
         
            } 

      

 
            
            $email_to_customer=isset($request->email_to_customer) ?true:false;

            $this->reportservice->account_id= $account_ids['0'];

            $email_and_whatsapp_details= $this->reportservice->getEmailAndWhatsappNumberFromAccount();
 
            if(  $report_mode=="email"){
                
                $this->reportservice->user_id=Auth::user()->id;

                $cansend=  $this->reportservice->setUserSmtpSettings();

                if($cansend==false){
                    
                    return response()->json(['status'=>'failure','message'=>'User Mail not configured properly']);
                }
                
        
                 Helper::connectDatabaseByName('Universal');

                $toemailid_string=$request->toemailid;

                    if(!empty($toemailid_string)){
                        $toemails=explode(",",$toemailid_string);
                    }
                    else{
                        $toemails=array();
                    } 


            if($email_to_customer==true && !empty($email_and_whatsapp_details['customer_emailid'])){

                array_push( $toemails ,$email_and_whatsapp_details['customer_emailid']);

            } 
            $mail_body=$request->emailmsg;

            $mail_body=(empty($mail_body)?"":$mail_body);

            $mail_subject=   $account_name." Report";
 
            $this->reportservice->subject=  $mail_subject;
            $this->reportservice->body=$mail_body;
            $this->reportservice->filepath='app/'.$filepath;
            $this->reportservice->showfilename= $downloadfilename;
            foreach($toemails as $toemail){
                $this->reportservice->to_email=$toemail;
                $this->reportservice->SendReportToMail();
            } 

            Helper::connectDatabaseByName(Session::get('company_name'));
  
            return response()->json(['status'=>'success','message'=>'Mail Request submitted successfully']);

                
            }
            else if( $report_mode=="whatsapp"){ 
 
               $whatsapp_file_url=  Storage::disk('public')->url('send_docs/sub_ledgers/'.  $downloadfilename);
  
                $whatsapp_to_customer=isset($request->whatsapp_to_customer)?1:0;

               $whatsapp_to_salesman=isset($request->whatsapp_to_salesman)?1:0;

               $towhatsappnumber_string=empty($request->towhatsappno)?NULL:trim($request->towhatsappno);

               $towhatsappnumbers=explode(",", $towhatsappnumber_string);
               $this->whatsappservice->whatsapp_template_id="18093";
               $this->whatsappservice->first_name= "Anonymus";
               $this->whatsappservice->last_name= "Anonymus"; 
               $this->whatsappservice->gender="male";
 
               if(  $whatsapp_to_customer==1 && !empty($email_and_whatsapp_details['customer_whatsappno'])  ){

                $this->whatsappservice->mob_num=$email_and_whatsapp_details['customer_whatsappno'];
    
                $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){

                        $this->whatsappservice->pdf_link=  $whatsapp_file_url;
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();   
                    }
 
               }
 
               if(  $whatsapp_to_salesman==1  &&   !empty($email_and_whatsapp_details['salesman_whatsappno'])  ){

                    $this->whatsappservice->mob_num=$email_and_whatsapp_details['salesman_whatsappno'];
        
                    $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){

                        $this->whatsappservice->pdf_link=  $whatsapp_file_url;
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();   
                    }
 
               } 



               foreach(     $towhatsappnumbers as      $towhatsappnumber){

                $this->whatsappservice->mob_num=trim($towhatsappnumber);
        
                $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                if($result['status']=="success"){

                    $this->whatsappservice->pdf_link=  $whatsapp_file_url;
                    $this->whatsappservice->sendPdfLinkOnWhatsApp();   
                }

               }
 

            return response()->json(['status'=>'success','message'=>'Whatsapp message submitted successfully']);

            }

        }


        public function testDownloadSubledgerAndMail(){

 
            $filepath='app/public/send_docs/1668072391.pdf';
            Helper::connectDatabaseByName('Universal');
            SendEmail::dispatch("smtp.gmail.com","587","tls","rjohri21@gmail.com","","test subject test subkect","tst body test body","lakhan singh","lakhan@gmail.com","rjohri22@gmail.com", $filepath, "mytest.pdf");
    
            Helper::connectDatabaseByName(Session::get('company_name'));

        }


        public function setGeneralSubledgerCacheInputs(Request $request,$companyname){
 
            $accounttype= $request->accounttype;
            $accountid=$request->accountid; 
            $startEndDate = Company::getStartEndDate(Session::get('company_name'));
            $name_of_company=  $startEndDate->comp_name;
            $reportname=$request->fromreportname;

            $user_id=Auth::user()->id;

            $input_json_string=Cache::get($user_id.$reportname);

            if(empty($input_json_string) ){
                return response()->json(['status'=>'failure','message'=>'input not found ']);
            }

            $input_array=json_decode($input_json_string,true);
 

            $fromdate=$input_array['start_date'];
 
            $todate=$input_array['end_date'];

            $cost_center=$input_array['cost_center'];

            $division=$input_array['division'];

            $financial_year=date('d/m/Y',strtotime($startEndDate->fs_date))." to ". date('d/m/Y',strtotime($startEndDate->fe_date));
           
            $this->reportservice->company_name=Session::get('company_name');
            $this->reportservice->user_id=Auth::user()->id;
            $this->reportservice->setAccountTreeData();
            $account_tree_data= $this->reportservice->account_tree_data;

            $all_accounts=array();

            $user_id=Auth::user()->id;

            if(   $accounttype=="G"){
 
                array_push($all_accounts, $accountid);

                $this->reportservice->account_id= $accountid;

                $child_accounts=  $this->reportservice->getChildrenAccountIds();

                $all_accounts= array_merge($all_accounts, $child_accounts); 
                $fromdate=date("Y-m-d",strtotime($fromdate));
                $todate=date("Y-m-d",strtotime( $todate));

                $general_ledger_inputs=array('all_accounts'=>$all_accounts,
                'fromdate'=>$fromdate,'todate'=>$todate,
                'selected_costcenter'=>$cost_center,'selected_division'=>$division,
                'chequeno'=>NULL,'chequestatus'=>NULL,'clearingdate'=>NULL,
                'costcentre'=> NULL,
                    'division'=> NULL,'executive'=>NULL,'foreignCurrency'=>NULL,
                    'no_of_additional_columns'=>0 ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year
                    );
 
                    $general_ledger_inputs_json=json_encode( $general_ledger_inputs);
                  
                    if(!empty(  Cache::get(  $user_id."_general_ledger_inputs"))){
                        Cache::forget(  $user_id."_general_ledger_inputs");
                    }
            
                    Cache::put( $user_id."_general_ledger_inputs", $general_ledger_inputs_json,108000);

                    return response()->json(['status'=>'success','message'=>'General Ledger is set']);
 
            }
            else{
 
            $account_name = Account::where('Id',  $accountid)->value('ACName');
      
            $financial_start= $fromdate;
            $financial_end= $todate; 

            $fromdate_array=explode("-",$fromdate);
            $todate_array=explode("-",$todate);
 

            $fromdate_string= implode('-',array_reverse($fromdate_array)) ;
            $todate_string= implode('-',array_reverse($todate_array)) ; 
 
 
            $sub_ledger_inputs=array('all_accounts'=>array(  $accountid),
            'fromdate'=>$fromdate_string,'todate'=>$todate_string, 'selected_costcenter'=>$cost_center, 'selected_division'=>$division    ,'chequeno'=>NULL,'chequestatus'=>NULL,'clearingdate'=>NULL,
            'costcentre'=> NULL,'executive'=> NULL,'division'=>NULL,'foreignCurrency'=>NULL ,'no_of_additional_columns'=>
              0 ,'name_of_company'=>$name_of_company,'financial_year'=>$financial_year
                );

            $sub_ledger_inputs_json=json_encode( $sub_ledger_inputs);

            if(!empty( Cache::get(  $user_id."_sub_ledger_inputs") )){
                Cache::forget(  $user_id."_sub_ledger_inputs") ;
            }
    
            Cache::put(  $user_id."_sub_ledger_inputs", $sub_ledger_inputs_json,108000);
            return response()->json(['status'=>'success','message'=>"Sub Ledger is set"]);

            }

        }

}
