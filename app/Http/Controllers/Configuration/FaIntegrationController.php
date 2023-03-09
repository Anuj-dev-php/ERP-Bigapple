<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TranAccount;
use App\Models\TableMaster;
use App\Models\VchType;
use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\TranAccDet;
use DB;


class FaIntegrationController extends Controller
{
     public function index($companyname){ 

         $tran_accounts=TranAccount::orderby('id','desc')->get();

        return view("configuration.fa_integration",compact('tran_accounts','companyname'));

     }


     public function addFaIntegration($companyname){

      $transactions=TableMaster::orderby('table_label','asc')->pluck('table_label','Table_Name');
      $vchtypes=VchType::where('parent',0)->orderby('name','asc')->pluck('Name','Id');
      $accounts=Account::orderby('ACName','asc')->pluck('ACName','Id');
 
 
        return view("configuration.add_fa_integration",compact('transactions','vchtypes','companyname','accounts'));

     }


     public function getSubVoucherTypes($companyname,  $vouchertypeid){
       

       $vouchersubtypes= VchType::where('Parent',$vouchertypeid)->pluck('Name','Id'); 

        return response()->json(['subvchtypes'=> $vouchersubtypes]); 

     }


     public function searchAccounts(Request $request){ 

      $searchterm=empty($request->searchTerm)?'':$request->searchTerm;

      $except_id=$request->except_id;

       $accounts= Account::where('ACName','Like','%'.$searchterm.'%')->take(5)->when(!empty($except_id),function($query)use($except_id){
         $query->where('id','<>',$except_id);
       })->select('ACName as text','Id as id')->get()->toArray();

         array_push(    $accounts , [
            'id' => 'Party Id',
            'text' => 'Party Id'
        ]);

        array_push($accounts,[
               'id' => 'Emp Id',
               'text' => 'Emp Id'
         ]);
      
         return response()->json($accounts);

     }


     public function submitAddFaIntegration($companyname,Request $request){
 
       $tranaccountid= $request->tran_account_id; 

      $templateid=$request->template_id;

      $description=$request->description;

      $transaction=$request->transaction;

      $vouchertype=$request->vouchertype;

      $vouchersubtype=$request->vouchersubtype;

      $mainaccountid=$request->mainaccount;

      $mainaccount_byto=$request->mainaccount_byto;

      $mainaccountformula=$request->main_account_formula;


      $makedefault=empty($request->make_default)?'False':'True';

      if($mainaccountid=="Party Id" || $mainaccountid=="Emp Id"){
         $mainaccount=$mainaccountid;
      }
      else{
         $mainaccount=Account::where('Id', $mainaccountid)->value('ACName');
      } 

         if(empty($tranaccountid)){
            $tranaccount=new TranAccount;  
            $msg="Fa Integration Added successfully";
         }
         else{
            $tranaccount=TranAccount::where('Id',$tranaccountid)->first(); 
            $msg="Fa Integration Updated successfully";
           
         }
      
         $tranaccount->TemplateId=$templateid; 
         $tranaccount->Description=$description;
         $tranaccount->VchType=$vouchertype;
         $tranaccount->VchSubTypes=$vouchersubtype;
         $tranaccount->Account=$mainaccount;
         $tranaccount->Transaction=$transaction;
         $tranaccount->is_default=$makedefault;
         $tranaccount->mainaccount_byto= $mainaccount_byto;
         $tranaccount->mainaccount_formula= strtolower($mainaccountformula);
         $tranaccount->save(); 

         $tranaccountid=  $tranaccount->Id;
 

         // delete all tran acc det in case of edit

         TranAccDet::where('TempId', $tranaccountid)->delete();

         $bytos=$request->byto;
         $accounts=$request->accounts;
         $formula=$request->formula;
          
         $index=0;
         foreach(    $bytos as     $byto){

            if(empty($byto) ||  empty( $accounts[$index]) || empty(    $formula[$index])  ){
               continue;
            }

            $tranaccdet= TranAccDet::insert(array('TempId'=>$tranaccountid,'By/To'=>$byto,'AccName'=>$accounts[$index],'Formula'=>$formula[$index])); 
      

            $index++; 

         }
 
         return redirect('/'.$companyname.'/fa-integration')->with("message",$msg); 

     }

     public function editFaIntegration($companyname,$tranaccountid){

      $tranaccount=TranAccount::where('Id',$tranaccountid)->first();
  

      $tranaccdets_without_lineaccs=$tranaccount->tranaccdet()->join('accounts','TranAccDet.AccName','=','accounts.id')->where('AccName','<>','line_acc')->select('TranAccDet.Id','TempId','By/To', 'Formula','accounts.id as accountid','accounts.ACName as accountname')->get()->toArray();
  
      $tranaccdets_with_lineaccs=$tranaccount->tranaccdet()->where(trim('AccName'),'=','line_acc')->select( 'TranAccDet.Id','TempId','By/To', 'Formula',DB::raw("'line_acc' as accountid"),DB::raw("'line_acc' as accountname"))->get()->toArray();
 
      
      $tranaccdets=array_merge( $tranaccdets_without_lineaccs, $tranaccdets_with_lineaccs);
 

      if(trim($tranaccount->Account)=="Emp Id" || trim($tranaccount->Account)=="Party Id"){

         $mainaccountid=trim($tranaccount->Account);
      }
      else{

         $mainaccountid=Account::where('ACName',$tranaccount->Account)->value('Id');
      }
  
      $transactions=TableMaster::orderby('table_label','asc')->pluck('table_label','Table_Name');
      $vchtypes=VchType::where('parent',0)->orderby('name','asc')->pluck('Name','Id');
      $accounts=Account::orderby('ACName','asc')->pluck('ACName','Id');
 
 
        return view("configuration.add_fa_integration",compact('transactions','vchtypes','companyname','accounts','tranaccount','tranaccdets','mainaccountid'));
 

     }


     public function deleteFaIntegration(Request $request){
 
      $tranaccounts=$request->tran_accounts; 
      TranAccDet::whereIn('TempId',   $tranaccounts)->delete();
      TranAccount::whereIn('Id',  $tranaccounts)->delete();

      return response()->json(['status'=>'success','message'=>'Tran Accounts Delete Successfully']); 

     }


     
     public function searchSubAccounts(Request $request){ 

      $searchterm=empty($request->searchTerm)?'':$request->searchTerm;

      $except_id=$request->except_id;

       $accounts= Account::where('ACName','Like','%'.$searchterm.'%')->take(5)->when(!empty($except_id),function($query)use($except_id){
         $query->where('id','<>',$except_id);
       })->select('ACName as text','Id as id')->get()->toArray();

       
       array_push(    $accounts , [
                  'id' => 'Party Id',
                  'text' => 'Party Id'
            ]);

            array_push($accounts,[
                     'id' => 'Emp Id',
                     'text' => 'Emp Id'
               ]);

               
            array_push($accounts,[
               'id' => 'line_acc',
               'text' => 'Line Acc'
         ]);
       
       return response()->json($accounts);


      }

}
