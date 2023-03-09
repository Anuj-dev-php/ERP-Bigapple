<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Code;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth; 
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Uom;
use App\Http\Controllers\Services\Function4FilterService;
use App\Models\UserCompany;
use Session;
use App\Models\FieldLevel;
use App\Http\CustomHelper;
use App\Models\RolesMap;
use App\Models\FieldCondition;
use App\Helper\Helper;
use App\Models\TblAt;
use App\Models\TblAuditData; 
use App\Models\RoleMonthLock;
use App\Models\TblLinkData;
use App\Models\TblLinkSetup;
use App\Models\StockDet;
use App\Models\TranAccount;
use App\Models\VchMain;
use App\Models\VchType;
use App\Models\VchDet;
use App\Models\TranAccDet;
use App\Models\InvAcc;
use App\Models\EmployeeMaster;
use App\Models\Account;
use App\Models\ProductMaster;
use App\Http\Controllers\Services\FunctionService;
use App\Http\Controllers\Services\EditTranDataService;
use App\Models\WorkFlowHead;
use App\Models\Location;
use App\Models\Receivables;
use App\Models\UserRestrictionTranxDay;
use App\Models\StatusTable;
use App\Models\TblPrintHeader;
use App\Exports\TransactionTableDataExcel; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use  App\Http\Controllers\Services\EmailTranDataService;
use  App\Events\EventSendAutoTranDataMail; 
use  App\Jobs\SendEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Models\TblEmailConfig;
use  App\Http\Controllers\Services\GstApiService;
use Illuminate\Support\Facades\Cache; 
use  App\Jobs\DownloadInvoiceFromUrl;
use App\Http\Controllers\Services\GatiService;
use  App\Http\Controllers\Services\WhatsAppService;
use App\Models\TableSmsHeader; 
use App\Jobs\SendWhatsappMessage;
use App\Models\Company; 

class TransactionActionRolesController extends Controller
{

    protected $function4filterservice;
    protected $functionservice;
    protected $edittrandataservice;
    protected $gstapiservice;
    protected $gatiservice;
    protected $whatsappservice;


    public function __construct(Function4FilterService $ffs,FunctionService $fservice,EditTranDataService $etdservice,GstApiService $gstservice,GatiService $gatiservice,WhatsAppService $whatsappservice){

        $this->function4filterservice=$ffs;

        $this->functionservice= $fservice;

        $this->edittrandataservice=$etdservice;

        $this->gstapiservice=$gstservice;

        $this->gatiservice= $gatiservice;

        $this->whatsappservice=$whatsappservice;

    }


    
    public function addTransactionInsertRoleFields($companyname,$tname,$tranid){
 

        $tablefound=TableMaster::findorfail($tranid);

        $tablename=  $tablefound->Table_Name;

        $tabledetname=$tablename."_det";

        $detailtableexists=TableMaster::where('Table_Name',$tabledetname)->exists();



        $headerfields=FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','<>','Pricing')->where('Field_Name','<>','status')->where('Field_Name','<>','reject_reason')->where('Tab_Id','<>','None')->orderby('id','asc')->get();


        if( $detailtableexists){
            $detailfields=FieldsMaster::where('Table_Name',  $tabledetname)->where('Tab_Id','<>','None')->orderby('id','asc')->get();
        }
        else{
            $detailfields=array();
        }

        $footerfields=FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','=','Pricing')
        ->where('Field_Name','<>','status')->where('Field_Name','<>','reject_reason')->orderby('id','asc')->get();

        $roleid=Session::get('role_id');
 

         $fieldlevels= FieldLevel::where(['uid'=>$roleid])->where( function($query)use($tablename){
            $query->where('txn_id',$tablename)->orwhere('txn_id',$tablename.'_det'); 

         })->select('txn_id','fld_id','hide','rdol')->get();

 
 
         $showhidefields=array();
         
         $showhide_detfields=array();
         foreach( $fieldlevels as  $fieldlevel){

            if(strpos($fieldlevel['txn_id'],'_det')==false){
                $showhidefields[$fieldlevel['fld_id']]=array('hide'=>trim($fieldlevel['hide']),'rdol'=>trim($fieldlevel['rdol']));
 
            }
            else{
                $showhide_detfields[$fieldlevel['fld_id']]=array('hide'=>trim($fieldlevel['hide']),'rdol'=>trim($fieldlevel['rdol']));
             }
 
         }
        $linksetupbase_txns= TblLinkSetup::where('link_txn',$tablename)->select('key_fld','base_txn')->get();
          

       $tran_accounts= TranAccount::where('Transaction','LIKE',$tablename.'%')->select('Id','TemplateId','is_default')->get();
 
        $mode="add";

        $this->edittrandataservice->tran_id=$tablefound->Id;
        $this->edittrandataservice->tran_table=$tablefound->Table_Name;
        $this->edittrandataservice->role=Session::get('role_id'); 
        $this->edittrandataservice->formmode="add";
        $showhidebuttons= $this->edittrandataservice->getButtonShowHideFromWorkflowHead();

        $allowpayablereceivable= $this->edittrandataservice->checkAllowReceivableOrPayable();
 
        $tblpdacc_result=$this->edittrandataservice->getTblPdAccDetails();

        $checkstockavailability = $this->edittrandataservice->checkStockAvailabilityValidation();

        $editdetailonreference='disable'; 

        $alltableswithoutdet=TableMaster::where('Table_Name','not like','%_det%')->orderby('table_label','asc')->pluck( 'table_label','Table_Name');

 

        if($detailtableexists==true){

            $this->edittrandataservice->tran_table=$tabledetname;

             $show_randp= $this->edittrandataservice->getShowRandP();


        }
        else{ 
            $show_randp=false;
        }
 
   

        return view('configuration.addTransactionInsertRoleFields',compact('companyname','tablefound','headerfields','detailfields','footerfields','showhidefields','showhide_detfields','linksetupbase_txns','tran_accounts','mode','showhidebuttons','allowpayablereceivable','tblpdacc_result','checkstockavailability','editdetailonreference','alltableswithoutdet','show_randp'));

    }


    public function getFunction2FieldValues(Request $request){
 

        $tablename=$request->data['table_name'];

        $fieldname=$request->data['field_name'];
  

        $this->functionservice->tablename=$tablename;

        $this->functionservice->fieldname= $fieldname;

        $responsearray=$this->functionservice->getFunction2FieldValues();
 
        return response()->json($responsearray); 

    }


    public function getFunction4TableRows(Request $request){

        $search=$request->searchTerm;

        $tablename=$request->data['table_name'];

        $fieldname=$request->data['field_name'];

        $this->function4filterservice->tablename=$tablename;

        $this->function4filterservice->fieldname=$fieldname;

        $this->function4filterservice->search=  $search; 

        $options= $this->function4filterservice->getFunction4Options();
 
        return response()->json( $options); 

    }


    public function getFunction5Codes(Request $request){

        $search=$request->searchTerm;

        $tablename=$request->data['table_name'];

        $fieldname=$request->data['field_name'];

        $this->functionservice->tablename= $tablename;
        $this->functionservice->fieldname=   $fieldname; 

        $codearray= $this->functionservice->getFunction5Codes();

        return response()->json( $codearray); 

    }


    public function getFunction19FieldValues(Request $request){

        $tablename=$request->table_name;

        $fieldname=$request->field_name;

        $fieldvaluedetail=FieldsMaster::where('Table_Name',$tablename)->where('Field_Name', $fieldname)->select('Field_Value','Field_Name')->first();
 
        if(!empty( $fieldvaluedetail->Field_Value)){
            $fieldvaluearray=explode(',',  $fieldvaluedetail->Field_Value);
        }
        else{
            $fieldvaluearray=array();
        }
  
        $responsearray=array();

        $index=0;
        foreach($fieldvaluearray as $fieldval){
            array_push($responsearray,array('id'=>$fieldval,'text'=>$fieldval));
            $index++;

        }

        return response()->json(['fieldvalues'=>$responsearray,'fieldname'=>strtolower($fieldvaluedetail->Field_Name)]); 

    }


    public function getFunction18Users(Request $request){

        $search=$request->searchTerm;
        $users=User::where('user_id','LIKE','%'. $search.'%')->orderby('user_id','asc')->select('id','user_id as text')->limit(10)->get()->toArray();
        return response()->json($users);

    }


    public function getCustomerAccBalance($companyname,$custid){

        $customerbalance=Customer::join('accounts','Customers.Acc_id','=','accounts.id')->where('Customers.Id',$custid)->select('accounts.bal')->value('bal');
        if(!empty($customerbalance)){
            $customerbalance= sprintf('%0.2f',$customerbalance);
        }
        else{
            $customerbalance=''; 
        }

        // $customerbalance=
        return response()->json(['balance'=> $customerbalance]); 

    }


    public function getFunction20CurrentUser($companyname){

        $user=Auth::user(); 
        return response()->json(array('id'=>$user->id,'user_id'=>trim($user->user_id))); 

    }


 

    public function getFunction4CheckOptions($companyname,$tablename){


        $this->function4filterservice->tablename=$tablename; 

        $responsearray= $this->function4filterservice->getTableFunction4CheckOptions();
 
 
        return response()->json($responsearray);

    }



    public function getFunction5CodesCheckOptions($companyname,$tablename){

        $this->functionservice->tablename=$tablename;

        $responsearray=   $this->functionservice->getFunction5CodeCheckOptions();
        
        return response()->json($responsearray);

    }


    public function getFunction2FieldValuesCheckOptions($companyname,$tablename){

        $this->functionservice->tablename=$tablename;
        $responsearray=  $this->functionservice->getFunction2_OptionDetail();
        
      
        return response()->json($responsearray); 
 

    }


    public function getFunction18UsersCheckOptions($companyname){
        $noofusers=User::count();
        $responsearray=array('noofusers'=> $noofusers,'single_id'=>'','single_text'=>''); 
        if($noofusers==1){
            $userdetail=User::first();
            $responsearray=array('noofusers'=> $noofusers,'single_id'=>   $userdetail->id,'single_text'=>trim($userdetail->user_id));
        }

        return response()->json(    $responsearray);


    }


    public function getFunction14Currency($companyname){



        $responsearray=$this->functionservice->getFunction14CurrencyOptions();

     

        return response()->json($responsearray);


    }


    public function getFunction14AllCurrencies(Request $request){
        $search=$request->searchTerm;

        $this->functionservice->search=$search;
        $currencies=  $this->functionservice->getFunction14AllCurrencies();

        return response()->json( $currencies);

    }


    public function getFunction15ExchangeRate(Request $request){
       
        $dategiven=$request->dategiven;

        $currency=$request->currency; 
 
        if(empty($dategiven) ||  empty($currency)){
            return;
        }


        $this->functionservice->dategiven=    $dategiven;
        $this->functionservice->currency=$currency;

        $this->functionservice->getFunction15ExchangeRate();




   
         $exchangeratedetail= ExchangeRate::where('curr_id',$currency)->where('edate','<=',$dategiven)->orderby('edate','desc')->first();
  
          $exchangerate='';

         if(!empty(  $exchangeratedetail)){
            $exchangerate=$exchangeratedetail->exrate;
         }

         return response()->json(['exrate'=>$exchangerate]); 

    }


    public function getFunction3FieldValues(Request $request){

        $search=$request->searchTerm;

        $fieldname=$request->data['field_name'];
        $tablename=$request->data['table_name'];

        $this->functionservice->tablename=$tablename;
        $this->functionservice->fieldname=   $fieldname;
 
        $fieldvalues=$this->functionservice->getFunction3FieldValues();
 
        
        return response()->json( $fieldvalues);

    }
 

    public function getFunction24FieldValuesCheckOptions(Request $request){  

        $tablename=$request->table_name;

        $scrfield=$request->scr_field;
        
        $fieldval=$request->field_val; 
       
        $responsearray=array(); 
        $fielddetails=FieldsMaster::where('Table_Name',$tablename)
        ->where('Field_Function',24)->where('SCR Field', $scrfield)->where('Field_Name','<>',$scrfield)->get();

       
        $fieldvalue=''; 

        $parenttable=TableMaster::where('Table_Name',$tablename)->value('Parent Table');

        if(trim($parenttable)=='None' || trim($parenttable)=='')
        goto response;
 
        foreach($fielddetails as $fielddetail){

            $fieldvalue=''; 

            $query1detail=FieldsMaster::where('Table_Name', $parenttable)->where('Field_Name',$fielddetail->{'Scr Field'})->first();   
   
            $noofoptions=0;
            if( !empty(trim($query1detail->From_Table))  && !empty(trim($query1detail->{'Scr Field'}))){
    
                $fromtable=trim($query1detail->From_Table);
    
                $scrfield=trim($query1detail->{'Scr Field'});
    
                $mapfield=trim($fielddetail->{'Map Field'});  
 
 
                $fieldvaluedetails=DB::table( $fromtable)->where(rtrim($scrfield), rtrim($fieldval))->select($mapfield)->pluck($mapfield);
                
                $noofoptions=count($fieldvaluedetails);  
                $fieldvalue= $fieldvaluedetails[0] ;   
            
                
            }
             
            array_push($responsearray,array('field_name'=>strtolower($fielddetail->Field_Name),'field_value'=>sprintf('%0.2f',$fieldvalue),'noofoptions'=>  $noofoptions));
         
        }

        response:
         
        return response()->json($responsearray);
    }


    public function getFunction16Uoms(Request $request){

        $searchterm=(isset($request->searchTerm)?$request->searchTerm:'');

        $uoms=Uom::where('name','LIKE','%'.$searchterm.'%')->select('id','name as text')->get()->toArray();

        return response()->json( $uoms);

    }



    
    public function getFunction3FieldValuesCheckOptions(Request $request){ 
        $tablename=$request->table_name;

        $scrfield=$request->scr_field;
        
        $fieldval=$request->field_val;

        $this->functionservice->tablename=   $tablename;
        $this->functionservice->scrfield=  $scrfield;
        $this->functionservice->fieldval=     $fieldval;

        $responsearray= $this->functionservice->getFunction3FieldValueCheckOptions();
 
        return response()->json($responsearray);
    }

    
    public function getFunction24FieldValues(Request $request){

        
        $search=$request->searchTerm;

        $fieldname=$request->data['field_name'];
        $tablename=$request->data['table_name'];
 
        $fielddetail=FieldsMaster::where('Table_Name',$tablename)->where('Field_Name' ,$fieldname)->first();

        
        $query1detail=FieldsMaster::where('Table_Name',$tablename)->where('Field_Name',$fielddetail->{'Scr Field'})->first(); 
 
        $fieldvalues=array(); 

        if( !empty(trim($query1detail->From_Table))  && !empty(trim($query1detail->{'Scr Field'}))){
            
            $fromtable=trim($query1detail->From_Table);

            $scrfield=trim($query1detail->{'Scr Field'});

            $mapfield=trim($fielddetail->{'Map Field'});  

            $fieldvalues=DB::table( $fromtable)->where(rtrim($scrfield), rtrim($fieldval))->select($mapfield.' as id',$mapfield.' as text')->get()->toArray();
             
            
        }
        
        return response()->json( $fieldvalues);


    }


    public function getFunction21WithoutFieldValues($companyname,$tablename){


        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',21)->where('Field_Value','=','')->get();

        $responsearray=array();


        foreach($fields as $field){

          $fieldvaluefound= DB::table($tablename)->orderby('id','desc')->limit(1)->value($field->Field_Name);

            $calulatefieldvalue='';

            if(!empty($fieldvaluefound)){
                $calulatefieldvalue= sprintf('%0.2f',$fieldvaluefound);
            }

            array_push($responsearray,array('field_name'=>strtolower($field->Field_Name),'field_value'=>$calulatefieldvalue));

        }


        return response()->json( $responsearray);

    }


    public function getFunction21WithFieldValues($companyname,$tablename){ 

        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',21)->where('Field_Value','<>','')->get();

        if(strpos($tablename,'_det')==false){
            $fieldisdet=0;
        }
        else{
            $fieldisdet=1;
        }

         $responsearray=array();
        foreach($fields as $field){

            
            $fromfields=array();


            $fieldvaluestring=$field->Field_Value;

            $fieldvaluearray=explode(',',$fieldvaluestring);
 


            foreach($fieldvaluearray as $fieldvaluestring1){

                $fieldvalue1array=explode('_IS_',trim($fieldvaluestring1));

                $tablenamefound=$fieldvalue1array[1];
 

                if(strpos( $tablenamefound,"_det") === false){
                    $isdet=0;

                }
                else{
                    $isdet=1;
                }

                array_push(   $fromfields,array('field_name'=>strtolower($fieldvalue1array[0]),'is_det'=>$isdet));
            }
 
            array_push($responsearray,array('forfield'=>strtolower($field->Field_Name),'fromfields'=>$fromfields,'fieldisdet'=>$fieldisdet));
        }
      

        return response()->json( $responsearray);



    }



    public function getFunction21SinfleFieldValueForDet(Request $request ){
 
        $tablename=$request->tablename;

        $fieldname=$request->fieldname;

        $fromfields=$request->fromfields;

        $fieldvalue=0;

        $foundfieldvalue=DB::table($tablename.'_det')->join($tablename,$tablename.'.id','=',$tablename.'_det.fk_id')
        ->where(function($query)use( $fromfields,$tablename){

            foreach($fromfields as $fromfield ){

                if($fromfield['is_det']==0){
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);
                }
                else{
                    $query->where($tablename.'_det.'.$fromfield['field_name'],'=',$fromfield['value']);

                }
             

            }

        })->orderby($tablename.'_det.id','desc')->limit(1)->value(    $fieldname);


        if(!empty($foundfieldvalue)){
            $fieldvalue= sprintf('%0.2f',$foundfieldvalue);
        }
        else{
            $fieldvalue= sprintf('%0.2f',$fieldvalue);
        }
        
        return response()->json(array('fieldvalue'=>$fieldvalue));

    }


    public function getFunction21SingleFieldValueForWithoutDet(Request $request){ 
        
        $tablename=$request->tablename;

        $fieldname=$request->fieldname;

        $fromfields=$request->fromfields;

        $fieldvalue=0;

        $foundfieldvalue=DB::table($tablename)->where(function($query)use( $fromfields,$tablename){

            foreach($fromfields as $fromfield ){

                if($fromfield['is_det']==0){
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);
                }
                else{
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);

                }
             

            }

        })->orderby($tablename.'.id','desc')->limit(1)->value($fieldname);


        if(!empty($foundfieldvalue)){
            $fieldvalue=  sprintf('%0.2f',$foundfieldvalue);
        }
        else{
            $fieldvalue=  sprintf('%0.2f',$fieldvalue);
        }
        
        return response()->json(array('fieldvalue'=>$fieldvalue));
 
    }


    public function getFunction45WithoutFieldValues($companyname,$tablename){

        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',45)->where('Field_Value','=','')->get();

        $responsearray=array();


        foreach($fields as $field){

          $fieldvaluefound= DB::table($tablename)->orderby('id','desc')->limit(1)->avg($field->Field_Name);

            $calulatefieldvalue='';

            if(!empty($fieldvaluefound)){
                $calulatefieldvalue= sprintf('%0.2f',$fieldvaluefound);
            }

            array_push($responsearray,array('field_name'=>strtolower($field->Field_Name),'field_value'=>$calulatefieldvalue));

        }


        return response()->json( $responsearray);

    }


    public function getFunction45WithFieldValues($companyname,$tablename){

        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',45)->where('Field_Value','<>','')->get();

        if(strpos($tablename,'_det')==false){
            $fieldisdet=0;
        }
        else{
            $fieldisdet=1;
        }

         $responsearray=array();
        foreach($fields as $field){

            
            $fromfields=array();


            $fieldvaluestring=$field->Field_Value;

            $fieldvaluearray=explode(',',$fieldvaluestring);
 


            foreach($fieldvaluearray as $fieldvaluestring1){

                $fieldvalue1array=explode('_IS_',trim($fieldvaluestring1));

                $tablenamefound=$fieldvalue1array[1];
 

                if(strpos( $tablenamefound,"_det") === false){
                    $isdet=0;

                }
                else{
                    $isdet=1;
                }

                array_push(   $fromfields,array('field_name'=>strtolower($fieldvalue1array[0]),'is_det'=>$isdet));
            }
 
            array_push($responsearray,array('forfield'=>strtolower($field->Field_Name),'fromfields'=>$fromfields,'fieldisdet'=>$fieldisdet));
        }
      

        return response()->json( $responsearray); 

    }


    public function getFunction45SingleFieldValueForDet(Request $request){

        $tablename=$request->tablename;

        $fieldname=$request->fieldname;

        $fromfields=$request->fromfields;

        $fieldvalue=0;

        $foundfieldvalue=DB::table($tablename.'_det')->join($tablename,$tablename.'.id','=',$tablename.'_det.fk_id')
        ->where(function($query)use( $fromfields,$tablename){

            foreach($fromfields as $fromfield ){

                if($fromfield['is_det']==0){
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);
                }
                else{
                    $query->where($tablename.'_det.'.$fromfield['field_name'],'=',$fromfield['value']);

                }
             

            }

        })->orderby($tablename.'_det.id','desc')->avg(    $fieldname);


        if(!empty($foundfieldvalue)){
            $fieldvalue=  sprintf('%0.2f', $foundfieldvalue);
        }
        else{
            $fieldvalue= sprintf('%0.2f',$fieldvalue);
        }
        
        return response()->json(array('fieldvalue'=>$fieldvalue));


    }


    public function getFunction45SingleFieldValueForWithoutDet(Request $request){

        $tablename=$request->tablename;

        $fieldname=$request->fieldname;

        $fromfields=$request->fromfields;

        $fieldvalue=0;

        $foundfieldvalue=DB::table($tablename)->where(function($query)use( $fromfields,$tablename){

            foreach($fromfields as $fromfield ){

                if($fromfield['is_det']==0){
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);
                }
                else{
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);

                }
             

            }

        })->orderby($tablename.'.id','desc')->avg($fieldname);


        if(!empty($foundfieldvalue)){
            $fieldvalue= sprintf('%0.2f',$foundfieldvalue);
        }
        else{
            $fieldvalue=  sprintf('%0.2f',$fieldvalue);
        }
        
        return response()->json(array('fieldvalue'=>$fieldvalue));
 

    }


    public function getFunction45SinfleFieldValueForDet(Request $request){

        $tablename=$request->tablename;

        $fieldname=$request->fieldname;

        $fromfields=$request->fromfields;

        $fieldvalue=0;

        $foundfieldvalue=DB::table($tablename.'_det')->join($tablename,$tablename.'.id','=',$tablename.'_det.fk_id')
        ->where(function($query)use( $fromfields,$tablename){

            foreach($fromfields as $fromfield ){

                if($fromfield['is_det']==0){
                    $query->where($tablename.'.'.$fromfield['field_name'],'=',$fromfield['value']);
                }
                else{
                    $query->where($tablename.'_det.'.$fromfield['field_name'],'=',$fromfield['value']);

                }
             

            }

        })->orderby($tablename.'_det.id','desc')->avg(    $fieldname);

        
        if(!empty($foundfieldvalue)){
            $fieldvalue=  sprintf('%0.2f',$foundfieldvalue);
        }  
        else{
            $fieldvalue=   sprintf('%0.2f',$fieldvalue);
        }
        return response()->json(array('fieldvalue'=>$fieldvalue));

    }


    public function addNewDetailFieldRow( Request $request){
 

        $tranid=$request->tranid;

        $rownumber=$request->rownum;

        $tablefound=TableMaster::findorfail($tranid);

        $tablename=  $tablefound->Table_Name;


        $tabledetname=$tablename."_det";

        $detailtableexists=TableMaster::where('Table_Name',$tabledetname)->exists();

        
        if( $detailtableexists){
            $detailfields=FieldsMaster::where('Table_Name',  $tabledetname)->where('Tab_Id','<>','None')->orderby('id','asc')->get();
 
        }
        else{ 
            $detailfields=array();
        }

        $roleid=Session::get('role_id');
 
        $fieldlevels= FieldLevel::where(['uid'=>$roleid])->where('txn_id',$tabledetname)->select('txn_id','fld_id','hide','rdol')->get();
  
        $showhide_detfields=array();
        foreach( $fieldlevels as  $fieldlevel){

            $showhide_detfields[$fieldlevel['fld_id']]=array('hide'=>trim($fieldlevel['hide']),'rdol'=>trim($fieldlevel['rdol']));
 
        }


        

        if($detailtableexists==true){

            $detailtablefound=TableMaster::where('Table_Name', $tabledetname)->first();
 

            if( (    trim($detailtablefound->Receivable)=="R" ||    trim($detailtablefound->Receivable)=="P")  &&  trim($detailtablefound->Tab_Id)=="Details"   ){
                $show_randp=true;
            }
            else{
                $show_randp=false;
            }

        }
        else{ 
            $show_randp=false;
        }
 
    
        $html=view("configuration.transactioninsertroledetailfieldstr",compact('detailfields','rownumber','showhide_detfields','show_randp' ))->render();
 

        return response()->json($html);

    }


    public function getFunction11FieldFormulas($companyname,$tablename){


        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',11)->select('Field_Name','Field_Value','Tab_Id')->get();

        $responsearray=array();

        foreach( $fields as  $field){
            
            $formulafields=$this->fetchFormula11Fields($field->Field_Value); 
 
            array_push($responsearray,array( 'field_name'=>strtolower($field->Field_Name) ,'tab_id'=>rtrim($field->Tab_Id),'formula_fields'=>$formulafields));

        }
 
        return response()->json($responsearray);
  
          
    }



    public function fetchFormula11Fields($formulastring){

        $formula=str_replace("*","#",$formulastring);
        $formula=str_replace("+","#",$formula);
        $formula=str_replace("-","#",$formula);
        $formula=str_replace("/","#",$formula);
        $formula=str_replace(")","#",$formula);    
         $formula=str_replace("(","#",$formula);

         $formularray=explode('#',$formula);
 
        $tempfieldsfound=array();

        foreach($formularray as $tempfield){

            if(strpos($tempfield, '_IS_') !== false)
            {
                array_push( $tempfieldsfound,$tempfield);
            }

        }
        $tempfieldsfound= array_unique($tempfieldsfound);


    
        $fieldsfound=array();

        foreach($tempfieldsfound as $fieldfound){

            $resultarray=explode('_IS_',$fieldfound); 
            if(strpos($fieldfound,'_det')!==false){
                 $isdet=1;
            }
            else{
                $isdet=0;
            }
           

            array_push($fieldsfound,array('fromfield'=>strtolower($resultarray[0]),'is_det'=>$isdet,'formula_field'=>$fieldfound,'values'=>array()));
 
        }


        return $fieldsfound;

    }





    public function getFunction11FieldDetDependentFormulas($companyname,$tablename){ 
        // 
        // ->where('Field_Value','LIKE','%det%')

        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',11)->where('Tab_Id','Pricing')->select('Field_Name','Field_Value','Tab_Id')->get();
 
        $responsearray=array();

        foreach( $fields as  $field){
            
            // $formulafields=$this->fetchFormula11FieldsWithDet($field->Field_Value); 
            $formulafields=$this->fetchFormula11Fields($field->Field_Value); 
            
            array_push($responsearray,array( 'field_name'=>strtolower($field->Field_Name) ,'tab_id'=>rtrim($field->Tab_Id),'formula_fields'=>$formulafields ));

        } 
 
     
        return response()->json($responsearray); 

    }


    public function fetchFormula11FieldsWithDet($formulastring){

        $formula=str_replace("*","#",$formulastring);
        $formula=str_replace("+","#",$formula);
        $formula=str_replace("-","#",$formula);
        $formula=str_replace("/","#",$formula);
        $formula=str_replace(")","#",$formula);    
         $formula=str_replace("(","#",$formula);

         $formularray=explode('#',$formula);
 
        $tempfieldsfound=array();

        foreach($formularray as $tempfield){

            if(strpos($tempfield, '_IS_') !== false)
            {
                array_push( $tempfieldsfound,$tempfield);
            }

        }
        $tempfieldsfound= array_unique($tempfieldsfound); 

        $fieldsfound=array();

        foreach($tempfieldsfound as $fieldfound){

            $resultarray=explode('_IS_',$fieldfound); 
            if(strpos($fieldfound,'_det')==false){
                 continue;
            }
           

            array_push($fieldsfound,array('fromfield'=>strtolower($resultarray[0]),'is_det'=>1,'formula_field'=>$fieldfound,'values'=>array()));
 

        }


        return $fieldsfound;



    }



    public function calculateFunction11PricingFieldValue(Request $request){
 
        $tablename=$request->tablename;
         
        $fieldname=$request->data['field_name'];  

        $formulafields=$request->data['formula_fields'];  
        $noofdetailfields=count($formulafields[0]['values']);

        $fielddetail=FieldsMaster::where('Table_Name',$tablename)->where('Field_Name',$fieldname)->select('Field_Value','Tab_Id')->first();


        $fieldvalueformulastring= $fielddetail->Field_Value;
        $tabid=$fielddetail->Tab_Id; 
        $total=0.00;  
        if(!empty($fieldvalueformulastring) || $fieldvalueformulastring!='Pricing'){  
     
                        $formulastrings=array(); 
                        
                        for($i=0;$i<$noofdetailfields;$i++){

                            $replaceformulastring=   $fieldvalueformulastring;

                            $isdone=0;
                            
                            foreach($formulafields as $formulafield){ 
                               
                                if(  array_key_exists($i,$formulafield['values'])  &&  is_numeric($formulafield['values'][$i]) ){  
                                    $replaceformulastring= str_replace($formulafield['formula_field'],$formulafield['values'][$i],$replaceformulastring);
                                    $isdone++;
                                } 
                            
                                
                            }


                            if(count($formulafields)==$isdone){ 
                                  eval( '$result = (' . $replaceformulastring. ');' );
                                $total= $total+$result; 
                            } 
            }
          
            $total= sprintf('%0.2f',$total);   
        
        }
     

        return response()->json(['field_name'=>$fieldname,'field_value'=>$total,'status'=>'success']);

    }




    public function getFunction11FieldFormulasOnlyHeader($companyname,$tablename){

        
        $fields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',11)->where('Tab_Id','Header')->select('Field_Name','Field_Value','Tab_Id')->get();

        $responsearray=array();

        foreach( $fields as  $field){
            
            $formulafields=$this->fetchFormula11Fields($field->Field_Value); 
 
            array_push($responsearray,array( 'field_name'=>strtolower($field->Field_Name) ,'tab_id'=>rtrim($field->Tab_Id),'formula_fields'=>$formulafields));

        }


        return response()->json($responsearray);
  


    }


    public function getFunction24DetFieldsToLoadFromFunction4(Request $request){
 
 
        $data=$request->data;
        
        $responsearray=array();

        if(empty($data) || count($data)==0){
            return response()->json($data);
        }

        $tablename=$request->tablename;

        $parenttable=TableMaster::where('Table_Name',    $tablename)->value('Parent Table');

        if(trim($parenttable)=='None' || trim($parenttable)=='')
        goto response;

        foreach($data as $field){ 

            $fieldsfunction24=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',24)->where('Scr Field',$field['fieldname'])->get();
 
            if(count($fieldsfunction24)==0){
                continue;
            } 
   
            foreach($fieldsfunction24 as $fielddetail){

                $fieldvalue=''; 

                $query1detail=FieldsMaster::where('Table_Name',  $parenttable)->where('Field_Name',$fielddetail->{'Scr Field'})->first();   
     
                $noofoptions=0;

                    if( !empty(trim($query1detail->From_Table))  && !empty(trim($query1detail->{'Scr Field'}))){
            
                        $fromtable=trim($query1detail->From_Table);
            
                        $scrfield=trim($query1detail->{'Scr Field'});
            
                        $mapfield=trim($fielddetail->{'Map Field'});  
        
 
                        $fieldvaluedetails=DB::table( $fromtable)->where(rtrim($scrfield), rtrim($field['fieldgivenvalue']))->select($mapfield)->pluck($mapfield);
                        
                        $noofoptions=count($fieldvaluedetails);  
                        $fieldvalue= $fieldvaluedetails[0] ;   
                    
                        
                    }
                    
                    // sprintf('%0.2f',$fieldvalue)
                    array_push($responsearray,array('field_name'=>strtolower($fielddetail->Field_Name),'field_value'=> $fieldvalue,'noofoptions'=>  $noofoptions));
                  
            } 
        }

        response:

        return response()->json($responsearray);
    }



    public function getFunction30FieldsFromTable($companyname,$tablename){
        
 
        $fields= FieldsMaster::where('Table_Name', $tablename)->where('Field_Function',30)->select('Field_Name','Field_Value','From_Table','Display Field','Scr Field')->get();

 

        if(strpos($tablename,'_det')==false){
            $isdet=0;
        }
        else{
            $isdet=1;
        }

        $responsearray=array();


        foreach($fields as $field){

            $comparisons=$this->fetchFunction30Where( $field->Field_Value);

            array_push( $responsearray,array( 'table_name'=>$tablename,'field_name'=>strtolower($field->Field_Name), 'is_det'=>$isdet,'display_field'=>strtolower($field->{'Display Field'}),'scr_field'=>strtolower($field->{'Scr Field'}) ,'from_table'=>$field->From_Table ,'comparisons'=>$comparisons));

        }
 

        return response()->json( $responsearray);
 

    }




    public function fetchFunction30Where($fieldvalue){

        $fieldsstringarray=explode(',',$fieldvalue);

        $newfieldarray=array();


        foreach($fieldsstringarray as $fieldstring){
 

            $temparray=explode('_IS_',$fieldstring);

            $comparefrom= $temparray[1];

            $compareto=$temparray[0];

            $comparetotable= $temparray[2];

            if(strpos($comparetotable,'_det')==false){
                $isdet=0;
            }
            else{
                $isdet=1;
            }

            array_push(  $newfieldarray ,array('comparefrom'=>$comparefrom,'compareto'=>strtolower($compareto),'comparetodet'=>$isdet,'value'=>''));


        }


        return $newfieldarray;

    }


    public function calculateFunction30FieldValue(Request $request){
 

        $fieldname=$request->data['field_name'];

        $isdet=$request->data['is_det'];

        $fromtable=$request->data['from_table'];

        $comparisons=$request->data['comparisons'];

        $displayfield=$request->data['display_field'];

        $scrfield=$request->data['scr_field'];

        $valuescomplete=true;

        
        foreach($comparisons as $comparison){ 

            if( array_key_exists("value",$comparison)==false || $comparison['value']==''){
                $valuescomplete=false;
            }
        }

        if($valuescomplete==false){
            return;
        } 
       

        $fieldvaluedetail=DB::table($fromtable)->where(function($query) use($comparisons){

            foreach($comparisons as $comparison){
                $query->where($comparison['comparefrom'],$comparison['value']);

            }

        })->pluck($displayfield,$scrfield)->toArray() ; 

        if(count($fieldvaluedetail)==0){ 
            return ;
        }

        $responsearray=array('field_name'=>strtolower($fieldname),'is_det'=>$isdet,'field_display'=>array_keys($fieldvaluedetail)[0],'field_value'=>array_values($fieldvaluedetail)[0]);
  


        return response()->json( $responsearray); 

 
    }


    public function calculateAllFunction11PricingFieldValue(Request $request){

           $tablename=$request->tablename;
         
        $data_array=$request->data ;  

  
        $responsearray=array();

        foreach( $data_array as $data){

            $fieldname= $data['field_name'];  
            $formulafields=$data['formula_fields'];   

            $noofdetailfields=count($formulafields[0]['values']);
    
            $fielddetail=FieldsMaster::where('Table_Name',$tablename)->where('Field_Name',$fieldname)->select('Field_Value','Tab_Id')->first();
    
    
            $fieldvalueformulastring= $fielddetail->Field_Value;
            $tabid=$fielddetail->Tab_Id; 
            $total=0.00;  
             
        if(!empty($fieldvalueformulastring) || $fieldvalueformulastring!='Pricing'){  
     
            $formulastrings=array(); 
            
            for($i=0;$i<$noofdetailfields;$i++){

                $replaceformulastring=   $fieldvalueformulastring;

                $isdone=0;
                
                foreach($formulafields as $formulafield){ 
                   
                    if(  array_key_exists($i,$formulafield['values'])  &&  is_numeric($formulafield['values'][$i]) ){  
                        $replaceformulastring= str_replace($formulafield['formula_field'],$formulafield['values'][$i],$replaceformulastring);
                        $isdone++;
                    } 
                
                    
                }


                if(count($formulafields)==$isdone){ 

                    try {
                        eval( '$result = (' . $replaceformulastring. ');' ); 
                    } catch(\DivisionByZeroError $e){ 
                        $result = 0;
                    } 
  

                       $total= $total+$result;  
                    } 
                 }

                    $total= sprintf('%0.2f',$total);   

                }

                
            array_push($responsearray,['field_name'=>strtolower($fieldname),'field_value'=>$total,'status'=>'success']);
        }

        return response()->json($responsearray);

    }


    public static function checkIt(){

        return 'aaya';
    }



    public static function CheckFieldDisplay($fieldname,$fieldarray){
 

            if(array_key_exists($fieldname,$fieldarray)){

                $data=$fieldarray[$fieldname];
        
                return  ($data['hide']=='True'?true:false);
        
            }
            else{
                return false;
            }

    }

    
    public static function CheckFieldReadOnly($fieldname,$fieldarray, $isreadonly="False"){
 
        if(!empty($isreadonly) &&  trim($isreadonly)=="True"){
            return true;
        }

        
        if(array_key_exists($fieldname,$fieldarray)){

            $data=$fieldarray[$fieldname];
    
            return  ($data['rdol']=='True'?true:false);
    
        }
        else{
            return false;
        }

}



   public static function CheckButtonTypeShowHide($buttontype,$tableid){

    
       $roleid=Session::get('role_id');

      $buttondetails= RolesMap::where(['RoleName'=>$roleid,'Tran_Id'=>$tableid])->select('Insert_Roles','Edit_Roles','Delete_Roles','View_Roles','Print_Roles')->first()->toArray();
  
        if(empty($buttondetails)){

            return true;

        }

      if($buttontype=='Save'){

        return (trim($buttondetails['Insert_Roles'])=='yes'?true:false);

      }
      else    if($buttontype=='Print' ||  $buttontype=='Email' || $buttontype=='Whatsapp'  ||    $buttontype=='Export'  ){

        return ((trim($buttondetails['View_Roles'])=='yes' &&  trim($buttondetails['Print_Roles'])=='yes')?true:false);

      }
      else    if($buttontype=='Delete' ){

        return (trim($buttondetails['Delete_Roles'])=='yes'?true:false);

      }
      else if($buttontype=='History'){

        return (trim($buttondetails['View_Roles'])=='yes'?true:false);

      }

   }


   public function getFunction4FieldNamesWithFieldConditions($companyname,$tablename){

      $fieldnames= FieldCondition::where('table_name',$tablename)->whereNotNull('rest_value')->distinct('field_name')->pluck('field_name');
 
      return response()->json( $fieldnames);

   }


   public function getFunction4FieldConditionRestrictedValue(Request $request){


    $val=$request->val;
    $fieldname=$request->fieldname;
    $tablename=$request->tablename;

    if(empty($val)){
        return;
    } 

    $this->function4filterservice->setTableNameFieldNameAndFieldVal($tablename,$fieldname,$val);


    $resultarray=$this->function4filterservice->getFunction4RelatedRestrictedFieldValueCondition();
 

    return response()->json($resultarray);


   }


   public function submitAddTransactionInsertRoleFields(Request $request){
 
        $data=$request->data; 

        $data_det=$request->data_det; 
  
        $tablename=$request->transaction_table; 

        $tranaccountid=$request->tran_account;

        $txnclass=$request->transaction_table_txnclass;

        $formmode=$request->formmode;

        $dataid=$request->data_id;

        $detail_row_indexes=json_decode($request->detail_rows_indexes,true);

        $receivablepayable_amtadjustments_detailwise=json_decode($request->receivablepayable_amountadjustments_detailwise,true);
         
        $role_id=Session::get('role_id');
        $companyname=Session::get('company_name');

        $recpay_amountadjustments= json_decode($request->receivablepayable_amountadjustments,true) ;
 
        $recpay_onaccount=$request->receivablepayable_onaccount;
        
        $trantableid=$request->transaction_table_id;

        $email_whatsapp_mode=$request->email_whatsapp_mode;

        $print_report_mode=(!empty($request->print_report_mode)?true:false);

        $this->edittrandataservice->role=$role_id;
        $this->edittrandataservice->tran_id=$trantableid;
        $this->edittrandataservice->tran_table=$tablename;

        $istablepurchaseorsales=TableMaster::where('Table_Name',$tablename)->where(function($query){

            $query->where('txn_class','LIKE','%Purchase%')->orwhere('txn_class','LIKE','%Sales%');

        })->exists();

        $fieldsarraywithvalue=array();
        
        if(  $formmode=="edit" && Schema::hasColumn($tablename, 'docno') ){
            
            $this->edittrandataservice->data_id=$dataid;
            $docno=$this->edittrandataservice->getDocNoFromTableUsingDataId();
            $fieldsarraywithvalue['docno']=$docno;

        }


        $fields= FieldsMaster::where('Table_Name',$tablename)->where('Field_Function','<>',12)->where('Field_Name','<>','Id')
        ->when(  $formmode=="edit",function($query){

            $query->where('Field_Function','<>',5);

        })
        ->select('Field_Name','Field_Function','Field_Type')->get();

        $function5codes=Code::where('table_name',$tablename)->select('field',DB::raw("CONCAT(prefix,code,suffix) as code"))->pluck('code','field')->toArray();
         
      
        $function5codes=array_change_key_case(   $function5codes,CASE_LOWER);
        $user=Auth::user();

        $docnumbernew="";
                    
        $currentcompany=Session::get('company_name'); 

         $subdetailrows_json= $request->subdetail_rows_data;

         if(!empty($subdetailrows_json)){

            $subdetailrows_array=json_decode($subdetailrows_json,true);

         }
         else{
            $subdetailrows_array=array();
         }
 

        DB::beginTransaction();

        try{
            $data= array_change_key_case($data,CASE_LOWER); 
  
 
            foreach( $fields as  $field){    

                if(array_key_exists(strtolower($field->Field_Name),$data)){
     
                    if($field->Field_Function==19){
                        // is checkboxes
                        $chks = $data[strtolower($field->Field_Name)];
                        $chkstring=implode(",", $chks);
                        $fieldsarraywithvalue[strtolower($field->Field_Name)]=  $chkstring;  
    
                    } 
                 else if($field->Field_Function==6){
                        // date 
                        $datestring= $data[strtolower($field->Field_Name)];
    
                        $datearray=explode("-",$datestring);
    
                        $fieldsarraywithvalue[strtolower($field->Field_Name)]=$datearray[2]."-".$datearray[1]."-".$datearray[0];
 
                         
                    }
                     else if($field->Field_Function==8){
                        $fileuploaded=$data[strtolower($field->Field_Name)]; 
    
                        $newfilename=$tablename."-".$field->Field_Name."-".time();
    
                        $newuploadfile= Helper::uploadFileAtFolder($fileuploaded,'transactiondocs', $newfilename);
     
                        if(  $newuploadfile!==false){
                            $fieldsarraywithvalue[strtolower($field->Field_Name)]=$newuploadfile;
                        }
                        else{
                            $fieldsarraywithvalue[strtolower($field->Field_Name)]='';
                        }
                         
                      
                    }
                    else if($field->Field_Function==40){
                        // image
    
                        $imguploaded=$data[strtolower($field->Field_Name)]; 
    
                        $newfilename=$tablename."-".$field->Field_Name."-".time();
    
                        $newuploadfile= Helper::uploadFileAtFolder($imguploaded,'transactiondocs', $newfilename,['png','jpg','jpeg' ]);
     
                        if(  $newuploadfile!==false){
                            $fieldsarraywithvalue[strtolower($field->Field_Name)]= $newuploadfile;
                        }
                        else{
                            $fieldsarraywithvalue[strtolower($field->Field_Name)]='';
                        }
    
    
                    }
                    else if($field->Field_Function==5){

                        $fieldsarraywithvalue[strtolower($field->Field_Name)]=$function5codes[strtolower($field->Field_Name)];
 
                        Code::where(['table_name'=>$tablename,'Field'=>strtolower($field->Field_Name)])->increment('code');
                        
                        if(strtolower($field->Field_Name)=="docno"){
                                        
                            $docnumbernew= $function5codes[strtolower($field->Field_Name)];

                        } 
 
 
                    }
                    else{

                        if(empty($data[strtolower($field->Field_Name)])){
                            
                         $fieldsarraywithvalue[strtolower($field->Field_Name)]=($field->Field_Type=="varchar"?'':NULL);
                          

                        }
                        else{
 
                            $fieldsarraywithvalue[strtolower($field->Field_Name)]=($field->Field_Type=="varchar"?trim($data[strtolower($field->Field_Name)]):$data[strtolower($field->Field_Name)]);
 
                        }
                    }
     
                }
                else{
                   $fieldsarraywithvalue[strtolower($field->Field_Name)]=($field->Field_Type=="varchar"?'':NULL);
                    
    
                } 
            }


            $headerstatusvalue=$this->edittrandataservice->getStatusIdFromWorkFlowsFromSave(  $fieldsarraywithvalue);

      
            if(empty($headerstatusvalue) ||  $headerstatusvalue==3){

                $statusbasedvalidation=true;
            }
            else{
                
                $statusbasedvalidation=false;;
            }

 
            if(empty(  $dataid)){ 


                if($tablename=="Customers"){
                   $newwaccountid= $this->AddCustomerToAccounts(  $fieldsarraywithvalue);
                   $fieldsarraywithvalue['acc_id']=$newwaccountid;
                   $fieldsarraywithvalue['status']='3';
                }
    
                
             
                if(!empty($headerstatusvalue)){

                    $fieldsarraywithvalue['status']= $headerstatusvalue;
                }

              
                $lastInsertId=  DB::table($tablename)->insertGetId( $fieldsarraywithvalue);
            }
            else{
 
                if(!empty($headerstatusvalue)){

                    $fieldsarraywithvalue['status']= $headerstatusvalue;
                }

                if(Schema::hasColumn($tablename,'reject_reason')){

                    $reject_reason=DB::table($tablename)->where("Id",$dataid)->value('reject_reason');

                    if(!empty($reject_reason)){
                        
                        $fieldsarraywithvalue['reject_reason']=$reject_reason;
    
                    }

                }
           
                DB::table($tablename)->where("Id",$dataid)->update($fieldsarraywithvalue);

                $lastInsertId=$dataid;

            }
      


     

            $stockoperation=TableMaster::where('id', $trantableid)->value('Stock Operation');
            $stockoperation=trim( $stockoperation);  

            $maintabledata=$fieldsarraywithvalue;

            $workflowsettings=WorkFlowHead::getLinkInvAccSettings( $role_id,$trantableid);

 
            
            // if( ($stockoperation=="Add" || $stockoperation=="Remove")  && $workflowsettings['inv_up']==true){

            //     $this->ManageAddRemoveStockOperation( $stockoperation,$trantableid,$lastInsertId,$maintabledata);
 
            // }
 
            
        $function5codes=Code::where('table_name',$tablename."_det")->select('field',DB::raw("CONCAT(prefix,code,suffix) as code"))->pluck('code','field')->toArray();
        $function5codes=array_change_key_case($function5codes,CASE_LOWER);
        
        $detdata=array(); 

            if( !empty($data_det)){


               $allpresentdetailids= array_filter(array_column($data_det,"Id"));
              
               if(  $formmode=="edit" ){ 
                  DB::table($tablename."_det")->where('fk_id',$lastInsertId)->whereNotIn('Id',$allpresentdetailids)->delete(); 

               }
                 
                 $fields=FieldsMaster::where('Table_Name',$tablename."_det")->where(function($query){
    
                    $query->where('Field_Function','<>',12);

                    $query->where('Field_Name','<>','Id');
                    
                    $query->where('Field_Name','<>','fk_Id');
    
                 })->select('Field_Name','Field_Function','Field_Type')->get();
    
                    foreach( $data_det as $data_det_key=>$data_det_key_value)
                    { 

                        $data_det[$data_det_key]=array_change_key_case($data_det_key_value,CASE_LOWER);
                        $fieldsarraywithvalue=array();
    
                        $fieldsarraywithvalue['fk_Id']=$lastInsertId;
                    
                        if(array_key_exists( "ref_detail_id",$data_det[$data_det_key])){

                            $refdetailid=$data_det[$data_det_key][ "ref_detail_id"];
                        }
                        else{
                            $refdetailid="";
                        }
 
                        if(array_key_exists("id",$data_det[$data_det_key]) && !empty($data_det[$data_det_key]) ){
                            $detailid=$data_det[$data_det_key]['id'];
                        }
                        else{
                            $detailid="";
                        }

                        foreach( $fields as  $field){  
                        
                            if(array_key_exists(strtolower($field->Field_Name),$data_det[$data_det_key])){
                
                                if($field->Field_Function==19){
                                    // is checkboxes
                                    $chks = $data_det[$data_det_key][strtolower($field->Field_Name)];
                                    $chkstring=implode(",", $chks);
                                    $fieldsarraywithvalue[strtolower($field->Field_Name)]=  $chkstring; 
                
                                } 
                            else if($field->Field_Function==6){
                                    // date 
                                    $datestring= $data_det[$data_det_key][strtolower($field->Field_Name)];
                
                                    $datearray=explode("-",$datestring);
                
                                    $fieldsarraywithvalue[strtolower($field->Field_Name)]=$datearray[2]."-".$datearray[1]."-".$datearray[0];
                                    
                                }
                                else if($field->Field_Function==5){
 
                                    $fieldsarraywithvalue[strtolower($field->Field_Name)]=  $function5codes[strtolower($field->Field_Name)];
  
                                    Code::where(['table_name'=>$tablename."_det",'Field'=>strtolower($field->Field_Name)])->increment('code');
                                


                                }
                                else if($field->Field_Function==8){
                                    $fileuploaded=$data_det[$data_det_key][strtolower($field->Field_Name)]; 
                
                                    $newfilename=$tablename."-".$field->Field_Name."-".time();
                
                                    $newuploadfile= Helper::uploadFileAtFolder($fileuploaded,'transactiondocs', $newfilename);
                
                                    if(  $newuploadfile!==false){
                                        $fieldsarraywithvalue[strtolower($field->Field_Name)]=$newuploadfile;
                                    }
                                    else{
                                        $fieldsarraywithvalue[strtolower($field->Field_Name)]='';
                                    }
                                
                                }
                                else if($field->Field_Function==40){
                                    // image
            
                                    $imguploaded=$data_det[$data_det_key][strtolower($field->Field_Name)]; 
                
                                    $newfilename=$tablename."-".$field->Field_Name."-".time();
                
                                    $newuploadfile= Helper::uploadFileAtFolder($imguploaded,'transactiondocs', $newfilename,['png','jpg','jpeg' ]);
                
                                    if(  $newuploadfile!==false){
                                        $fieldsarraywithvalue[strtolower($field->Field_Name)]= $newuploadfile;
                                    }
                                    else{
                                        $fieldsarraywithvalue[strtolower($field->Field_Name)]='';
                                    }
                
                
                                }
                                else{

                                    if(!array_key_exists(strtolower($field->Field_Name),$data_det[$data_det_key])){

                                    $fieldsarraywithvalue[strtolower($field->Field_Name)]=(trim($field->Field_Type)=='varchar'?'':NULL);
                                          
                                    }
                                    else{
 
                                        
                                    $fieldsarraywithvalue[strtolower($field->Field_Name)]=(trim($field->Field_Type)=='varchar'?trim($data_det[$data_det_key][strtolower($field->Field_Name)]):$data_det[$data_det_key][strtolower($field->Field_Name)]);

                                    }


                                }
                
                            }
                            else{

                            $fieldsarraywithvalue[ strtolower($field->Field_Name)]=(trim($field->Field_Type)=='varchar'?'':NULL); 
                            } 
                        } 

 
                        

                        if(empty($detailid)){
                                    
                            $lastinsertdetailid=DB::table($tablename."_det")->insertGetId($fieldsarraywithvalue);
                            $fieldsarraywithvalue['id']=  $lastinsertdetailid;

                        }
                        else{ 

                            $lastinsertdetailid=$detailid;
                            DB::table($tablename."_det")->where('Id',$detailid)->update($fieldsarraywithvalue);
                            $fieldsarraywithvalue['id']=  $lastinsertdetailid;
                        }

                        $subdetailindex=$data_det_key+1;

                        if(array_key_exists("subdetailrow_".$subdetailindex,$subdetailrows_array)){
 
                           $this->addDeleteDetailSubDetails( $tablename."_det",$fieldsarraywithvalue['fk_Id'],$lastinsertdetailid,$subdetailrows_array["subdetailrow_".$subdetailindex]);

                        }


                        if($formmode=="edit" &&   $stockoperation!="None"  ){
 
                            $this->ManageDeleteStockOperation( $maintabledata['docno'],$allpresentdetailids);
                        }        
                        
                        if( ($stockoperation=="Add" || $stockoperation=="Remove")    && $workflowsettings['inv_up']==true  &&   $statusbasedvalidation==true   ){
 
                            $this->ManageAddRemoveStockOperation( $stockoperation,$trantableid,$lastInsertId,$maintabledata, $lastinsertdetailid,$fieldsarraywithvalue);
            
                        }

 

                     if(!empty( $refdetailid)  && $workflowsettings['link_up']==true && $statusbasedvalidation==true ){


                        $this->updateTblLinkDataReffnoAndBalanceQty( $refdetailid,$lastinsertdetailid,$fieldsarraywithvalue['quantity']);
                          

                     }
 
                        array_push(  $detdata,  $fieldsarraywithvalue);
    
                    }
     
                //  DB::table($tablename."_det")->insert( $detdata); 

            } 

             
             if($tablename=="journal"){
                 

                $this->addUpdateJournalVoucherEntry($tablename ,$txnclass ,$tranaccountid ,$lastInsertId,$maintabledata,$detdata);

            }
           else if( !empty($tranaccountid) && $workflowsettings['acc_up']==true &&  $statusbasedvalidation==true){
                    
                $this->addFaIntegration($tablename ,$txnclass ,$tranaccountid ,$lastInsertId,$maintabledata,$detdata);
 
            }






            if($formmode=="add"){
                
                TblAt::insert(['Txn'=>$tablename,'opr'=>'Save','uid'=>$user->id,'stime'=>date("m/d/Y h:i:s A",strtotime('now')),'ntime'=>'','rec_id'=>$lastInsertId]);
              
            }
            else{
                TblAt::insert(['Txn'=>$tablename,'opr'=>'Edit','uid'=>$user->id,'stime'=>date("m/d/Y h:i:s A",strtotime('now')),'ntime'=>'','rec_id'=>$lastInsertId]);
               
            }


            $this->AddDeleteTblAuditData($tablename, $lastInsertId,$maintabledata,$detdata,$formmode);

 
            if($statusbasedvalidation==true){
                $this->ManageTblLinkSetupAndData($tablename,$maintabledata,$lastInsertId,$detdata);
            }
          
 
            $allowreceivableorpayable=  $this->edittrandataservice->checkAllowReceivableOrPayable();
          
                       
            if(  ($workflowsettings['inv_up']==true ||  $statusbasedvalidation==true) &&  array_key_exists('cust_id',$maintabledata) &&  $allowreceivableorpayable==true){
      
               $this->addUpdateReceivablesPayables($tablename, $maintabledata,$recpay_onaccount,$recpay_amountadjustments);
            }

            $this->edittrandataservice->tran_table=$tablename."_det";


            $show_randp= $this->edittrandataservice->getShowRandP();


            if(   $show_randp==true){

                $this->addUpdateDetailRowsReceivables($tablename."_det" ,$maintabledata ,$detail_row_indexes,$receivablepayable_amtadjustments_detailwise);
            }



           
            DB::commit(); 

 
  
            // $reditecturl=($formmode=="add"?"add-transaction-insert-role-fields/".$tablename."/".$trantableid:"edit-transaction-table-data/".$tablename."/".$trantableid);
         
            if($print_report_mode==false){
                $reditecturl="/".$companyname."/add-transaction-insert-role-fields/".$tablename."/".$trantableid;
            }
            else{
                $reditecturl="/".$companyname."/edit-transaction-table-data/".$tablename."/".$trantableid."/".$lastInsertId;
           
            }
       
            $action=($formmode=="add"?"Added":"Updated");

            $form_message= $docnumbernew.' Transaction Table '.$tablename.' Data '. $action.' Successfully';

            
            if( $email_whatsapp_mode=="email"){

      
                // $em=new EmailTranDataService;
                // $em->setSmtpUniversalSettings();
                // $em->tran_table=$tablename;
                // $em->data_id= $lastInsertId;  
                // $em->db_name=Session::get('company_name');  
                // $emails_array= $em->SendAutoMailByEmailConfiguration();
                // $emails_array= $em->formatEmailsArraySubjectAndBody( $emails_array);
                // Helper::connectDatabaseByName('Universal');
                // $em->SetSendTranDataMailsFromArray($emails_array);
                // Helper::connectDatabaseByName(Session::get('company_name'));
 
            }

            // handle gst api service and send invoice
        
            // $formmode=="add"  &&

            if($formmode=="add"){
                

            if( $tablename=="GSI" ||  $tablename=="GSR" ||  $tablename=="GSRA"   ){

                $this->gstapiservice->tran_table=  $tablename;
            
                $this->gstapiservice->data_id= $lastInsertId;
                 $validate_gst= $this->gstapiservice->validateForGstApi(); 


                 if( $validate_gst==true){
             

                $result=$this->gstapiservice->setGstInvoiceAuthToken();

                $user_id= Auth::user()->user_id;

                if($result['status']==false){

                    $this->gstapiservice->addGstErrorMessage(  $user_id  ,$result);
                }

                $gstresponse=$this->gstapiservice->generateIRN($tranaccountid);
  
                if($gstresponse['status']==true){

                    $this->gstapiservice->saveIrnGeneratedDetails($gstresponse['data']);

                }
                else{

                    $this->gstapiservice->addGstErrorMessage(   $user_id, $gstresponse);

                    Session::put('gst_error',$gstresponse['message']);
                }

                        
            }

  
            }

             
        }
             
           return redirect(  $reditecturl)->with('message',     $form_message);

        }
        catch(\Exception $e){
 

            DB::rollback(); 

            LogMessage($e,$request->all());

            
            return redirect()->back()->with('error_message','Something went wrong ');
        }
 
    
   }




   public function validateSubmitTransactionTableData(Request $request){

           $tran_account_id= $request->tran_account; 
           $trantableid=$request->transaction_table_id;
           $tablename=$request->transaction_table; 
           $data=$request->data; 
           $data_det=(empty($request->data_det)?array():$request->data_det);
           $tranaccountid=$request->tran_account;
 

           if(empty($request->data_id)){
               $form_mode="add";
           }
           else{
            $form_mode="edit";
            $data_id=$request->data_id;
           }
           

           $data= array_change_key_case($data,CASE_LOWER); 

           $data_det= array_change_key_case($data_det,CASE_LOWER); 

           $roleid=  Session::get('role_id');

           $user=Auth::user();
 
           if( array_key_exists('docdate',$data)){

                    $docdatestring= $data['docdate'] ;
                    $docdatearray=explode("-", $docdatestring);
                    $docno= $data['docno'] ;


                    if(empty($docdatearray[2]) || empty($docdatearray[1]) ||  empty($docdatearray[0])){
                        return response()->json(['status'=>'failure','message'=>"Doc Date is incorrect"]);
                    }
 
                    $docdate=$docdatearray[2]."-".$docdatearray[1]."-".$docdatearray[0];


                    if(checkdate($docdatearray[1],$docdatearray[0],$docdatearray[2])==false){
                        return response()->json(['status'=>'failure','message'=>"Doc Date is incorrect"]);
                    }

                    $this->edittrandataservice->dbname=Session::get('company_name');
                    $this->edittrandataservice->docdate=  $docdate;
                     $isof_fy= $this->edittrandataservice->checkDocDateOfCurrentFinancialYear();

                     if( $isof_fy==false){
                         
                           return response()->json(['status'=>'failure','message'=>"Doc Date is not of Financial Year"]);

                     }

                                 //  in edit check if docdate is of month lock period then also it should not allow
                                 if($form_mode=="edit"){

                                    $presentdocdetail= DB::table($tablename)->where('Id',$data_id)->select('docdate','docno')->first();
             
                                    $presentdocdate= date("Y-m-d",strtotime(  $presentdocdetail->docdate));
             
                                     $monthlockexists= RoleMonthLock::where('role_id',$roleid)->where( 'from_date','<=',   $presentdocdate)->where( 'to_date','>=',   $presentdocdate)->exists();
             
                                 }
                                 else{
             
                                     $monthlockexists= RoleMonthLock::where('role_id',$roleid)->where( 'from_date','<=',  $docdate)->where( 'to_date','>=',  $docdate)->exists();
                     
                                 } 
             
                                 if($monthlockexists){
                     
                                 return response()->json(['status'=>'failure','message'=>"You are locked for this month"]);
                                 } 
              




           }



                 
            
                    if($form_mode=="edit"){
                        //  check in table tbl_user_rest_tran_day if edit days after doc date
    
                        $editafterdays= UserRestrictionTranxDay::where(['user_id'=>$user->id,'tranx_id'=>   $trantableid])->where('edit_days','>',0)->value('edit_days');
    
                        $currentdate=date("Y-m-d",strtotime("now"));
    
                        if(  $editafterdays!==0  && !empty($editafterdays)){
    
                            $editafterdate=date('Y-m-d', strtotime( $presentdocdate. ' + '.$editafterdays.' days'));
     
                            if(  $currentdate> $editafterdate){
                                
                                return array('status'=>false,'message'=> $presentdocdetail->docno." is not allowed to edit after ".$editafterdays." days");
                            }
    
                        }
    
                    }
                    else{
     
                        $addafterdays= UserRestrictionTranxDay::where(['user_id'=>$user->id,'tranx_id'=>   $trantableid])->where('add_days','>',0)->value('add_days');
    
                        $currentdate=date("Y-m-d",strtotime("now"));
    
                        if(  $addafterdays!==0  && !empty($addafterdays)){
    
                            $addafterdate=date('Y-m-d', strtotime(     $currentdate. ' + '.$addafterdays.' days'));
    
                            $addafterbeforedate=date('Y-m-d', strtotime(     $currentdate. ' - '.$addafterdays.' days'));
     
                            if(  $docdate> $addafterdate){
                                
                                return array('status'=>false,'message'=> $docno." is not allowed to add after ".$addafterdays." days");
                            }
                            else if(  $docdate< $addafterbeforedate){
                                return array('status'=>false,'message'=> $docno." is not allowed to add before ".$addafterdays." days");
                         
                            }
    
                        }
    
    
                    }



         if( $tablename=="Customers" && $form_mode=="add"){

            $newcustid=trim($data['cust_id']);


           $customer_alreadyexists= Account::where('ACName',$newcustid)->exists();


           if( $customer_alreadyexists==true){

                return response()->json(['status'=>false,'message'=>'Customer by this name already exists']);
           }

         }

      
         if( $tablename=="Customers" && !empty(trim($data['gstno']))){
 
            $this->gstapiservice->setGstInvoiceAuthToken();
            $this->gstapiservice->check_gst_no=trim($data['gstno']); 
            $isvalid= $this->gstapiservice->checkGstNumberValidity(); 

            if($isvalid==false){
                
                return response()->json(['status'=>false,'message'=>'GST No. entered is invalid , Please check GST No.']);

            }
 
         }
 
         
        //  in case of journal check in loop that that debit amount and credit amount must be equal
        
        if($tablename=="journal" && array_key_exists('totaldebitamount',$data) && array_key_exists('totalcreditamount',$data) ){

            $totaldebitamount=$data['totaldebitamount'];

            $totalcreditamount=$data['totalcreditamount'];

            if( $totaldebitamount!=$totalcreditamount){
                return response()->json(['status'=>false,'message'=>'Total Debit Amount and Total Credit Amount must be equal']);
            }
 
        }


        // in case of detail table check if show r and p is true or false , if true then check in each detail row line_acc field must have unique values

          $tabledetname=$tablename."_det";

          $tabledetexists=TableMaster::where('Table_Name', $tabledetname)->exists();


          if(   $tabledetexists==true){

               $this->edittrandataservice->tran_table=  $tabledetname;
              $show_randp= $this->edittrandataservice->getShowRandP();
         
          }
          else{
              $show_randp=false;
          }



          if($show_randp==true){

              $islineacc_repeated=false;

              $used_lineacc=array();

              foreach(  $data_det as $data_single_det){

                  if(in_array($data_single_det['line_acc'],$used_lineacc)){
                      $islineacc_repeated=true;
                  }

                  array_push(  $used_lineacc,$data_single_det['line_acc']);
                 
              }

              if(  $islineacc_repeated==true){
                  return response()->json(['status'=>false,'message'=>'Please enter different accounts because using same accounts are not allowed']);
              }



          }


           $nooftranaccounts=TranAccount::where('Transaction','LIKE',$tablename.'%')->count();


           if($nooftranaccounts>0){

                if(!array_key_exists('docdate',$data)){

                    return response()->json(['status'=>'failure','message'=>'Doc Date Field is missing']);

                }
                else if(!array_key_exists('docno',$data)){

                    return response()->json(['status'=>'failure','message'=>'Doc Number Field is missing']);
                }


           }


        //    check in  case of Customers Table if customer phone number got repeated then do no allow customer to save 
  
           if(  $tablename=="Customers" && !empty($data['phone#'])  && $form_mode=="add"){

              $phonenumber= $data['phone#'];
 

              $phoneresult=DB::select("select count(id) as noofrepeat, id as customerid
              from dbo.customers
              where replace(phone#,' ','') like '%".$phonenumber."%'
              group by id");
 
              $phoneresult = json_decode(json_encode($phoneresult ), true);
 
              if( count(  $phoneresult)>0 && $phoneresult[0]['noofrepeat']>0){
                  return response()->json(['status'=>false,'message'=>"Customer with the same contact no exists - Customer ID No. ".$phoneresult[0]['customerid']]);
              }
 
           }

 

           if(  $tablename=="Customers" && !empty($data['whatsappno']) && $form_mode=='add'){

            $whatsappnumber= $data['whatsappno'];


            $phoneresult=DB::select("select count(id) as noofrepeat, id as customerid
            from dbo.customers
            where replace(whatsappno,' ','') like '%".$whatsappnumber."%'
            group by id");

            $phoneresult = json_decode(json_encode($phoneresult ), true);

            if( count(  $phoneresult)>0 && $phoneresult[0]['noofrepeat']>0){
                return response()->json(['status'=>false,'message'=>"Customer with the same contact no exists - Customer ID No. ".$phoneresult[0]['customerid']]);
            }

         }

 
        //    check in case of Fa Integration of sum of all amounts must be between -1 to +1


        if(!empty( $tranaccountid)  && $tablename!="Journal"){
 

            $faintegration_amounts=array();


            $tranaccdetail=TranAccount::where('Id',$tranaccountid)->first();

            $mainaccountbyto=   $tranaccdetail->mainaccount_byto;
 

            $mainaccountformulafield= $tranaccdetail->mainaccount_formula;

            if(array_key_exists( $mainaccountformulafield,$data)){
                $mainaccountformulafieldvalue=(float)$data[ $mainaccountformulafield];
            }
            else{
                $mainaccountformulafieldvalue=0;
            }
           

              if($mainaccountbyto=="To"){
                $mainaccountformulafieldvalue=$mainaccountformulafieldvalue*(-1);
              }


              array_push($faintegration_amounts,   $mainaccountformulafieldvalue);
            
            $tranaccdets=TranAccDet::where('TempId',$tranaccountid)->get();
 
                    foreach($tranaccdets as $tranaccdet){

                        $byto=trim($tranaccdet->{'By/To'});

                        $formulafieldname=strtolower(trim($tranaccdet->{'Formula'}));
 

                            if(trim($tranaccdet->{'AccName'})=="line_acc"){

                                foreach(   $data_det as $det_single){

                                    if(array_key_exists('amount',$det_single)){
                                        $lineaccamount=(float)$det_single['amount']; 
                                    }
                                    else if(array_key_exists('debitamount',$det_single)){
                                        $lineaccamount=(float)$det_single['debitamount'];  
                                    }
                                    else{
                                        $lineaccamount=(float)$det_single['creditamount'];  
                                    }
 
            
                                        if( $byto=="By" &&    $lineaccamount<0){
                                            $lineaccamount= $lineaccamount*(-1);
                                        
                                        }
                                        else if($byto=="To" &&  $lineaccamount>0){
                                            $lineaccamount=$lineaccamount*(-1);
                                        }
              
                               
                                  array_push($faintegration_amounts,  $lineaccamount);
                                } 

                            }
                            else{  

                                if(array_key_exists( $formulafieldname,$data)){

                                    $amount=(float)$data[$formulafieldname];
        
                                    if( $byto=="By" &&    $amount<0){
                                        $amount= $amount*(-1);
                                    
                                    }
                                    else if($byto=="To" &&   $amount>0){
                                        $amount= $amount*(-1);
                                    }
        
                                }
                                else{
                                    $amount=0.00;
                                }
         
 
                                  array_push($faintegration_amounts,  $amount);
                                
                            } 

                    } 
                     

                    $calculated_faintegration=array_sum($faintegration_amounts);
                    $calculated_faintegration=round($calculated_faintegration,2);
 

                    if(   $calculated_faintegration<-1  ||  $calculated_faintegration>1   ){
            
                        return response()->json(['status'=>false,'message'=>"Debit Amount and Credit Amount is not equal"]);
                    } 

        }

    
        //    check if in table table has receivable R or P then check if its header contain acc_id or not  

          $receivable=TableMaster::where('Table_Name',$tablename)->value('Receivable');
 
          if(array_key_exists('cust_id',$data) &&  is_numeric($data['cust_id']) ){

                $custid=$data['cust_id'];
                $accid=Customer::where('Id', $custid)->value('Acc_id');
    
                $accountdetail=Account::where('Id', $accid)->first();
    
                if( (trim($receivable)=='P' ||  trim($receivable)=='R') && empty( $accountdetail) ){
                    return response()->json(['status'=>'failure','message'=>'Account Id field is missing']);
                }
  
          }
 

           $tranaccdetformulafields= TranAccDet::where('TempId',$tran_account_id)->where('AccName','<>','line_acc')->pluck('formula') ;


           $tranaccdetmissing=array();


           foreach($tranaccdetformulafields as $tranaccdetformulafield){

                if(!array_key_exists(trim(strtolower($tranaccdetformulafield)),$data)){
                    array_push(  $tranaccdetmissing,$tranaccdetformulafield);
                }

           }

           $tranaccdetformulafields_lineacc= TranAccDet::where('TempId',$tran_account_id)->where('AccName','=','line_acc')->pluck('formula');


           foreach(  $tranaccdetformulafields_lineacc as   $tranaccdetformulafield_lineacc){

                    foreach($data_det as $single_data_det){

                                    
                            if(!array_key_exists(trim(strtolower($tranaccdetformulafield_lineacc)),$single_data_det)){
                                array_push(  $tranaccdetmissing,$tranaccdetformulafield_lineacc);
                            }

                    }
 
           }




           if(count( $tranaccdetmissing)>0){
               return response()->json(['status'=>'failure','message'=>implode(',',$tranaccdetmissing)."  are mandatory , which are missing ."]);
           }
           
           $tranaccdetmissingvalues=array();

           foreach($tranaccdetformulafields as $tranaccdetformulafield){

                if(array_key_exists(trim(strtolower($tranaccdetformulafield)),$data)  &&  empty($data[trim(strtolower($tranaccdetformulafield))])){
                    array_push(  $tranaccdetmissingvalues,$tranaccdetformulafield);
                }

             }

             

           foreach(  $tranaccdetformulafields_lineacc as   $tranaccdetformulafield_lineacc){

                    foreach($data_det as $single_data_det){

                        if(array_key_exists(trim(strtolower($tranaccdetformulafield_lineacc)),$single_data_det)  &&  empty($single_data_det[trim(strtolower($tranaccdetformulafield_lineacc))])){
                            array_push(  $tranaccdetmissingvalues,$tranaccdetformulafield_lineacc);
                        }

                        
                    }

            }


             if(count($tranaccdetmissingvalues)>0){
                return response()->json(['status'=>'failure','message'=>implode(',',$tranaccdetmissingvalues)."  are mandatory  "]);
         
             }




           $fields= FieldsMaster::where('Table_Name',$tablename)->whereNotIn('Field_Function',array(19,6,8,40,5))->select('Field_Name','Field_Function','Field_Type','fld_label')->get();
         
           $data= array_change_key_case($data,CASE_LOWER); 
           
           $fieldwithconsequtivespaces=array();
           
           foreach( $fields as  $field){   

                if(array_key_exists(strtolower($field->Field_Name),$data)){

                    $givenvalue=  $data[strtolower($field->Field_Name)];
 
                    if(preg_match('/\s\s+/',   $givenvalue)){ 
                        
                        array_push($fieldwithconsequtivespaces,$field->fld_label);
                        
                    }

                }

           
           }


           
           $detfields= FieldsMaster::where('Table_Name',$tablename."_det")->whereNotIn('Field_Function',array(19,6,8,40,5))->select('Field_Name','Field_Function','Field_Type','fld_label')->get();
         
            
            $data_det= array_filter($data_det);
  
           foreach( $data_det as $datadetkey=>$datadetkeyvalue){

                $detdata=array_change_key_case($datadetkeyvalue);


                foreach( $detfields as  $field){   

                    if(array_key_exists(strtolower($field->Field_Name),$detdata)){
      
                        $givenvalue=  $detdata[strtolower($field->Field_Name)];
      
                        if(preg_match('/\s\s+/',   $givenvalue)){ 

                            
                            array_push($fieldwithconsequtivespaces,$field->fld_label." ".($i+1));
                            
                        }
      
                    }
      
               
               }
 

           }
   
           if(count($fieldwithconsequtivespaces)>0){ 


            $fieldnamestring="[".implode(" , ",$fieldwithconsequtivespaces)."]";
            
                $responsearray=array("status"=>"failure","message"=>$fieldnamestring." field contains two consecutive spaces , only 1 space allowed");

                return response()->json($responsearray);

           }





        //    check for both tables if field is unique or not 

                $notuniquefields=array();

                 
                $uniquefields=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function','<>',12)->where( 'fld_unique', 'True')->select('Field_Name','fld_label')->get();
                 
                $valueexists=false;
                foreach($uniquefields as $uniquefield){
                    
                    if(array_key_exists(strtolower($uniquefield->Field_Name),$data)){ 
                        
                        $valueexists=DB::table($tablename)->where($uniquefield->Field_Name,$data[strtolower($uniquefield->Field_Name)])->exists();

                        if($valueexists==true){
                            array_push($notuniquefields,$uniquefield->fld_label);
                        }


                    }

                } 


                 
                $uniquedetfields=FieldsMaster::where('Table_Name',$tablename."_det")->where('Field_Function','<>',12)->where( 'fld_unique', 'True')->select('Field_Name','fld_label')->get();
                 
                $valueexists=false;

                
                $uniqueindex=1;
           foreach(  $data_det as $data_det_key=>$data_det_key_value){

                $detdata=array_change_key_case($data_det[$data_det_key]); 

                foreach($uniquedetfields as $uniquedetfield){


                    if(array_key_exists(strtolower($uniquedetfield->Field_Name),$detdata)){

                        $valueexists=DB::table($tablename."_det")->where($uniquedetfield->Field_Name,$detdata[strtolower($uniquedetfield->Field_Name)])->exists();

                        if($valueexists==true){
                            array_push($notuniquefields,$uniquedetfield->fld_label." ".($uniqueindex));
                        }


                    } 

                }

                $uniqueindex++;
           }


                if(count($notuniquefields)>0){

                    $notuniquestring="[".implode(" , ",$notuniquefields)."]";
                    return response()->json(['status'=>'failure','message'=>$notuniquestring." field value already present , Please enter different value as per unique constraint"]);
                }

                
                if(count($data_det)==0)
                goto finalresponse;


                $firstdetdata=array_change_key_case($data_det[array_key_first($data_det)]); 

                 $linksetuprulesfollwed=true;

                 $exceedbase_array=array();

               if(!empty($firstdetdata['ref_detail_id'])) 
                {

                    // for($i=0;$i<count( $data_det);$i++){
                        $linkindex=1;
                        foreach( $data_det as $data_det_key=> $data_det_key_value){

                        $detdata=array_change_key_case($data_det[$data_det_key]); 
         
                        $refdetailid= $detdata['ref_detail_id'];  

                        $enteredquantity=$detdata['quantity'];

                        $tbllinkdatadetail=TblLinkData::where('id',  $refdetailid)->select('link_txn','txn_id','qty','used_qty')->first();

                        $balanceqty=((int)$tbllinkdatadetail->qty)-((int)$tbllinkdatadetail->used_qty);
                        

                        $linktxn=trim($tbllinkdatadetail->link_txn);

                        $txnid=trim($tbllinkdatadetail->txn_id);

                        $linksetup=TblLinkSetup::where('link_txn',  $linktxn)->where('base_txn',$txnid)->first();


                        if( trim($linksetup->exced_base)=="False" && $enteredquantity>$balanceqty){

                            array_push(  $exceedbase_array,"Quantity of serial no.".($linkindex)."  exceeds base quantity limit of ".$balanceqty);
 
                        }
                        $linkindex++;
                    }

                    if(count($exceedbase_array)>0){


                        return response()->json(['status'=>'failure','message'=>implode(" , ",$exceedbase_array)]);
              
         

                    }
                 

                }
 
              finalresponse:

              return response()->json(array("status"=>"success"));
 

   }



   public function getTransactionCallDataForSelection($companyname,Request $request){
 
    $link_txn=$request->link_txn;

    $keyfield=$request->keyfield;

    if($keyfield=="Party"){
        $keyfield="cust_id";
    } 

    $keyvalue=$request->keyfieldval;
 
    $txn_id=$request->txn_id;

   $tbllinksetupdetail= TblLinkSetup::where(['base_txn'=>$txn_id,'link_txn'=>   $link_txn])->first(); 
  
//    Log::info('txn_id='.$txn_id);
//    Log::info('key field='.$keyfield);
//    Log::info('key Value='.$keyvalue);
//    Log::info('Link txn='.$link_txn);
//    ->join('Location','tbl_link_data.location','=','Location.Id')
    $datas=DB::table('tbl_link_data')->where('tbl_link_data.txn_id', $txn_id)
    ->where(  'tbl_link_data.'.$keyfield,$keyvalue)
    ->where('link_txn',$link_txn) 
    ->whereColumn('tbl_link_data.used_qty','<','tbl_link_data.qty')
     ->orderby('Id','asc')
    ->select('tbl_link_data.Id' , 
    DB::raw("FORMAT(doc_date,'dd/MM/yyyy') as format_doc_date")
     ,'tbl_link_data.doc_no' 
     , 'tbl_link_data.cust_id'  , 'tbl_link_data.location as location_id' ,
     'tbl_link_data.qty',  'tbl_link_data.rate',  'tbl_link_data.amount' ,'tbl_link_data.used_qty','tbl_link_data.batch_no','tbl_link_data.product','tbl_link_data.line_acc'
     )->get()->toArray();

   
    //  location_name
    
     $datas=json_decode(json_encode( $datas),true);

     $index=0;

     foreach( $datas as $single_data){ 
  

         $datas[$index]['product_id']=$single_data['product'];


         
        if(!empty($single_data['cust_id']) ){

            $datas[$index]['party_name']=Customer::where('Id',$single_data['cust_id'])->value('cust_id');


        }



        

        if(!empty($single_data['location_id'])){
            $datas[$index]['location_name']=Location::where('Id',$single_data['location_id'])->value('location');

        }

        $datas[$index]['product_name']= ProductMaster::where('Id',$single_data['product'])->value('Product');


        if(!empty($single_data['line_acc']) ){
            
              
            $datas[$index]['line_account_name']= Account::where('Id',$single_data['line_acc'])->value('ACName');  
          
        }
        if(!empty($single_data['batch_no']) ){

            $datas[$index]['batch_number']=  $keyvalue ;
             
        }
       
        $index++;
     }
  
     return response()->json(['data'=>$datas,'linksetup'=>$tbllinksetupdetail]); 

   }

   public function checkTransactionCallDataForMultipleMainId(Request $request){
 

       $calldataarray=$request->calldataarray;
 

       $txnmainids=DB::table('tbl_link_data')->whereIn('Id',$calldataarray)->distinct('txn_main_id')->pluck('txn_main_id');


       if(count( $txnmainids)>0){

        return response()->json(['status'=>'failure']);

       }
       else{
           

        return response()->json(['status'=>'success']);


       }

   }

   public function getTransactionCallDataForSelectedDataFromTblLinkData(Request $request){
 

       $calldatarray=$request->calldataarray;  

       $headerdetail=TblLinkData::where('Id', $calldatarray[0])->select('txn_main_id','link_txn','txn_id','doc_date','doc_no' )->first();

        
       $tablename=$headerdetail->txn_id;

       $tableid=$headerdetail->txn_main_id;  
 
       $headerdata=DB::table( $tablename)->where('Id',$tableid)->first(); 


       $docdate= $headerdetail->doc_date;

       $docno= $headerdetail->doc_no;

       $headerfields=FieldsMaster::where('Table_Name', $tablename)->where('Field_Function','<>',5)->where('Tab_Id','<>','Pricing')->where('Tab_Id','<>','None')->orderby('id','asc')->get() ;
 
       $footerfields=FieldsMaster::where('Table_Name', $tablename)->where('Field_Function','<>',5)->where('Tab_Id','=','Pricing')->orderby('id','asc')->get();


        $data=array(); 
        foreach( $headerfields as  $headerfield){
 
            
            $displayvalue= $this->getFieldDisplayValueFromSelectedValue($headerfield,$headerdata->{$headerfield->Field_Name});

            $fieldbackvalue=(empty($headerdata->{$headerfield->Field_Name})?'':$headerdata->{$headerfield->Field_Name});
 
            array_push($data,array( 'field_function'=>$headerfield->Field_Function,'field_name'=>strtolower($headerfield->Field_Name),'field_display'=>$displayvalue,'field_value'=>   $fieldbackvalue ));

        }


        foreach( $footerfields as  $footerfield){
            
            $displayvalue= $this->getFieldDisplayValueFromSelectedValue($footerfield,$headerdata->{$footerfield->Field_Name});

            array_push($data,array( 'field_function'=>$footerfield->Field_Function,'field_name'=>strtolower($footerfield->Field_Name),'field_display'=>$displayvalue,'field_value'=>sprintf('%0.2f',$headerdata->{$footerfield->Field_Name})));

        }

        $tabledetname= $tablename."_det";

 

        $detailtableexists=TableMaster::where('Table_Name',$tabledetname)->exists();
 

        $datadet=array();

        if($detailtableexists==false){
           goto give_response;
        }
 
        $detail_data= TblLinkData::whereIn('Id', $calldatarray)->select('txn_det_id','qty','used_qty' )->get();

        $detail_ids=array();

        $givequantities=array();
        foreach($detail_data as $detail_d){

            array_push($detail_ids,$detail_d->txn_det_id); 

            $remainingqty=((int)$detail_d->qty)-((int)$detail_d->used_qty);

            array_push(  $givequantities,$remainingqty); 

        }
         
        $detail_tabledata=DB::table($tabledetname)->whereIn('id',$detail_ids)->get()->toArray();
        
        // $datadetrefs=array();
 
        $detailfields=FieldsMaster::where('Table_Name',  $tabledetname)->where('Field_Function','<>',24)->where('Tab_Id','<>','None')->orderby('id','asc')->get();
  
        $detailindex=0;
        foreach($detail_tabledata as $detail_data){ 
            
            $detail_data=(array)$detail_data;
            
            $detail_data= array_change_key_case($detail_data,CASE_LOWER); 
 
            // array_push($datadetrefs,$detail_data['id']);

            $detaildata_array=array();

            foreach($detailfields as $detailfield){

               
                $displayvalue= $this->getFieldDisplayValueFromSelectedValue($detailfield,$detail_data[strtolower($detailfield->Field_Name)]);


                if(trim($detailfield->Field_Type)=="integer" ||  trim($detailfield->Field_Type)=="decimal")
                { 
                    // check if its quantity then select only balance quantity using tbl_link_setup 

                    if(strtolower(trim($detailfield->Field_Name))=="quantity"){
                
                        $detailfieldvalue= $givequantities[ $detailindex];
                    }
                    else{ 
                        $detailfieldvalue=$detail_data[strtolower(trim($detailfield->Field_Name))]; 
                    }
                     

                }
                else{
                    
                    $detailfieldvalue=$detail_data[strtolower(trim($detailfield->Field_Name))];

                }
             
                array_push($detaildata_array,array( 'field_function'=>$detailfield->Field_Function,'field_name'=>strtolower($detailfield->Field_Name),'field_display'=>$displayvalue,'field_value'=>$detailfieldvalue));
 
            }
  

            array_push(  $datadet, $detaildata_array);
            $detailindex++;

        }


        give_response:
 
        $response_array=array('data'=>$data,'datadet'=>$datadet,'datadet_refs'=>$calldatarray,'docdate'=>date("d-m-Y",strtotime($docdate)),'docno'=>$docno,'datasubdet'=>array(),'detailwise_receivables'=>array());
 
        return response()->json($response_array);
 
   }



   public function getFieldDisplayValueFromSelectedValue( $sendfield,$selectedfieldval){
 
            $fielddisplayvalue=''; 

            if( $sendfield->Field_Function==4){

                $fromtable=$sendfield->From_Table;
                $scrfield=$sendfield->{'Scr Field'};
                $displayfield=$sendfield->{'Display Field'};  

                $fielddisplayvalue= DB::table($fromtable)->where($scrfield,$selectedfieldval)->value( $displayfield);
 
            }
            else if($sendfield->Field_Function==6){
 

                $fielddisplayvalue="";

            }
            else if($sendfield->Field_Function==16){

                $fielddisplayvalue=Uom::where('id',$selectedfieldval)->value('name');


            }
            else if($sendfield->Field_Function==14){

                $fielddisplayvalue=Currency::where('id',$selectedfieldval)->value('currname');


            }
            else if($sendfield->Field_Function==18){
                $fielddisplayvalue=User::where('id',$selectedfieldval)->value('user_id');
            }
            else if($sendfield->Field_Function==2){
                $fielddisplayvalue=$selectedfieldval;
            }
            else if($sendfield->Field_Function==3   ){
                $fielddisplayvalue=$selectedfieldval;
            }
            else if($sendfield->Field_Function==24   ){
                $fielddisplayvalue=sprintf('%0.2f',$selectedfieldval);
              
            }
            
         return $fielddisplayvalue;


   }

   public function updateTblLinkDataReffnoAndBalanceQty($refid,$newdetailid,$quantity){
 
            
            $tbllinkdetail=  TblLinkData::where('id',$refid)->select('reff_no','used_qty')->first();


            $reffno_string=$tbllinkdetail->reff_no;
            $usedqty=$tbllinkdetail->used_qty;

            $quantity=(int)$quantity;

            $usedqty=  $usedqty+$quantity; 

            if(empty(  $reffno_string)){
                $newrefstring=$newdetailid;
            }
            else{
                $reffno_array=explode(",",$reffno_string);
                array_push( $reffno_array,$newdetailid);
                $newrefstring=implode(",",$reffno_array);
                
            }
            
            TblLinkData::where('id',$refid)->update(['reff_no'=>  $newrefstring,'used_qty'=> $usedqty]);
       
   }



   public function ManageAddRemoveStockOperation($stockoperation,$tableid,$headid,$headerarray,$detailid=null,$detailarray=array()){
 
  
                $isedit=StockDet::where('Fk',$headid)->where( 'Pk',$detailid)->where('Location',$headerarray['location'])->exists();
 
                $stockdetdata=array( 
                    'DocNo'=>$headerarray['docno'],
                'DocDate'=>$headerarray['docdate'],
                'PartyId'=>(array_key_exists("cust_id",$headerarray)?$headerarray["cust_id"]:NULL) , 
                'Location'=>$headerarray['location'],
                'BatchNo'=>(array_key_exists("batch_no",$headerarray)?$headerarray["batch_no"]:NULL) ,
                'Pk'=>$detailid, 'Fk'=>$headid,'Txn_Name'=>$tableid);
 
                $hascontralocation=false;


                if(array_key_exists("contra_location",$headerarray)){
                    
                 $hascontralocation=true;
                }


                if(array_key_exists("product",$headerarray) &&  array_key_exists("rate",$headerarray) &&  array_key_exists("quantity",$headerarray)){
                    $insertdata["Prodid"]=$headerarray['product'];
                    $insertdata["CRate"]=$headerarray['rate'];
                    $quantity=(int)$headerarray['quantity'];

                        if($stockoperation=="Remove"){

                            $stockdetdata['Qty']= $quantity*(-1);  

                        } 
                        else{
                            $stockdetdata['Qty']= $quantity;

                        }


                        if($isedit==true){

                            StockDet::where('Fk',$headid)->where( 'Pk',$detailid)->update($stockdetdata);

                        }
                        else{

                            StockDet::insert($stockdetdata);
                        }
                        

                    if( $hascontralocation==true){

                        $stockdetdata['Location']=$headerarray['contra_location'];

                        $stockdetdata['Qty']= $insertdata['Qty']*(-1);
                         

                         if($isedit==true){

                            StockDet::where('Fk',$headid)->where( 'Pk',$detailid)->update($stockdetdata);

                        }
                        else{

                            StockDet::insert($stockdetdata);
                        }
                        

                    }



                }
                else if( !empty($detailid) && array_key_exists("product",$detailarray)  &&  array_key_exists("rate",$detailarray) &&  array_key_exists("quantity",$detailarray) ){
                    $stockdetdata["Prodid"]=$detailarray['product'];
                    $stockdetdata["CRate"]=$detailarray['rate'];
                    $quantity=(int)$detailarray['quantity']; 

                    $stockdetdata_contra= $stockdetdata;
 
                        if($stockoperation=="Remove"){

                            $stockdetdata['Qty']= $quantity*(-1);  

                        }  
                        else{
                            $stockdetdata['Qty']=$quantity;
                        }       
 
                        if($isedit==true){

                            StockDet::where('Fk',$headid)->where( 'Pk',$detailid)->where('Location',$headerarray['location'])->update($stockdetdata);

                        }
                        else{

                            StockDet::insert($stockdetdata);
                        } 
              
                        if( $hascontralocation==true){

                            $isedit_contra=StockDet::where('Fk',$headid)->where( 'Pk',$detailid)->where('Location',$headerarray['contra_location'])->exists();
 
                           $stockdetdata['Location']=$headerarray['contra_location'];
    
                            $stockdetdata['Qty']= $stockdetdata['Qty']*(-1);
                             

                             if($isedit_contra==true){

                                StockDet::where('Fk',$headid)->where( 'Pk',$detailid)->where('Location',$headerarray['contra_location'])->update($stockdetdata);
    
                            }
                            else{
    
                                StockDet::insert($stockdetdata);
                            }
    
                        }
                
                }
 
      
   }



   public function addFaIntegration($tablename,$vchtypename,$tranaccountid,$lastinsertid,$header_array,$detail_array){

        $docno=$header_array['docno'];

        $foundvchmainid=VchMain::where('VchNo', $docno)->value('Id');

        if(!empty($foundvchmainid)){

            VchDet::where('MainId',$foundvchmainid)->delete();
        } 


         $currencyfieldname=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',14)->value('Field_Name');

         $currencyfieldname=(!empty( $currencyfieldname)?strtolower($currencyfieldname):$currencyfieldname);

         if(array_key_exists( $currencyfieldname,$header_array)){
             $currencyfieldvalue=$header_array[$currencyfieldname];
         }
         else{
            $currencyfieldvalue=NULL;
         }

         $currencyexfieldname=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',15)->value('Field_Name');

         $currencyexfieldname=(!empty( $currencyexfieldname)?strtolower($currencyexfieldname):$currencyexfieldname);

         if(array_key_exists( $currencyexfieldname,$header_array)){
             $currencyexfieldvalue=$header_array[$currencyexfieldname];
         }
         else{
            $currencyexfieldvalue=NULL;
         }
 
       
         $vchtype=VchType::where(trim('name'),trim($vchtypename) )->select('Id','Parent')->first();

         $tranaccountdetail=TranAccount::where('Id',$tranaccountid)->first();

         $mainaccountbyto=$tranaccountdetail->mainaccount_byto;
         $mainaccountformula=$tranaccountdetail->mainaccount_formula;
         $mainaccount_accountfield=trim($tranaccountdetail->Account);

 
        $vchdata=array('VchDate'=>$header_array['docdate'] ,'VchNo'=>$header_array['docno'],
        'MainType'=> (!empty($vchtype)?$vchtype->Parent:NULL)  ,
        'SubType'=>(!empty($vchtype)?$vchtype->Id:NULL) ,
        'Amount'=> (array_key_exists('net_amount',$header_array)?$header_array['net_amount']:NULL) ,
        'Naration'=>(array_key_exists('narration',$header_array)?$header_array['narration']:NULL),
        'Type'=>'T',
        'Txn_Id'=>$tablename,
        'Base_Id'=>$lastinsertid,
        'chq_no'=>(array_key_exists('chq_no',$header_array)?$header_array['chq_no']:NULL),
        'ch_status'=>(array_key_exists('ch_status',$header_array)?$header_array['ch_status']:NULL),
        'cl_date'=>(array_key_exists('cl_date',$header_array)?$header_array['cl_date']:NULL),
        'location'=>(array_key_exists('location',$header_array)?$header_array['location']:NULL),
        'fccur'=>$currencyfieldvalue,
        'fcexrate'=> $currencyexfieldvalue,
        'dept'=>(array_key_exists('dept',$header_array)?$header_array['dept']:NULL),
        'executive'=>(array_key_exists('executive',$header_array)?$header_array['executive']:NULL),
        'ist_op1'=>NULL,
        'ist_op2'=>NULL,
        'ist_op3'=>NULL,
        'ist_op4'=>NULL,
        'tax_code'=>(array_key_exists('tax_code',$header_array)?$header_array['tax_code']:NULL),
        'projid'=>(array_key_exists('projid',$header_array)?$header_array['projid']:NULL),
        'pay_lvl'=>(array_key_exists('pay_lvl',$header_array)?$header_array['pay_lvl']:NULL) 
    );


    

    if(!empty($foundvchmainid)){
        $vchmainid=$foundvchmainid;
        VchMain::where('Id',   $vchmainid)->update($vchdata); 
    }
    else{
        $vchmainid= VchMain::insertGetId(  $vchdata);
    }

 
      $tranaccdets=TranAccDet::where('TempId',$tranaccountid)->get();

      $vchdetarray=array();


        foreach($tranaccdets as $tranaccdet){

            $byto=trim($tranaccdet->{'By/To'});

            $formulafieldname=strtolower(trim($tranaccdet->{'Formula'}));


            if(array_key_exists( $formulafieldname,$header_array)){

                $amount=(float)$header_array[$formulafieldname];

                if( $byto=="By" &&    $amount<0){
                    $amount= $amount*(-1);
                
                }
                else if($byto=="To" &&   $amount>0){
                    $amount= $amount*(-1);
                }

            }
            else{
                $amount=0.00;
            }
            
    // check for line acc if not then simple insert otherwise multiple insert for same


                if(trim($tranaccdet->{'AccName'})=="line_acc"){

 
                    foreach($detail_array as $det_single){
                                
                            $found_costcentre='';
                            $found_division='';

                        if(array_key_exists('costcentre',$det_single)){
                            $found_costcentre=$det_single['costcentre'];
                        }
                        else if(array_key_exists('costcentre',$header_array)){
                            $found_costcentre=$header_array['costcentre'];
                        }

                        if(empty( trim($found_costcentre))){
                            $found_costcentre=NULL; 
                        }
                        else{
                            $found_costcentre= trim($found_costcentre);  
                        }

                        if(array_key_exists('division',$det_single)){
                            $found_division=$det_single['division'];
                        }
                        else if(array_key_exists('division',$header_array)){
                            $found_division=$header_array['division'];
                        }

                        if(empty(trim(   $found_division))){

                            $found_division=NULL;

                        }
                        else{
                            $found_division=trim( $found_division);
                        }
 
                   
  
                        if(array_key_exists('amount',$det_single)){

                            $lineaccamount=(float)$det_single['amount'];
                        }
                        else if(array_key_exists('debitamount',$det_single)){

                            $lineaccamount=(float)$det_single['debitamount'];
                        }
                        else{
                            
                            $lineaccamount=(float)$det_single['creditamount'];
                        }



                        if( $byto=="By" &&    $lineaccamount<0){
                            $lineaccamount= $lineaccamount*(-1);
                        
                        }
                        else if($byto=="To" &&   $lineaccamount>0){
                            $lineaccamount=  $lineaccamount*(-1);
                        }



                        $lineaccidselected=$det_single['line_acc'];

                        array_push($vchdetarray,array('MainId'=>   $vchmainid ,'Amount'=>$lineaccamount,'AcId'=>  $lineaccidselected,
                        'Narration'=>(array_key_exists('narration',$header_array)?$header_array['narration']:NULL),
                        'Costcentre'=>$found_costcentre,
                        'division'=> $found_division,
                        'FCamt'=>(array_key_exists('fcamt',$header_array)?$header_array['fcamt']:NULL),
                        'linearr2'=> (array_key_exists('linearr2',$header_array)?$header_array['linearr2']:NULL)
                        ) );

                        
                        $this->updateFaIntegrationAccountDebitCredit( $lineaccidselected,$lineaccamount);

                    }
                    
                }
                else{

                         $accountid=trim($tranaccdet->{'AccName'}) ;
                                        
                        array_push($vchdetarray,array('MainId'=>   $vchmainid ,'Amount'=>$amount,'AcId'=> $accountid,
                        'Narration'=>(array_key_exists('narration',$header_array)?$header_array['narration']:NULL),
                        'Costcentre'=>(array_key_exists('costcentre',$header_array)?$header_array['costcentre']:NULL),
                        'FCamt'=>(array_key_exists('fcamt',$header_array)?$header_array['fcamt']:NULL),
                        'linearr2'=> (array_key_exists('linearr2',$header_array)?$header_array['linearr2']:NULL),
                        'division'=>(array_key_exists('division',$header_array)?$header_array['division']:NULL),
                        ) ); 
                        $this->updateFaIntegrationAccountDebitCredit( $accountid,$amount);
  

                }
 
 
        }

 
        VchDet::insert($vchdetarray);  

        // insert last row using main account formula

        if(trim( $mainaccount_accountfield)=="Party Id"){
            $mainaccount_accountfieldid=Customer::where('Id',$header_array['cust_id'])->value('acc_id');
        }
        else if(trim($mainaccount_accountfield)=="Emp Id"){

            $mainaccount_accountfieldid= EmployeeMaster::where('ID',$header_array['emp_id'])->value('acc_id');

        }
        else{
            $mainaccount_accountfieldid=  Account::where('acname', 'LIKE',  $tranaccountdetail->Account.'%')->value('id');

        }


        if(array_key_exists($mainaccountformula,$header_array)){

            $mainaccount_amount=(float)$header_array[$mainaccountformula];

            if( (trim($mainaccountbyto)=="By" &&   $mainaccount_amount<0) ||    (trim($mainaccountbyto)=="To" &&   $mainaccount_amount>0) ){
                $mainaccount_amount=  $mainaccount_amount*(-1);
            }
    
            VchDet::insert(array('MainId'=>   $vchmainid ,'Amount'=>   $mainaccount_amount,'AcId'=>$mainaccount_accountfieldid  ,
            'Narration'=>(array_key_exists('narration',$header_array)?$header_array['narration']:NULL),
            'Costcentre'=>(array_key_exists('costcentre',$header_array)?$header_array['costcentre']:NULL),
            'FCamt'=>(array_key_exists('fcamt',$header_array)?$header_array['fcamt']:NULL),
            'linearr2'=> (array_key_exists('linearr2',$header_array)?$header_array['linearr2']:NULL)
            ) );

            $this->updateFaIntegrationAccountDebitCredit($mainaccount_accountfieldid,$mainaccount_amount);
        }


        InvAcc::where('Txn_Id',$lastinsertid)->where('tablename',trim($tranaccountdetail->Transaction))->delete();
 
        InvAcc::insert(['Txn_Id'=>$lastinsertid,'TempName'=>trim($tranaccountdetail->TemplateId),'tablename'=>trim($tranaccountdetail->Transaction)]);

        
   }



   public function editTransactionTableData(Request $request,$companyname,$tablename,$tableid,$print_dataid=NULL){
   
    $role_id=Session::get('role_id');
    $this->edittrandataservice->role=$role_id;
    // if table sequence present then use sequenece otherwise order by id asc
    $this->edittrandataservice->tran_table=$tablename;

    $fields= $this->edittrandataservice->getRoleWiseTransactionTableFields();

    $searchheaderfields=FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','<>','None')->orderby('fld_label','asc')->get();
 
    if(count(   $fields)==0){

        $headerfields= FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','<>','None')->orderby('id','asc');
    }
    else{
        
        $headerfields= FieldsMaster::join('tbl_transaction_fields',function($join){
            $join->on('fields_master.Field_Name','=','tbl_transaction_fields.field_name');
            $join->on('fields_master.Table_Name','=','tbl_transaction_fields.transaction_table');

        })->where('tbl_transaction_fields.role',$role_id)->where('Table_Name', $tablename)->where('Tab_Id','<>','None')->whereIn('fields_master.Field_Name', $fields)
        ->orderby('tbl_transaction_fields.sequence');
    }


    $editheaderfields= $headerfields->select('fields_master.Field_Name','fld_label','Field_Size','Field_Function')->get();

    $editheaderfieldsarray= $headerfields->get()->pluck( 'fld_label','Field_Name');
  

    $searchfields=$request->searchfield;

    if(  $request->isMethod('post')){
        $searchconditions=$request->searchcondition;
        $searchval=$request->searchval;
        $searchoperator=$request->searchoperator; 

        
        $searchfieldfunctions=FieldsMaster::where('Table_Name', $tablename)->whereIn('Field_Name',$searchfields)->pluck('Field_Function','Field_Name')->toArray();
 
        $searchfieldfunctions=(array)$searchfieldfunctions;
 
        $searchfieldfunctions=array_change_key_case( $searchfieldfunctions,CASE_LOWER);
 
        $searchfunctions=array();

        foreach($searchfields as $searchfield){

            array_push($searchfunctions,   $searchfieldfunctions[strtolower($searchfield)] );

        }

        Session::forget('edit_tran_data_search_fields');
        Session::forget('edit_tran_data_search_tablename');
  
        $this->edittrandataservice->searchfields=$searchfields;
        $this->edittrandataservice->searchvalues=$searchval;
        $this->edittrandataservice->searchfunctions=$searchfunctions;
         $fielddisplayarray=$this->edittrandataservice->getSearchFieldDisplayValues(); 

         $searchfieldarray=array("searchfield"=>$searchfields,"searchcondition"=> $searchconditions,"searchval"=>$searchval,"searchoperator"=> $searchoperator,'searchfunction'=> $searchfunctions,'displayvalues'=> $fielddisplayarray);
  
        $searchfieldarraystring=json_encode($searchfieldarray);

        $transactiondata=$this->edittrandataservice->searchTransactionDataTable( $searchfields,$searchconditions, $searchval,  $searchoperator);
         
        Session::put('edit_tran_data_search_fields', $searchfieldarraystring);
   
        Session::put('edit_tran_data_search_tablename', $tablename);


   
    }
    else if( !empty(Session::get('edit_tran_data_search_fields'))  &&     Session::get('edit_tran_data_search_tablename')== $tablename ){
        
        $edittrandatasearchfieldstring=Session::get('edit_tran_data_search_fields');

        $edittrandataarray=json_decode(  $edittrandatasearchfieldstring,true);
        $transactiondata=$this->edittrandataservice->searchTransactionDataTable(  $edittrandataarray['searchfield'], $edittrandataarray['searchcondition'],  $edittrandataarray['searchval'], $edittrandataarray['searchoperator']);
    
    }
    else{ 
        
         $transactiondata=$this->edittrandataservice->searchTransactionDataTable();
    }

 
    
    $this->edittrandataservice->tran_id=$tableid;
    $this->edittrandataservice->tran_table=$tablename;
    $this->edittrandataservice->role=Session::get('role_id'); 
    $this->edittrandataservice->formmode="edit";
    $showhidebuttons= $this->edittrandataservice->getButtonShowHideFromWorkflowHead();
 
 
    $alltableswithoutdet=TableMaster::where('Table_Name','not like','%_det%')->orderby('table_label','asc')->pluck( 'table_label','Table_Name');

    $crystaltemplates=TblPrintHeader::where('Txn_Name',$tablename)->pluck( 'TempName','crystal');

    $whatsapp_templates=TableSmsHeader::where('txn_name',$tablename)->pluck('msg_txt','tempname')->toArray();

    $reportserver_url=TblPrintHeader::where('Txn_Name',$tablename)->value('link');
    return view("configuration.edit_transactions_data",compact('companyname','tablename','transactiondata', 'tableid','editheaderfields','tableid','editheaderfieldsarray','showhidebuttons','searchheaderfields','alltableswithoutdet','crystaltemplates','reportserver_url','print_dataid','whatsapp_templates'));

   }


   public function editTransactionTableSingleData($companyname,$tranname,$tranid,$dataid){


    if($tranname=='GSI' || $tranname=='GSR' || $tranname=="GSRA"){

        $this->gstapiservice->tran_table=$tranname;

        $this->gstapiservice->data_id=$dataid;


        $irn_generated= $this->gstapiservice->CheckIrnAlreadyGenerated();
 
        if( $irn_generated==true){

          return  redirect()->back()->with('error_message','Cannot edit record , IRN already generated for this ');

        } 

    }

    $tablefound=TableMaster::findorfail($tranid);

    $tablename=  $tablefound->Table_Name;


    $tabledetname=$tablename."_det";

    $detailtableexists=TableMaster::where('Table_Name',$tabledetname)->exists();


    $headerfields=FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','<>','Pricing')->where('Tab_Id','<>','None')
    ->where('Field_Name','<>','status')->where('Field_Name','<>','reject_reason')->orderby('id','asc')->get();


    if( $detailtableexists){
        $detailfields=FieldsMaster::where('Table_Name',  $tabledetname)->where('Tab_Id','<>','None')->orderby('id','asc')->get();
    }
    else{
        $detailfields=array();
    }


    $footerfields=FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','=','Pricing')->where('Field_Name','<>','status')->where('Field_Name','<>','reject_reason')->orderby('id','asc')->get();

    
    $roleid=Session::get('role_id');


     $fieldlevels= FieldLevel::where(['uid'=>$roleid])->where( function($query)use($tablename){
        $query->where('txn_id',$tablename)->orwhere('txn_id',$tablename.'_det'); 

     })->select('txn_id','fld_id','hide','rdol')->get();



     $showhidefields=array();
     
     $showhide_detfields=array();
     foreach( $fieldlevels as  $fieldlevel){

        if(strpos($fieldlevel['txn_id'],'_det')==false){
            $showhidefields[$fieldlevel['fld_id']]=array('hide'=>trim($fieldlevel['hide']),'rdol'=>trim($fieldlevel['rdol']));

        }
        else{
            $showhide_detfields[$fieldlevel['fld_id']]=array('hide'=>trim($fieldlevel['hide']),'rdol'=>trim($fieldlevel['rdol']));
         }

     }
    $linksetupbase_txns= TblLinkSetup::where('link_txn',$tablename)->select('key_fld','base_txn')->get();

 

   $tran_accounts= TranAccount::where('Transaction','LIKE',$tablename.'%')->select('Id','TemplateId','is_default')->get();

   $mode="edit";
 
   $this->edittrandataservice->tran_id=$tranid;
   $this->edittrandataservice->tran_table=$tranname;
   $this->edittrandataservice->role=Session::get('role_id'); 
   $this->edittrandataservice->formmode="edit";
   $this->edittrandataservice->data_id=$dataid; 
   
   $showhidebuttons= $this->edittrandataservice->getButtonShowHideFromWorkflowHead();
 
   $allowpayablereceivable=  $this->edittrandataservice->checkAllowReceivableOrPayable();
   
   $tblpdacc_result=$this->edittrandataservice->getTblPdAccDetails();
 
   $checkstockavailability = $this->edittrandataservice->checkStockAvailabilityValidation();
  
   $tranaccount_tempid=  $this->edittrandataservice->getTempIdFromInvAcc();
   
   $editdetailonreference= $this->edittrandataservice->getEditBaseFromTblLinkSetup(); 
 
   $alltableswithoutdet=TableMaster::where('Table_Name','not like','%_det%')->orderby('table_label','asc')->pluck( 'table_label','Table_Name');

 

   if($detailtableexists==true){

    $this->edittrandataservice->tran_table=$tabledetname;

     $show_randp= $this->edittrandataservice->getShowRandP();

        }
        else{ 
            $show_randp=false;
        }

 

    return view('configuration.addTransactionInsertRoleFields',compact('companyname','tablefound','headerfields','detailfields','footerfields','showhidefields','showhide_detfields','linksetupbase_txns','tran_accounts','mode','dataid','showhidebuttons','allowpayablereceivable','checkstockavailability','tblpdacc_result','tranaccount_tempid','editdetailonreference','alltableswithoutdet','show_randp' ));


   }


   public function getTransactionTableDataById(Request $request){
            
                $tablename=$request->tablename;
                $dataid=$request->data_id;

                
                $headerdata=DB::table( $tablename)->where('Id', $dataid)->first(); 
 
                $headerdata=(array)$headerdata;

                $headerdata= array_change_key_case($headerdata,CASE_LOWER);

          

                $headerfields=FieldsMaster::where('Table_Name', $tablename)->where('Field_Function','<>',5)->where('Tab_Id','<>','Pricing')->orderby('id','asc')->get() ;
            
                $footerfields=FieldsMaster::where('Table_Name', $tablename)->where('Field_Function','<>',5)->where('Tab_Id','=','Pricing')->orderby('id','asc')->get();

                $data=array(); 

                foreach( $headerfields as  $headerfield){
            
                        
                    $displayvalue= $this->getFieldDisplayValueFromSelectedValue($headerfield,$headerdata[strtolower($headerfield->Field_Name)]);

                    if($headerfield->Field_Function==6){
                        $fieldbackvalue=(empty($headerdata[strtolower($headerfield->Field_Name)])?'':date("d-m-Y",strtotime($headerdata[strtolower($headerfield->Field_Name)])));

                    }
                    else      if($headerfield->Field_Function==8){

                        $fieldbackvalue=(empty($headerdata[strtolower($headerfield->Field_Name)])?'':$headerdata[strtolower($headerfield->Field_Name)] );

                        $fieldbackvalue=  asset('storage/transactiondocs/'.   $fieldbackvalue);

                    }
                    else{
                        $fieldbackvalue=(empty($headerdata[strtolower($headerfield->Field_Name)])?'':$headerdata[strtolower($headerfield->Field_Name)]);

                    }
                
                    array_push($data,array( 'field_function'=>$headerfield->Field_Function,'field_name'=>strtolower($headerfield->Field_Name),'field_display'=>$displayvalue,'field_value'=>   $fieldbackvalue ));

                }

                if(array_key_exists("docno",$headerdata)){
                    $docnofound=$headerdata['docno'];
                }
                else{
                    $docnofound="";  
                }
 
                
                foreach( $footerfields as  $footerfield){
                        
                    $displayvalue= $this->getFieldDisplayValueFromSelectedValue($footerfield,$headerdata[strtolower($footerfield->Field_Name)]);



                    array_push($data,array( 'field_function'=>$footerfield->Field_Function,'field_name'=>strtolower($footerfield->Field_Name),'field_display'=>$displayvalue,'field_value'=>sprintf('%0.2f',$headerdata[strtolower($footerfield->Field_Name)])));

                }

                $datasubdet=array();

                $tabledetname= $tablename."_det";
            
                $detailtableexists=TableMaster::where('Table_Name',$tabledetname)->exists();
            
                $datadet=array();

                        
                if($detailtableexists==false){
                    
                    $detailwise_receivables=array();
                    goto give_response;
                }

                
                $detail_tabledata= DB::table($tablename."_det")->where('fk_Id',    $dataid)->orderby('Id','asc')->get()->toArray();

                $this->edittrandataservice->tran_table= $tabledetname;
                  $show_randp= $this->edittrandataservice->getShowRandP();

                  if(  $show_randp==true){
                    $this->edittrandataservice->docno=  $headerdata['docno'];

                     $line_accs=array_column($detail_tabledata,'line_acc');

                    $detailwise_receivables= $this->edittrandataservice->getDetailRowsReceivables($line_accs);

                  }
                  else{
                    $detailwise_receivables=array();
                  }

 
 
                $detailfields=FieldsMaster::where('Table_Name',  $tabledetname)->orderby('id','asc')->get();

               

                   $detailindex=0;


                    foreach($detail_tabledata as $detail_data){ 
                        $detail_data=(array)$detail_data;
            
                        $detail_data= array_change_key_case($detail_data,CASE_LOWER); 
             
                        // array_push($datadetrefs,$detail_data['id']);
            
                        $detaildata_array=array();
            
                        foreach($detailfields as $detailfield){
            
                           
                            $displayvalue= $this->getFieldDisplayValueFromSelectedValue($detailfield,$detail_data[strtolower($detailfield->Field_Name)]);
               
                            if(trim($detailfield->Field_Type)=="integer" ||  trim($detailfield->Field_Type)=="decimal")
                            { 
                                // check if its quantity then select only balance quantity using tbl_link_setup 
             
                                $detailfieldvalue=$detail_data[strtolower(trim($detailfield->Field_Name))]; 
                               
                            }
                            else{
                                
                                $detailfieldvalue=$detail_data[strtolower(trim($detailfield->Field_Name))];
            
                            }
                         
                            array_push($detaildata_array,array( 'field_function'=>$detailfield->Field_Function,'field_name'=>strtolower($detailfield->Field_Name),'field_display'=>$displayvalue,'field_value'=>$detailfieldvalue));
             
                        }
              
            
                        array_push(  $datadet, $detaildata_array);
 
                        $detailindex++;
                    }


                    $subdetailtabledetail=TableMaster::where('Parent Table', $tabledetname)->first();
                 

                    if(  empty(   $subdetailtabledetail)){
                        goto give_response;
                    }


                    $subdetailtablefields=FieldsMaster::where('Tab_Id','<>','None')->where('Table_Name',  $subdetailtabledetail->Table_Name)->get();
 
                    $detailids=array_column($detail_tabledata,'Id');
                    $datasubdet=array();

                    $detailindex=1;

                    foreach(  $detailids as   $detailid){
                        
                         $subdetailtabledatas=DB::table($subdetailtabledetail->Table_Name)->where('fk_id',$dataid)->where('fk_id_id',$detailid)->get()->toArray();


                         if(count($subdetailtabledatas)==0){
 
                            continue;
                         }

                         
                         $datasubdet['subdetailrow_'.$detailindex]=array();
                         foreach($subdetailtabledatas as  $subdetailtabledata){

                            
                             $subdetail_data_array=array();

                            $subdetailtabledata=(array)$subdetailtabledata;
            
                            $subdetailtabledata= array_change_key_case($subdetailtabledata,CASE_LOWER); 
 
                            foreach(    $subdetailtablefields  as    $subdetailtablefield){

                                $foundfieldvalue=$subdetailtabledata[strtolower($subdetailtablefield->Field_Name)];

                                $founddisplayvalue= $this->getFieldDisplayValueFromSelectedValue($subdetailtablefield,$subdetailtabledata[strtolower($subdetailtablefield->Field_Name)]);

                                // $datasubdet['subdetailrow_'.$detailindex][strtolower($subdetailtablefield->Field_Name)]=array('fielddisplay'=>$founddisplayvalue,'fieldvalue'=>$foundfieldvalue);

                                $subdetail_data_array[strtolower($subdetailtablefield->Field_Name)]=array('fielddisplay'=>$founddisplayvalue,'fieldvalue'=>$foundfieldvalue);

                                // array_push($subdetail_data_array ,array(strtolower($subdetailtablefield->Field_Name)=>array('fielddisplay'=>$founddisplayvalue,'fieldvalue'=>$foundfieldvalue)));

                            }
                            

                            array_push($datasubdet['subdetailrow_'.$detailindex],$subdetail_data_array);
 
                         } 
                     
                        $detailindex++;
                    }
 
                   give_response:
 
 
                   $response_array=array('data'=>$data,'datadet'=>$datadet,'datadet_refs'=>array(),'docno'=>$docnofound,'datasubdet'=> $datasubdet ,'detailwise_receivables'=>  $detailwise_receivables ); 
 

                   return response()->json($response_array);
 
   }


   public function getEditTranDataSearchFields(){

    
       $edittrandatasearchfieldstring=Session::get('edit_tran_data_search_fields');

       $jsonarray=array();

       if(!empty($edittrandatasearchfieldstring)){

           $edittrandatasearchfieldarray=json_decode($edittrandatasearchfieldstring,true);
 
 
           $index=0;
           foreach( $edittrandatasearchfieldarray['searchfield'] as $searchfield){

               array_push($jsonarray,array('searchfield'=>$searchfield,
               "searchcondition"=>$edittrandatasearchfieldarray['searchcondition'][$index],
               "searchval"=>$edittrandatasearchfieldarray['searchval'][$index],
               "searchoperator"=>$edittrandatasearchfieldarray['searchoperator'],
               "searchfunction"=>$edittrandatasearchfieldarray['searchfunction'][$index],
               "displayvalue"=>$edittrandatasearchfieldarray['displayvalues'][$index], 
            ));

            $index++;

           }
 
       }

       return response()->json(  $jsonarray);

   }


   public function resetEditTranDataSearch(){
       Session::forget('edit_tran_data_search_fields');
       Session::save();
       return redirect()->back();

   }


   public function getFunction17BatchNumbers(Request $request){
    
    $tablename=$request->data['table_name'];

    $search=$request->searchTerm;

    $batchnumbers=TblLinkData::where('link_txn',  $tablename)->where('batch_no','LIKE','%'. $search.'%')->get();


    $foundbatchnumbers=array();
    foreach(    $batchnumbers as     $batchnumber){

      $qty=(float)$batchnumber->qty;

      $used_qty=(float)$batchnumber->used_qty;

      if($qty>$used_qty  && !in_array($batchnumber->batch_no,$foundbatchnumbers))
        {
            array_push(  $foundbatchnumbers,$batchnumber->batch_no);
        }

    }


    $responsearray=array();


    foreach( $foundbatchnumbers as  $foundbatchnumber){
        array_push(   $responsearray,array('id'=>$foundbatchnumber,'text'=>$foundbatchnumber));

    } 

    return  response()->json( $responsearray);

   }
 

   public function getPrevNextTransactionTableRecord(Request $request){

    $currentid=$request->currentid;


    $trantable=$request->tran_table;

    if(empty(  $currentid)){

        $currentid=DB::table($trantable)->max('Id');
    } 
    $action=$request->action;
 

    $this->function4filterservice->user=Auth::user();

    $this->function4filterservice->tablename= $trantable;
   
    $filterfields= $this->function4filterservice->getAllFilteredFieldsWithValues();
  
    $prevnextid=DB::table($trantable)
      ->where(function($query)use($filterfields){

        foreach($filterfields as $filterfield){
            $query->whereIn($filterfield['field_name'],$filterfield['field_filter_values']);
        }

    })->where(function($query1)use($action,$currentid){
        if($action=="Previous"){
            $query1->where('Id','<',$currentid);
        }
        else{
            $query1->where('Id','>',$currentid);
        }
    })
    ->limit(1)->orderby('Id','desc')->value('Id');
 
 
    if(empty( $prevnextid)){

        $response=array('status'=>'failure','message'=>'No Data Found');
    }
    else{
      
        $response=array('status'=>'success','message'=>'','prevnextid'=>$prevnextid);

    } 

    return response()->json( $response);
 

   }


   public function getEditTranDataHistoryUsingDocno($companyname,$docno){
 
      $tblauditdatas= TblAuditData::where('docno',$docno)->get()->toArray();

      $index=0;
      foreach(   $tblauditdatas as    $tblauditdata){

        $username= User::where('id',$tblauditdata['user_id'])->value('user_id');
        $tblauditdatas[$index]['user_name']= (!empty($username)?$username:'');

        $custname=Customer::where('Id',$tblauditdata['cust_id'])->value('cust_id');
        $tblauditdatas[$index]['cust_name']=    (!empty( $custname)? $custname:'');
  
        $location=Location::where('Id',$tblauditdata['location'])->value('location');
        $tblauditdatas[$index]['location_name']=   (!empty($location)?$location:"");
 
        $productname=ProductMaster::where('Id',$tblauditdata['product'])->value('Product');
        $tblauditdatas[$index]['product_name']= (!empty( $productname)? $productname:'');
        // product Product_master

        $tblauditdatas[$index]['docdate']=date('d/m/Y',strtotime(   $tblauditdatas[$index]['docdate']));

        $tblauditdatas[$index]['servertime']=date('d/m/Y H:i A',strtotime( $tblauditdatas[$index]['servertime']));
 
        $index++;

      }
 
       return response()->json(['data'=>$tblauditdatas]);

   }


   public function getTransactionSubDetailRows(Request $request){

    $tablename=$request->table_name;
    $subdetailscount=$request->no_of_rows;

    $subdetailtablename=TableMaster::where('Parent Table',   $tablename)->value('Table_Name');
    $detailfields=FieldsMaster::where('Table_Name',$subdetailtablename)->where('Tab_Id','<>','None')->orderby('id','asc')->get();

    $allfieldlabels=$detailfields->pluck('fld_label');

    $allfieldnames=$detailfields->pluck('Field_Name');
  
 
    $roleid=Session::get('role_id');
 
    $fieldlevels= FieldLevel::where(['uid'=>$roleid])->where('txn_id',$tablename)->select('txn_id','fld_id','hide','rdol')->get();

    $showhide_detfields=array();
    foreach( $fieldlevels as  $fieldlevel){

        $showhide_detfields[$fieldlevel['fld_id']]=array('hide'=>trim($fieldlevel['hide']),'rdol'=>trim($fieldlevel['rdol']));

    }

 
    $html=view("configuration.transactioninsertrolesubdetailfieldstr",compact('detailfields','tablename','subdetailscount','showhide_detfields' ))->render();
  
    return response()->json([ 'subdetailtablename'=> $subdetailtablename,'fieldnames'=>$allfieldnames,'fieldlabels'=>$allfieldlabels ,'html'=>$html]);
 

   }


   public function addDeleteDetailSubDetails($dettablename,$fkid,$fkidid,$data_array){
            
                $subdetailtablename=TableMaster::where('Parent Table',   $dettablename)->value('Table_Name');

                if(empty($subdetailtablename)){
                    return;
                } 


                // delete old data and insert new one

                DB::table($subdetailtablename)->where(['fk_id'=>$fkid,'fk_id_id'=>$fkidid])->delete();
                
               $subdetailfields=FieldsMaster::where('Table_Name',$subdetailtablename)->where('Tab_Id','<>','None')->orderby('id','asc')->select('Field_Name')->pluck('Field_Name');
 
                $index=1; 

                $subdetailsdata=array();
                foreach($data_array as $data){

                    $newsubdata=array();

                    $newsubdata['fk_id']=$fkid;
                    $newsubdata['fk_id_id']=$fkidid;

                    foreach( $subdetailfields as  $subdetailfield){
                        $newsubdata[$subdetailfield]=(array_key_exists($subdetailfield,$data)?$data[$subdetailfield]['fieldvalue']:'');
                         
                    }

                    array_push(  $subdetailsdata,$newsubdata );
                    

                    $index++;
                }

             
            
                DB::table($subdetailtablename)->insert($subdetailsdata);
            

   }


   public function updateFaIntegrationAccountDebitCredit($accountid,$enteredamount){


            $enteredamount=(float)$enteredamount;

            $accountdetail= Account::where('Id',$accountid)->select('OpBal','Debits','Credits','Bal')->first();

            $opbalance=(float)$accountdetail->OpBal;

            $currentcredit=(float)  $accountdetail->Credits;
            $currentdebit=(float)$accountdetail->Debits;
            $currentbal=(float)$accountdetail->Bal;

           

            if(  $enteredamount<0){ 

                $enteredamount=   $enteredamount*(-1); 

                $newcredit= round(($enteredamount+ $currentcredit),2);

                $newbalance=round(($opbalance+$currentdebit-$newcredit),2); 

                Account::where('Id',$accountid)->update(['Credits'=>$newcredit,'Bal'=>$newbalance ]);
                
            }
            else{

                
                $newdebit= round(($enteredamount+ $currentdebit),2);

                $newbalance=round(($opbalance+$newdebit-$currentcredit),2); 
                
                Account::where('Id',$accountid)->update(['Debits'=>$newdebit,'Bal'=>$newbalance ]);
 
            }
 
   }



   public function getTransactionCustomerAccountReceivableDetails($companyname,Request $request){

    $this->edittrandataservice->cust_id=$request->custid;
    
    $this->edittrandataservice->docno=$request->docno;

   
    $response= $this->edittrandataservice->getCustomerAccountDetailWithReceivables();

     return response()->json($response);

}



   public function addUpdateReceivablesPayables($tablename,$headerdata,$onaccount,$adjustments){

  
    $onaccount=(float)$onaccount; 
    $custid=$headerdata['cust_id']; 
    $accid=Customer::where('Id',$custid)->value('Acc_id');
    
    //   find it with doc no if present then update all except amount,docno else insert

    $found_id_receivable=Receivables::whereNULL('reff_no')->where('DocNo',$headerdata['docno'] )->value('id');

    $receivables=Receivables::where('Accid', $accid)->whereRaw('ISNULL(org_amt,0) > ISNULL(amount,0)')->orderby('id','desc')->select('id','reff_no','lastreceipt')->get()->toArray();

    $docdate=date("Y-m-d",strtotime($headerdata['docdate'])) ;
    $this->edittrandataservice->docdate=$docdate;
    $this->edittrandataservice->tran_table=$tablename;
    $this->edittrandataservice->head_data=$headerdata;
 
    $duedate=$this->edittrandataservice->calculateDueDateFromDocDate();

      $r_or_p= TableMaster::where('Table_Name',$tablename)->value('Receivable');

      $this->edittrandataservice->field_function=14;

      $function14fieldvalue= $this->edittrandataservice->getFunctionFieldNameValueFromData();
      
      $this->edittrandataservice->field_function=15;

      $function15fieldvalue= $this->edittrandataservice->getFunctionFieldNameValueFromData();

     
       $data=array(
           'CustomerId'=>$headerdata['cust_id'] ,
           'Accid'=>   $accid, 
           'DocNo'=>$headerdata['docno']  , 
           'DocDate'=> $docdate, 
           'Amount'=>0,
           'TxnId'=>$tablename,
           'PendingFlag'=>'True' ,
           'Area'=>NULL,
          'Productid'=> (array_key_exists('product',$headerdata)?$headerdata['product']:NULL),
          'lastreceipt'=> NULL,
          'reff_no'=>NULL,
          'onaccount'=>  $onaccount,
          'duedate'=> $duedate,
          'r_p'=>$r_or_p,
          'location'=>(array_key_exists('location',$headerdata)?$headerdata['location']:NULL),
          'dept'=>(array_key_exists('dept',$headerdata)?$headerdata['dept'] :NULL),
          'salesman'=>(array_key_exists('salesman',$headerdata)?$headerdata['salesman']:NULL),
          'cur_name'=>  $function14fieldvalue,
          'org_amt'=>(array_key_exists('net_amount',$headerdata)?$headerdata['net_amount']:NULL),
          'Exc_rate'=>  $function15fieldvalue,
          'linearr2'=>(array_key_exists('linearr2',$headerdata)?$headerdata['linearr2']:NULL)
       ); 


       if(  !empty($found_id_receivable)){
           unset(  $data['DocNo']);
           unset(  $data['Amount']);
           unset($data['PendingFlag']);
           Receivables::where('id',$found_id_receivable)->update($data);
            $lastinsertid=$found_id_receivable;
      
       }
       else{

          $lastinsertid= Receivables::insertGetId( $data);
       }


    //    delete all receivable before adding entry
    $lastinsertid=(string)$lastinsertid;

    Receivables::where('reff_no', $lastinsertid)->delete();
 

    foreach($adjustments as $adjustment){ 


        $adjustment_data=array(
            'CustomerId'=>$headerdata['cust_id'] ,
            'Accid'=>   $accid, 
            'DocNo'=>$adjustment['docno']  , 
            'DocDate'=> Receivables::getDocDateFromDocno($adjustment['docno']), 
            'Amount'=>$adjustment['amtentry'],
            'TxnId'=>$tablename,
            'PendingFlag'=>'True' ,
            'Area'=>NULL,
           'Productid'=> (array_key_exists('product',$headerdata)?$headerdata['product']:NULL),
           'lastreceipt'=> NULL,
           'reff_no'=>$lastinsertid,
           'onaccount'=> 0,
           'duedate'=> $duedate,
           'r_p'=>$r_or_p,
           'location'=>(array_key_exists('location',$headerdata)?$headerdata['location']:NULL),
           'dept'=>(array_key_exists('dept',$headerdata)?$headerdata['dept'] :NULL),
           'salesman'=>(array_key_exists('salesman',$headerdata)?$headerdata['salesman']:NULL),
           'cur_name'=>  $function14fieldvalue,
           'org_amt'=>0,
           'Exc_rate'=>  $function15fieldvalue,
           'linearr2'=>(array_key_exists('linearr2',$headerdata)?$headerdata['linearr2']:NULL) ,
        
        ); 

     
 

          $receivableexists=   Receivables::where('reff_no',$lastinsertid)->where('DocNo',$adjustment['docno'])->exists();

        if(  $receivableexists==false){

            Receivables::insert($adjustment_data);

            
        }
        else{

            unset(  $adjustment_data['PendingFlag']);

            Receivables::where('reff_no',$lastinsertid)->where('DocNo',$adjustment['docno'])->update($adjustment_data);
        }

 
    }
 

  
   }


   public function getTransactionReceivablePayableAmountAdjustments(Request $request){

        $custid=$request->cust_id;

        $netamount=(float)$request->net_amount;

        $onaccount=(float)$request->on_account;

        $action=$request->action;

        $accid=Customer::where('Id',  $custid)->value('Acc_id');


        $this->edittrandataservice->accid= $accid;

        
        $amountadjustments= $this->edittrandataservice->getReceivableInLifoFifo($netamount,$onaccount,$action);


        return response()->json(['adjustments'=>$amountadjustments]);
   }

    
   public function checkTransactionCreditLimitExceeded(Request $request){
 

    $custid=$request->cust_id;

    $netamount=$request->net_amount;

    $this->edittrandataservice->cust_id=  $custid;

    $result=$this->edittrandataservice->checkNetAmountBalanceExceeded($netamount);
 
 
    $response=array('balance_exceeded'=>  $result['amountexceeded'],'net_amount'=>sprintf('%0.2f',$netamount),'ledger_balance'=>sprintf('%0.2f',$result['ledgerbalance']),'credit_limit'=>sprintf('%0.2f',$result['creditlimit']));
  

    return response()->json( $response);

   }
 
   public function checkTransactionCreditDaysLimitExceeded(Request $request){

    $custid=$request->cust_id;
    $netamount=$request->net_amount;
    $currentdb=Session::get('company_name'); 
    $trantable=$request->tran_table;
    $docdate=$request->doc_date; 
    $this->edittrandataservice->cust_id=  $custid;
    $this->edittrandataservice->db_name=$currentdb;
    $this->edittrandataservice->tran_table=$trantable;
    $result=$this->edittrandataservice->checkNetAmountDaysExceeded($netamount,   $docdate);

    $response=array('days_exceeded'=>$result['daysexceeded'],'average_receivable_days'=> $result['averagereceivabledays'],'allowed_days'=>$result['alloweddays']);

    return response()->json($response);
   }

   public function checkTransactionProductsStockAvailability(Request $request){

            $products=json_decode($request->products,true);
             $location=$request->location;
             $trantable=$request->tran_table;
             $this->edittrandataservice->location=   $location;
             $this->edittrandataservice->ask_products=  $products;
             $this->edittrandataservice->tran_table=   $trantable;

             $product_status=  $this->edittrandataservice->checkProductStockAvailabilityFromStockDet();

             return response()->json(['unavailableproducts'=>$product_status]);
   }


   public function deleteTransactionTableDataByIds(Request $request){
 
      $trantable=$request->trantable;
      $deleteids=$request->deleteids;
 
      $user=Auth::user();

      $this->edittrandataservice->user_id=$user->id;
      $this->edittrandataservice->tran_table=$trantable;
      $this->edittrandataservice->role=Session::get('role_id');
 
      $errormessages=array();
      $candelete=true;

      foreach($deleteids as $deleteid){
        $this->edittrandataservice->data_id=$deleteid;
        $result= $this->edittrandataservice->CheckTransactionTableDataDeleteById();
      
        if($result['status']==false){
            array_push($errormessages,"<p>".$result['message']."</p>");
            $candelete=false;
        }
  
      }
 
      if($candelete==false){

        return response()->json(['status'=>false,'message'=>implode(" ",$errormessages)]);
      }
 
      $give_message="Selected Transaction Table Data deleted successfully";

        DB::beginTransaction();
        try{

            if($trantable=="GSI" || $trantable=="GSR"  || $trantable=="GSRA"){
                $this->gstapiservice->tran_table=$trantable;
                $this->gstapiservice->delete_ids=   $deleteids;
                $this->gstapiservice->setGstInvoiceAuthToken();
                $cancel_array=$this->gstapiservice->cancelGstIrnGenerated();
                $deleteids= $cancel_array['deleted_irn_ids'];
                $not_deleted_doc_nos=$cancel_array['not_deleted_doc_nos'];
 
            }
            else{
                $not_deleted_doc_nos=array();
            }

 
            foreach($deleteids as $deleteid){
                $this->edittrandataservice->data_id=$deleteid;
                $this->edittrandataservice->deleteTransactionTableDataById();
            }

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            LogMessage($e);

        }
 

      return response()->json(['status'=>true,'message'=>   $give_message,'deleteids'=>$deleteids ,'not_deleted_doc_nos'=>$not_deleted_doc_nos]);
 
   }



   public function ManageDeletedDetailTableData($headid,$detailids,$tablename){

       DB::table($tablename)->where('fk_id',$headid)->whereNotIn('Id',$detailids)->delete();

   }


   public function ManageDeleteStockOperation($docno,$alldetailids){

      $presentids=StockDet::where('DocNo',$docno)->pluck('Pk')->toArray();


      $index=0;
      foreach( $presentids as  $presentid){
        $presentids[$index]=trim($presentid);
        $index++;
      }
 

      if(count($presentids)==0)
       return;

       $notfounddetailids= array_diff( $presentids,$alldetailids);
 

       if(count( $notfounddetailids)==0)
       return;
 
       StockDet::where('DocNo',$docno)->whereIn('Pk',$notfounddetailids)->delete();
    
   }




   public function AddDeleteTblAuditData($tablename,$lastinsertid,$headdata,$detaildata,$operation){

                $allauditdata=array();

                $txn_class_used=TableMaster::where('Table_Name',$tablename)->value('txn_class');

                $txn_class_used=trim( $txn_class_used);
 

                $operation=($operation=="edit"?"EDIT":"ADD");

                $user=Auth::user();

                if($txn_class_used=="Masters" && array_key_exists('docno',$headdata)==false){
                    $docno_found=NULL;
                }
                else{

                    $docno_found=$headdata['docno'];
                } 
               

                if($txn_class_used=="Masters" && array_key_exists('docdate',$headdata)==false){

                    $docdate_found= NULL;


                }
                else{
                    $docdate_found= $headdata['docdate'];


                }    

         
                // $istablepurchaseorsales=TableMaster::where('Table_Name',$tablename)->where(function($query){

                //     $query->where('txn_class','LIKE','%Purchase%')->orwhere('txn_class','LIKE','%Sales%');

                // })->exists();


                // if($istablepurchaseorsales==false)
                // return;

                foreach( $detaildata as $detsingledata){ 


                    if(array_key_exists('product',$detsingledata)){

                        $productid=$detsingledata['product'];
                    }
                    else if(array_key_exists('line_acc',$detsingledata)){
                        $productid=$detsingledata['line_acc'];
                    }
                    else{
                        $productid=NULL;
                    }

                    $auditdata=array("user_id"=>$user->id,"table_name"=>$tablename ,"docno"=> $docno_found,"docdate"=> $docdate_found,
                    "cust_id"=>(array_key_exists("cust_id",$headdata)?$headdata['cust_id']:''),
                    "salesman"=>(array_key_exists('salesman',$headdata)?$headdata['salesman']:NULL) ,
                    "location"=> (array_key_exists('location',$headdata)?$headdata['location']:NULL) ,
                    "grossamt"=>  (array_key_exists('gross_amount',$headdata)?$headdata['gross_amount']:NULL),
                    "netamt"=> (array_key_exists('net_amount',$headdata)?$headdata['net_amount']:NULL),
                    "product"=> $productid,
                    "qty"=>(array_key_exists('quantity',$detsingledata)?$detsingledata['quantity']:NULL),
                    "rate"=> (array_key_exists('rate',$detsingledata)?$detsingledata['rate']:NULL),
                    "amount"=> (array_key_exists('amount',$detsingledata)?$detsingledata['amount']:NULL),
                    "operation"=>$operation,"base_id"=>$lastinsertid,"servertime"=>date("Y-m-d H:i:s",strtotime("now")));

                    array_push(   $allauditdata,$auditdata);
                }

             
 
                TblAuditData::insert($allauditdata);  
  
   }


   public static function getLastTransactionMessageWithStatus($dataid,$tablename){
  
        $tblauditdata= TblAuditData::where('table_name',$tablename)->where('base_id',$dataid)->orderby('servertime','desc')->first();

        if(empty($tblauditdata)){
 
            return '';
        }

        $userid=User::where('id', $tblauditdata->user_id)->value('user_id');

        $servertime=date("d/m/Y h:i A",strtotime(   $tblauditdata->servertime));

        $statusfield_exists= FieldsMaster::where('Table_Name',$tablename)->where('Field_Name','status')->exists();

        $statusfield_msg="";

        if(  $statusfield_exists==true){
            $baseid= $tblauditdata->base_id;

            $statusid=DB::table($tablename)->where('Id',$baseid)->value('status');

            $statusname= StatusTable::getStatusNameFromStatusId( $statusid);
 
            $statusfield_msg=" Current Status is  ".ucfirst(strtolower($statusname)).".";
        }


        $msg="Last ".ucfirst(strtolower($tblauditdata->operation))." done by ".strtoupper($userid)." at  $servertime. ".  $statusfield_msg;

        return $msg;

   }

   public function ManageTblLinkSetupAndData($tablename,$maintabledata,$lastinsertid,$detdata){

                $tbllinksetups=TblLinkSetup::where('base_txn',$tablename)->get();
                
                if(count( $tbllinksetups)==0){ 
                    return ;
                }
           

                $tbllinkdataarray=array();


                $presenttxndetids= TblLinkData::where('doc_no',$maintabledata['docno'])->pluck('txn_det_id')->toArray();


                $giventxndetids=array_column($detdata,'id');

                $not_present_detids=array_diff($presenttxndetids,$giventxndetids);


                if(count($not_present_detids)>0){
                    TblLinkData::where('doc_no',$maintabledata['docno'])->whereIn('txn_det_id', $not_present_detids)->delete();
                }
                

           foreach($tbllinksetups as $tbllinksetup){

                foreach(  $detdata as $det){

                                $det=(array)$det;

                                $det= array_change_key_case($det,CASE_LOWER); 

                                if(array_key_exists('product',$det)){
                                    $productid=$det['product']; 
                                }
                                else if(array_key_exists('line_acc',$det)){
                                    $productid=$det['line_acc']; 
                                }


                                if(array_key_exists('line_acc',$maintabledata)  &&  !empty($maintabledata['line_acc'])){
                                    $lineaccvalue=$maintabledata['line_acc'];
                                }
                                else{
                                    $lineaccvalue=NULL;
                                }


                                
                                if(array_key_exists('line_acc',$det)  &&  !empty($det['line_acc'])){
                                    $lineaccdetvalue=$det['line_acc'];
                                }
                                else{
                                    $lineaccdetvalue=NULL;
                                }

                                
                                if(array_key_exists('batch_no',$maintabledata)  &&  !empty($maintabledata['batch_no'])){
                                    $batchno=$maintabledata['batch_no'];
                                }
                                else{
                                    $batchno=NULL;
                                }

                            //     array_push( $tbllinkdataarray, array('txn_id'=>$tablename,
                            //     'doc_date'=>(empty($maintabledata['docdate'])?NULL:$maintabledata['docdate'])  ,
                            //     'doc_no'=>(empty($maintabledata['docno'])?NULL:$maintabledata['docno']),
                            //     'location'=>(empty($maintabledata['location'])?1:$maintabledata['location']),
                            //     'cust_id'=>(empty($maintabledata['cust_id'])?NULL:$maintabledata['cust_id']),
                            //     'product'=> $productid,
                            //     'batch_no'=> $batchno,
                            //     'qty'=>(empty($det['quantity'])?NULL:$det['quantity']),
                            //     'rate'=>(empty($det['rate'])?NULL:$det['rate']),
                            //     'amount'=>(empty($det['amount'])?NULL:$det['amount']),
                            //     'reff_no'=>NULL,
                            //     'txn_main_id'=>$lastInsertId,
                            //     'link_main_id'=>NULL,
                            //     'txn_det_id'=>$det['id'],
                            //     'link_det_id'=>NULL,
                            //     'due_date'=>(empty($maintabledata['duedate'])?NULL:$maintabledata['duedate']),
                            //     'salesman'=>(empty($maintabledata['salesman'])?NULL:$maintabledata['salesman']),
                            //     'link_txn'=>$tbllinksetup->link_txn ,
                            //     'line_acc'=>$lineaccvalue,
                            //     'line_acc_det'=>  $lineaccdetvalue
                            // ));
                             

                            // TblLinkData::where('doc_no',$maintabledata['docno'])->where('txn_det_id')

                            $current_tbllinkdata=   TblLinkData::where( [
                                'doc_no'=>(!array_key_exists('docno',$maintabledata)?NULL:$maintabledata['docno']),
                                'txn_main_id'=>$lastinsertid, 
                                'txn_det_id'=>$det['id'],

                            ])->select('product','qty','used_qty','reff_no')->first();


                            if(empty($current_tbllinkdata)){
                                $newreffno=NULL;
                                $newusedqty=0;   
                            }
                            else if($current_tbllinkdata->product!=$productid  || $current_tbllinkdata->qty!=$det['quantity'] ){
                                $newreffno=NULL;
                                $newusedqty=0; 
                            }
                            else{
                                $newreffno= $current_tbllinkdata->reff_no;
                                $newusedqty=  $current_tbllinkdata->used_qty;

                            }
 
                            if(empty($current_tbllinkdata)){
 

                                TblLinkData::insert(
                                    [
                                    'doc_no'=>( !array_key_exists('docno',$maintabledata)?NULL:$maintabledata['docno']) ,
                                    'txn_main_id'=>$lastinsertid, 
                                    'txn_det_id'=>$det['id'], 
                                'txn_id'=>$tablename,
                                'doc_date'=>(!array_key_exists('docdate',$maintabledata)?NULL:$maintabledata['docdate'])  ,
                                'location'=>(!array_key_exists('location',$maintabledata)?1:$maintabledata['location']),
                                'cust_id'=>(!array_key_exists('cust_id',$maintabledata)?NULL:$maintabledata['cust_id']),
                                'product'=> $productid,
                                'batch_no'=> $batchno,
                                'qty'=>( !array_key_exists('quantity',$det)?NULL:$det['quantity']),
                                'rate'=>( !array_key_exists('rate',$det)?NULL:$det['rate']),
                                'amount'=>( !array_key_exists('amount',$det)?NULL:$det['amount']),
                                'reff_no'=>    $newreffno,  
                                'used_qty'=> $newusedqty ,
                                'link_main_id'=>NULL,
                                'link_det_id'=>NULL,
                                'due_date'=>(!array_key_exists('duedate',$maintabledata)?NULL:$maintabledata['duedate']),
                                'salesman'=>( !array_key_exists('salesman',$maintabledata)?NULL:$maintabledata['salesman']),
                                'link_txn'=>$tbllinksetup->link_txn ,
                                'line_acc'=>$lineaccvalue,
                                'line_acc_det'=>  $lineaccdetvalue]);

                            }
                            else{

                                TblLinkData::where( [
                                    'doc_no'=>( !array_key_exists('docno',$maintabledata)?NULL:$maintabledata['docno']),
                                    'txn_main_id'=>$lastinsertid, 
                                    'txn_det_id'=>$det['id'],

                                ])->update(
                                    [ 
                                    'txn_main_id'=>$lastinsertid, 
                                    'txn_det_id'=>$det['id'],  
                                'location'=>(!array_key_exists('location',$maintabledata)?1:$maintabledata['location']),
                                'cust_id'=>( !array_key_exists('cust_id',$maintabledata)?NULL:$maintabledata['cust_id']),
                                'product'=> $productid,
                                'batch_no'=> $batchno,
                                'qty'=>( !array_key_exists('quantity',$det)?NULL:$det['quantity']),
                                'rate'=>( !array_key_exists('rate',$det)?NULL:$det['rate']),
                                'amount'=>( !array_key_exists('amount',$det)?NULL:$det['amount']),
                                'reff_no'=>    $newreffno,  
                                'used_qty'=> $newusedqty ,
                                'link_main_id'=>NULL,
                                'link_det_id'=>NULL,
                                'due_date'=>( !array_key_exists('duedate',$maintabledata)?NULL:$maintabledata['duedate']),
                                'salesman'=>( !array_key_exists('salesman',$maintabledata)?NULL:$maintabledata['salesman']),
                                'link_txn'=>$tbllinksetup->link_txn ,
                                'line_acc'=>$lineaccvalue,
                                'line_acc_det'=>  $lineaccdetvalue]
                            );

                            }

                              
                            // TblLinkData::updateOrCreate(
                            //     [
                            //         'doc_no'=>(empty($maintabledata['docno'])?NULL:$maintabledata['docno']),
                            //         'txn_main_id'=>$lastinsertid, 
                            //         'txn_det_id'=>$det['id'],

                            //     ],
                            //     [
                            //         'doc_no'=>(empty($maintabledata['docno'])?NULL:$maintabledata['docno']),
                            //         'txn_main_id'=>$lastinsertid, 
                            //         'txn_det_id'=>$det['id'], 
                            //     'txn_id'=>$tablename,
                            //     'doc_date'=>(empty($maintabledata['docdate'])?NULL:$maintabledata['docdate'])  ,
                            //     'location'=>(empty($maintabledata['location'])?1:$maintabledata['location']),
                            //     'cust_id'=>(empty($maintabledata['cust_id'])?NULL:$maintabledata['cust_id']),
                            //     'product'=> $productid,
                            //     'batch_no'=> $batchno,
                            //     'qty'=>(empty($det['quantity'])?NULL:$det['quantity']),
                            //     'rate'=>(empty($det['rate'])?NULL:$det['rate']),
                            //     'amount'=>(empty($det['amount'])?NULL:$det['amount']),
                            //     'reff_no'=>    $newreffno,  
                            //     'used_qty'=> $newusedqty ,
                            //     'link_main_id'=>NULL,
                            //     'link_det_id'=>NULL,
                            //     'due_date'=>(empty($maintabledata['duedate'])?NULL:$maintabledata['duedate']),
                            //     'salesman'=>(empty($maintabledata['salesman'])?NULL:$maintabledata['salesman']),
                            //     'link_txn'=>$tbllinksetup->link_txn ,
                            //     'line_acc'=>$lineaccvalue,
                            //     'line_acc_det'=>  $lineaccdetvalue]
                            // ); 

                }
            }
          
        }


        public function checkDetailRowIsReferencedBeforeDelete(Request $request){
 
            $docno=$request->docno;

            $detailid=$request->detail_id;

            $trantable=$request->tran_table;


            $this->edittrandataservice->tran_table= $trantable;

             $checkcandelete=$this->edittrandataservice->getEditBaseFromTblLinkSetup();


             $isref= TblLinkData::where('txn_id',$trantable)->where('doc_no',$docno)->where('txn_det_id',    $detailid)->whereNotNull('reff_no')->exists();


             if($isref==true &&     $checkcandelete=="stop"){
                 
                return response()->json(['status'=>false,'message'=>'This is referenced , therefore cannot delete it']);
             }
             else{
                return response()->json(['status'=>true]);

             }

        }


        public function checkTranDetailsAreReferencedCheckDelete(Request $request){

 
            $docno=$request->docno; 

            $trantable=$request->tran_table;
            
            $isref= TblLinkData::where('txn_id',$trantable)->where('doc_no',$docno)->whereNotNull('reff_no')->exists();


            $this->edittrandataservice->tran_table= $trantable;

            $editbase= $this->edittrandataservice->getEditBaseFromTblLinkSetup();
  

            if( $isref==false ||      $editbase=="disable"){
              
                return response()->json(['status'=>false ]);
            }


            $detailrows=$request->detailrows;

            $productschanged=false;



            foreach( $detailrows as  $detailrow){

                $this->edittrandataservice->detailid=$detailrow['id'];
                $this->edittrandataservice->product=$detailrow['product'];
                $this->edittrandataservice->quantity=$detailrow['quantity'];
                 $ischanged= $this->edittrandataservice->checkIfDetailRowIsChanged();

                 if($ischanged==true){
                    $productschanged=true;
                 }
 

            } 
 
           if(   $productschanged==true){
               return response()->json(['status'=>true ]);
           }
           else{
            return response()->json(['status'=>false ]);
           }
   
        }


        public function getEditTranDataReceivables($companyname,$docno){

             $firstreceivable=  Receivables::where('DocNO',$docno)->select('onaccount','id')->first();

            if(empty( $firstreceivable)){
                return response()->json(['onaccount'=>"","receivables"=>array()]);
            }

            $firstreceivableid=(string) $firstreceivable->id;

            $receivables= Receivables::where('reff_no', 'LIKE' , '%'.$firstreceivableid. '%')->select('DocNO as docno','Amount as amount')->get()->toArray();

            return response()->json(['onaccount'=>    $firstreceivable->onaccount,"receivables"=>$receivables]);


        }



        public function getCopyDataIdsAndDocNumbersFromTransactionTable(Request $request){
 
            $type=$request->type;
             
            $trantable=$request->trantable;

            $term=$request->term;

            $response=array();


            if($type=="id"){
              
                $results= DB::table(   $trantable)->where('Id','LIKE','%'. $term.'%')->orderby('Id','desc')->limit(10)->pluck('Id','Id as id1') ;
 
            }
            else{
                      
                $results= DB::table(   $trantable)->where('docno','LIKE','%'. $term.'%')->orderby('docno','desc')->limit(10)->pluck('docno','docno as docno1') ;
 
            }


            foreach(  $results as  $resultkey=>$resultval){
                $response[]=array('value'=>$resultkey,"label"=>$resultval);
            }
 
            
            return response()->json( $response);
        }



        public function getCopyDataSpecificIdFromDocNumberGiven(Request $request){

             $trantable=$request->tran_table;

            $docno=$request->docno;

            $dataid= DB::table($trantable)->where('docno',$docno)->value('Id');
 
           return response()->json(['status'=>true,'data_id'=>$dataid]); 
        }



        public function getTransactionTableDataRejectReasonByDataId(Request $request){
            
            $trantable=$request->tran_table;
            $dataid=$request->data_id;

            $rejectreason=DB::table(   $trantable)->where('Id',  $dataid)->value('reject_reason');

            return response()->json(['status'=>true,'reject_reason'=> $rejectreason]);


        }

        public function submitTransactionTableRejectReasonUsingDataId(Request $request){
 
            $rejectreason=$request->reject_reason;

            $dataid=$request->data_id;

            $trantable=$request->tran_table;

            DB::table( $trantable)->where('Id',   $dataid)->update(['reject_reason'=>$rejectreason]);

            $detailtablename=TableMaster::where('Parent Table',$trantable)->value('Table_Name');


            if(empty( $detailtablename)){
                goto finalresponse;
            } 
            // also update the tbl audut data 

            $detailrows=DB::table($detailtablename)->where('fk_Id',$dataid)->get();;  

             $detailrows= json_decode(json_encode( $detailrows),true);

            $headdata=DB::table($trantable)->where('Id',$dataid)->first();
            $headdata= json_decode(json_encode( $headdata),true);

            $headdata=array_change_key_case($headdata,CASE_LOWER); 

            $user=Auth::user();
            $operation='EDIT'; 
            $allauditdata=array();
                        
            foreach( $detailrows as $detsingledata){   
                $detsingledata=   array_change_key_case($detsingledata,CASE_LOWER);
 
                if(array_key_exists('product',$detsingledata)){

                    $productid=$detsingledata['product'];
                }
                else{
                    $productid=$detsingledata['line_acc'];
                }

                $auditdata=array("user_id"=>$user->id,"table_name"=>$trantable ,"docno"=>$headdata['docno'],"docdate"=>date("Y-m-d",strtotime($headdata['docdate'])),
                "cust_id"=>(array_key_exists("cust_id",$headdata)?$headdata['cust_id']:''),
                "salesman"=>(array_key_exists('salesman',$headdata)?$headdata['salesman']:NULL) ,
                "location"=>$headdata['location'],
                "grossamt"=>$headdata['gross_amount'],
                "netamt"=>$headdata['net_amount'],
                "product"=> $productid,
                "qty"=>(array_key_exists('quantity',$detsingledata)?$detsingledata['quantity']:NULL),
                "rate"=> (array_key_exists('rate',$detsingledata)?$detsingledata['rate']:NULL),
                "amount"=>$detsingledata['amount']  ,
                "operation"=>$operation,"base_id"=>$dataid,"servertime"=>date("Y-m-d H:i:s",strtotime("now")));

                array_push(   $allauditdata,$auditdata);
            }
 
            TblAuditData::insert($allauditdata);  

            finalresponse:
            return response()->json(['status'=>true,'message'=>"Reject Reason updated successfully"]);


        }



        public function AddCustomerToAccounts($data){

          $newaccountid=Account::insertGetId([
              'ACName'=>trim($data['cust_id']),
              'G-A'=>'A',
              'Parent2'=>$data['acc_group'],
              'OpBal'=>0 , 
              'Debits'=>0,
              'Credits'=>0,
              'Bal'=>0,
              'SplType'=>0,
              'SelType'=>0,
              'C1'=>0,
              'C2'=>0,
              'C3'=>0,
              'C4'=>0,
              'C5'=>0 ,
              'C6'=>0 ,
              'C7'=>0,
              'C8'=>0,
              'C9'=>0,
              'C10'=>0,
              'C11'=>0,
              'C12'=>0,
              'D1'=>0,
              'D2'=>0,
              'D3'=>0,
              'D4'=>0,
              'D5'=>0,
              'D6'=>0,
              'D7'=>0,
              'D8'=>0,
              'D9'=>0,
              'D10'=>0,
              'D11'=>0,
              'D12'=>0,
              'Accode'=>0 ,
              'rpt_seq'=>0,
              'accdesc'=>0 ,
              'Parent'=>$data['acc_group']
          ]);

          return $newaccountid;

        }


        public function addUpdateJournalVoucherEntry($tablename,$vchtypename,$tranaccountid,$lastinsertid,$header_array,$detail_array){
 
                        $docno=$header_array['docno'];

                        $foundvchmainid=VchMain::where('VchNo', $docno)->value('Id');

                     
                        $currencyfieldname=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',14)->value('Field_Name');

                        $currencyfieldname=(!empty( $currencyfieldname)?strtolower($currencyfieldname):$currencyfieldname);

                        if(array_key_exists( $currencyfieldname,$header_array)){
                            $currencyfieldvalue=$header_array[$currencyfieldname];
                        }
                        else{
                            $currencyfieldvalue=NULL;
                        }

                        $currencyexfieldname=FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',15)->value('Field_Name');

                        $currencyexfieldname=(!empty( $currencyexfieldname)?strtolower($currencyexfieldname):$currencyexfieldname);

                        if(array_key_exists( $currencyexfieldname,$header_array)){
                            $currencyexfieldvalue=$header_array[$currencyexfieldname];
                        }
                        else{
                            $currencyexfieldvalue=NULL;
                        }

                        

                        $vchtype=VchType::where(trim('name'),trim($vchtypename) )->select('Id','Parent')->first();

                        $tranaccountdetail=TranAccount::where('Id',$tranaccountid)->first();
                        
                        

                        $vchdata=array('VchDate'=>$header_array['docdate'] ,'VchNo'=>$header_array['docno'],
                        'MainType'=> (!empty($vchtype)?$vchtype->Parent:NULL)  ,
                        'SubType'=>(!empty($vchtype)?$vchtype->Id:NULL) ,
                        'Amount'=> (array_key_exists('totaldebitamount',$header_array)?$header_array['totaldebitamount']:NULL) ,
                        'Naration'=>(array_key_exists('narration',$header_array)?$header_array['narration']:NULL),
                        'Type'=>'J',
                        'Txn_Id'=>$tablename,
                        'Base_Id'=>$lastinsertid,
                        'chq_no'=>(array_key_exists('chq_no',$header_array)?$header_array['chq_no']:NULL),
                        'ch_status'=>(array_key_exists('ch_status',$header_array)?$header_array['ch_status']:NULL),
                        'cl_date'=>(array_key_exists('cl_date',$header_array)?$header_array['cl_date']:NULL),
                        'location'=>(array_key_exists('location',$header_array)?$header_array['location']:NULL),
                        'fccur'=>$currencyfieldvalue,
                        'fcexrate'=> $currencyexfieldvalue,
                        'dept'=>(array_key_exists('dept',$header_array)?$header_array['dept']:NULL),
                        'executive'=>(array_key_exists('executive',$header_array)?$header_array['executive']:NULL),
                        'ist_op1'=>NULL,
                        'ist_op2'=>NULL,
                        'ist_op3'=>NULL,
                        'ist_op4'=>NULL,
                        'tax_code'=>(array_key_exists('tax_code',$header_array)?$header_array['tax_code']:NULL),
                        'projid'=>(array_key_exists('projid',$header_array)?$header_array['projid']:NULL),
                        'pay_lvl'=>(array_key_exists('pay_lvl',$header_array)?$header_array['pay_lvl']:NULL) 
                        ); 


                        if(!empty($foundvchmainid)){
                            $vchmainid=$foundvchmainid; 
                            VchDet::where('MainId',$foundvchmainid)->delete();

                            VchMain::where('Id',   $vchmainid)->update($vchdata); 
                        }
                        else{
 
                            $vchmainid= VchMain::insertGetId(  $vchdata);
                        }



                        $vchdetarray=array();

                        foreach($detail_array as $single_det){

                            $debitamount=$single_det['debitamount'];
                            $creditamount=$single_det['creditamount'];

                            if($creditamount>0){
                                $amount= (int) $creditamount*(-1);
                            }
                            else{
                                $amount= (int)  $debitamount;
                            }
                        
                            $lineaccidselected=$single_det['line_acc'];
                        
                            array_push($vchdetarray,array('MainId'=>   $vchmainid ,'Amount'=>  $amount,'AcId'=>  $lineaccidselected,
                                                'Narration'=>(array_key_exists('narration',$single_det)?$single_det['narration']:NULL),
                                                'Costcentre'=>(array_key_exists('costcentre',$header_array)?$header_array['costcentre']:NULL),
                                                'FCamt'=>(array_key_exists('fcamt',$header_array)?$header_array['fcamt']:NULL),
                                                'linearr2'=> (array_key_exists('linearr2',$header_array)?$header_array['linearr2']:NULL)
                                                ) );
                        
                        }

                        VchDet::insert($vchdetarray);
                        
 
        }


        public function getTransactionAccountReceivableDetails($companyname,$accid){
 
            $this->edittrandataservice->acc_id=$accid;

            $response= $this->edittrandataservice->getAccountDetailWithReceivables();

            return response()->json($response);
 

        }



        public function getAccountReceivablePayableAmountAdjustments(Request $request){
                    $accid=$request->acc_id; 

                    $netamount=$request->net_amount;

                    $action=$request->action;

                    $onaccount=$request->on_account;
            
                    $this->edittrandataservice->accid= $accid;

                    $amountadjustments= $this->edittrandataservice->getReceivableInLifoFifo($netamount,$onaccount,$action);

                    return response()->json(['adjustments'=> $amountadjustments]);
 
        }





        public function addUpdateDetailRowsReceivables( $tablename,$headerdata,$detail_indexes,$receivables_detailwise){
            // 'acc_name':accname , 'acc_balance':accbalance,'onaccount':onaccount,'acc_id':accid,'receivables': receivables


            foreach($detail_indexes as $detail_index){

                // if not found then continue
                if(!array_key_exists('show_randp_'.$detail_index,$receivables_detailwise)){
                    continue;
                }


               $detailwise_receivables=  $receivables_detailwise['show_randp_'.$detail_index];

               $accid= $detailwise_receivables['acc_id'];

               $onaccount=$detailwise_receivables['onaccount'];

               $netamount=$detailwise_receivables['net_amount'];

               $detailwise_receivables=$detailwise_receivables['receivables'];
 
               $found_id_receivable=Receivables::whereNULL('reff_no')->where('Accid', $accid)->where('DocNo',$headerdata['docno'] )->value('id');

               $receivables=Receivables::where('Accid', $accid)->whereRaw('ISNULL(org_amt,0) > ISNULL(amount,0)')->orderby('id','desc')->select('id','reff_no','lastreceipt')->get()->toArray();

               $docdate=date("Y-m-d",strtotime($headerdata['docdate'])) ;
               $this->edittrandataservice->docdate=$docdate;
               $this->edittrandataservice->tran_table=$tablename;
               $this->edittrandataservice->head_data=$headerdata;
               
               $duedate=$this->edittrandataservice->calculateDueDateFromDocDate();

               $r_or_p= TableMaster::where('Table_Name',$tablename)->value('Receivable');

               $this->edittrandataservice->field_function=14;
         
               $function14fieldvalue= $this->edittrandataservice->getFunctionFieldNameValueFromData();
               
               $this->edittrandataservice->field_function=15;
         
               $function15fieldvalue= $this->edittrandataservice->getFunctionFieldNameValueFromData();
              
               $customerid=Customer::getCustomerIdFromAccountId($accid );
               
               $data=array(
                'CustomerId'=>     $customerid,
                // from customers table using accid get customer id
                'Accid'=>   $accid, 
                'DocNo'=>$headerdata['docno']  , 
                'DocDate'=> $docdate, 
                'Amount'=>0,
                'TxnId'=>$tablename,
                'PendingFlag'=>'True' ,
                'Area'=>NULL,
               'Productid'=> (array_key_exists('product',$headerdata)?$headerdata['product']:NULL),
               'lastreceipt'=> NULL,
               'reff_no'=>NULL,
               'onaccount'=>  $onaccount,
               'duedate'=> $duedate,
               'r_p'=>$r_or_p,
               'location'=>(array_key_exists('location',$headerdata)?$headerdata['location']:NULL),
               'dept'=>(array_key_exists('dept',$headerdata)?$headerdata['dept'] :NULL),
               'salesman'=>(array_key_exists('salesman',$headerdata)?$headerdata['salesman']:NULL),
               'cur_name'=>  $function14fieldvalue,
               'org_amt'=>$netamount,
               // get from detail credit amount or debit amount which is greater
               'Exc_rate'=>  $function15fieldvalue,
               'linearr2'=>(array_key_exists('linearr2',$headerdata)?$headerdata['linearr2']:NULL)
             ); 
     
     
                    if(  !empty($found_id_receivable)){
                        unset(  $data['DocNo']);
                        unset(  $data['Amount']);
                        unset($data['PendingFlag']);
                        Receivables::where('id',$found_id_receivable)->update($data);
                        $lastinsertid=$found_id_receivable; 
                    }
                    else{ 
            
                        $lastinsertid= Receivables::insertGetId( $data);
                    }



                    $lastinsertid=(string)$lastinsertid;
 

                    Receivables::where('reff_no', $lastinsertid)->delete();
 
                    
                    $adjustment_data=array();
 
                    foreach(    $detailwise_receivables as     $detailwise_receivable){

                        array_push($adjustment_data ,

                        array(    'CustomerId'=>     $customerid ,
                        // get cust id using account id like above
                        'Accid'=>   $accid, 
                        'DocNo'=>$detailwise_receivable['docno']  , 
                        'DocDate'=> Receivables::getDocDateFromDocno($detailwise_receivable['docno']), 
                        'Amount'=>$detailwise_receivable['amtentry'],
                        'TxnId'=>$tablename,
                        'PendingFlag'=>'True' ,
                        'Area'=>NULL,
                       'Productid'=> (array_key_exists('product',$headerdata)?$headerdata['product']:NULL),
                       'lastreceipt'=> NULL,
                       'reff_no'=>$lastinsertid,
                       'onaccount'=> 0,
                       'duedate'=> $duedate,
                       'r_p'=>$r_or_p,
                       'location'=>(array_key_exists('location',$headerdata)?$headerdata['location']:NULL),
                       'dept'=>(array_key_exists('dept',$headerdata)?$headerdata['dept'] :NULL),
                       'salesman'=>(array_key_exists('salesman',$headerdata)?$headerdata['salesman']:NULL),
                       'cur_name'=>  $function14fieldvalue,
                       'org_amt'=>0,
                       'Exc_rate'=>  $function15fieldvalue,
                       'linearr2'=>(array_key_exists('linearr2',$headerdata)?$headerdata['linearr2']:NULL) ,)
                    
                         );

                    } 

                    Receivables::insert($adjustment_data);
 

            }
          

        }



        public function editTransactionTableDataExcelDownload($companyname,$tablename){
 
            $this->edittrandataservice->role=Session::get('role_id');
            // if table sequence present then use sequenece otherwise order by id asc
            $this->edittrandataservice->tran_table=   $tablename;

            $fields= $this->edittrandataservice->getRoleWiseTransactionTableFields();

           if(count(   $fields)==0){
        
                $headerfields= FieldsMaster::where('Table_Name', $tablename)->where('Tab_Id','<>','None')->orderby('id','asc');
            }
            else{
                
                $headerfields= FieldsMaster::join('tbl_transaction_fields',function($join){
                    $join->on('fields_master.Field_Name','=','tbl_transaction_fields.field_name');
                    $join->on('fields_master.Table_Name','=','tbl_transaction_fields.transaction_table');
        
                })->where('Table_Name', $tablename)->where('Tab_Id','<>','None')->whereIn('fields_master.Field_Name', $fields)
                ->orderby('tbl_transaction_fields.sequence');
            }
         

            $headerfieldlabelfieldnames=array('id'=>'Id');
        
            $editheaderfieldlabelfieldnames= $headerfields->pluck( 'fld_label','Field_Name')->toArray();
 
            $fieldfunctions=$headerfields->pluck('Field_Function')->toArray();

            $fieldfunctions=array_merge( array(12), $fieldfunctions);
            
            $editheaderfieldlabelfieldnames= array_change_key_case(   $editheaderfieldlabelfieldnames,CASE_LOWER);
 
            $headerfieldlabelfieldnames=array_merge( $headerfieldlabelfieldnames,   $editheaderfieldlabelfieldnames);

 
            $this->edittrandataservice->exceldownload=true;


            if( !empty(Session::get('edit_tran_data_search_fields')) ){
        
                $edittrandatasearchfieldstring=Session::get('edit_tran_data_search_fields');
        
                $edittrandataarray=json_decode(  $edittrandatasearchfieldstring,true);
                $transactiondata=$this->edittrandataservice->searchTransactionDataTable(  $edittrandataarray['searchfield'], $edittrandataarray['searchcondition'],  $edittrandataarray['searchval'], $edittrandataarray['searchoperator']);
            
            }
            else{ 
                
                 $transactiondata=$this->edittrandataservice->searchTransactionDataTable();
            }

 
 
 
            // return Excel::download(new TransactionTableDataExcel(   $tablename, $headerfieldlabelfieldnames,      $fieldfunctions,$transactiondata), $tablename.'.xlsx');

            $excel_data_collection=collect([ ]); 


            $this->function4filterservice->tablename= $tablename;


           $function4allvalues= $this->function4filterservice->getTableAllFunction4FieldValuesInArray();

           $function4allvalues= array_change_key_case(   $function4allvalues,CASE_LOWER);
 
  
           $column_names=array_keys(   $headerfieldlabelfieldnames); 

           $index=0;
 
        foreach( $transactiondata as $single_transaction_data){
 

                $single_row=array();

                $column_index=0; 

                $single_transaction_data=(array)$single_transaction_data;
 
                $single_transaction_data= array_change_key_case(   $single_transaction_data,CASE_LOWER);
 
                foreach(  $column_names as   $column_name){ 
 
                
                    if(  $fieldfunctions[$column_index]==4){ 

                        if(array_key_exists($single_transaction_data[$column_name],$function4allvalues[$column_name])){

                            $showdata=  $function4allvalues[$column_name][$single_transaction_data[$column_name] ];
                        }
                        else{
                            $showdata=""; 
                        }

                    }
                        else if(  $fieldfunctions[$column_index]==31 ||  $fieldfunctions[$column_index]==27 ||  $fieldfunctions[$column_index]==6 )
                        {
                            $showdata=date("d/m/Y",strtotime($single_transaction_data[$column_name]));
                        }
                        else{

                          
                            $showdata=$single_transaction_data[$column_name]; 
                        }

                   

                    $single_row[$column_name]=$showdata;

                    $column_index++;

                }



                $excel_data_collection[ $index]=   $single_row; 
                $index++;
        }
  

            return Excel::download(new TransactionTableDataExcel(    $headerfieldlabelfieldnames,$excel_data_collection), $tablename.'.xlsx');



        }


       public function testUploadPdfFromUrl(){


        $email_tran=new EmailTranDataService;

        $email_tran->tran_table='GSI';
        $email_tran->data_id=4952;

         
        $data=$email_tran->formatMsgWithValues('test msg test msg #lotcharge is this ');


 
        // stream_context_set_default(array(
        //     'ssl'                => array(
        //     'peer_name'          => 'generic-server',
        //     'verify_peer'        => FALSE,
        //     'verify_peer_name'   => FALSE,
        //     'allow_self_signed'  => TRUE
        //      )));

    
        // $filename = 'test1.pdf';
        // $tempImage = tempnam(sys_get_temp_dir(), $filename);
        // copy('https://reportapi.bigapple.in/reportapi/generatereport?id=4850&reportfilename=GSI.rpt&databasename=BLPL21111', $tempImage);
     
     
        // $path = Storage::putFileAs('temp_pdf',     $tempImage, 'test2.pdf');
 
 
       }


       public function testSendEventMail(){


        // $emailJob = (new SendAutoTranDataEmail(1,'GSI'))->delay(Carbon::now()->addSeconds(3));
        // dispatch($emailJob) ;

        Helper::connectDatabaseByName("Universal");  

        // $host,$port,$encryption,$username,$password,$subject,$body,$fromname,$fromemail,$toemail,$filepath=NULL

        SendEmail::dispatch("smtp.gmail.com","587","tls","rjohri21@gmail.com","","Today","TEST NAME TEST NAME TEST NAME TEST NAME",'from namee from name' ,"rjohri21@gmail.com", "rjohri22@gmail.com,rjohri21@gmail.com" );
  
        Helper::connectDatabaseByName("BLPL21111");  

        // $em=new EmailTranDataService;

        // $em->testmail();

    
        

       }


       public function submitPrintReport(Request $request){
 
                    $reportname=$request->reportname;

                    $tablename=$request->tablename;

                    $reportmode=$request->reportmode;
 
                    $whatsapp_to_cust=(!empty($request->whatsapp_to_customer)?true:false);
                    
                    $whatsapp_to_sales=(!empty($request->whatsapp_to_salesman)?true:false);

                    $email_to_cust=(!empty($request->email_to_customer)?true:false);

                    $email_to_sales=(!empty($request->email_to_salesman)?true:false);
            
                    $toemail_string=$request->toemailid;

                    $dataid=$request->dataid;

                    $towhatsapp_string=$request->towhatsappno;
                     
                    $whatsapp_template_id=(!empty($request->whatsapp_template_id)?$request->whatsapp_template_id:NULL);



                    $send_email=false;

                    $send_whatsapp=false;

                    $message_array=array();
 

                    if($email_to_cust==true ||  $email_to_sales==true ||   !empty( $toemail_string)  ){
                        $send_email=true;  
                    }

                    if(   $whatsapp_to_cust==true || $whatsapp_to_sales==true || !empty( $towhatsapp_string) ){
                        $send_whatsapp=true;
                    }
 


                    $em=new EmailTranDataService; 

                    $user=Auth::User(); 

                    $em->tran_table=$tablename; 
                    $em->data_id= $dataid;
                    $em->user_id=  $user->id; 
                    $em->txn_id=$dataid;
                    $em->setUserSmtpSettings();

                    $em->db_name=Session::get('company_name');
                    
                     $salesman_detail=$em->getMailMobNumToSalesman();
                     $customer_detail=$em->getMailMobNumToCustomer();
                
                    $em->report_name=$request->reportname; 

                    $invoice_url= $em->getInvoicePdfUrl(); 
 
                        $em->data_id=     $dataid;
                        $em->tran_table=   $tablename;
                        $em->db_name=Session::get('company_name');
                        $em->report_name= $reportname;
                          // because it is not downloa." "ding
                         $pdf_url="";
                    

                        $download_detail= $em->downloadInvoiceFromUrl();   

                       $invoice_exists=  Storage::disk('public')->exists("invoices_pdf/". $download_detail['file_name']);

                       if($invoice_exists==true){

                        Storage::disk('public')->delete("invoices_pdf/". $download_detail['file_name']);
                       }

 
                                
                    $invoice_url_contents = file_get_contents($invoice_url);
                    
                    Storage::disk('public')->put('invoices_pdf/'.$download_detail['file_name'],       $invoice_url_contents); 



                    $pdf_downloaded_url=asset('storage/invoices_pdf/'. $download_detail['file_name']);

 
                    if(empty($invoice_url)){
                        goto end_of_print_report;
                    }
 
                    if( $send_email==false  && $send_whatsapp==false ){
                        goto end_of_print_report;
                    }
                    else if($send_email==false && $send_whatsapp==true  ){
                        goto send_report_whatsapp;
                    } 
 

  
                        // Helper::connectDatabaseByName('Universal'); 

                        // DownloadInvoiceFromUrl::dispatch(  $download_detail['file_url'] ,$download_detail['file_name'] );
                        
                        // Helper::connectDatabaseByName( $em->db_name);  
                        
                    if(!empty( $toemail_string)){  

                        $to_email_array=explode(',',$toemail_string);

                        foreach($to_email_array as $to_email_single){
                            
                            $em->addEmailAtToMail( $to_email_single);
                        }
                   
                    }
               

                    if( $email_to_sales==true &&     !empty($salesman_detail['email']) ){ 
                      
                            $em->addEmailAtToMail( trim($salesman_detail['email']));
                         
                    }

                    if(  $email_to_cust==true && !empty($customer_detail['email_id']) ){ 
                   
                            $em->addEmailAtToMail(trim($customer_detail['email_id'])); 
                    }


                    if(!empty($em->to_email)){

                        $emailconfig=TblEmailConfig::join('tbl_print_header','tbl_email_config.print_temp','=','tbl_print_header.Tempid')->where('tbl_email_config.table_name','=',  $tablename)->where('crystal',$reportname)->where('tbl_email_config.is_manual','=',1)->orderby('tbl_email_config.id','desc')->select('crystal','group_id','email_subject','email_body','send_exec','send_cust','conj')->first();
      
                        if(!empty( $emailconfig)){

                            $subject_text=$emailconfig->email_subject;

                            $body_text=$emailconfig->email_body;
 
                            $em->subject= $em->formatTextWithFieldValues( $subject_text);
                       
                            $em->body=  $em->formatTextWithFieldValues( $body_text);

                        }
                        else{
                            $em->subject="";
                            $em->body="";
                        }

                        Helper::connectDatabaseByName('Universal');

                        $em->SendTranDataEmail();

                        Helper::connectDatabaseByName( $em->db_name);

                        array_push( $message_array,"Email Request added successfully");

                    }

                    if($send_whatsapp==false){
                        goto end_of_print_report;
                    }

                    send_report_whatsapp:  
                    
                    $this->whatsappservice->whatsapp_template_id= $whatsapp_template_id;
 
                    if($whatsapp_to_cust==true &&  !empty(  $customer_detail['mob_num'])){
 
                        $this->whatsappservice->mob_num= $customer_detail['mob_num'];
                        $this->whatsappservice->first_name= $customer_detail['first_name'];
                        $this->whatsappservice->last_name=$customer_detail['last_name'];
                        $this->whatsappservice->gender="male";
                        $result= $this->whatsappservice->getUserIdFromMobNumber();
 

                        if($result['status']=="success"){
                            $this->whatsappservice->db_name=Session::get('company_name');
                            $this->whatsappservice->txn_id=$dataid; 
                            $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                            $this->whatsappservice->sendPdfLinkOnWhatsApp();   
                        }
 
                    }

                    if($whatsapp_to_sales==true &&  !empty(  $salesman_detail['mob_num'])){
                      
                        $this->whatsappservice->mob_num= $salesman_detail['mob_num'];
                        $this->whatsappservice->first_name=$salesman_detail['first_name'];
                        $this->whatsappservice->last_name=$salesman_detail['last_name'];
                        $this->whatsappservice->gender="male";
                        $result= $this->whatsappservice->getUserIdFromMobNumber(); 
                        if($result['status']=="success"){
                            
                            $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                            $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                        }
 
                    }

                    
                    if(!empty( $towhatsapp_string)){  

                        $to_whatsapp_array=explode(',',$towhatsapp_string);
                        
                        foreach($to_whatsapp_array as $to_whatsapp_single){
 
                            $this->whatsappservice->mob_num= trim($to_whatsapp_single);
                            $this->whatsappservice->first_name="Anonymus";
                            $this->whatsappservice->last_name="Anonymus";
                            $this->whatsappservice->gender="male";
                            $result= $this->whatsappservice->getUserIdFromMobNumber();
  
                            $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                            $this->whatsappservice->sendPdfLinkOnWhatsApp(); 
                            
                        }
                        
                        }

 
                    array_push( $message_array,"Whatsapp done successfully");

                    
                    end_of_print_report:
 
                    if(count($message_array)==0){
                        $message_string="";
                    }
                    else{
                        $message_string=implode("<br/>",$message_array); 
                    }
                

                    return response()->json(['status'=>'success','message'=> $message_string ]);
 
       }


       public function generateGstToken(){

            
         $auth_token=  $this->gstapiservice->setGstInvoiceAuthToken();

         dd( $auth_token); 

       }


       public function generateGstInvoice($dbname,$tablename,$dataid){
 

        if(!Helper::checkDatabaseExists(  $dbname)){

            echo "Database with Name=".$dbname." does not exist";
           exit();

        } 

         Helper::connectDatabaseByName( $dbname); 

         if(!in_array($tablename,array('GSI','GSR','GSRA'))){
             echo "This table not allowed for GST Invoice";
             exit();
         }

         if(!Schema::hasTable($tablename)){

            echo "Tablw with Name=".$tablename." does not exist";
            exit();

         }

         $this->gstapiservice->tran_table=$tablename;
         $this->gstapiservice->data_id=$dataid;
         $validate_for_gst=$this->gstapiservice->validateForGstApi();


         if(  $validate_for_gst==false){

            echo "Validation For Gst Failed";

            exit();
         }



        $auth_token= $this->gstapiservice->setGstInvoiceAuthToken();
      
        dump( $auth_token);

        $irn_result= $this->gstapiservice->generateIRN(147);


        dump(  $irn_result);
        
        if($irn_result['status']==true){

            $this->gstapiservice->saveIrnGeneratedDetails($irn_result['data']);
            dump('Irn Generated and saved');
        }
        else{

            $this->gstapiservice->addGstErrorMessage("by url", $irn_result ); 
        }

  
         Helper::connectDatabaseByName("Universal"); 
         dd("End");
 
    }


    public function checkGstNumber($companyname,$gstnumber){

        $this->gstapiservice->setGstInvoiceAuthToken();
        $this->gstapiservice->check_gst_no=$gstnumber;

        $result=$this->gstapiservice->checkGstNumberValidity();

        dd(   $result);


    }

 
   public function getEditTranDataHistoryUsingTableAndId($companyname,$tablename,$id){
 

    $docno=DB::table($tablename)->where('Id',$id)->value('docno');
    
    if(empty($docno)){

        return response()->json(['data'=>array()]);
    }


    $tblauditdatas= TblAuditData::where('docno',$docno)->get()->toArray();


    $index=0;
    foreach(   $tblauditdatas as    $tblauditdata){

      $username= User::where('id',$tblauditdata['user_id'])->value('user_id');
      $tblauditdatas[$index]['user_name']= (!empty($username)?$username:'');

      $custname=Customer::where('Id',$tblauditdata['cust_id'])->value('cust_id');
      $tblauditdatas[$index]['cust_name']=    (!empty( $custname)? $custname:'');

      $location=Location::where('Id',$tblauditdata['location'])->value('location');
      $tblauditdatas[$index]['location_name']=   (!empty($location)?$location:"");

      $productname=ProductMaster::where('Id',$tblauditdata['product'])->value('Product');
      $tblauditdatas[$index]['product_name']= (!empty( $productname)? $productname:'');
      // product Product_master

      $tblauditdatas[$index]['docdate']=date('d/m/Y',strtotime(   $tblauditdatas[$index]['docdate']));

      $tblauditdatas[$index]['servertime']=date('d/m/Y H:i A',strtotime( $tblauditdatas[$index]['servertime']));

      $index++;

    }

     return response()->json(['data'=>$tblauditdatas]);

 }


 public function testDownloadInvoiceFromUrl(){
    // Data Id=4 Tablename=GSI dbname=BLPL21111 report name =GSI.rpt  
    // $em=new EmailTranDataService;
    //  $em->data_id=4;
    //  $em->tran_table="GSI";
    //  $em->db_name=Session::get('company_name');
    //  $em->report_name= "GSI.rpt";
    //   $em->downloadInvoiceFromUrl(); 
    $pdf = file_get_contents('https://reportapi.bigapple.in/reportapi/generatereport?id=4&reportfilename=GSI.rpt&databasename=BLPL21111');
    Storage::disk('local')->put('public/invoices_pdf/samplepdf1258.pdf', $pdf); 
// ->deleteFileAfterSend(true)


 }


public function trackOrderByBillNumber($company_name,$data_id){
 
    $bill_no=DB::table('GSI')->where('Id',$data_id)->value('docno');

    $bill_no=trim( $bill_no);

     $data=  DB::select("select a.docno, a.docdate, a.docketno, c.transportname, c.transwebsite from dbo.DeliveryNote a
    inner join (select docno from dbo.deliverynote where base_doc_no like '".$bill_no."' ) b on a.docno=b.docno
    inner join dbo.actualtransport c on a.actualtransport=c.id");
 
    $data_array=json_decode(json_encode($data),true);
 
    $transportname= (array_key_exists(0,$data_array)?$data_array[0]['transportname']:"" ) ;

    $docketno=  (array_key_exists(0,$data_array)?$data_array[0]['docketno']:"" );
 
    $isgati=false;

    if(str_contains( $transportname,'Gati') || str_contains( $transportname,'GATI')  ){
        $isgati=true;
    }
    $gati_info=array();
    $nongati_info=array();

    if(  $isgati==true){

        $this->gatiservice->docket_no=    $docketno;

       $gati_info= $this->gatiservice->getGatiInfoUsingDocketno();
 
    }
    else{
        $nongati_info=$data_array;
    } 
    // $gati_info=collect(  $gati_info);
  
    $html=view('reports.gati-nongati-info',compact('nongati_info','gati_info','isgati'))->render();

    return response()->json(['html'=>$html]);

}


public function testWhatsAppSend(){

    
    $this->whatsappservice->first_name="rohit";
    
    $this->whatsappservice->last_name="jain";

    $this->whatsappservice->gender="male";
    $this->whatsappservice->mob_num='9754885526';


   $whatsapp_register=  $this->whatsappservice->getUserIdFromMobNumber();
 

   if(   $whatsapp_register['status']=="success"){

    // $this->whatsappservice->custom_field_name="invoice_link";
    $this->whatsappservice->whatsapp_template_id="874152";
    // $this->whatsappservice->pdf_link="https://reportapi.bigapple.in/reportapi/generatereport/GSI%20-%207993?id=7992&reportfilename=GSI.rpt";

    $this->whatsappservice->pdf_link="https://www.africau.edu/images/default/sample.pdf";
     $result=  $this->whatsappservice->sendPdfLinkOnWhatsApp();

     dd($result);


   }
 


}

        public function sendGeneratedInvoicesAutoMode(Request $request){

            
            $currentmonth=date('n',strtotime('now'));

            $currentyear=date('Y',strtotime('now'));

            if(in_array($currentmonth,array(1,2,3))){

                $start_year= $currentyear-1;

                $end_year=$currentyear;

            }
            else{

                $start_year= $currentyear;
                
                $end_year=$currentyear+1; 

            }

            $fy_start=$start_year."-04-01";

            $fy_end=$end_year.'-03-31';

            $cuurentdbname= Company::where('fs_date',  '>=' ,$fy_start)->where('fe_date','<=', $fy_end)->value('db_name');
 
 
            Helper::connectDatabaseByName($cuurentdbname);

              $email_tran=new EmailTranDataService;
              $email_tran->db_name=$cuurentdbname;

             $schedular_data= $email_tran->getEmailAndWhatsappSchedularData();
 
             $invoices_data=$schedular_data['invoices_data'];


             $email_tran->setSmtpUniversalSettings();
             $email_tran->db_name=Session::get('company_name');
              Helper::connectDatabaseByName('Universal');

              foreach(  $invoices_data as   $invoices_single){

                DownloadInvoiceFromUrl::dispatch(  $invoices_single['file_url'] ,$invoices_single['file_name'] );
              }
  

               $email_tran->SetSendTranDataMailsFromArray($schedular_data['emails_data']); 
            $whatsapp_data= $schedular_data['whatsapp_data'];
 
            foreach($whatsapp_data as $whatsapp_single){  
                SendWhatsappMessage::dispatch($whatsapp_single['template_id'],$whatsapp_single['mob_num'],$whatsapp_single['first_name'],$whatsapp_single['last_name'],$whatsapp_single['gender'], $whatsapp_single['pdf_link'],$whatsapp_single['db_name'],$whatsapp_single['txn_id'],$whatsapp_single['schedular_id']);
  
            }

 
             
 
        }


        public function checkCustomFields(){

            $response_array=   $this->whatsappservice->getCustomFieldsFromAccount();

            dd(  $response_array);


        }

        public function getWhatsappCustomFields($companyname,Request $request){


            $response_array=   $this->whatsappservice->getCustomFieldsFromAccount();

            return response()->json(  $response_array);


        }

        public function addAnotherWhatsappCustomField($companyname,$noofcustomfield){

            $user_id=Auth::user()->id;


            $custom_fields=   Cache::get($user_id."_whatsapp_custom_fields");

            if(empty( $custom_fields)){

                $custom_fields=   $this->whatsappservice->getCustomFieldsFromAccount();
            }

 
 
            $html=view('configuration.whatsapp_custom_fields',compact('custom_fields','noofcustomfield'))->render();

            return response()->json(['html'=>$html]);

        }
   
}
