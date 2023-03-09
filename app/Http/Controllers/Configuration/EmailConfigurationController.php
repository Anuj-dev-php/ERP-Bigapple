<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use App\Models\TblPrintHeader;
use Illuminate\Support\Facades\Log;
use App\Models\TblEmailConfig;
use App\Models\FieldsMaster;
use App\Models\TableSmsHeader;
use App\Models\WhatsappCustomField;
use Illuminate\Support\Facades\Cache; 
use  App\Http\Controllers\Services\WhatsAppService;
use Auth;


class EmailConfigurationController extends Controller
{
   public function index($companyname){
  
      $emailconfigs=TblEmailConfig::leftjoin('tbl_print_header','tbl_email_config.print_temp','=','tbl_print_header.Tempid')->select('email_configuration_name' ,'table_name','print_temp','tbl_email_config.id','tbl_print_header.TempName')->get();
     
      return view("configuration.emailconfiguration",compact('companyname' ,'emailconfigs'));
   }


   public function addedit($companyname,$id=null){

       if(!empty($id)){
         $emailconfigs=TblEmailConfig::where('id',$id)->get()->toArray(); 
 
         $whatsapptemplates= TableSmsHeader::where('txn_name',$emailconfigs[0]['table_name'])->orderby('tempname','asc')->select('tempid','tempname')->get();
         $printtemplates=TblPrintHeader::where('Txn_Name',$emailconfigs[0]['table_name'])->orderby('TempName','asc')->select('Tempid','TempName')->get();

               
         $trantable=$emailconfigs[0]['table_name'];

            $fields=FieldsMaster::where(function($query)use($trantable){
               
               $query->where('Table_Name',$trantable)->orwhere('Table_Name',$trantable."_det");

            })->where('Tab_Id','<>','None')->orderby('fld_label','asc')->select('fld_label as  label','Field_Name as name')->get()->toArray(); 

            $whatsapp_custom_fields=WhatsappCustomField::where('email_config_id',$id)->select('custom_field_id as field_id','custom_field_name as field_name')->get()->toArray();


       }
       else{
         $emailconfigs=array();
         $printtemplates=array();
         $whatsapptemplates=array();
         $fields=array();  
         $whatsapp_custom_fields=array();

       }

       $user_id=Auth::user()->id;


       $custom_fields=   Cache::get($user_id."_whatsapp_custom_fields");


       if(empty( $custom_fields)){

         
             $was= new WhatsAppService;

           $custom_fields=     $was->getCustomFieldsFromAccount();
       }
 
 
        $transactions= TableMaster::where('table_name','NOT LIKE','%_det')->orderby('table_label','asc')->get()->pluck( 'table_label','Table_Name'); 

        return view("configuration.emailconfiguration_add",compact('transactions','companyname','emailconfigs','printtemplates','fields','id','whatsapptemplates','whatsapp_custom_fields','custom_fields'));
   }

   public function getTransactionPrintTemplates(Request $request){
         $trantable=$request->tran_table;
        $printtemplates=TblPrintHeader::where('Txn_Name', $trantable)->orderby('TempName','asc')->select('TempName','Tempid')->get();
        $whatsapptemplates=TableSmsHeader::where('txn_name',$trantable)->orderby('tempname','asc')->select('tempid','tempname')->get();
        return response()->json( ['printtemplates'=>$printtemplates,'whatsapptemplates'=>$whatsapptemplates]); 
   }


   public function getEmailConfigurationAnotherRow($companyname,Request $request){ 

      $trantable=$request->tran_table;
      $noofrow=$request->no_of_row;

      $conj=$request->conj;
      
      $fields=FieldsMaster::where(function($query)use($trantable){
         
         $query->where('Table_Name',$trantable)->orwhere('Table_Name',$trantable."_det");

      })->where('Tab_Id','<>','None')->orderby('fld_label','asc')->select('fld_label as  label','Field_Name as name')->get();


    $emailconfigurationrow= view("configuration.emailconfigurationtr",compact('noofrow','fields','conj'))->render();

    return response()->json($emailconfigurationrow);

   }
 
   public function submitEmailConfiguration($companyname,Request $request){
 
      $email_conf_name=$request->email_conf_name;
      $id=$request->id;
      $transaction=$request->transaction;

      $printtemp=$request->printtemplate;

      $fields=$request->field;

      $conditions=$request->condition;

      $values=$request->value;

      $conjs=$request->conj;

      $emails=$request->email;

      $email_subject=$request->email_subject;

      $email_body=$request->email_body;

      $manual=(isset($request->manual)?1:0);

      $whatsapp_template_id=(empty($request->whatsapptemplate)?NULL:$request->whatsapptemplate);

      $whatsapp_no=(empty($request->enter_whatsapp_no)?NULL:$request->enter_whatsapp_no);
   
      $emails=(empty($request->enter_emails)?NULL:$request->enter_emails);
      
      $sendemail=isset($request->send_email)?"True":"False";
      $sendsales=isset($request->send_salesman)?"True":"False";
      $sendcust=isset($request->send_customer)?"True":"False"; 
      $whatsapp=$request->whatsapp;
      $index=0;

      $conditions_query=(!empty($request->conditions_query)?$request->conditions_query:NULL);

      $emailconfigs=array();

      // if($formgroupid==0){

      //    $groupidfound=TblEmailConfig::max('id');

      //    $groupid=empty( $groupidfound)?1:($groupidfound+1);
      // }
      // else{
      //    $groupid=$formgroupid;
      //    TblEmailConfig::where('group_id', $groupid)->delete(); 

      // }

      $groupid=NULL;

      // array_push($emailconfigs,array( 'email_configuration_name'=> $email_conf_name,'table_name'=>$transaction,'field_name'=>'','cond'=>'','cond_val'=>'','conj'=>'','Email'=>  $emails,'print_temp'=>$printtemp,'send_mail'=>$sendemail,'send_exec'=>$sendsales,'send_cust'=>$sendcust,'group_id'=>$groupid,'email_subject'=>$email_subject,'email_body'=>$email_body,'whatsapp_no'=>   $whatsapp_no,'whatsapp_template_id'=> $whatsapp_template_id,'conditions_query'=> $conditions_query)); 


      if(empty(   $id)){
         TblEmailConfig::create([ 'email_configuration_name'=> $email_conf_name,'table_name'=>$transaction,'field_name'=>'','cond'=>'','cond_val'=>'','conj'=>'','Email'=>  $emails,'print_temp'=>$printtemp,'send_mail'=>$sendemail,'send_exec'=>$sendsales,'send_cust'=>$sendcust,'group_id'=>$groupid,'email_subject'=>$email_subject,'email_body'=>$email_body,'whatsapp_no'=>   $whatsapp_no,'whatsapp_template_id'=> $whatsapp_template_id,'conditions_query'=> $conditions_query,'is_manual'=>  $manual]);

      }
      else{
         TblEmailConfig::where('id', $id)->update([ 'email_configuration_name'=> $email_conf_name,'table_name'=>$transaction,'field_name'=>'','cond'=>'','cond_val'=>'','conj'=>'','Email'=>  $emails,'print_temp'=>$printtemp,'send_mail'=>$sendemail,'send_exec'=>$sendsales,'send_cust'=>$sendcust,'group_id'=>$groupid,'email_subject'=>$email_subject,'email_body'=>$email_body,'whatsapp_no'=>   $whatsapp_no,'whatsapp_template_id'=> $whatsapp_template_id,'conditions_query'=> $conditions_query,'is_manual'=>  $manual]);
      }
      // foreach( $fields as $field){
      //    array_push($emailconfigs,array( 'email_configuration_name'=> $email_conf_name,'table_name'=>$transaction,'field_name'=>$field,'cond'=> $conditions[$index],'cond_val'=> $values[$index],'conj'=>$conjs[$index],'Email'=> (empty($emails[$index])?'':$emails[$index]) ,'print_temp'=>$printtemp,'send_mail'=>$sendemail,'send_exec'=>$sendsales,'send_cust'=>$sendcust,'group_id'=>$groupid,'email_subject'=>$email_subject,'email_body'=>$email_body,'whatsapp_no'=>(empty($whatsapp[$index])?'':$whatsapp[$index]) ,'whatsapp_template_id'=> $whatsapp_template_id)); 

      //    $index++;
      // }

      $whatsapp_custom_fields=(isset($request->whatsapp_custom_field_id)?$request->whatsapp_custom_field_id:array());

      $custom_field_names=(isset($request->whatsapp_custom_field_name)?$request->whatsapp_custom_field_name:array());

 
      WhatsappCustomField::where('email_config_id',$id)->delete();

      $custom_field_index=0;

      foreach(   $whatsapp_custom_fields as    $whatsapp_custom_field){

         $custom_field_name=  $custom_field_names[ $custom_field_index];

         WhatsappCustomField::create(['email_config_id'=>$id,'custom_field_id'=>$whatsapp_custom_field,'custom_field_name'=> $custom_field_name]);

         $custom_field_index++;
      }

       

      // TblEmailConfig::insert($emailconfigs); 

      return redirect('/'.$companyname.'/email-configuration')->with('message','Email Configuration saved successfully');
   }


   public function deleteEmailConfiguration($companyname,Request $request){

      $ids=$request->ids; 
      TblEmailConfig::whereIn('id', $ids)->delete();

      return response()->json(['status'=>'success','message'=>'Email Configuration Deleted successfully']);

   }
}
