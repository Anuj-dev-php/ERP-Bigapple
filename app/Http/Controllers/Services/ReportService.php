<?php
namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\Log; 
use App\Models\VchMain;
use App\Models\VchDet;
use App\Models\Account;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator; 
use Auth;
use App\Models\TblMailConfig;
use App\Jobs\SendEmail;
use App\Models\User;
use App\Models\Account2;
use BlueM\Tree\Node;
use BlueM\Tree as Tree;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;
use App\Models\TblLinkData;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\Schema;
use App\Models\TableMaster;
use App\Models\StockDet;
use App\Models\Customer;
use App\Models\TblStkVal;
use App\Models\ProductMaster; 

class ReportService{

    public $start_date;
    public $end_date; 
    public $account_ids;
    public $cost_center;
    public $department;
    public $account_id;
    public $add_subtotals ;
    public $search_account;
    public $location_in_tree_array=array();
    public $parent_account_id;
    public $account_level; 
    public $show_foreign_currency;
    public $project;
    public $account_tree_data=array();
    public $parent_account_ids;
    public $children_account_ids;
    public $accounts_column_data=array();
    public $company_name;
    public $show_p_and_l=false;
    public $division;
    public $subject ;
    public $body;
    public $from_name;
    public $from_email;
    public $to_email;
    public $host;
    public $port;
    public $encryption;
    public $username;
    public $password;
    public $showfilename;
    public $filename;
    public $filepath;
    public $accounts_sequential=array();
    public $account_temp=array();
    public $account_marked=array();
    public $no_of_marked_accounts;
    public $user_id;
    public $fieldname;
    public $searchterm='';
    public $fieldvalue;
    public $tablename; 
    public $product_id;
    public $location_id;
    public $txn_name;
    public $pk;
    public $valuation_method;
    public $balance_row_qtys=array();
    public $amount_rates=array(); 
    public $parent_product_ids;
    public $product_ids;
    public $product_nodes;
 

    public function __construct(){

        $mailconfig= TblMailConfig::first();

        if(!empty( $mailconfig)){
            $this->host=$mailconfig->smtp_host;
            $this->port=$mailconfig->smtp_port;
            $this->encryption=$mailconfig->encryption;
        }
 
   }

   
   public function setUserSmtpSettings(){ 

            $mailconfig=User::where('id',$this->user_id)->select('email','email_password','user_id')->first();

            if(empty(  $mailconfig) || empty($mailconfig->email_password)){
                return false;
            }

            $this->username=   $mailconfig->email;

            $this->password=    $mailconfig->email_password;

            $this->from_name=$mailconfig->user_id;
            $this->from_email=  $mailconfig->email;

            return true;
}



    public function SendReportToMail(){ 
        SendEmail::dispatch($this->host,$this->port,$this->encryption,$this->from_email, $this->password,$this->subject,$this->body,$this->from_name,$this->from_email,$this->to_email, $this->filepath,$this->showfilename);
        
    }



   
    public function getTreeStyleTrialBalances(){

        $accountids=$this->account_ids;

 
        $balances_data=array();

        foreach( $accountids as  $accountid){

            $this->account_id=$accountid; 
             $account_totals=$this->getAccountTotals();
             array_push(  $balances_data,   $account_totals); 
        }
 
        return $balances_data;

    }


    public function getSingleAccountTotal($account_id){

       $accounts_column_data= $this->accounts_column_data;
       if(array_key_exists($account_id,$accounts_column_data)){
           return $accounts_column_data[$account_id];
       }
       else{
           return array();
       } 
        // $account_detail=  Account::where('Id',$account_id)->select('ACName','OpBal','Debits','Credits','Bal','G-A','Fc_OpBal','Fc_Debits','Fc_Credits','Fc_Bal')->first();

        // $account_g_or_a= trim($account_detail->{'G-A'});

        // $account_name=  $account_detail->ACName;

        // if(empty($account_detail->OpBal)){
        //     $opening_debitbalance=0; 
        //     $opening_creditbalance=0;

        // }
        // else if($account_detail->OpBal>0){
        //     $opening_debitbalance=  $account_detail->OpBal; 
        //     $opening_creditbalance=0;
        // }
        // else{

        //     $opening_debitbalance= 0; 
        //     $opening_creditbalance=$account_detail->OpBal;

        // }

        // $total_debit= (empty($account_detail->Debits)?0:$account_detail->Debits);
        // $total_credit=  (empty($account_detail->Credits)?0:$account_detail->Credits);

        
        // if(empty($account_detail->Bal)){
        //     $closingdebitbalance=0;
        //     $closingcreditbalance=0;
        // }
        // else if(  $account_detail->Bal>0){
        //     $closingdebitbalance= $account_detail->Bal;
        //     $closingcreditbalance=0;
        // }
        // else{

        //     $closingdebitbalance=0;
        //     $closingcreditbalance= $account_detail->Bal;

        // }


        // if($this->show_foreign_currency==0){
        //     $fcamt_opening_debitbalance=0;
        //     $fcamt_opening_creditbalance=0;
        //     $fcamt_total_debit=0;
        //     $fcamt_total_credit=0;
        //     $fcamt_closing_debit_balance=0;
        //     $fcamt_closing_credit_balance=0;
        // }
        // else{ 

        //     if(empty( $account_detail->Fc_OpBal)){
        //         $fcamt_opening_debitbalance= 0;
        //         $fcamt_opening_creditbalance= 0;

        //     }
        //     else if($account_detail->Fc_OpBal>0){
        //         $fcamt_opening_debitbalance=$account_detail->Fc_OpBal;
        //         $fcamt_opening_creditbalance= 0; 
        //     }
        //     else{
        //         $fcamt_opening_debitbalance=0;
        //         $fcamt_opening_creditbalance= $account_detail->Fc_OpBal; 

        //     }

        //     $fcamt_total_debit=(empty($account_detail->Fc_Debits)?0:$account_detail->Fc_Debits);

        //     $fcamt_total_credit= (empty($account_detail->Fc_Credits)?0:$account_detail->Fc_Credits);

        //     if(empty( $account_detail->Fc_Bal)){
        //         $fcamt_closing_debit_balance=0;
        //         $fcamt_closing_credit_balance=0;
        //     }
        //     else if($account_detail->Fc_Bal>0){
        //         $fcamt_closing_debit_balance=$account_detail->Fc_Bal;
        //         $fcamt_closing_credit_balance=0;
        //     }
        //     else{
        //         $fcamt_closing_debit_balance=0;
        //         $fcamt_closing_credit_balance=$account_detail->Fc_Bal;

        //     } 
        // }

        // $account_detail=array('account_name'=> $account_name ,'opening_debitbalance'=> $opening_debitbalance,'opening_creditbalance'=>   $opening_creditbalance ,'total_debit'=>    $total_debit,'total_credit'=> $total_credit,'closing_debit_balance'=>   $closingdebitbalance,'closing_credit_balance'=>   $closingcreditbalance ,
        //              'fcamt_opening_debitbalance'=>$fcamt_opening_debitbalance , 'fcamt_opening_creditbalance'=> $fcamt_opening_creditbalance ,'fcamt_total_debit'=>   $fcamt_total_debit,'fcamt_total_credit'=>    $fcamt_total_credit,'fcamt_closing_debit_balance'=> $fcamt_closing_debit_balance,'fcamt_closing_credit_balance'=>$fcamt_closing_credit_balance
        //              );

 
        // return array('account_detail'=>  $account_detail,'account_g_or_a'=>  $account_g_or_a);
 
    }


    public function getAccountTotals(){
 
        $account_result=$this->getSingleAccountTotal($this->account_id);
 
        $account_detail=  $account_result['account_detail'];

        $account_type=trim($account_result['account_g_or_a']);
  
        $opening_debitbalance= $account_detail['opening_debitbalance'];
        $opening_creditbalance= $account_detail['opening_creditbalance'];
        $total_debit=$account_detail['total_debit'];
 
        $total_credit= $account_detail['total_credit'];
        $closing_debit_balance= $account_detail['closing_debit_balance'];
        $closing_credit_balance=$account_detail['closing_credit_balance'];

        $fcamt_opening_debitbalance= $account_detail['fcamt_opening_debitbalance'];
        $fcamt_opening_creditbalance= $account_detail['fcamt_opening_creditbalance'];
        $fcamt_total_debit=$account_detail['fcamt_total_debit'];
        $fcamt_total_credit= $account_detail['fcamt_total_credit'];
        $fcamt_closing_debit_balance= $account_detail['fcamt_closing_debit_balance'];
        $fcamt_closing_credit_balance=$account_detail['fcamt_closing_credit_balance'];
 
 
  
        if($this->add_subtotals==true && array_key_exists($this->account_id,$this->account_tree_data)==true &&  $account_result['account_g_or_a']=="G"){
  

          $result= $this->getAllChildrenAccountTotals(); 
 
 
            $opening_debitbalance=   $opening_debitbalance+$result['opening_debitbalance'];

          $opening_creditbalance= $opening_creditbalance+$result['opening_creditbalance'];

          $total_debit=$total_debit+$result['total_debit'];

          $total_credit=  $total_credit +$result['total_credit'];

          $closing_debit_balance=   $closing_debit_balance+$result['closing_debit_balance'];

          $closing_credit_balance= $closing_credit_balance+$result['closing_credit_balance'];

          $fcamt_opening_debitbalance=    $fcamt_opening_debitbalance+$result['fcamt_opening_balances'];
          $fcamt_opening_creditbalance=      $fcamt_opening_creditbalance+$result['fcamt_opening_creditbalance'];
          $fcamt_total_debit=    $fcamt_total_debit+$result['fcamt_total_debit'];
          $fcamt_total_credit=   $fcamt_total_credit+$result['fcamt_total_credit'];
          $fcamt_closing_debit_balance=   $fcamt_closing_debit_balance+$result['fcamt_closing_debit_balance'];
          $fcamt_closing_credit_balance=   $fcamt_closing_credit_balance+$result['fcamt_closing_credit_balance'];

        }



        $diff_account_opening=round($opening_debitbalance,2)-round($opening_creditbalance,2);

        $diff_account_opening=round($diff_account_opening,2);

        if( $diff_account_opening>=0){

            $account_opening_debitbalance=$diff_account_opening;
            $account_opening_creditbalance=0;

        }
        else{

            $account_opening_debitbalance=0;
            $account_opening_creditbalance=$diff_account_opening*-1;

        }

        $diff_account_closing=round($closing_debit_balance,2)-round($closing_credit_balance,2) ;
        $diff_account_closing=round($diff_account_closing,2);

        if( $diff_account_closing>=0){
            $account_closing_debit_balance=$diff_account_closing;
            $account_closing_credit_balance=0;

        }
        else{
            $account_closing_debit_balance=0;
            $account_closing_credit_balance=$diff_account_closing*-1;

        }


        $diff_account_closing=round($closing_debit_balance,2)-round($closing_credit_balance,2) ;
        $diff_account_closing=round($diff_account_closing,2);

        if( $diff_account_closing>=0){
            $account_closing_debit_balance=$diff_account_closing;
            $account_closing_credit_balance=0;

        }
        else{
            $account_closing_debit_balance=0;
            $account_closing_credit_balance=$diff_account_closing*-1;

        }


        
 

        $diff_fcamt_account_opening=round(  $fcamt_opening_debitbalance,2) -round(  $fcamt_opening_creditbalance,2) ;

        $diff_fcamt_account_opening=round($diff_fcamt_account_opening,2);

        if( $diff_fcamt_account_opening>=0){

            $account_fcamt_opening_debitbalance=$diff_fcamt_account_opening;
            $account_fcamt_opening_creditbalance=0;

        }
        else{

            $account_fcamt_opening_debitbalance=0;
            $account_fcamt_opening_creditbalance=$diff_fcamt_account_opening*-1;

        }
 
        $diff_fcamt_account_closing=round($fcamt_closing_debit_balance,2)-round($fcamt_closing_credit_balance,2);

        $diff_fcamt_account_closing=round($diff_fcamt_account_closing,2);

        if( $diff_fcamt_account_closing>=0){

            $account_fcamt_closing_debitbalance=$diff_fcamt_account_closing;
            $account_fcamt_closing_creditbalance=0;

        }
        else{

            $account_fcamt_closing_debitbalance=0;
            $account_fcamt_closing_creditbalance=$diff_fcamt_account_closing*-1;

        }
 

        return array(  'account_name'=> $account_detail['account_name'],'account_type'=>  $account_type , 'parent_name'=>$account_detail['parent_name'] ,'account_id'=>$this->account_id ,'opening_debitbalance'=>$account_opening_debitbalance ,'opening_creditbalance'=> $account_opening_creditbalance,'total_debit'=> round($total_debit,2),'total_credit'=>  round($total_credit,2),'closing_debit_balance'=> $account_closing_debit_balance,'closing_credit_balance'=>$account_closing_credit_balance ,
    'fcamt_opening_debitbalance'=>round(  $fcamt_opening_debitbalance,2) , 'fcamt_opening_creditbalance'=>round(  $fcamt_opening_creditbalance,2) ,'fcamt_total_debit'=>round( $fcamt_total_debit,2),'fcamt_total_credit'=>round( $fcamt_total_credit,2) ,'fcamt_closing_debit_balance'=>$account_fcamt_closing_debitbalance,'fcamt_closing_credit_balance'=>$account_fcamt_closing_creditbalance
    );
 
    }


    public function getChildrenAccountIds(){

        $account_tree_data=$this->account_tree_data;

        $childrenaccount_ids=(array_key_exists($this->account_id,$account_tree_data)?$account_tree_data[$this->account_id]:array());
        
      
        if(count($childrenaccount_ids)==0){
            return array();
        }
        else{
            $this->children_account_ids= $childrenaccount_ids;
        }
       
        $this->parent_account_ids= $childrenaccount_ids;
   
        $this->getAllChildsTillLastChild();

        $all_children_account_ids=$this->children_account_ids;


        // foreach( $childrenaccount_ids as  $childrenaccount_id){

        //    $temp_ids = (array_key_exists($childrenaccount_id, $account_tree_data)? $account_tree_data[$childrenaccount_id]:array()) ;
  
        //    $childrenaccount_ids= array_merge( $childrenaccount_ids,$temp_ids );

        // }
 
        // $childrenaccount_ids= Account::where('Parent2',$this->account_id)->pluck('Id')->toArray();


        // foreach( $childrenaccount_ids as  $childrenaccount_id){

        //    $temp_ids = Account::where('Parent2',$childrenaccount_id)->pluck('Id')->toArray();

        //    $childrenaccount_ids= array_merge( $childrenaccount_ids,$temp_ids );

        // }
 
 
        return $all_children_account_ids;

    }



    public function getAllChildrenAccountTotals(){


          $children_account_ids=  $this->getChildrenAccountIds(); 
 
            $accounts_data=array();

            foreach( $children_account_ids as  $children_account_id){

                 $result= $this->getSingleAccountTotal($children_account_id);
 
  
                 if(array_key_exists('account_detail',$result) ){

                    array_push( $accounts_data,$result['account_detail']);
                 }
    
            }

           $all_openingdebitbalances= array_sum( array_column( $accounts_data,'opening_debitbalance'));

           $all_openingcreditbalances=  array_sum( array_column( $accounts_data,'opening_creditbalance')); 
            
           $all_totaldebits=array_sum(  array_column( $accounts_data,'total_debit'));
 
          $all_totalcredits=  array_sum(  array_column( $accounts_data,'total_credit'));
            
           $all_closingdebitbalances=  array_sum(  array_column( $accounts_data,'closing_debit_balance'));
 
          $all_closingcreditbalances= array_sum(  array_column( $accounts_data,'closing_credit_balance'));


          $all_fcamt_openingdebitbalances= array_sum( array_column( $accounts_data,'fcamt_opening_debitbalance'));

          $all_fcamt_openingcreditbalances=  array_sum( array_column( $accounts_data,'fcamt_opening_creditbalance')); 
           
          $all_fcamt_totaldebits=array_sum(  array_column( $accounts_data,'fcamt_total_debit'));

         $all_fcamt_totalcredits=  array_sum(  array_column( $accounts_data,'fcamt_total_credit'));
           
          $all_fcamt_closingdebitbalances=  array_sum(  array_column( $accounts_data,'fcamt_closing_debit_balance'));

         $all_fcamt_closingcreditbalances= array_sum(  array_column( $accounts_data,'fcamt_closing_credit_balance'));

           return array('opening_debitbalance'=>       $all_openingdebitbalances,'opening_creditbalance'=>$all_openingcreditbalances,'total_debit'=>$all_totaldebits,'total_credit'=>    $all_totalcredits,'closing_debit_balance'=>$all_closingdebitbalances,'closing_credit_balance'=>  $all_closingcreditbalances ,
            'fcamt_opening_balances'=>  $all_fcamt_openingdebitbalances ,'fcamt_opening_creditbalance'=> $all_fcamt_openingcreditbalances ,'fcamt_total_debit'=> $all_fcamt_totaldebits ,
            'fcamt_total_credit'=>      $all_fcamt_totalcredits ,'fcamt_closing_debit_balance'=> $all_fcamt_closingdebitbalances ,'fcamt_closing_credit_balance'=>$all_fcamt_closingcreditbalances
              );
    }




    public function getAccountLocationInTree(){

        
        $locations_string=array();


        if(!empty($this->search_account)){
            
            $accounts=   Account::where('ACName', 'LIKE','%'.$this->search_account.'%')->limit(5)->get();

            
            if(empty($accounts)){
                return array( 'status'=>false ,'locations'=>array()); 
            }
  
            foreach($accounts as $account){ 
                $this->location_in_tree_array=array();

                $this->parent_account_id=   (int) $account['Parent2']; 
    
                if( $this->parent_account_id==0){

                    array_push(  $locations_string,$account['ACName']); 
                    continue;
                }
               
                array_push($this->location_in_tree_array,  $account->ACName); 
    
                $this->addLocationTillLastParent();

                $locations=  $this->location_in_tree_array;

                $locations=  array_reverse( $locations);
         
                 $location_string= implode('/',$locations);

                 array_push(  $locations_string, $location_string);

            }


        

        }



        return  array('status'=>true ,'locations'=>$locations_string);



    }


    public function addLocationTillLastParent(){
 
 
            $account_detail= Account::where('Id',$this->parent_account_id)->select('ACName','Parent2')->first();

            $parent2=(int) $account_detail->Parent2;
 
            if( $parent2==0){
                array_push($this->location_in_tree_array, $account_detail->ACName);
                return   ;
            }
            else{
                array_push($this->location_in_tree_array, $account_detail->ACName);
            }
 

            $this->parent_account_id= $parent2;

            $this->addLocationTillLastParent();
 
    }


    public function setAccountTreeData(){


        $account_tree_data_json=Cache::get($this->user_id."_account_tree_data");

        if(empty($account_tree_data_json)){

                        
                    $all_subaccounts=Account::where( "accounts.Parent2",'<>',0)->distinct('Parent2')->pluck('Parent2')->toArray();  


                    $account_tree_data=array();


                    foreach($all_subaccounts as $subaccount_id){

                        $subaccount_id=(string)trim($subaccount_id);

                        $childaccount_ids=Account::where(  "accounts.Parent2" ,$subaccount_id)->pluck('Id')->toArray();

                        $account_tree_data[  $subaccount_id]=$childaccount_ids;
            
                    } 


                    Cache::put($this->user_id."_account_tree_data",json_encode($account_tree_data),10800);

                    $this->account_tree_data=  $account_tree_data;
            
        }
        else{
            $this->account_tree_data=  json_decode( $account_tree_data_json,true);
        }

 
    }


    public function getAccountLevelAccountIds(){

        $accountlevel=$this->account_level;

        $selected_project=$this->project;
        $accountids=$this->parent_account_ids;

        // if($this->show_p_and_l==false){

        //     $accountids=Account::where('Parent2',0)->pluck('Id')->toArray(); 
        // }
        //  else{
        //     $accountids=array(4,3);
        //  }

       

       $account_tree_data= $this->account_tree_data;

        // $all_subaccounts=Account::where('Parent2','<>',0)->distinct('Parent2')->pluck('Parent2')->toArray();  


        // $account_tree_data=array();


        // foreach($all_subaccounts as $subaccount_id){

        //     $subaccount_id=(int)trim($subaccount_id);

        //     $childaccount_ids=Account::where('Parent2' ,$subaccount_id)->pluck('Id')->toArray();

        //     $account_tree_data[  $subaccount_id]=$childaccount_ids;
 
        // }
 
 
        $searched_level=1;

        $tillaccountlevel_accountids=$accountids;

        while(  $searched_level<$accountlevel){

            $temp_account_ids=array();
            foreach($accountids as $accountid){
                $found_array=(array_key_exists($accountid, $account_tree_data)?$account_tree_data[$accountid] :array());

                $temp_account_ids=array_merge(   $temp_account_ids,  $found_array);

            }   
            $accountids=   $temp_account_ids;

            $tillaccountlevel_accountids=array_merge( $tillaccountlevel_accountids,$accountids);

            // if(count($accountids)>10){

            //     $accountids_parts=array_chunk($accountids,10);
 
            //     $accountids=array();
            //     foreach($accountids_parts as $accountids_part){
  
            //         $temp_accountids=Account::whereIn('Parent2', $accountids_part)->pluck('Id')->toArray();
            //         $accountids=array_merge(  $accountids, $temp_accountids);
            //     }
            // }
            // else{

            //     $accountids=Account::whereIn('Parent2',$accountids)->pluck('Id')->toArray();
            // } 
            $searched_level++;
        }


        // return   $accountids;

        return  $tillaccountlevel_accountids;

    }




    public function getAllChildrenAccounts(){


        $children_account_ids=  $this->getChildrenAccountIds();

          $accounts_data=array();
          foreach( $children_account_ids as  $children_account_id){
 

               $result= $this->getSingleAccountTotal($children_account_id);

               array_push( $accounts_data,$result);
  
          }

         $all_openingbalances= array_sum( array_column( $accounts_data,'opening_debitbalance'));

         $all_openingcreditbalances=  array_sum( array_column( $accounts_data,'opening_creditbalance')); 
          
         $all_totaldebits=array_sum(  array_column( $accounts_data,'total_debit'));

        $all_totalcredits=  array_sum(  array_column( $accounts_data,'total_credit'));
          
         $all_closingdebitbalances=  array_sum(  array_column( $accounts_data,'closing_debit_balance'));

        $all_closingcreditbalances= array_sum(  array_column( $accounts_data,'closing_credit_balance'));

         return array('opening_debitbalance'=> $all_openingbalances,'opening_creditbalance'=>$all_openingcreditbalances,'total_debit'=>$all_totaldebits,'total_credit'=>    $all_totalcredits,'closing_debit_balance'=>$all_closingdebitbalances,'closing_credit_balance'=>  $all_closingcreditbalances);
  }


  public function getChildAccountIds(){

    $account_id=$this->account_id;
    $account_id=(string)  $account_id; 
        $account_ids=Account::where("accounts.Parent2",    $account_id)->orderby('ACName','asc')->pluck("Id")->toArray();

        return  $account_ids;


  }


  public function getAllChildsTillLastChild(){

            $parent_ids=$this->parent_account_ids;

            $account_tree_data=$this->account_tree_data;

            $new_children_ids=array();

            foreach($parent_ids as $parent_id){

                if(array_key_exists($parent_id,  $account_tree_data)==false){

                    continue;

                }

                $new_children_ids=array_merge(   $new_children_ids,$account_tree_data[$parent_id]);
 
            }


            $this->children_account_ids=array_merge( $this->children_account_ids ,$new_children_ids);


            if(count($new_children_ids)>0){
                $this->parent_account_ids=$new_children_ids;
                $this->getAllChildsTillLastChild();

            }
 

  }


  public function calculateAccountWiseTotalsByQuery(){

    //    $result= DB::statement("update dbo.accounts
    //     set dbo.accounts.OpBal=0, dbo.accounts.debits=0, dbo.accounts.credits=0, dbo.accounts.bal=0
    //     from dbo.accounts
        
    //     update A
    //     set A.OpBal=B.opbal
    //     from dbo.accounts as A
    //     inner join 
    //     (select dbo.accounts.id, sum(dbo.vchdet.amount) as opbal
    //     from dbo.accounts
    //     inner join dbo.Vchdet on dbo.accounts.Id=dbo.Vchdet.AcId
    //     inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    //     and dbo.VchMain.VchNo like '%op%'
    //     group by dbo.accounts.id) as B
    //     on A.id=B.id
        
        
    //     update A
    //     set A.debits=B.debits
    //     from dbo.accounts as A
    //     inner join 
    //     (select dbo.accounts.id, sum(dbo.vchdet.amount) as debits
    //     from dbo.accounts
    //     inner join dbo.Vchdet on dbo.accounts.Id=dbo.Vchdet.AcId
    //     inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    //     and dbo.VchMain.VchNo not like '%op%' and dbo.Vchdet.Amount>0
    //     group by dbo.accounts.id) as B
    //     on A.id=B.id
        
        
    //     update A
    //     set A.credits=B.credits
    //     from dbo.accounts as A
    //     inner join 
    //     (select dbo.accounts.id, sum(dbo.vchdet.amount) as credits
    //     from dbo.accounts
    //     inner join dbo.Vchdet on dbo.accounts.Id=dbo.Vchdet.AcId
    //     inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    //     and dbo.VchMain.VchNo not like '%op%' and dbo.Vchdet.Amount<0
    //     group by dbo.accounts.id) as B
    //     on A.id=B.id
        
    //     update dbo.accounts
    //     set dbo.accounts.OpBal=0
    //     from dbo.accounts
    //     where dbo.accounts.opbal is null
        
    //     update dbo.accounts
    //     set dbo.accounts.Debits=0
    //     from dbo.accounts
    //     where dbo.accounts.Debits is null
        
    //     update dbo.accounts
    //     set dbo.accounts.Credits=0
    //     from dbo.accounts
    //     where dbo.accounts.Credits is null
        
    //     update dbo.accounts
    //     set dbo.accounts.Bal=OpBal+Debits+Credits
    //     from dbo.accounts"); 

    $start_date=$this->start_date.' 00:00:00.000';
    $end_date=$this->end_date.' 23:59:59.999';


    if(empty($this->cost_center) &&  empty($this->division)){
        
    $result=DB::statement("truncate table dbo.accounts2

    SET IDENTITY_INSERT dbo.accounts2 ON 
    
    insert into dbo.accounts2 ([Id],[ACName],[G-A],[Parent2],[OpBal],[Debits],[Credits],[Bal],[SplType],[SelType],[accdesc],[Parent],[Fc_OpBal],[Fc_Debits],[Fc_Credits],[Fc_Bal])
    select [Id],[ACName],[G-A],[Parent2],[OpBal],[Debits],[Credits],[Bal],[SplType],[SelType],[accdesc],[Parent],[Fc_OpBal],[Fc_Debits],[Fc_Credits],[Fc_Bal] from dbo.accounts 

	update dbo.accounts2
	set parentname=dbo.accounts.acname
	from dbo.accounts2
	inner join dbo.accounts on dbo.accounts2.Parent2=dbo.accounts.id
    
    SET IDENTITY_INSERT dbo.accounts2 OFF
    
    update dbo.accounts2
    set dbo.accounts2.OpBal=0, dbo.accounts2.debits=0, dbo.accounts2.credits=0, dbo.accounts2.bal=0
    from dbo.accounts2
    
    update A
    set A.OpBal=B.opbal
    from dbo.accounts2 as A
    inner join 
    (select dbo.accounts2.id, sum(dbo.vchdet.amount) as opbal
    from dbo.accounts2
    inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
    inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    where dbo.vchmain.vchdate<'".$start_date."'
    group by dbo.accounts2.id) as B
    on A.id=B.id
    
    
    update A
    set A.debits=B.idebits
    from dbo.accounts2 as A
    inner join 
    (select dbo.accounts2.id, sum(dbo.vchdet.amount) as idebits
    from dbo.accounts2
    inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
    inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    and dbo.Vchdet.Amount>0 and dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'
    group by dbo.accounts2.id) as B 
    on A.id=B.id
    
    
    update A
    set A.credits=B.icredits
    from dbo.accounts2 as A
    inner join 
    (select dbo.accounts2.id, sum(dbo.vchdet.amount) as icredits
    from dbo.accounts2
    inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
    inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    and dbo.Vchdet.Amount<0 and dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'
    group by dbo.accounts2.id) as B
    on A.id=B.id
    
    update dbo.accounts2
    set dbo.accounts2.OpBal=0
    from dbo.accounts2
    where dbo.accounts2.opbal is null
    
    update dbo.accounts2
    set dbo.accounts2.Debits=0
    from dbo.accounts2
    where dbo.accounts2.Debits is null
    
    update dbo.accounts2
    set dbo.accounts2.Credits=0
    from dbo.accounts2
    where dbo.accounts2.Credits is null
    
    update dbo.accounts2
    set dbo.accounts2.Bal=OpBal+Debits+Credits
    from dbo.accounts2
    
    update dbo.accounts2
    set dbo.accounts2.fc_opbal=0, dbo.accounts2.fc_debits=0, dbo.accounts2.fc_credits=0, dbo.accounts2.fc_bal=0
    from dbo.accounts2
    
    update A
    set A.fc_opbal=B.fc_opbal
    from dbo.accounts2 as A
    inner join 
    (select dbo.accounts2.id, sum(dbo.vchdet.amount) as fc_opbal
    from dbo.accounts2
    inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
    inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    where dbo.vchmain.vchdate<'".$start_date."'
    group by dbo.accounts2.id) as B
    on A.id=B.id
    
    
    update A
    set A.fc_debits=B.idebits
    from dbo.accounts2 as A
    inner join 
    (select dbo.accounts2.id, sum(dbo.vchdet.amount) as idebits
    from dbo.accounts2
    inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
    inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    where dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'
    group by dbo.accounts2.id) as B 
    on A.id=B.id
    
    
    update A
    set A.fc_credits=B.icredits
    from dbo.accounts2 as A
    inner join 
    (select dbo.accounts2.id, sum(dbo.vchdet.amount) as icredits
    from dbo.accounts2
    inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
    inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
    where dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'
    group by dbo.accounts2.id) as B
    on A.id=B.id
    
    update dbo.accounts2
    set dbo.accounts2.fc_opbal=0
    from dbo.accounts2
    where dbo.accounts2.fc_opbal is null
    
    update dbo.accounts2
    set dbo.accounts2.fc_debits=0
    from dbo.accounts2
    where dbo.accounts2.fc_debits is null
    
    update dbo.accounts2
    set dbo.accounts2.fc_credits=0
    from dbo.accounts2
    where dbo.accounts2.fc_credits is null
    
    update dbo.accounts2
    set dbo.accounts2.fc_bal=fc_opbal+Debits+Credits
    from dbo.accounts2
    
    ");

        
    }
    else{


        if(!empty($this->cost_center) && !empty($this->division)){

            $costcenter_division_query="and dbo.vchdet.costcentre=".$this->cost_center." and dbo.vchdet.division=".$this->division." " ;

        }
        else if(!empty($this->cost_center) &&  empty($this->division)){
            $costcenter_division_query="and dbo.vchdet.costcentre=".$this->cost_center." " ;

        }
        else if(empty($this->cost_center) &&  !empty($this->division)){
            $costcenter_division_query="and dbo.vchdet.division=".$this->division." " ;

        } 
        $sqlstatement="truncate table dbo.accounts2

        SET IDENTITY_INSERT dbo.accounts2 ON 
        
        insert into dbo.accounts2 ([Id],[ACName],[G-A],[Parent2],[OpBal],[Debits],[Credits],[Bal],[SplType],[SelType],[accdesc],[Parent],[Fc_OpBal],[Fc_Debits],[Fc_Credits],[Fc_Bal])
        select [Id],[ACName],[G-A],[Parent2],[OpBal],[Debits],[Credits],[Bal],[SplType],[SelType],[accdesc],[Parent],[Fc_OpBal],[Fc_Debits],[Fc_Credits],[Fc_Bal] from dbo.accounts
        

		update dbo.accounts2
		set parentname=dbo.accounts.acname
		from dbo.accounts2
		inner join dbo.accounts on dbo.accounts2.Parent2=dbo.accounts.id

        SET IDENTITY_INSERT dbo.accounts2 OFF
        
        update dbo.accounts2
        set dbo.accounts2.OpBal=0, dbo.accounts2.debits=0, dbo.accounts2.credits=0, dbo.accounts2.bal=0
        from dbo.accounts2
        
        update A
        set A.OpBal=B.opbal
        from dbo.accounts2 as A
        inner join 
        (select dbo.accounts2.id, sum(dbo.vchdet.amount) as opbal
        from dbo.accounts2
        inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
        inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
        where dbo.vchmain.vchdate<='".$start_date."'   ".$costcenter_division_query."
        group by dbo.accounts2.id) as B   
        on A.id=B.id
        
        
        update A
        set A.debits=B.idebits
        from dbo.accounts2 as A
        inner join 
        (select dbo.accounts2.id, sum(dbo.vchdet.amount) as idebits
        from dbo.accounts2
        inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
        inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
        and dbo.Vchdet.Amount>0 and dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'  ".$costcenter_division_query."
        group by dbo.accounts2.id) as B 
        on A.id=B.id
        
        
        update A
        set A.credits=B.icredits
        from dbo.accounts2 as A
        inner join 
        (select dbo.accounts2.id, sum(dbo.vchdet.amount) as icredits
        from dbo.accounts2
        inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
        inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
        and dbo.Vchdet.Amount>0 and dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'  ".$costcenter_division_query."
        group by dbo.accounts2.id) as B
        on A.id=B.id
        
        update dbo.accounts2
        set dbo.accounts2.OpBal=0
        from dbo.accounts2
        where dbo.accounts2.opbal is null
        
        update dbo.accounts2
        set dbo.accounts2.Debits=0
        from dbo.accounts2
        where dbo.accounts2.Debits is null
        
        update dbo.accounts2
        set dbo.accounts2.Credits=0
        from dbo.accounts2
        where dbo.accounts2.Credits is null
        
        update dbo.accounts2
        set dbo.accounts2.Bal=OpBal+Debits+Credits
        from dbo.accounts2
        
        update dbo.accounts2
        set dbo.accounts2.fc_opbal=0, dbo.accounts2.fc_debits=0, dbo.accounts2.fc_credits=0, dbo.accounts2.fc_bal=0
        from dbo.accounts2
        
        update A
        set A.fc_opbal=B.fc_opbal
        from dbo.accounts2 as A
        inner join 
        (select dbo.accounts2.id, sum(dbo.vchdet.amount) as fc_opbal
        from dbo.accounts2
        inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
        inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
        where dbo.vchmain.vchdate<='".$start_date."'   ".$costcenter_division_query."
        group by dbo.accounts2.id) as B
        on A.id=B.id
        
        
        update A
        set A.fc_debits=B.idebits
        from dbo.accounts2 as A
        inner join 
        (select dbo.accounts2.id, sum(dbo.vchdet.amount) as idebits
        from dbo.accounts2
        inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
        inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
        and dbo.Vchdet.Amount>0 and dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'  ".$costcenter_division_query."
        group by dbo.accounts2.id) as B 
        on A.id=B.id
        
        
        update A
        set A.fc_credits=B.icredits
        from dbo.accounts2 as A
        inner join 
        (select dbo.accounts2.id, sum(dbo.vchdet.amount) as icredits
        from dbo.accounts2
        inner join dbo.Vchdet on dbo.accounts2.Id=dbo.Vchdet.AcId
        inner join dbo.VchMain on dbo.VchMain.Id=dbo.Vchdet.MainId
        and dbo.Vchdet.Amount>0 and dbo.vchmain.vchdate>='".$start_date."' and dbo.vchmain.vchdate<='".$end_date."'  ".$costcenter_division_query."
        group by dbo.accounts2.id) as B
        on A.id=B.id
        
        update dbo.accounts2
        set dbo.accounts2.fc_opbal=0
        from dbo.accounts2
        where dbo.accounts2.fc_opbal is null
        
        update dbo.accounts2
        set dbo.accounts2.fc_debits=0
        from dbo.accounts2
        where dbo.accounts2.fc_debits is null
        
        update dbo.accounts2
        set dbo.accounts2.fc_credits=0
        from dbo.accounts2
        where dbo.accounts2.fc_credits is null
        
        update dbo.accounts2
        set dbo.accounts2.fc_bal=fc_opbal+Debits+Credits
        from dbo.accounts2
        "; 
 
 
        DB::statement( $sqlstatement);

    }




  }



  public function getAllAccountTotals(){

     if(!empty(Cache::get($this->user_id."_account_totals_data"))){

        $account_totals_data_json=Cache::get($this->user_id."_account_totals_data");

        $account_totals_data_array=json_decode( $account_totals_data_json,true);
        $this->accounts_column_data= $account_totals_data_array;
  
     }
     else{
        $accounts=Account2::select('Id', 'ACName','parentname' ,'G-A','OpBal','Debits','Credits','Bal','Fc_OpBal','Fc_Debits','Fc_Credits','Fc_Bal')->orderBy('Id','asc')->get();

        $accounts_column_data=array();

        foreach(    $accounts as     $account_detail){

            $account_g_or_a= trim($account_detail->{'G-A'});

            $account_name=  $account_detail->ACName;

            $parent_name=$account_detail->parentname;
    
            if(empty($account_detail->OpBal)){
                $opening_debitbalance=0; 
                $opening_creditbalance=0;
    
            }
            else if($account_detail->OpBal>0){
                $opening_debitbalance=  $account_detail->OpBal; 
                $opening_creditbalance=0;
            }
            else{
    
                $opening_debitbalance= 0; 
                
                $opening_creditbalance=$account_detail->OpBal  ;

                $opening_creditbalance=(  $opening_creditbalance<0?($opening_creditbalance*-1):$opening_creditbalance);
 
    
            }
    
            $total_debit= (empty($account_detail->Debits)?0:$account_detail->Debits);
            $total_credit=  (empty($account_detail->Credits)?0:$account_detail->Credits);

            $total_credit= ( $total_credit<0?($total_credit*-1):$total_credit);
 
            if(empty($account_detail->Bal)){
                $closingdebitbalance=0;
                $closingcreditbalance=0;
            }
            else if(  $account_detail->Bal>0){
                $closingdebitbalance= $account_detail->Bal;
                $closingcreditbalance=0;
            }
            else{
    
                $closingdebitbalance=0;
                $closingcreditbalance= $account_detail->Bal ;
                $closingcreditbalance=(  $closingcreditbalance<0?($closingcreditbalance*-1):$closingcreditbalance);
 
            } 
            
        if($this->show_foreign_currency==0){
            $fcamt_opening_debitbalance=0;
            $fcamt_opening_creditbalance=0;
            $fcamt_total_debit=0;
            $fcamt_total_credit=0;
            $fcamt_closing_debit_balance=0;
            $fcamt_closing_credit_balance=0;
        }
        else{ 

            if(empty( $account_detail->Fc_OpBal)){
                $fcamt_opening_debitbalance= 0;
                $fcamt_opening_creditbalance= 0;

            }
            else if($account_detail->Fc_OpBal>0){
                $fcamt_opening_debitbalance=$account_detail->Fc_OpBal;
                $fcamt_opening_creditbalance= 0; 
            }
            else{
                $fcamt_opening_debitbalance=0;
                $fcamt_opening_creditbalance= $account_detail->Fc_OpBal ; 

                $fcamt_opening_creditbalance=( $fcamt_opening_creditbalance<0?($fcamt_opening_creditbalance*-1):$fcamt_opening_creditbalance);
 
                }

            $fcamt_total_debit=(empty($account_detail->Fc_Debits)?0:$account_detail->Fc_Debits);

            $fcamt_total_credit= (empty($account_detail->Fc_Credits)?0:$account_detail->Fc_Credits);

            if( $fcamt_total_credit<0){
                $fcamt_total_credit=$fcamt_total_credit*(-1);
            }

            if(empty( $account_detail->Fc_Bal)){
                $fcamt_closing_debit_balance=0;
                $fcamt_closing_credit_balance=0;
            }
            else if($account_detail->Fc_Bal>0){
                $fcamt_closing_debit_balance=$account_detail->Fc_Bal;
                $fcamt_closing_credit_balance=0;
            }
            else{
                $fcamt_closing_debit_balance=0;
                $fcamt_closing_credit_balance=$account_detail->Fc_Bal ;

                if($fcamt_closing_credit_balance<0){
                    $fcamt_closing_credit_balance=$fcamt_closing_credit_balance*(-1);
                }

            } 
        }
 

        $single_account_detail=array('account_id'=>$account_detail->Id ,'account_name'=> $account_name ,'parent_name'=> $parent_name ,'opening_debitbalance'=> $opening_debitbalance,'opening_creditbalance'=>   $opening_creditbalance ,'total_debit'=>    $total_debit,'total_credit'=> $total_credit,'closing_debit_balance'=>   $closingdebitbalance,'closing_credit_balance'=>   $closingcreditbalance ,
        'fcamt_opening_debitbalance'=>$fcamt_opening_debitbalance , 'fcamt_opening_creditbalance'=> $fcamt_opening_creditbalance ,'fcamt_total_debit'=>   $fcamt_total_debit,'fcamt_total_credit'=>    $fcamt_total_credit,'fcamt_closing_debit_balance'=> $fcamt_closing_debit_balance,'fcamt_closing_credit_balance'=>$fcamt_closing_credit_balance
        );

        $account_id=(int)trim($account_detail->Id);
        
        $accounts_column_data[$account_id]=array('account_detail'=>  $single_account_detail,'account_g_or_a'=>  $account_g_or_a);
         
        }
 
        Cache::put($this->user_id."_account_totals_data",json_encode($accounts_column_data),10800);
 
        $this->accounts_column_data=$accounts_column_data; 

        
        
     }
 
 
  }



  public function getGeneralLedger($accountid, $fromdate, $todate, $costcenter, $division){

                $sql = new VchMain();
                $from_date = date('Y-m-d', strtotime($fromdate));
                $to_date = date('Y-m-d', strtotime($todate));

                $sql = $sql->select('VchMain.Id', 'VchMain.VchDate', 'VchMain.VchNo', 'VchMain.Naration', 'VchMain.chq_no', 'VchMain.ch_status', 'VchMain.cl_date', 'VchMain.fcexrate', 'VchMain.dept', 'VchMain.executive', 'VchMain.projid', 'VchMain.fccur', 'VchMain.fcexrate', 'Vchdet.Amount as vcAmount', 'Vchdet.FCamt as FcFCamt', 'Vchdet.Costcentre as Costcentre', 'Vchdet.Narration as Narration', 'accounts.Id as AcId', 'accounts.ACName as ACName', 'Costcentre.Name as costName', 'Division.division as DivisionName', 'SalesMen.Name as exeName' )
                ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
                ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
                ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id") 
                ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
                ->leftjoin("Division",  "Vchdet.division", "=", "Division.Id")
                ->orderBy("VchMain.VchDate", 'ASC')
                ->where('Vchdet.AcId', '=', $accountid)
                ->when(!empty($costcenter),function($query)use($costcenter){
                    $query->where('Vchdet.Costcentre',$costcenter);
                })
                ->when(!empty($division),function($query)use($division){
                    $query->where('Vchdet.division',$division);
                })
                ->whereDate("VchMain.VchDate", ">=", $from_date)
                ->whereDate("VchMain.VchDate", "<=", $to_date);

                $data = $sql->get();

                $data_array=$data->toArray();

                
                $particulars_array=array();

                $main_ids=array_column(    $data_array,'Id');
 
                $main_ids_chunks=array_chunk(   $main_ids,40);

 
                // fetch particulars using main id 


                $sql = new VchDet();

                $particulars_array=array();

                foreach(  $main_ids_chunks as   $main_ids_chunk){
 
                        $main_datas = $sql->select('VchMain.*', 'Vchdet.Amount as vcAmount', 'Vchdet.FCamt as FcFCamt', 'Vchdet.Costcentre as Costcentre', 'Vchdet.Narration as Narration', 'accounts.ACName as ACName')
                        ->Join("VchMain", "VchMain.Id", "=", "Vchdet.MainId")
                        ->Join("accounts", "Vchdet.AcId", "=", "accounts.Id")
                        ->where("Vchdet.AcId", "!=", $accountid)
                        ->whereIn("Vchdet.MainId", $main_ids_chunk)
                        ->get();



                        foreach(   $main_datas as    $main_data){

                            if(!array_key_exists($main_data->Id,$particulars_array)){
                                $particulars_array[$main_data->Id]=""; 
                            }

                            if ($main_data->vcAmount < 0) {
                                $amount = abs($main_data->vcAmount) . " CR ";
                            } else {
                                $amount = abs($main_data->vcAmount) . " DR ";
                            }
                            $narration = isset($main_data->Naration) ? $main_data->Naration : "";
                            $particulars_array[$main_data->Id]= $particulars_array[$main_data->Id].str_replace('&','and',$main_data->ACName . " " . $amount . '<br>' ) ;
 
                        }
 
                }

                $account_detail= Account::where('Id',$accountid)->select('ACName','G-A as account_type')->first();

               $account_name= $account_detail['ACName'];

               $account_type= $account_detail['account_type'];

               $account_name= str_replace("&","and",$account_name);
  
                $response=array("data"=>$data,"count"=>count(    $data_array),'particulars'=>$particulars_array,'account_name'=> $account_name ,'account_type'=>  $account_type);


                return $response;

  }



  public function getOpeningBalances($account_ids, $fromdate){
                
                $sql = new VchMain();

                $data_collection=new Collection([]);


                $account_ids_chunks=array_chunk($account_ids,10);


                foreach(  $account_ids_chunks as   $account_ids_chunk){

                    $datas = $sql->select('Vchdet.Amount as vcAmount', 'Vchdet.FCamt as fc_amount',"Vchdet.AcId as account_id")
                    ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
                    ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
                    ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
                    ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
                    ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
                    ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")
                    ->orderBy("VchMain.VchDate", 'ASC')
                    ->whereIn("Vchdet.AcId",$account_ids_chunk)
                    ->whereDate("VchMain.VchDate", "<", $fromdate)
                    ->get();

                    $data_collection= $data_collection->merge(    $datas );

                }
 

                $result_data=array();


                foreach($account_ids as $account_id){

                    $result_data[$account_id]['openingbalance'] =0;

                    $result_data[$account_id]['OpeningFCbalance']=0;

                }
 
 
            foreach (   $data_collection as $key => $value) {
                $result_data[$value->account_id]['openingbalance'] += (float)$value->vcAmount;
                $result_data[$value->account_id]['OpeningFCbalance'] += (float)$value->fc_amount;
            }
            return $result_data;


  }



  public function getClosingBalances($account_ids,$fromdate,$todate){


    $sql = new VchMain(); 


    $account_ids_chunks=array_chunk($account_ids,15);

    $all_data=new Collection([]);
    foreach($account_ids_chunks as $account_ids_chunk){

                
            $data = $sql->select('Vchdet.Amount as vcAmount', 'Vchdet.FCamt as fc_amount', "Vchdet.AcId as account_id")
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")
            ->orderBy("VchMain.VchDate", 'ASC')
            ->whereIn("Vchdet.AcId", $account_ids_chunk)
            ->whereDate("VchMain.VchDate", ">=", $fromdate)
            ->whereDate("VchMain.VchDate", "<=", $todate)
            ->get(); 
            $all_data= $all_data->merge(   $data);

    }


    $result_array=array();


    foreach($account_ids as $account_id){

        $result_array[$account_id]['Closingbalance'] = 0;
        
        $result_array[$account_id]['ClosingFCbalance'] = 0;
        
        $result_array[$account_id]['ClosingCreditbalance'] = 0;
 
        $result_array[$account_id]['ClosingDebitbalance'] = 0;

    }
 
    foreach ($all_data as $key => $value) {
        $result_array[$value->account_id]['Closingbalance'] += (int)$value->vcAmount;
        $result_array[$value->account_id]['ClosingFCbalance'] += (int)$value->fc_amount;

        $result_array[$value->account_id]['ClosingCreditbalance'] += ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
        $result_array[$value->account_id]['ClosingDebitbalance'] += ($value->vcAmount > 0) ? $value->vcAmount : "0.00";
    }
 
    return $result_array;
 
  }


  public function paginate($all_inputs,$routename,$items, $perPage = 5, $page = null, $options = [])
  {
      $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
      $items = $items instanceof Collection ? $items : Collection::make($items);
     $pagination= new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
     $pagination->setPath( route($routename, $all_inputs));
      return    $pagination;
  }
 
  public function getOpeningBalance($account_id, $fromdate,$costcenter,$division){
                            
                $sql = new VchMain();

       
                  $datas = $sql->select('Vchdet.Amount as vcAmount', 'Vchdet.FCamt as fc_amount',"Vchdet.AcId as account_id")
                    ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
                    ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
                    ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
                    ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
                    ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
                    ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")
                    ->orderBy("VchMain.VchDate", 'ASC')
                    ->where("Vchdet.AcId",$account_id)
                    ->when(!empty($costcenter),function($query)use($costcenter){
                        $query->where("Vchdet.Costcentre",$costcenter);
                    })
                    ->when(!empty($division),function($query)use($division){
                        $query->where("Vchdet.division",$division);
                    })
                    ->whereDate("VchMain.VchDate", "<", $fromdate)
                    ->get();
 

                $result_data=array();
                $result_data['openingbalance'] =0;
                $result_data['OpeningFCbalance']=0;
 
            foreach (    $datas as $key => $value) {
                $result_data['openingbalance'] += (float)$value->vcAmount;
                $result_data['OpeningFCbalance'] += (float)$value->fc_amount;
            }
            return $result_data;


        }



        

  public function getClosingBalance($account_opening_balances,$account_id,$fromdate,$todate,$costcenter,$division){


    $sql = new VchMain(); 
  
                
            $datas = $sql->select('Vchdet.Amount as vcAmount', 'Vchdet.FCamt as fc_amount', "Vchdet.AcId as account_id")
            ->rightJoin("Vchdet", "VchMain.Id", "=", "Vchdet.MainId")
            ->leftJoin("accounts", "Vchdet.AcId", "=", "accounts.Id")
            ->leftjoin("Costcentre",  "Vchdet.Costcentre", "=", "Costcentre.Id")
            ->leftjoin("Department",  "VchMain.dept", "=", "Department.Id")
            ->leftjoin("SalesMen",  "VchMain.executive", "=", "SalesMen.Id")
            ->leftjoin("Project",  "VchMain.projid", "=", "Project.Id")
            ->orderBy("VchMain.VchDate", 'ASC')
            ->where("Vchdet.AcId",$account_id)
            ->when(!empty($costcenter),function($query)use($costcenter){
                $query->where('Vchdet.Costcentre',$costcenter);
            })
            ->when(!empty($division),function($query)use($division){
                $query->where('Vchdet.division', $division);
            })
            ->whereDate("VchMain.VchDate", ">=", $fromdate)
            ->whereDate("VchMain.VchDate", "<=", $todate)
            ->get();   


    $result_array=array();

    
    $result_array['Closingbalance'] = 0;
        
    $result_array['ClosingFCbalance'] = 0;
    
    $result_array['ClosingCreditbalance'] = 0;

    $result_array['ClosingDebitbalance'] = 0;


 
    foreach (            $datas  as $key => $value) {
        $result_array['Closingbalance'] += (float)$value->vcAmount;
        $result_array['ClosingFCbalance'] += (float)$value->fc_amount;

        $result_array['ClosingCreditbalance'] += ($value->vcAmount < 0) ? $value->vcAmount : "0.00";
        $result_array['ClosingDebitbalance'] += ($value->vcAmount > 0) ? $value->vcAmount : "0.00";
    }

    $result_array['Closingbalance'] +=(float)$account_opening_balances['openingbalance'] ;
    $result_array['ClosingFCbalance'] +=(float)$account_opening_balances['OpeningFCbalance'] ;
    return $result_array;
 
  }


  public function filterOutNotUsedAccountIds($account_ids,$fromdate,$todate){

        $account_id_chunks=array_chunk($account_ids,50);

        $all_accounts=array(); 

        foreach( $account_id_chunks as  $account_id_chunk){
            $filtered_accountids= VchMain::join('Vchdet','VchMain.Id', '=','Vchdet.MainId')->whereIn('Vchdet.AcId',$account_id_chunk)
            ->where('VchMain.VchDate','>=',$fromdate)->where('VchMain.VchDate','<=',$todate)->groupby('Vchdet.AcId')->select('Vchdet.AcId as account_id')->pluck('account_id')->toArray();
              $all_accounts=array_merge(  $all_accounts,$filtered_accountids); 

        }
  
 
         return      $all_accounts;

  }


  public function clearAllReportsCacheInputs(){

    $user_id=Auth::user()->id; 


    if(!empty(Cache::get($user_id."_sub_ledger_inputs"))){
        Cache::forget( $user_id."_sub_ledger_inputs");
    }

    

    if(!empty(Cache::get($user_id."_general_ledger_inputs"))){
    Cache::forget( $user_id."_general_ledger_inputs");

    }


    if(!empty(Cache::get($user_id."_tree_style_trial_balances_input"))){
        Cache::forget( $user_id."_tree_style_trial_balances_input");
     }

     

    if(!empty(Cache::get($user_id."_tree_style_trial_balances_open_childaccounts"))){
        Cache::forget( $user_id."_tree_style_trial_balances_open_childaccounts");
    }
 
    if(!empty(Cache::get($user_id."_trial_balances_input"))){
        Cache::forget( $user_id."_trial_balances_input");
    
        } 

        
    if(!empty(Cache::get($user_id."_account_totals_data"))){
        Cache::forget( $user_id."_account_totals_data");
    }


    if(!empty(Cache::get($user_id."_account_tree_data"))){
        Cache::forget( $user_id."_account_tree_data");
    }
 
    if(!empty(Cache::get($user_id."_balance_report"))){
        Cache::forget($user_id."_balance_report"); 
        }

    

    if(!empty( Cache::get($user_id."_whatsapp_custom_fields"))){
        Cache::forget($user_id."_whatsapp_custom_fields");
    }


       $company_dbs=  Company::pluck('db_name')->toArray();
       foreach(   $company_dbs as    $company_db){
        Cache::forget( $company_db."_account_tree_data");
       } 

        if(!empty(Cache::get($user_id."_cash_book_inputs"))){
            Cache::forget($user_id."_cash_book_inputs"); 
            }

            
        if(!empty(Cache::get($user_id."_bank_book_inputs"))){
            Cache::forget($user_id."_bank_book_inputs"); 
            } 
            
            Cache::forget(   $user_id."_register_report");


            if(!empty(  Cache::get(   $user_id."_register_report"))){
                Cache::forget(   $user_id."_register_report");
            }
 
            if(!empty(Cache::get($user_id."_pending_documents_input"))){
                Cache::forget($user_id."_pending_documents_input");
            }
       
            if(!empty(Cache::get($user_id."_salesman_report"))){
                Cache::forget($user_id."_salesman_report");
            }

            if(!empty(Cache::get($user_id."_product_tree_data"))){
                Cache::forget( $user_id."_product_tree_data");

            }
             
            if(!empty(Cache::get($user_id."_stock_ledger"))){
                Cache::forget( $user_id."_stock_ledger");

            }


            if(!empty(Cache::get($user_id."_stock_statement"))){
                Cache::forget( $user_id."_stock_statement");

            }

            
            if(!empty(Cache::get($user_id."_stock_movement"))){
                Cache::forget( $user_id."_stock_movement");

            }

            if(!empty(Cache::get($user_id."_stock_movement_slow"))){
                Cache::forget( $user_id."_stock_movement_slow");

            }

            if(!empty(Cache::get($user_id."_stock_movement_fast"))){
                Cache::forget( $user_id."_stock_movement_fast");

            }


            if(!empty(Cache::get($user_id."_opening_stockregister"))){
                Cache::forget( $user_id."_opening_stockregister");

            }


            if(!empty(Cache::get($user_id."_reorder_report"))){
                Cache::forget( $user_id."_reorder_report");

            } 
            
  
                
        
  }

 
  
  public function getAccountClosingBalancesTotals(){
 
    $account_result=$this->getSingleAccountTotal($this->account_id);

    $account_detail=  $account_result['account_detail'];
 
    $closing_debit_balance= $account_detail['closing_debit_balance'];
    $closing_credit_balance=$account_detail['closing_credit_balance'];
 
    $fcamt_closing_debit_balance= $account_detail['fcamt_closing_debit_balance'];
    $fcamt_closing_credit_balance=$account_detail['fcamt_closing_credit_balance'];


    if($this->add_subtotals==true && array_key_exists($this->account_id,$this->account_tree_data)==true &&  $account_result['account_g_or_a']=="G"){

      $result= $this->getAllChildrenAccountTotals(); 
 

      $closing_debit_balance=   $closing_debit_balance+$result['closing_debit_balance'];

      $closing_credit_balance= $closing_credit_balance+$result['closing_credit_balance'];
 
      $fcamt_closing_debit_balance=   $fcamt_closing_debit_balance+$result['fcamt_closing_debit_balance'];
      $fcamt_closing_credit_balance=   $fcamt_closing_credit_balance+$result['fcamt_closing_credit_balance'];

    }

     $diff_account_closing=round($closing_debit_balance,2)-round($closing_credit_balance,2) ;
     $diff_account_closing=round($diff_account_closing,2);

   


    $diff_account_closing=round($closing_debit_balance,2)-round($closing_credit_balance,2) ;
    $diff_account_closing=round($diff_account_closing,2);

  
    $diff_fcamt_account_closing=round($fcamt_closing_debit_balance,2)-round($fcamt_closing_credit_balance,2);

    $diff_fcamt_account_closing=round($diff_fcamt_account_closing,2);

     
    return array( 'account_name'=> $account_detail['account_name'], 'account_id'=>$this->account_id , 'total_balances'=>   $diff_account_closing ,'fcamt_total_balances'=>  $diff_fcamt_account_closing);

}


public function getEmailAndWhatsappNumberFromAccount(){

//    $email_and_whatsapp= Account::join('customers','accounts.id','=','customers.Acc_id')->where('accounts.id',$this->account_id)->select('customers.whatsappno as whatsapp_no','customers.Email_id as email_id')->first()->toArray();
 
          $result= DB::select("select c.despatchemailid as 'customer_emailid', c.whatsappno as 'customer_whatsappno', s1.emailid as 'salesman_emailid', s1.salesmanphone as 'salesman_whatsappno' from dbo.accounts a inner join dbo.customers c on a.id=c.Acc_id inner join dbo.salesmen2 s2 on c.salesmen3=s2.Id inner join dbo.SalesMen s1 on s2.Name+'%'= s1.name+'%'
               where a.id=".$this->account_id);
               
        $table_data=json_decode(json_encode(   $result),true);   
 
        return   $table_data; 

}



public function getAccountsSequenctially(){
 
    $data=Account::whereNotNull('Parent2')->where('Id','!=' , DB::Raw("CONVERT(INT,Parent2)") )->select('Id as id','Parent2 as parent' ,'ACName as account_name'  )->get()->toArray();

  
    $tree = new Tree($data );
    
    $allNodes = $tree->getNodes();
     
     $all_ids=array_column($allNodes,'id');
 

     $parent_accounts=array();

     $sended_accounts=$this->account_ids;
 

     foreach($this->account_ids as $single_parent){
        $single_parent=  (int) $single_parent;
       
        array_push(   $parent_accounts ,  $single_parent);
     }

     if(count($this->account_ids)==0){

        return array_values($all_ids); 
     }

  
     $not_allowed=array_diff(    $all_ids, $this->account_ids);


     foreach(    $not_allowed as $single_not_allowed){

        $pos = array_search($single_not_allowed,    $all_ids);
 
        if ($pos !== false) {
          
            unset($all_ids[$pos]);
        }
   

     } 
     return array_values($all_ids);


 
}

public function getAllChildAccountsSequentially($account_id){

    array_push($this->accounts_marked,$account_id);

     $childaccount_ids=Account::where( DB::raw("accounts.Parent2") ,$account_id)->pluck('Id')->toArray();


     return  $childaccount_ids;
 
    // if(count($childaccount_ids)>0){
    //     foreach($childaccount_ids as $childaccount_id){
         
    //         array_push($this->accounts_sequential,$childaccount_id);
           
    //         $this->getAllChildAccountsSequentially($childaccount_id);
    
    //     }
    // }
   
 
}


public function getTreeStyleAccountDetail($account_id,$open_childaccounts=false){
 
    if($open_childaccounts==false){
        $tree_style_input= Cache::get($this->user_id."_tree_style_trial_balances_input");
    }
    else{
        $tree_style_input= Cache::get($this->user_id."_tree_style_trial_balances_open_childaccounts");
    }


    $tree_style_input_array=json_decode(  $tree_style_input,true);

    return  $tree_style_input_array['all_balances'][$account_id];

}


public function getTrialAccountDetail($account_id,$open_childaccounts){

    if($open_childaccounts==false){
        $trial_input= Cache::get($this->user_id."_trial_balances_input");
    }
    else{
        $trial_input= Cache::get($this->user_id."_trial_balances_open_childaccounts");  
    }
 
    $trial_input_array=json_decode(  $trial_input,true);
 
 
    return  $trial_input_array['all_balances'][$account_id];
}


public function getTreeStyleDrilldownAccountDetail($account_id){

    
    $trial_input= Cache::get(Auth::user()->id."_tree_style_trial_balances_open_childaccounts");
    
    $trial_input_array=json_decode(  $trial_input,true);

    return  $trial_input_array['all_balances'][$account_id];

}


public function deleteAllCreatedFilesAtFolderAfter24Hour($foldername){
       
                    $files = Storage::disk('public')->allFiles($foldername);
                    $fileNames = array_map(function($file){
                        return basename($file); // remove the folder name
                    }, $files);


                    foreach(   $fileNames as    $fileName){
                        $filepath=$foldername."/".$fileName;

                        $created_time=  Storage::disk('public')->lastModified( $filepath); 
                        
                        $time_limit=  $created_time+(24*60*60); 
                
                        $current_time=time();
                
                        if(  $current_time>$time_limit){ 
                            Storage::disk('public')->delete(   $filepath); 

                        } 
                    }
                
            }

 
public function getPandLAccountDetail($account_id){


    $p_and_l_inputs_json=Cache::get($this->user_id."_p_and_l_report");

    $p_and_l_inputs_array=json_decode(    $p_and_l_inputs_json,true);
 
    return   $p_and_l_inputs_array['all_balances'][$account_id];

}

public function getBalanceSheetAccountDetail($account_id){

    $balance_inputs_json=Cache::get($this->user_id."_balance_report");

    $balance_inputs_array=json_decode(    $balance_inputs_json,true);
 
    return   $balance_inputs_array['all_balances'][$account_id];

}


public function getProfitLossUsingExpenseAndIncome(){

    $this->account_id=4;
    $expense_account_detail= $this->getAccountTotals();

    $expense_amt=$expense_account_detail['closing_debit_balance']-$expense_account_detail['closing_credit_balance'];
    $expense_fc_amt=  $expense_account_detail['fcamt_closing_debit_balance']-$expense_account_detail['fcamt_closing_credit_balance'];
 
    $this->account_id=3;
    $income_account_detail= $this->getAccountTotals(); 

    $income_amt=$income_account_detail['closing_credit_balance']-$income_account_detail['closing_debit_balance'];
    $income_fc_amt=$income_account_detail['fcamt_closing_credit_balance']-$income_account_detail['fcamt_closing_debit_balance'];
 
    $total_profit_loss=   $income_amt-   $expense_amt;

    $total_fc_profit_loss=   $income_fc_amt-    $expense_fc_amt;

    return array('profit_loss'=>round($total_profit_loss,2),'fc_profit_loss'=> round($total_fc_profit_loss,2));
    
}
 

public function searchPendingDocuments($searchfields=array(),$searchconditions=array(),$searchvals=array(),$searchoperator=array() ){

    if(count( $searchfields)>0){
        $hassearchfields=true;
    }
    else{
        $hassearchfields=false;
    }


     $pending_document_query="select dbo.tbl_link_data.id, dbo.tbl_link_data.doc_date, dbo.tbl_link_data.doc_no, dbo.location.location, dbo.customers.cust_id, dbo.salesmen.name, dbo.Product_master.product, 
     dbo.tbl_link_data.qty, dbo.tbl_link_data.rate, dbo.tbl_link_data.used_qty, (dbo.tbl_link_data.qty-dbo.tbl_link_data.used_qty) as 'Bal Qty', DATEDIFF(day,doc_date, getdate()) as 'Ageing Days'
     from dbo.tbl_link_data
     left join dbo.Location on dbo.tbl_link_data.location=dbo.location.id
     left join dbo.customers on dbo.tbl_link_data.cust_id=dbo.customers.id
     left join dbo.Product_master on dbo.tbl_link_data.product=dbo.Product_master.id
     left join dbo.salesmen on dbo.tbl_link_data.salesman=dbo.salesmen.id
     where used_qty<qty  " ;

 

            
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
 

public function getPendingDocumentsData($searchfields,$searchconditions,$searchvals,$searchoperator){

                $index=0;
                // array('id','doc_date','doc_no','location','cust_id','name','product','qty','rate','used_qty','Bal Qty','Ageing Days');


                foreach($searchfields as $searchfield){

                    if($searchfield=='id'){
                        $searchfields[$index]='tbl_link_data.id';
                    }
                    else if($searchfield=='doc_date'){
                        $searchfields[$index]='tbl_link_data.doc_date';
                        $searchvals[$index]=formatDateInYmd( $searchvals[$index]);
                    }
                    else if($searchfield=='cust_id'){
                        $searchfields[$index]='customers.cust_id';
                    } 
                    else if($searchfield=='doc_no'){
                        $searchfields[$index]='tbl_link_data.doc_no';
                    }
                    else if($searchfield=='location'){
                        $searchfields[$index]='location.location';
                    }
                    else if($searchfield=='name'){
                        $searchfields[$index]='salesmen.name';
                    }
                    else if($searchfield=='product'){
                        $searchfields[$index]='Product_master.product';
                    }
                    else if($searchfield=='qty'){
                        $searchfields[$index]='tbl_link_data.qty';
                    }
                    else if($searchfield=='rate'){
                        $searchfields[$index]='tbl_link_data.rate';
                    }
                    else if($searchfield=='used_qty'){
                        $searchfields[$index]='tbl_link_data.used_qty';
                    }
                      else if($searchfield=='Bal Qty'){
                        $searchfields[$index]='(dbo.tbl_link_data.qty - dbo.tbl_link_data.used_qty)';
                    }
                    else if($searchfield=='Ageing Days'){
                        $searchfields[$index]='DATEDIFF(day, doc_date, getdate())';
                    }
                    
 
                    $index++;
                }

                $hassearchfields=(count($searchfields)>0?true:false);

                

            $transactiondata_array=TblLinkData::leftjoin('dbo.Location','dbo.tbl_link_data.location','=','dbo.location.id')
            ->leftjoin('dbo.customers','dbo.tbl_link_data.cust_id','=','dbo.customers.id')
            ->leftjoin('dbo.Product_master','dbo.tbl_link_data.product','=','dbo.Product_master.id')
            ->leftjoin('dbo.salesmen','dbo.tbl_link_data.salesman','=','dbo.salesmen.id')
            ->where(DB::raw('CONVERT(NUMERIC(18, 2),dbo.tbl_link_data.used_qty)'),'<',DB::raw('convert(NUMERIC(18, 2),dbo.tbl_link_data.qty)'))
            ->when( $hassearchfields,function($query)use($searchfields,$searchconditions,$searchvals,$searchoperator){

                $index=0;
                foreach($searchfields as $searchfield){

                    $searchconditiongiven=$searchconditions[$index];

                    $searchvalgiven=$searchvals[$index];

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

        })->orderby('dbo.tbl_link_data.id','desc')->select('dbo.tbl_link_data.id', 'dbo.tbl_link_data.doc_date', 'dbo.tbl_link_data.doc_no', 'dbo.location.location', 'dbo.customers.cust_id', 'dbo.salesmen.name', 'dbo.Product_master.product', 
            'dbo.tbl_link_data.qty', 'dbo.tbl_link_data.rate', 'dbo.tbl_link_data.used_qty', DB::raw("(dbo.tbl_link_data.qty-dbo.tbl_link_data.used_qty) as 'Bal Qty'"), DB::raw("DATEDIFF(day,doc_date, getdate()) as 'Ageing Days'"));
 
            $transactiondata_array=$transactiondata_array->get()->toArray();
 

            return    $transactiondata_array;
}


    public function getSearchedFunction4TableFieldDetails(){

        $function4fields=FieldsMaster::where('Field_Function',4)->where('field_name','like',$this->fieldname)->groupby('from_table','Scr Field','Display Field')->select('from_table','Scr Field','Display Field')->get()->toArray();
  
        $all_field_values=array(); 

        foreach(  $function4fields as   $function4field){

            $found_records=DB::table($function4field['from_table'])->where($function4field['Display Field'],'Like','%'.$this->searchterm.'%')->orderby('text','asc')->select(DB::raw("cast(".$function4field['Scr Field']." as varchar(300)) as id"),DB::raw("cast(".$function4field['Display Field']." as varchar(300)) as text"))->get();
            $found_records= json_decode(  $found_records ,true); 

            $all_field_values=array_merge(    $all_field_values,   $found_records);
           
        } 

        $all_field_values= array_slice(  $all_field_values,0,5);
 
        return    $all_field_values;

    }


    public function getSearchedFunction4TableUsingValue(){


        
        $table_names=FieldsMaster::where('Field_Function',4)->where('field_name','like',$this->fieldname)->pluck('Table_Name')->toArray();


        $found_tables=array();

        foreach( $table_names as  $table_name){
 

            if(Schema::hasTable($table_name)==false){
                continue;
            }

           $value_exists=  DB::table($table_name)->where($this->fieldname,$this->fieldvalue)->exists();

            if(   $value_exists==true){
                array_push( $found_tables,$table_name);
            }
     

        }
        
        return       $found_tables;
    }


    
    public function getSearchedFunction4TableFieldValueText(){

        $function4field=FieldsMaster::where('Field_Function',4)->where('Table_Name',$this->tablename)->where('field_name',$this->fieldname)->select('from_table','Scr Field','Display Field')->first();


        $displaytext= DB::table($function4field['from_table'])->where($function4field['Scr Field'],$this->fieldvalue)->value($function4field['Display Field']);
 
        return   $displaytext;
    }

    public function getSearchedTableDataFromFieldQuery($searchfields, $searchconditions, $searchvals,    $searchoperator){

        $has_searchfields=(count($searchfields)>0?true:false);

        $detailtablename=TableMaster::where('Parent Table',$this->tablename)->value('Table_Name');

        $tblname=$this->tablename; 

        $hassearchfields=(count($searchfields)>0?true:false);


        $index=0;

        foreach($searchfields as $searchfield){ 
            $searchfields[  $index]=$this->tablename.".".$searchfield; 
            $index++;

        }
 

        $table_data=DB::table($this->tablename)->when(!empty( $detailtablename),function($query)use(  $tblname ,$detailtablename){

            $query->join($detailtablename,$tblname.".id",'=',$detailtablename.".fk_Id");

        })
        ->when( $hassearchfields,function($query)use($searchfields,$searchconditions,$searchvals,$searchoperator){
 
            $index=0;
            foreach($searchfields as $searchfield){

                $searchconditiongiven=$searchconditions[$index];

                $searchvalgiven=$searchvals[$index];

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
       
    })->where($this->tablename.".".$this->fieldname,$this->fieldvalue)->orderby($this->tablename.".Id",'desc');

        

        // if($this->exceldownload==true){
        //     $table_data=$table_data->get();

        // }
        // else{
        //     $table_data=$table_data->paginate(10);
        // }
 

        return $table_data;
    }


    public function getSalesmanDetailHeaderFields(){

   
        $detailtablename=TableMaster::where('Parent Table',$this->tablename)->value('Table_Name');
 

        if(!empty(   $detailtablename)){

            $header_fields=FieldsMaster::where('Table_Name',$this->tablename)->where('Tab_Id','<>','None')->select( 'Field_Function','fld_label','Field_Name')->get()->toArray() ;
          
              $det_fields=array('rate','quantity','disc','product'); 

              $detail_fields=FieldsMaster::where('Table_Name',$detailtablename)->whereIn('Field_Name',       $det_fields)->orderby('Id','asc')->select( 'Field_Function','fld_label','Field_Name')->get()->toArray()  ;
 
              $header_fields=array_merge(  $header_fields,  $detail_fields); 

 
        } 
        else{

            $header_fields=FieldsMaster::where('Table_Name',$this->tablename)->where('Tab_Id','<>','None')->select( 'Field_Function','fld_label','Field_Name')->get()->toArray();
          

        } 
        return   $header_fields;

    }



    public function getOpeningProductStock(){

        $location_id=$this->location_id;

      $openingstock=  StockDet::where('docdate','<',$this->start_date.' 00:00:00.000')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){

        $query->where('location',$location_id);

      })->sum('Qty');

    

    //   $openingstock=  StockDet::where('docno','LIKE','op-%')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){

    //     $query->where('location',$location_id);

    //   })->sum('Qty');

      if(empty( $openingstock)){
        $openingstock=0;
      }

        return   $openingstock;
    }


    public function getClosingProductStock(){

        $location_id=$this->location_id;

        $closingstock=  StockDet::where('docdate','<=',$this->end_date.' 23:59:59.999')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){
  
          $query->where('location',$location_id);
  
        })->sum('Qty');
  
  
        if(empty( $closingstock)){
          $closingstock=0;
        }
  
          return   $closingstock;

    }


    public function getProductStockDetails($opening_stock){
        
        $location_id=$this->location_id; 

        // where('docdate','>=',$this->start_date.' 00:00:00.000')->where('docdate','<=',$this->end_date.' 23:59:59.999')->
        // where('docdate','>=',$this->start_date.' 00:00:00.000')->where('docdate','<=',$this->end_date.' 23:59:59.999')->
        $stockdetails=  StockDet::where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){
            $query->where('location',$location_id);

        })->where('docno','NOT LIKE','op-%')->select('id','docno','docdate','partyid','Qty','Txn_Name','Pk','CRate')->orderby('docdate','asc')->get()->toArray();
  
  
    //   $stockdetails=  StockDet::where('docdate','<=',$this->end_date.' 23:59:59.999')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){
  
    //     $query->where('location',$location_id);

    //   })->select('docno','docdate','partyid','Qty','Txn_Name','Pk','CRate')->orderby('docdate','asc')->get()->toArray();


      $index=0;

      $stk_formulas=$this->stk_formulas;

      $current_balance=$opening_stock;

      foreach($stockdetails as $stockdetail){

        if(array_key_exists($stockdetail['Txn_Name'] , $stk_formulas)){

            $formula_array=$stk_formulas[$stockdetail['Txn_Name']];

            $this->pk=$stockdetail['Pk'];

            $calc_spec=   $this->getStkFormulaValue($formula_array);
        }
        else{
            $calc_spec='';
        }

        $current_balance=   $current_balance+$stockdetail['Qty'];

        $stockdetails[ $index]['spec_rate']= $calc_spec;

        $stockdetails[ $index]['balance_qty']=   $current_balance; 
 
        $index++;
      }
 
      return $stockdetails;
    }



    public function getAllStkFormulaStringWithTables(){


        $stk_formula_strings= TblStkVal::pluck('stk_formula','table_name')->toArray();

        $all_formulas=array();

        foreach(   $stk_formula_strings as    $stk_formula_string_table=>$stk_formula_string_formula){

       
            $formula=str_replace("*","#",$stk_formula_string_formula);
            $formula=str_replace("+","#",$formula);
            $formula=str_replace("-","#",$formula);
            $formula=str_replace("/","#",$formula);
            $formula=str_replace(")","#",$formula);    
             $formula=str_replace("(","#",$formula);
     
             $formula_items=explode('#',$formula);
     
             $formula_items= array_filter(   $formula_items);
     
     
             $det_table_name='';
     
             $field_names=array();
     
     
             foreach(    $formula_items as     $formula_item){
     
                 if (str_contains( $formula_item, 'IS')) {  
                     $formula_item_string=$formula_item;
     
                     $formula_item_array= explode("_IS_",   $formula_item_string);
     
                     $found_field_name=$formula_item_array[0];
                     $found_table_name= $formula_item_array[1];
                     if(str_contains(    $found_table_name  , '_det') ){
                         $det_table_name=$formula_item_array[1];
                     }
     
                     $field_names[$formula_item]=$found_table_name.".".$found_field_name;
        
                 }
         
     
             }
     
             foreach(  $field_names as $field_key=>$field_val){
                 $stk_formula_string_formula=str_replace($field_key,$field_val,    $stk_formula_string_formula);
             } 
     
             $stk_formula_string_formula="(". $stk_formula_string_formula.")";
     
             $main_table_name=str_replace("_det","",    $det_table_name);

             $all_formulas[ $stk_formula_string_table]=array('main_table'=>    $main_table_name ,'det_table'=> $det_table_name,'formula_string'=> $stk_formula_string_formula);
      
     

        }

        $this->stk_formulas=$all_formulas;
     
        // return   $all_formulas;
    }



    public function getStkFormulaValue($formula_detail){
        $main_table_name=$formula_detail['main_table'];
        $det_table_name=$formula_detail['det_table'];
        $stk_formula_string=$formula_detail['formula_string'];
        
       $calculatedvalue= DB::table( $main_table_name)->join($det_table_name,$main_table_name.".id",'=',  $det_table_name.".fk_Id")->where($det_table_name.".id",$this->pk)->select(DB::raw(  $stk_formula_string." as calcval"))->value('calcval');
 
       if(!empty(  $calculatedvalue)){
        $calculatedvalue=round( $calculatedvalue,2);
       }

       return    $calculatedvalue;

    }


    public function getProductStockDetailAmounts($valuation_method,$opening_qty,$opening_rate ,$stock_details, $purchase_invoice_txn_ids, $sales_invoice_txn_ids){

        $final_amount=round(($opening_qty*$opening_rate),2);
          
 
            $qtys=      array_column($stock_details,'Qty');

            // dd(  $qtys);
            // $qtys=array(10,-30,-50,58);
            if($opening_qty>0){

                array_unshift(   $qtys,$opening_qty);
    
            }
            else{
    
                array_unshift(   $qtys,0);
    
            }

            $doc_nos=array_column($stock_details,'docno');


            $stock_ids=array_column($stock_details,'id');
 
            $balance_qtys=array_column($stock_details,'balance_qty');

            // $balance_qtys=array(20,-10,-60,-2);
 
            if($opening_qty>0){
            array_unshift( $balance_qtys,$opening_qty);
            }
            else{
                array_unshift( $balance_qtys,0);
            }
        
             $crate=array_column($stock_details,'CRate');

            $spec_rate=array_column($stock_details,'spec_rate');

             $amount_rates=array();

             array_push(  $amount_rates,$opening_rate );

            $index=0;
            foreach( $spec_rate as $single_spec_rate){

                $cal_rate=(!empty($single_spec_rate)?$single_spec_rate: $crate[ $index]);
                array_push(     $amount_rates,  $cal_rate);

                $index++;

            }

        //   $amount_rates=array(10, 11,1,1 ,12);


            $this->amount_rates= $amount_rates;

            $txn_names=array_column($stock_details,'Txn_Name');

            $purchases=array();
            
            array_push(  $purchases,true);

            foreach( $txn_names as  $txn_name){

                if(in_array($txn_name, $purchase_invoice_txn_ids)){

                    array_push(  $purchases,true);

                }
                else{
                    
                    array_push(  $purchases,false);
                }

            }

            $sales=array();
            
            array_push(  $sales,true);

            foreach( $txn_names as  $txn_name){

                if(in_array($txn_name, $sales_invoice_txn_ids)){

                    array_push(  $sales,true);

                }
                else{
                    
                    array_push(  $sales,false);
                }

            }

 

            //  $purchases=array(true,true,false ,false,true );
 
            $index=0;
            $final_amounts=array();

            $no_of_items=count(  $qtys);

            $balance_row_qtys=array();
 

            for($i=0;$i<$no_of_items;$i++){

                if($i==0){

                    if($qtys[$i]>0){
                        array_push( $balance_row_qtys,array('bal_qty'=>$opening_qty,'bal_amount'=>round($opening_qty*$opening_rate ,2) ,'processed'=>false));
                    }
                    else{
                        array_push( $balance_row_qtys,array('bal_qty'=>0,'bal_amount'=>0,'processed'=>false)); 
                    }

                    continue;
                }

                array_push( $balance_row_qtys,array('bal_qty'=>0,'bal_amount'=>0,'processed'=>false));

            }

        
           
            $this->balance_row_qtys=$balance_row_qtys;


 
            $balance_qty=$opening_qty;
 

            if($valuation_method=="Fifo" || $valuation_method=="Lifo" ){
                $final_amounts=$this->calculateFinalAmountFromBalanceQtys( $stock_ids,$valuation_method, $qtys,    $amount_rates ,$opening_rate ,$opening_qty, $purchases, $balance_qtys,  $doc_nos);
  
            }
            else{
                $final_amounts= $this->calculateFinalAmountForOther($stock_ids,$valuation_method, $qtys,    $amount_rates ,$opening_rate ,$opening_qty, $purchases,$sales,$balance_qtys);
            }
            return  $final_amounts;
 
 

    }



    public function calculateFinalAmountFromBalanceQtys($stock_ids, $valuation_method, $qtys,  $amount_rates,$opening_rate,$opening_qty, $purchases ,$balance_qtys,$doc_no){
  
      $balance_row_qtys=  $this->balance_row_qtys;
 
      $final_amounts=array(); 
 
      $index=0;

      $last_purchase_rate=$opening_rate;
 
     
      $negative_index='';

      $no_of_rows=count($qtys);

      foreach($qtys as $qty){ 
 
        if($index==0){
            
            array_push($final_amounts,  $balance_row_qtys[$index]['bal_amount']);
            $index++;
            continue;
        }
        

        $current_docno=$doc_no[$index-1];
 

        if( $purchases[$index]==true){

            $last_purchase_rate=$amount_rates[$index];
  
        }

                  $remaining_qty=0;

                  if($balance_qtys[$index]<0){
                  $calc_rate=  $last_purchase_rate;
                  }
                  else    if($balance_qtys[$index]>0 &&   $purchases[$index]==true ){
                    $calc_rate=  $amount_rates[$index];
                    } 
                    else{
                        $calc_rate=  $last_purchase_rate;  
                    }
              

                    // if($balance_qtys[$index]<0){
                    //     $calc_rate=  $last_purchase_rate;
                    // }
                    // else{
                    //     $calc_rate=  $amount_rates[$index];
                    // }
           


                  if($qty>0){ 


                    if(!empty($negative_index)  ||   $negative_index===0){ 

                        if( $valuation_method=="Fifo"){
                            $balance_row_qtys[$negative_index]['bal_qty']=   $balance_row_qtys[$negative_index]['bal_qty']+$qty;
                            $balance_row_qtys[$negative_index]['bal_amount']=  $balance_row_qtys[$negative_index]['bal_qty']*      $calc_rate;
                  
                            if(   $balance_row_qtys[$negative_index]['bal_qty']>=0){
                                $negative_index='';
                            }
                        }
                        else{

                            $new_balance_qty=$balance_row_qtys[$negative_index]['bal_qty']+$qty;

                            if(    $new_balance_qty>0){

                                $balance_row_qtys[$negative_index]['bal_qty']= 0;
                                $balance_row_qtys[$negative_index]['bal_amount']= 0;
                                $negative_index='';
                                $balance_row_qtys[$index]['bal_qty']= $new_balance_qty;
                              
                                $balance_row_qtys[$index]['bal_amount']=   round( $balance_row_qtys[$index]['bal_qty']*    $calc_rate,2);

                      
                            }
                            else{
                                $balance_row_qtys[$negative_index]['bal_qty']=   $balance_row_qtys[$negative_index]['bal_qty']+$qty;
                                $balance_row_qtys[$negative_index]['bal_amount']=  $balance_row_qtys[$negative_index]['bal_qty']*      $calc_rate;
                      
                                if(   $balance_row_qtys[$negative_index]['bal_qty']>=0){
                                    $negative_index='';
                                }
                            } 

                        }

                  
                    }
                    else{ 
 
                        $balance_row_qtys[$index]['bal_qty']=  $balance_row_qtys[$index]['bal_qty']+$qty;
                        $balance_row_qtys[$index]['bal_amount']=round(($qty*     $calc_rate),2);
  

                    }

                    

                  }
                  else{ 

                    $required_qty=$qty*-1;
 

                    if( $valuation_method=="Fifo")
                    {
                            for($i=0;$i<($index+1);$i++){

                              
                               if(   $i<$index && $balance_row_qtys[$i]['bal_qty']==0 )  {
                                     continue;
                               }
                             

                               if($balance_qtys[$i]>0 &&   $purchases[$i]==true){
                                   $calc_rate=  $amount_rates[$i];
                                } 

                                if( $balance_row_qtys[$i]['bal_qty']>0){

                                
                                    if($balance_row_qtys[$i]['bal_qty']>      $required_qty){
 
                                        $balance_row_qtys[$i]['bal_qty']=$balance_row_qtys[$i]['bal_qty']-  $required_qty;


                                        $balance_row_qtys[$i]['bal_amount']=round(($balance_row_qtys[$i]['bal_qty']*     $calc_rate),2);
 
                                 
                                        break;
 
                                    }
                                    else{
                                    
 
                                        $required_qty=   $required_qty-  $balance_row_qtys[$i]['bal_qty'];
 
                                  
                                        $balance_row_qtys[$i]['bal_qty']=0;
                                        $balance_row_qtys[$i]['bal_amount']=0; 

                                        $balance_row_qtys[$i]['processed']=true;
 
                                            

                                    }
 


                                }
                                else{
 

                                    $balance_row_qtys[$i]['bal_qty']=  $balance_row_qtys[$i]['bal_qty']+$required_qty*-1;

                                    $balance_row_qtys[$i]['bal_amount']=    $balance_row_qtys[$i]['bal_qty']*     $calc_rate;

                                    $negative_index=$i; 
 
                                    break;

                                }
 

                            }


                        }
                        else{

                            
                            for($i=$index-1;$i>-1;$i--){

                              
                                if(   $i>0 && $balance_row_qtys[$i]['bal_qty']==0 )  {
                                      continue;
                                }
                              
                                // 
 
                                if( $balance_qtys[$index]>0 &&  $purchases[$i]==true){
                                    $calc_rate=  $amount_rates[$i];
                                 } 

                             
 
                                 if( $balance_row_qtys[$i]['bal_qty']>0){

  

                                     if($balance_row_qtys[$i]['bal_qty']>      $required_qty){
 
  
                                         $balance_row_qtys[$i]['bal_qty']=$balance_row_qtys[$i]['bal_qty']-  $required_qty;
 
 
                                         $balance_row_qtys[$i]['bal_amount']=round(($balance_row_qtys[$i]['bal_qty']*     $calc_rate),2);
  
                                  
                                         break;
  
                                     }
                                     else{

  
                                         $required_qty=   $required_qty-  $balance_row_qtys[$i]['bal_qty']; 
                                        
                                         if($i==0 && $required_qty>0){
                                            
                                         $balance_row_qtys[$i]['bal_qty']=    $required_qty*-1;
                                         $balance_row_qtys[$i]['bal_amount']=   $balance_row_qtys[$i]['bal_qty'] * $calc_rate; 

                                         if(   $balance_row_qtys[$i]['bal_amount']<0){

                                            $negative_index=$i;

                                         }

                                         }
                                         else{

                                            $balance_row_qtys[$i]['bal_qty']=0;
                                            $balance_row_qtys[$i]['bal_amount']=0; 
                                            $balance_row_qtys[$i]['processed']=true;
                                         }

  
                                         
                                          
                                             
 
                                     }
 
  
 
 
                                 }
                                 else{
  
 
                                     $balance_row_qtys[$i]['bal_qty']=  $balance_row_qtys[$i]['bal_qty']+$required_qty*-1;
 
                                     $balance_row_qtys[$i]['bal_amount']=    $balance_row_qtys[$i]['bal_qty']*     $calc_rate;
 
  
                                     $negative_index=$i; 
  
                                     break;
 
                                 }
  
 
                             }
 
 

                        }

                  }
  
                //   dump($qty); 

                //   dump( $balance_row_qtys);
  
    
                $bal_amount_column=array_column(    $balance_row_qtys,'bal_amount');
 
    
                $bal_amount_sum=round(array_sum($bal_amount_column),2);

                $final_amounts[ $stock_ids[$index-1]]=$bal_amount_sum; 

            $index++;

        
      }
 
      return $final_amounts;
   


    }
 
    
    public function setProductTreeData(){


        $product_tree_data_json=Cache::get($this->user_id."_product_tree_data");

        if(empty($product_tree_data_json)){

                        
                    $all_subproducts=ProductMaster::where( "parent",'<>',0)->distinct('parent')->pluck('parent')->toArray();  


                    $product_tree_data=array();


                    foreach($all_subproducts as $product_id){

                        $product_id=(string)trim($product_id);

                        $childproduct_ids=ProductMaster::where("parent" ,$product_id)->pluck('Id')->toArray();

                        $product_tree_data[  $product_id]=$childproduct_ids;
            
                    } 


                    Cache::put($this->user_id."_product_tree_data",json_encode($product_tree_data),10800);

                    $this->product_tree_data=  $product_tree_data;
            
        }
        else{
            $this->product_tree_data=  json_decode( $product_tree_data_json,true);
        }
 

 
    } 

    public function getProductsSequentially(){
        


        $product_tree=$this->product_nodes;
        $allNodes=  $product_tree->getNodes();
         
         $all_ids=array_column($allNodes,'id'); 

         $not_allowed=array_diff(    $all_ids, $this->product_ids); 

         foreach(    $not_allowed as $single_not_allowed){
    
            $pos = array_search($single_not_allowed,    $all_ids);
     
            if ($pos !== false) {
              
                unset($all_ids[$pos]);
            }
       
    
         } 

         return array_values($all_ids); 
    }


    public function getAllProductWithChildIds(){


        $product_ids=$this->parent_product_ids;

        $product_tree_data= $this->product_tree_data;

        
        $searched_level=1;
        $till_productids= $product_ids;
        
        while(  $searched_level<100){

            $temp_product_ids=array();

            foreach(  $product_ids as   $product_id){

                $found_array=(array_key_exists($product_id, $product_tree_data)?$product_tree_data[$product_id] :array());

                $temp_product_ids=array_merge(   $temp_product_ids,  $found_array);

            } 

            $product_ids=  $temp_product_ids;
            
             $till_productids=array_merge( $till_productids,$product_ids);

            $searched_level++;


        }

        return     $till_productids;
 

    }


    public function calculateFinalAmountForOther($stock_ids,$valuation_method, $qtys,    $amount_rates ,$opening_rate ,$opening_qty, $purchases,$sales,$balance_qtys){
        
        $final_amounts=array();

        // $final_amount=round(($opening_rate*$opening_qty),2);

        // array_push( $final_amounts,$final_amount);

        $index=0;
        $purchase_row_rate=$opening_rate ;

        $sales_row_rate=$opening_rate ;

        
        $avg_row_rate=$opening_rate ;

        foreach($qtys as $qty){ 

            if($index==0){

                $final_amounts['opening_amount']=round(($opening_rate*$opening_qty),2);
                // array_push( $final_amounts,round(($opening_rate*$opening_qty),2));
                $index++;
                continue;
            }

            $current_balance=$balance_qtys[ $index];

            $previous_balance=$balance_qtys[ $index-1];

            if($valuation_method=="Purchase Invoice"    ){
  
                if(  $purchases[$index]==true){
                    $purchase_row_rate=$amount_rates[$index];
                } 

                $final_amount= round(    $purchase_row_rate*	$current_balance,2) ; 
 

             }
             else 	 if($valuation_method=="Sales Invoice"    ){

                if($sales[$index]==true  ){

                    $sales_row_rate=$amount_rates[$index];

                }

                $final_amount= round(  $sales_row_rate*	$current_balance,2) ; 
             }
             else if($valuation_method=="Avg Cost Method"  ){

                if($index==1){
                     $final_amount=round(      $avg_row_rate*	$current_balance,2) ;
                }
                else if($purchases[$index]==true   ){

                    $avg_row_rate=$amount_rates[$index];
  
                    if($previous_balance<0){
                         
                        $final_amount= ((($final_amount*-1)+($qty*$avg_row_rate))/(($previous_balance*-1)+$qty))*$current_balance;

}
                    else{
                         $final_amount= (($final_amount+($qty*$avg_row_rate))/($previous_balance +$qty))*$current_balance;
 
                    } 

                    $final_amount=round(	$final_amount,2);
                } 
                else{

                    if($previous_balance==0){
                        $amount_rate=0;
                    }
                    else{

                        $amount_rate=	$final_amount/$previous_balance ;
                    }


                    $final_amount= $current_balance*$amount_rate; 
                    $final_amount=round(	$final_amount,2); 
                }

             }


            //  array_push( $final_amounts,    $final_amount );

            $final_amounts[$stock_ids[$index-1]]=$final_amount;
 

            $index++;
        }



    //     $previous_balance=	$current_balance;
	// 								$current_balance= $stock_detail['balance_qty'];
 
	// 							$calc_spec=$stock_detail['spec_rate'];

	// 							if($valuation_method=="Purchase Invoice"     ){
 

	// 								if(in_array($stock_detail['Txn_Name'] ,$purchase_invoice_txn_ids)  ){
	// 									$amount_rate=(!empty($calc_spec)?$calc_spec:$stock_detail['CRate']);
	// 								}
							

	// 							   $final_amount=$amount_rate*	$current_balance;

	// 							   $final_amount_string=$amount_rate.'*'.$current_balance;
 
 
	// 							}
	// 							else 	 if($valuation_method=="Sales Invoice"   ){

	// 								if(in_array($stock_detail['Txn_Name'] ,$sales_invoice_txn_ids)){

	// 									$amount_rate=(!empty($calc_spec)?$calc_spec:$stock_detail['CRate']);
	// 								}

 
	// 								$final_amount=$amount_rate*	$current_balance;

	// 							}
	// 							else if($valuation_method=="Avg Cost Method"  ){


	// 								if($row_index==0){
     
	// 									$final_amount=$amount_rate*$current_balance;


	// 									$final_amount_string=$amount_rate.'*'.$current_balance;


	// 								}
	// 								else if(in_array($stock_detail['Txn_Name'] ,$purchase_invoice_txn_ids)  ){

	// 									$amount_rate=(!empty($calc_spec)?$calc_spec:$stock_detail['CRate']);

	// 									if($previous_balance<0){
											
										 

	// 										$final_amount= ((($final_amount*-1)+($stock_detail['Qty']*$amount_rate))/(($previous_balance*-1)+$stock_detail['Qty']))*$current_balance;

	// }
	// 									else{
	// 									 	$final_amount= (($final_amount+($stock_detail['Qty']*$amount_rate))/($previous_balance +$stock_detail['Qty']))*$current_balance;

									
	// 									} 

	// 									$final_amount=round(	$final_amount,2);
	// 								} 
	// 								else{

	// 									if($previous_balance==0 ){
	// 										$amount_rate=	0;
	// 									}
	// 									else{
	// 										$amount_rate=	$final_amount/$previous_balance ;
	// 									}

								
	// 									$final_amount= $current_balance*$amount_rate;

	// 									$final_amount_string=  $current_balance."*".$amount_rate;

	// 									$final_amount=round(	$final_amount,2);


	// 								}
	// 							}
	// 					      else if($valuation_method=="Fifo" || $valuation_method=="Lifo"  ){

	// 							$final_amount=$stock_detail_amounts[$stock_detail['id']];

	// 						 }
							  
	// 							$final_amount=$stock_detail_amounts[$stock_detail['id']];
 

	// 							 $qty=$stock_detail['Qty'];



    
        return  $final_amounts;

    }



    public function getStartOpeningProductStock(){

        $location_id=$this->location_id;

      $openingstock=  StockDet::where('docno','LIKE','op-%')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){

        $query->where('location',$location_id);

      })->sum('Qty');

    

    //   $openingstock=  StockDet::where('docno','LIKE','op-%')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){

    //     $query->where('location',$location_id);

    //   })->sum('Qty');

      if(empty( $openingstock)){
        $openingstock=0;
      }

        return   $openingstock;
    }


    
    public function setSmtpUniversalSettings(){

        $mailconfig= TblMailConfig::first();

        if(empty( $mailconfig)){
            return false;
        }
        $this->username=   $mailconfig->comp_mail;

        $this->password=    $mailconfig->comp_pwd; 

        $this->from_name= (!empty($mailconfig->from_name)?$mailconfig->from_name:'');
        $this->from_email=  $mailconfig->comp_mail;

        return true;

}




public function getProductLastStockDetailAmount($valuation_method,$opening_qty,$opening_rate ,$stock_details, $purchase_invoice_txn_ids, $sales_invoice_txn_ids ){
 
        $final_amounts= $this->getProductStockDetailAmounts($valuation_method,$opening_qty,$opening_rate ,$stock_details, $purchase_invoice_txn_ids, $sales_invoice_txn_ids);
        $location_id=$this->location_id; 
    
       $latest_stock_detail=  StockDet::where('docdate','<=',$this->end_date.' 23:59:59.999')->where('prodid',$this->product_id)->when(!empty( $location_id),function($query)use($location_id){
  
            $query->where('location',$location_id);
    
          })->where('docno','NOT LIKE','op-%')->orderby('docdate','desc')->first();

        

          if(empty(  $latest_stock_detail)){
            return 0;
          }
 
  
          $stock_detail_id=  $latest_stock_detail->Id; 
 
         
        return    $final_amounts[   $stock_detail_id]; 
}


public function setProductNodes(){
    
    $data=ProductMaster::select('Id as id','parent','Product as product_name','g_p as product_type')->get()->toArray();
 
    $tree = new Tree($data ); 
    
    // $allNodes = $tree->getNodes();

    $this->product_nodes=   $tree ;
 
}


public function getProductDetailById($product_id){
 

    $node = $this->product_nodes->getNodeById($product_id); 

    if(in_array($product_id,array(1,21,765))){
        return array('product_name'=> $node->get('product_name'),'parent_name'=>'','product_type'=>  $node->get('product_type') );
 

    }

    $parentNode = $node->getParent(); 

    return array('product_name'=> $node->get('product_name'),'parent_name'=>$parentNode->get('product_name'),'product_type'=>  $node->get('product_type') );
 
}


public function getStockFastSlowMovingOutAmount($productid,$month_key){
 
 
    $month_out_amount=StockDet::join('table_master','Stock_Det.Txn_Name','=','table_master.id')
    ->where('Prodid',$productid)
    ->where(function($query){
        $query->where('table_master.txn_class','like','sales return%');
        $query->orwhere('table_master.txn_class','like','sales invoice%'); 
    })->where(DB::raw('MONTH(Stock_Det.DocDate)'),$month_key)->where('Stock_Det.DocDate','>=',$this->start_date.' 00:00:00.000')->where('Stock_Det.DocDate','<=',$this->end_date.' 23:59:59.999')->sum('Qty');
  
  if(empty( $month_out_amount)){
        $month_out_amount=0;
    }
  
    return    abs($month_out_amount);
}


public function getOpeningStockDetail($selected_location,$selected_product){
 
    $opening_detail=StockDet::where('docno','LIKE','op%')->where('Prodid',$selected_product)->where('Location',$selected_location)->select('id','docno','docdate','qty','crate as rate' )->first();

    if(empty(  $opening_detail)){
        return NULL;
    }

    $amount=round($opening_detail->qty*$opening_detail->rate,2);

    return array('id'=>$opening_detail->id ,'docno'=> $opening_detail->docno,'docdate'=>date('d/m/Y',strtotime($opening_detail->docdate)),'qty'=>$opening_detail->qty,'rate'=>round($opening_detail->rate,2),'amount'=>    $amount);


}


}

?>