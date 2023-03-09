<?php
namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\Auth; 
use App\Models\TblTransactionFields;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Services\FunctionService;
use App\Http\Controllers\Services\Function4FilterService;
use App\Models\WorkFlowHead;
use App\Models\RolesMap;
use App\Models\StatusTable;
use App\Models\WorkFlowDet;
use App\Models\FieldsMaster;
use App\Models\Customer;
use App\Models\Account;
use App\Models\Receivables;
use App\Models\CreditMaster;
use App\Models\TableMaster;
use App\Models\Company;
use  App\Helper\Helper;
use App\Models\ProductMaster;
use App\Models\StockDet;
use App\Models\TblLinkData;
use App\Models\UserRestrictionTranxDay;
use App\Models\RoleMonthLock;
use App\Models\VchMain;
use App\Models\VchDet;
use App\Models\TblAt;
use App\Models\TblAuditData;
use App\Models\InvAcc;
use App\Models\TranAccount;
use App\Models\TblLinkSetup; 

class EditTranDataService{
    public $role;
    public $tran_table;
    public $searchfunctions;
    public $searchvalues;
    public $searchfields;
    public $formmode;
    public $tran_id;
    public $tran_table_status_field;
    public $data_id;
    public $cust_id;
    public $docdate;
    public $head_data;
    public $field_function;
    public $dbname;
    public $location;
    public $ask_products;
    public $user_id;
    public $detailid;
    public $product;
    public $quantity;
    public $docno;
    public $accid;
    public $exceldownload=false;


 

    public function getRoleWiseTransactionTableFields(){
 
    
        $fields=TblTransactionFields::where('role',$this->role)->where('transaction_table',$this->tran_table)->orderby('sequence','asc')->get()->pluck('field_name');

          return $fields;

    }


    public function searchTransactionDataTable($searchfields=array(),$searchconditions=array(),$searchvals=array(),$searchoperator=array() ){

        if(count( $searchfields)>0){
            $hassearchfields=true;
        }
        else{
            $hassearchfields=false;
        }

        $function4filterservice=new Function4FilterService;
        

        $function4filterservice->user=Auth::user();

        $function4filterservice->tablename= $this->tran_table;
                
         $filterfields=$function4filterservice->getAllFilteredFieldsWithValues();
   
                
            $transactiondata=DB::table($this->tran_table)
            ->where(function($query)use($filterfields){
                
                foreach($filterfields as $filterfield){

                    $query->whereIn($filterfield['field_name'],$filterfield['field_filter_values']);

                }

            })
            ->when( $hassearchfields,function($query)use($searchfields,$searchconditions,$searchvals,$searchoperator){
 
                    $index=0;
                    foreach($searchfields as $searchfield){



                        $searchconditiongiven=$searchconditions[$index];

                        $searchvalgiven=$searchvals[$index];

                        if($searchfield=="docdate"){
                            $searchvalgiven=formatDateInYmd( $searchvalgiven);  
                        }

                        if($searchconditiongiven=="Contains" ){
                            $newsearchconditiongiven ="Like";
                            $newsearchvalgiven='%'. $searchvalgiven.'%';

                        }
                        else if( $searchconditiongiven=="Begin With"){
                            $newsearchconditiongiven ="Like";
                            $newsearchvalgiven= $searchvalgiven.'%';
                            
                        }
                        else if($searchconditiongiven=="Ends With"){
                            $newsearchconditiongiven ="Like";
                            $newsearchvalgiven= '%'.$searchvalgiven;

                        }
                        else{
                            $newsearchconditiongiven =$searchconditiongiven;
                            $newsearchvalgiven=$searchvalgiven;

                        } 


                        if($searchoperator=="Or"){
                            
                            if($index==0){

                                $query=$query->where($searchfield, $newsearchconditiongiven,$newsearchvalgiven);
                            }
                            else{
                                
                                $query=$query->orwhere($searchfield,$newsearchconditiongiven,$newsearchvalgiven);
                            }
                        }
                        else{
                           
                            $query=$query->where($searchfield,$newsearchconditiongiven,$newsearchvalgiven);
                        }

                        $index++;
                    } 
               
            })->orderby('id','desc');


            if($this->exceldownload==true){
                $transactiondata=$transactiondata->get();

            }
            else{
                $transactiondata=$transactiondata->paginate(10);
            }
          


            return   $transactiondata;
    }



    public function getSearchFieldDisplayValues(){

        $functionservice=new FunctionService;

        $function4filterservice=new  Function4FilterService;

        $functionservice->tablename=$this->tran_table;

        $function4filterservice->tablename=$this->tran_table;

        $searchfunctions=$this->searchfunctions;

        $searchvalues=$this->searchvalues;

        $searchfields=$this->searchfields;

        $datefunctions=array(6,27,31);

        $ddnfunctions=array(2,4,18 ,14,16);

        $displayvalues=array();
 
        $index=0;
        foreach( $searchfunctions as  $searchfunction){

            if(in_array($searchfunction,   $ddnfunctions)){

                if($searchfunction==2 || $searchfunction==5){

                    $fielddisplayvalue=$searchvalues[$index];


                }
                else if($searchfunction==4){

                    $fielddisplay=   $function4filterservice->getFunction4FieldValueUsingId($searchfields[$index], $searchvalues[$index]);

                }
                else if($searchfunction==18){

                    $functionservice->fieldval=$searchvalues[$index];

                    $fielddisplay=  $functionservice->getFunction18UsernameById();

                }
                else if($searchfunction==14){

                    $functionservice->fieldval=$searchvalues[$index];

                    $fielddisplay=  $functionservice->geFunction14CurrencyNameById();


                }
                else if($searchfunction==16){

                    $functionservice->fieldval=$searchvalues[$index];

                    $fielddisplay=  $functionservice->getFunction16UomNameById();

                }

            }
            else if(in_array($searchfunction,$datefunctions)){
                  $datestring= $searchvalues[$index];
                  $fielddisplay= date("d/m/Y",strtotime($datestring));

            }
            else{
                $fielddisplay='';
            }
            
            array_push($displayvalues, $fielddisplay);
            $index++;
        }

        return $displayvalues;
    }


 

    public function getButtonShowHideFromWorkflowHead(){


        $response=array('reject'=>false,'approve_and_save'=>false,"save"=>true,"copy"=>true,"edit"=>false,"insert"=>false,"delete"=>false,"view"=>false,"masters"=>false,"history"=>false);


       $workflowhead= WorkFlowHead::where('TranId',$this->tran_id)->where('RoleName',$this->role)->first();


    //    if(empty($workflowhead)){
 

    //     return $response;
    //    }

       $rolesmap= RolesMap::getRoleTransactionActions($this->role,$this->tran_id);

 
       $response['insert']= $rolesmap['insert'] ;

       $response['delete']= $rolesmap['delete'];
 
       $response['view']=$rolesmap['view'] ;
       $response['masters']= $rolesmap['masters'] ;

       $response['print']= $rolesmap['print'] ;
       $response['export']=$rolesmap['amend'] ;
 
  
      $statusexists= FieldsMaster::where('Table_Name',$this->tran_table)->where('Field_Name','status')->exists();

       if(!empty($this->data_id) &&  $statusexists){
        $statusfieldvalue=DB::table($this->tran_table)->where('Id',$this->data_id)->value('status');
       }
       else{
        $statusfieldvalue=''; 
       }

       if($this->formmode=="edit"){

        $response['history']= $rolesmap['history'] ;
       }
       else{
        $response['history']= false;
       }
       
       


       if($this->formmode=="edit" &&  !empty($workflowhead) && $workflowhead->Savestatusid==3 &&  ( $statusfieldvalue==2 ||  $statusfieldvalue==4 )){
        $response['reject']=true;

       }
  

       if($this->formmode=="edit"  &&  !empty($workflowhead)  &&  $workflowhead->Savestatusid==3 &&  ( $statusfieldvalue==2 ||  $statusfieldvalue==4 )){
        $response['approve_and_save']=true;

       }


       if($this->formmode=="add"){
        $response['save']=true;
       }
       else if($this->formmode=="edit" && $rolesmap['amend']==true){
         $response['save']=($response['approve_and_save']==true?false:true); 
       }
       else if($this->formmode=="edit" &&  $rolesmap['edit']==false){
        $response['save']=false;
       }

       
       if($this->formmode=="add"){
        $response['edit']=false;
        $response['copy']=true;
       }
       else{
        $response['edit']=($rolesmap['amend']==true?true:false);
        $response['copy']=($rolesmap['copy']==true?true:false);
       }
      
       return $response;

    }



    public function getStatusIdFromWorkFlowsFromSave( $fieldsarray ){

        $workflowhead=WorkflowHead::where('TranId',$this->tran_id)->where('RoleName',$this->role)->first();
 
        if(empty( $workflowhead)){
            return NULL;
        }


        try{

        $savedets=  WorkFlowDet::where('fk_id',$workflowhead->id)->where('Statustype','Save')->get();
 
        $mainstatusid= $workflowhead->Savestatusid;

        $firstsavedet=$savedets->first();
 
         $noofconditions=count( $savedets);

        $savestatusarray=array();
 
        foreach($savedets as $savedet){

            $fieldname=strtolower(trim($savedet->Fieldid));

            $condition=trim($savedet->Condition);

            $value=trim($savedet->Value);
 
            $statusgiven=$savedet->statusid;

            $enteredvalue=$fieldsarray[$fieldname]; 


            if($condition=="Starts With"  &&   str_starts_with( $enteredvalue,   $value) ){
                
                array_push( $savestatusarray,$statusgiven);

            }
            else    if($condition=="Ends With" &&   str_ends_with( $enteredvalue,   $value)){

                array_push( $savestatusarray,$statusgiven);
            }
            else    if($condition=="Contains" && str_contains( $enteredvalue,   $value) ){

                array_push( $savestatusarray,$statusgiven);
            }
            else    if($condition=="="){

                $enteredvalue=(float) $enteredvalue;
                $value=(float)$value;

                if( $enteredvalue== $value){
                    
                  array_push( $savestatusarray,$statusgiven);
                } 
            }
            else    if($condition=="<>"){
                 
                $enteredvalue=(float) $enteredvalue;
                $value=(float)$value;

                if( $enteredvalue!=$value){
                    
                  array_push( $savestatusarray,$statusgiven);

                } 
            }
            else    if($condition=="<"){
                
                $enteredvalue=(float) $enteredvalue;
                $value=(float)$value;

                if($enteredvalue<$value){
                  array_push( $savestatusarray,$statusgiven);
                } 

            }
            else    if($condition==">"){
                
                $enteredvalue=(float) $enteredvalue;
                $value=(float)$value;

                if($enteredvalue>$value){
                    array_push( $savestatusarray,$statusgiven);
                  } 


            }
            else    if($condition=="like"){

                if($enteredvalue==$value){
                    array_push( $savestatusarray,$statusgiven);
                  } 

            }
            else    if($condition=="notlike"){

                if($enteredvalue!=$value){
                    array_push( $savestatusarray,$statusgiven);
                  } 

            }

        }
 
        
        if(trim($firstsavedet->conjestion)=="And" && count($savestatusarray)==$noofconditions ){

           return (int)$savestatusarray[0];
 
        }
        else if(trim($firstsavedet->conjestion)=="Or" && count($savestatusarray)>0){

            
           return (int)$savestatusarray[0];

        }
        else if(trim($firstsavedet->conjestion)=="Only"  && count($savestatusarray)>0){

           $orderedstatusids= StatusTable::whereIn('id',$savestatusarray)->orderby('Weightage','desc')->pluck('id');
 
           return (int)$orderedstatusids[0];

        }
        else{ 

            return    (int)$mainstatusid;
        }
        
    }
    catch(\Exception $e){
        LogMessage($e); 
    }
 
    }


    public function getCustomerAccountDetailWithReceivables(){

      $accid=Customer::where('Id',$this->cust_id)->value('Acc_id');



       $accountdetail=Account::where('Id',$accid)->select('ACName','Bal')->first();
    //    ,ROUND(ISNULL(org_amt,0)-ISNULL(amount,0),2) as balance

    try{
        

          $receivables=DB::select("select a.DocNO as docno ,FORMAT(docdate,'dd/MM/yyyy') as  docdate,   org_amt as orgamount , cast((a.org_amt-b.amt-b.onac) as numeric(36,2))  as balance
          from [receivables] as a
          left join (select cast(sum(amount) as numeric(36,2)) as amt, cast(sum(onaccount) as numeric(36,2)) as  onac, dbo.receivables.docno from [receivables] group by docno) as  b on a.docno like b.docno 
            where a.[Accid] =".$accid." and a.org_amt > (b.amt+b.onac)
			group by a.docno, a.docdate, a.org_amt, b.amt, b.onac
            order by  docno");
 
       $balances=array_column(   $receivables,'balance') ;
       $result=array('account_name'=>$accountdetail->ACName,'account_balance'=>sprintf('%0.2f',$accountdetail->Bal),'receivables'=>$receivables,'balances'=>$balances);

       return $result;
    }
    catch(\Exception $e){
        LogMessage($e);

    }


    }



    public function calculateDueDateFromDocDate(){

        $fieldname=FieldsMaster::where(['Table_Name'=>$this->tran_table ,
        'Field_Function'=>4,
        'From_Table'=>'creditmaster'
        ])->value('Field_Name');

        if(empty($fieldname))
        return NULL;
 
        $headerdata=$this->head_data;

        if(!array_key_exists($fieldname,$headerdata))
        return NULL;


        $creditmasterid=$headerdata[$fieldname];

        $duedays=CreditMaster::where('Id',  $creditmasterid)->value('duedays');

        $duedays=(int)   $duedays;

         $duedate= date('Y-m-d', strtotime($this->docdate. ' + '. $duedays.' days'));

        return       $duedate;
    }


   
   public function getFunctionFieldNameValueFromData(){

        $functionfieldname=FieldsMaster::where([
            'Table_Name'=>$this->tran_table,
            'Field_Function'=>$this->field_function
            ])->value('Field_Name');

        if(empty( $functionfieldname))  
        return NULL; 
        
        if(!array_key_exists($functionfieldname,$this->head_data))
        return NULL;

        return $this->head_data[$functionfieldname];
 
   }


   public function checkAllowReceivableOrPayable(){

        $tblpdacc=DB::table('tbl_pd_acc')->first();

       $receivable= TableMaster::where('Table_Name',$this->tran_table)->value('Receivable');


        if(trim($tblpdacc->billwise)=="True"  &&  (trim($receivable)=='R' || trim($receivable)=='P'  ) ){
            return true;
        }
        else{
            return false;
        } 

   }




   public function getTblPdAccDetails(){

     $cr_chk=TableMaster::where('Table_Name',$this->tran_table)->value('cr_chk');
     
    $tblpdacc=DB::table('tbl_pd_acc')->first();

    $result=array(
              'crelimit'=>(trim( $tblpdacc->crelmt)=="True" && trim($cr_chk)=="True"  )?true:false ,
              'warn_stop'=>(trim($tblpdacc->warn_stop)=="True")?true:false
            );

      return $result;

   }


   public function checkNetAmountBalanceExceeded($netamount){


            $accid=Customer::where('Id',$this->cust_id)->value('Acc_id');
            $netamount=(float)$netamount;
            $accountdetail=Account::where('Id',   $accid)->select('Bal','Credits')->first();
            $balance=(float)  ($accountdetail->Bal);

            $creditlimit=(float)($accountdetail->Credits);
 
            $total= $balance+ $netamount;  
            if(   $total>$creditlimit){
                $amountexceeded=true;
            }
            else{
                $amountexceeded=false;
            } 
            
            if($creditlimit==0){
                $result=array('amountexceeded'=>false,'creditlimit'=>$creditlimit,'ledgerbalance'=>  $balance);
   
            }
            else{
                $result=array('amountexceeded'=>$amountexceeded,'creditlimit'=>$creditlimit,'ledgerbalance'=>  $balance);
  
            }
 
      
            return   $result;

   }

 
   public function checkNetAmountDaysExceeded($netamount,$docdatecreated){


            $customerdetail=Customer::where('Id',$this->cust_id)->select('Acc_id','Credit_days')->first();
            
            $accid=$customerdetail->Acc_id;

            $netamount=(float)$netamount;

            $customercreditdays=(int) $customerdetail->Credit_days;

            $accountdetail=Account::where('Id',   $accid)->select('Bal','Credits')->first();

           $previousdb= Company::where('db_name',$this->db_name)->value('pfy_db');
            
           $currentdb=$this->db_name;

           $sum_currentnetamount=DB::table($this->tran_table)->where('cust_id',$this->cust_id)->sum('net_amount');

           if(empty( $sum_currentnetamount)){
               $sum_currentnetamount=0;
           }

           $balance=(float)$accountdetail->Bal; 

          $docdate_currentdbstring=  DB::table($this->tran_table)->where('cust_id',$this->cust_id)->orderby('docdate','asc')->limit(1)->value('docdate');
           
          $docdate_current=date("Y-m-d",strtotime($docdatecreated));


           Helper::connectDatabaseByName(  $previousdb);  

           $docdate_previousdbstring=  DB::table($this->tran_table)->where('cust_id',$this->cust_id)->orderby('docdate','asc')->limit(1)->value('docdate');
 
           if(!empty($docdate_previousdbstring)){

            $docdate_previous=date("Y-m-d",strtotime( $docdate_previousdbstring));
           }
           else{
            $docdate_previous=date("Y-m-d",strtotime('now'));   
           }

           $sum_previousnetamount=DB::table($this->tran_table)->where('cust_id',$this->cust_id)->sum('net_amount');

           if(empty($sum_previousnetamount)){
               $sum_previousnetamount=0;
           }


           Helper::connectDatabaseByName( $currentdb);

           $daysdiff=date_diff(date_create($docdate_previous),date_create($docdate_current));

          $noofdays_difference= intval($daysdiff->format("%a")); 

          $totalnetamount=$sum_currentnetamount+ $sum_previousnetamount;

          if($balance==0){
            $noofnetamount_per_balance=1;
          }
          else{

            $noofnetamount_per_balance=round(($totalnetamount/$balance),2) ;
          }

          $average_receivabledays=ceil( $noofdays_difference/ $noofnetamount_per_balance);
 
           
          if(  $customercreditdays==0){
               
              return array('daysexceeded'=>false,'averagereceivabledays'=>$average_receivabledays,'alloweddays'=>$customercreditdays);
          }
          else if($average_receivabledays>$customercreditdays){
              
            return array('daysexceeded'=>true,'averagereceivabledays'=>$average_receivabledays,'alloweddays'=>$customercreditdays);
             
          } 
          else{
            return array('daysexceeded'=>false,'averagereceivabledays'=>$average_receivabledays,'alloweddays'=>$customercreditdays);
            
          }
   }


   public function checkDocDateOfCurrentFinancialYear(){ 
       
            $yeardetails= Company::where('db_name',$this->dbname)->select('fe_date','fs_date')->first();
            $yearenddate=date("Y-m-d",strtotime(  $yeardetails->fe_date));
            $yearstartdate=date("Y-m-d",strtotime(  $yeardetails->fs_date));
            $docdatecreated=date("Y-m-d",strtotime($this->docdate));
 
            if($docdatecreated>= $yearstartdate && $docdatecreated<=$yearenddate){
                return true;
            }
            else{
                return false;
            }
 
   }


   public function checkStockAvailabilityValidation(){

       $pdinvdetail=DB::table('tbl_pd_inv')->select('check_stock','warn_stop')->first();
 
       $stock_check=   $pdinvdetail->check_stock;
       $warn_stop= $pdinvdetail->warn_stop;

       $nghtcheck=TableMaster::where('Table_Name',$this->tran_table)->value('ngt_chk');
     
       
       if(empty($stock_check) ||  empty($nghtcheck)){
            $checkvalidation=false;
       }
       else if(trim($stock_check)=="True" &&  trim($nghtcheck)=="True"){
        $checkvalidation=true;
       }
       else{
        $checkvalidation=false;
       }

       if(trim( $warn_stop)=="True"){
           $warnstop_validation=true;
       }
       else{
        $warnstop_validation=false;  
       }


       return array('check_validation'=>$checkvalidation,'warn_stop'=>  $warnstop_validation);
 
   }


   public function checkProductStockAvailabilityFromStockDet(){


            $products=$this->ask_products;

            $location=$this->location;

            $stockoperation=TableMaster::where('Table_Name',$this->tran_table)->value('Stock Operation');

            if(trim($stockoperation)=="None" || trim($stockoperation)=="Remove" ){
                $operand="minus";
            }
            else{
                $operand="plus";
            }


            $productids=array_column($products,'product_id');
 

            $productnamesbyid=ProductMaster::whereIn('Id',   $productids)->get()->pluck('Product',"Id") ;

            $productnames=array();
            foreach(  $productids as   $productid){ 
                array_push($productnames,$productnamesbyid[$productid]);
            }
 
 
            $product_status=array();
            $index=0;

            $locationgiven=$this->location;;
 
 
            foreach(   $products as    $product){
 
                $currentstockquantity= StockDet::where('Prodid',$product['product_id'])
               ->when( !empty( $locationgiven),function($query)use( $locationgiven){
                $query->where('Location', $locationgiven);  })->sum('Qty');
 

                if(empty( $currentstockquantity)){
                    $currentstockquantity=0;
                }
                
                $askedqty=(int)$product['quantity'];

                if($operand=="plus"){ 
                    $remainingqty= $currentstockquantity+ $askedqty;
                }
                else{
                    $remainingqty= $currentstockquantity-$askedqty;
                } 

                if( $remainingqty<0){
                    
                     array_push($product_status,array('product_name'=> $productnames[$index],'asked_qty'=>$askedqty,'stock_qty'=>$currentstockquantity));
 
                }

                $index++;

            }


            return $product_status; 

   }



   public function CheckTransactionTableDataDeleteById(){
    //    first check in Tbl Link Data
            try{
          $tableid=TableMaster::getTableIdByName($this->tran_table);


          if($this->tran_table=="Customers"){
              $custexists=TblAuditData::where('cust_id',$this->data_id)->exists();

              if($custexists==true){
                  return array('status'=>false,'message'=>'Entry for this customer made during the year. Party Master cannot be deleted.');
              }
              
              $accid=Customer::getAccountIdFromCustomerId($this->data_id);

              $accbalexists= Account::where('Id', $accid)->where(function($query){
                $query->where('opbal','<>',0)->orwhere('debits','<>',0)->orwhere('credits','<>',0);
              })->exists();


              if($accbalexists==true){
                  return array('status'=>false,'message'=>' Account Balances of the customer is not 0. Party Master cannot be deleted.');
              }


             $ledgerentries_exists= VchDet::where('AcId',  $accid)->exists();

             if( $ledgerentries_exists==true){
                return array('status'=>false,'message'=>'Ledger for the customer has entries. Party Master cannot be deleted.');
            
             }
 
              
          }
 

          $docdetail=DB::table($this->tran_table)->where('Id',$this->data_id)->first() ; 

          $docdetail=(array)     $docdetail ;

 
          if(array_key_exists('docno',  $docdetail)==false &&  array_key_exists('docdate',  $docdetail)==false  ){
          
            return array('status'=>true);
          }
        

         $docno= $docdetail['docno'];
         $docdate=$docdetail['docdate'];
         $docdate=date("Y-m-d",strtotime( $docdate));

         $monthlockexists= RoleMonthLock::where('role_id',$this->role)->where( 'from_date','<=',  $docdate)->where( 'to_date','>=',  $docdate)->exists();
          

         if( $monthlockexists==true){
           return array( 'status'=>false ,'message'=>$docno." is locked for document date month ");
      
        }

        //  check in table tbl_user_rest_tran_day if delete days after doc date

        $deleteafterdays= UserRestrictionTranxDay::where(['user_id'=>$this->user_id,'tranx_id'=>$tableid])->where('delete_days','>',0)->value('delete_days');

        $currentdate=date("Y-m-d",strtotime("now"));

        if(  $deleteafterdays!==0  && !empty($deleteafterdays)){

            $deleteafterdate=date('Y-m-d', strtotime($docdate. ' + '.$deleteafterdays.' days'));

            if(  $currentdate> $deleteafterdate){
                
                return array('status'=>false,'message'=>$docno." is not allowed after ".$deleteafterdays." days");
            }

        }
 
         $docids_details=TblLinkData::where('doc_no',  $docno)->whereNotNull('reff_no')->select('Id','reff_no','txn_id','link_txn')->get();
         

         if(count($docids_details)==0){
            return array( 'status'=>true );
         }


         $usedby_docno=array();

         foreach($docids_details as $docids_detail){

            $found_reff_no=explode(",",$docids_detail->reff_no);
 
            $found_docno= TblLinkData::where('txn_id',$docids_detail->link_txn)->whereIn('txn_det_id', $found_reff_no)->pluck('doc_no')->toArray();

            $usedby_docno=array_merge(   $usedby_docno,  $found_docno);
 
         } 

         $usedby_docno= array_unique( $usedby_docno);

         if( count($usedby_docno)>0 ){
            return array( 'status'=>false ,'message'=>$docno." is used by document numbers ".implode(",",$usedby_docno));
        }

    }
    catch(\Exception $e){
        LogMessage($e);

    }


        return array('status'=>true);
 
   }


   public function deleteTransactionTableDataById(){

    // ,'cust_id'
                    $docdetail=DB::table($this->tran_table)->where('Id',$this->data_id)->first();


                    $docdetail_array=(array)  $docdetail;

                    if(array_key_exists('docno',$docdetail_array)==false  ){
                        goto final_deletion;
                    }
 
                
                    $docno= $docdetail_array['docno'];
                    $detailtablename=TableMaster::getChildTableName($this->tran_table);

                    if(!empty($detailtablename)){

                        $subdetailtablename=TableMaster::getChildTableName($detailtablename);

                    }
                    else{
                        $subdetailtablename="";
                    }
  

                    $noofdetailrows=DB::table($detailtablename)->where('fk_id',$this->data_id)->count();
                
                    // $accountid=Customer::getAccountIdFromCustomerId( $docdetail->cust_id);
 
 
                    // update at tbl audit data
 
                    TblAt::insert(['Txn'=>$this->tran_table,'opr'=>'Delete','uid'=>$this->user_id,'stime'=>date("m/d/Y h:i:s A",strtotime('now')),'ntime'=>'','rec_id'=>$this->data_id]);

                   
                   $receivableid= Receivables::whereNull('reff_no')->where('DocNo',$docno)->value('id');

                   if(!empty($receivableid)){
                        Receivables::where('reff_no',$receivableid)->delete();

                        Receivables::whereNull('reff_no')->where('DocNo',$docno)->delete();
                   }
            
                    StockDet::where('DocNo',$docno)->delete();

                  $vchmainid=VchMain::where('VchNo',$docno)->value('Id');

                  if(!empty(  $vchmainid)){

                    // before deleting vch det update the accounst as well

                    $vchdets=VchDet::where('MainId',$vchmainid)->select('AcId','Amount')->get();


                    foreach(  $vchdets as   $vchdet){
 
                        $accountdetail= Account::where('Id',$vchdet->AcId)->select('OpBal','Debits','Credits','Bal')->first();

                        $opbalance=(float)$accountdetail->OpBal;

                        $currentcredit=(float)  $accountdetail->Credits;
                        $currentdebit=(float)$accountdetail->Debits;
                        $currentbal=(float)$accountdetail->Bal;     

                        $presentamount=(float)$vchdet->Amount;

                        if( $presentamount>0){

                            $newdebit= round(( $currentdebit-$presentamount),2);

                            $newbalance=round(($opbalance+$newdebit-$currentcredit),2); 
                            
                            Account::where('Id',$vchdet->AcId)->update(['Debits'=>$newdebit,'Bal'=>$newbalance ]);

                        }
                        else{

                            $presentamount=   $presentamount*(-1); 

                            $newcredit= round(( $currentcredit-$presentamount ),2);

                            $newbalance=round(($opbalance+$currentdebit-$newcredit),2); 

                            Account::where('Id',$vchdet->AcId)->update(['Credits'=>$newcredit,'Bal'=>$newbalance ]);

                        }
 

                    }
 
                    VchDet::where('MainId',$vchmainid)->delete();
                    VchMain::where('Id',$vchmainid)->delete();
 
                  }

                    $audit_datas= TblAuditData::where('docno', $docno)->limit(  $noofdetailrows)->orderby('id','desc')->get();

                    foreach( $audit_datas as  $audit_data){

                        $newauditdata= $audit_data->replicate();
                        $newauditdata->operation="DELETE";
                        $newauditdata->save();

                    }

                    TblLinkData::where('doc_no', $docno)->delete();
                     
                   $receivableid= Receivables::where('DocNO', $docno)->value('id');

                   if(!empty($receivableid)){  
                      Receivables::where('reff_no',$receivableid)->delete(); 
                   }
                // i have added code to delete receivable 

                final_deletion:

                    if(!empty($subdetailtablename)){
                        
                        DB::table(  $subdetailtablename)->where('fk_id',$this->data_id)->delete();

                    }


                    if(!empty($detailtablename)){
                        DB::table($detailtablename)->where('fk_id',$this->data_id)->delete();
                    }


                    DB::table($this->tran_table)->where('Id',$this->data_id)->delete();



   }


   public function getDocNoFromTableUsingDataId(){

       return  DB::table($this->tran_table)->where('Id',$this->data_id)->value('docno');
   }



   public function getTempIdFromInvAcc(){

        $invacc_detail=InvAcc::where('Txn_Id',$this->data_id)->where('tablename',$this->tran_table)->first();

        if(empty(  $invacc_detail)){
            return '';
        }

       $tempid= TranAccount::where('TemplateId',$invacc_detail->TempName)->where('Transaction',$invacc_detail->tablename)->value('Id');

        return $tempid;
   }


   public function getEditBaseFromTblLinkSetup(){

      $editbases= TblLinkSetup::where('base_txn',$this->tran_table)->pluck('edit_base')->toArray();

      if(count( $editbases)==0){
            return 'disable';
      }

      $editbases=array_map('trim',    $editbases);
      
      if(in_array('True',      $editbases)){
              return 'warn';
        }
        else{
            return 'stop';
        } 
        
   }


   public function checkIfDetailRowIsChanged(){

       $newtable=$this->tran_table."_det";
 
       $detailrow= DB::table(  $newtable)->where("Id",$this->detailid)->select('product','quantity')->first();

       if(  $detailrow->product!=$this->product ||   $detailrow->quantity!=$this->quantity ){
           return true;
       } 

       return false;

   }



   
   public function getAccountDetailWithReceivables(){
 

     $accountdetail=Account::where('Id',$this->acc_id)->select('ACName','Bal')->first();
  //    ,ROUND(ISNULL(org_amt,0)-ISNULL(amount,0),2) as balance

  try{
      

        $receivables=DB::select("select a.DocNO as docno ,FORMAT(docdate,'dd/MM/yyyy') as  docdate,   org_amt as orgamount , cast((a.org_amt-b.amt-b.onac) as numeric(36,2))  as balance
        from [receivables] as a
        left join (select cast(sum(amount) as numeric(36,2)) as amt, cast(sum(onaccount) as numeric(36,2)) as  onac, dbo.receivables.docno from [receivables] group by docno) as  b on a.docno like b.docno 
          where a.[Accid] =".$this->acc_id." and a.org_amt > (b.amt+b.onac)
          group by a.docno, a.docdate, a.org_amt, b.amt, b.onac
          order by  docno");

     $balances=array_column(   $receivables,'balance') ;
     $result=array('account_name'=>$accountdetail->ACName,'account_balance'=>sprintf('%0.2f',$accountdetail->Bal),'receivables'=>$receivables,'balances'=>$balances);

     return $result;
  }
  catch(\Exception $e){
      LogMessage($e);

  }


  }



  public function getReceivableInLifoFifo( $netamount,$onaccount ,$action){
 
    $balances= Receivables::where('Accid', $this->accid)->whereRaw('ISNULL(org_amt,0) > ISNULL(amount,0)')->selectRaw( "(ISNULL(org_amt,0)-ISNULL(amount,0))  as balance")->pluck('balance')->toArray();
    
    $amountadjustments=array();

     if(  $action=='lifo'){
         $balances= array_reverse( $balances);

     }

     $netamount= $netamount-$onaccount;

     foreach( $balances as  $balance){


         if($balance>  $netamount){ 

             $amountentry=round($netamount,2); 
             $netamount=0;
         }
         else{
              
             $amountentry=round($balance,2);  
             $netamount= round(($netamount-$balance),2);

         }   
         array_push(  $amountadjustments, $amountentry);

     }

     if(  $action=='lifo'){
         $amountadjustments= array_reverse(  $amountadjustments);

     }
 
     return    $amountadjustments;
 
  }



  public function getShowRandP(){
      
                $detailtablefound=TableMaster::where('Table_Name', $this->tran_table)->first();

                if(empty( $detailtablefound)){
                    return false;
                }
            

                if( (    trim($detailtablefound->Receivable)=="R" ||    trim($detailtablefound->Receivable)=="P")  &&  trim($detailtablefound->Tab_Id)=="Details"   ){
                    $show_randp=true;
                }
                else{
                    $show_randp=false;
                }

                return   $show_randp;
  }




  public function getDetailRowsReceivables($lineaccs){

       
       $onaccountreceivables= Receivables::where('DocNO',$this->docno)->whereIn('Accid',$lineaccs)->whereNull('reff_no')->get();

       $index=1;

       $result=array();

       foreach( $onaccountreceivables as  $onaccountreceivable){

        $receivables_data=array();

        $receivables_data=Receivables::where('reff_no',$onaccountreceivable->id)->select('DocNO as docno','Amount as amtentry')->get()->toArray();

    
        // 'acc_name':accname , 'acc_balance':accbalance,'net_amount':parseFloat(netamount).toFixed(2) ,'onaccount':onaccount,'acc_id':accid,'receivables': receivables

        $result['show_randp_'.$index]=array(
            'acc_name'=>Account::getAccountNameFromAccId($lineaccs[ $index-1]) ,
            'acc_balance'=>sprintf('%0.2f',Account::getAccountBalanceFromAccId($lineaccs[ $index-1])),
            'net_amount'=>$onaccountreceivable['org_amt'],
            'onaccount'=>sprintf('%0.2f',$onaccountreceivable['onaccount']),
            'acc_id'=>$lineaccs[ $index-1],
            'receivables'=>$receivables_data
        ) ;

        $index++;

       }


       return $result;
     

  }



}

