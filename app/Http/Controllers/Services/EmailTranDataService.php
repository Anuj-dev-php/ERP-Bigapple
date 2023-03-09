<?php
namespace App\Http\Controllers\Services;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Attachment;
use Storage;
use Illuminate\Support\Facades\Log;
use App\Models\TblMailConfig;
use App\Models\User;
use App\Models\TblEmailConfig;
use  App\Models\TblPrintHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use  App\Models\Customer;
use  App\Models\Salesmen;
use App\Jobs\SendEmail;
use App\Helper\Helper;
use App\Jobs\DownloadInvoiceFromCrystalServer; 
use App\Models\EmailSchedular;
use App\Models\TableSmsHeader;
use App\Models\FieldsMaster;

 

class EmailTranDataService{


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
    public $user_id;
    public $tran_table;
    public $data_id;
    public $report_name;
    public $db_name=NULL;
    public $group_id;
    public $conjunction; 
    public $schedule;
    public $schedular_last_run;
    public $schedular_timings=array();
    public $schedular_sended_ids=array();
    public $config_id;
    public $schedular_id=NULL;
    public $txn_id=NULL;




    public function __construct(){

         $mailconfig= TblMailConfig::first();

         if(!empty( $mailconfig)){
             $this->host=$mailconfig->smtp_host;
             $this->port=$mailconfig->smtp_port;
             $this->encryption=$mailconfig->encryption;
         }

 

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


    public function setUserSmtpSettings(){


                $mailconfig=User::where('id',$this->user_id)->select('email','email_password','user_id')->first();

                if(empty(  $mailconfig)){
                    return false;
                }

                $this->username=   $mailconfig->email;

                $this->password=    $mailconfig->email_password;

                $this->from_name=$mailconfig->user_id;
                $this->from_email=  $mailconfig->email;

                return true;
    }

 

    public function SendTranDataEmail(){
 

        if(!empty($this->filename)  && !empty($this->showfilename)){ 
       
            $filepath='app/public/invoices_pdf/'.$this->filename;
 
    
            SendEmail::dispatch($this->host,$this->port,$this->encryption,$this->username,$this->password,$this->subject,$this->body,$this->from_name,$this->from_email, $this->to_email, $filepath, $this->showfilename,$this->db_name,$this->txn_id,$this->schedular_id );
            
          
        }
      

    }


    public function downloadInvoiceFromUrl(){

        stream_context_set_default(array(
            'ssl'                => array(
            'peer_name'          => 'generic-server',
            'verify_peer'        => FALSE,
            'verify_peer_name'   => FALSE,
            'allow_self_signed'  => FALSE
             )));

            $docno=DB::table($this->tran_table)->where('Id',$this->data_id)->value('docno');

 
            //  $this->filename=$this->db_name.'_'.$this->tran_table."_".$this->data_id.".pdf";
             
            //  $this->filename=time()."-".$this->user_id.".pdf";

             $this->showfilename=$docno.".pdf";
             $this->filename=$docno.".pdf";

 
          $reportserver_url=TblPrintHeader::where('Txn_Name',$this->tran_table)->value('link');

          if(empty( $reportserver_url)){
              return false;
          }
 
          $report_url_pdf=$reportserver_url.'?id='.$this->data_id.'&reportfilename='.$this->report_name.'&databasename='.$this->db_name;
 
        //   $tempImage = tempnam(sys_get_temp_dir(), $this->filename);

        //   $report_url_pdf=str_replace("https","http",     $report_url_pdf);
 
        //   copy(    $report_url_pdf, $tempImage);
        
        //     if(Storage::exists('public/invoices_pdf/'.$this->filename)){
        //         Storage::delete('public/invoices_pdf/'.$this->filename);
        //     }
 
        //    $path = Storage::putFileAs('public/invoices_pdf',     $tempImage, $this->filename );

        return array('file_url'=>$report_url_pdf,'file_name'=>$this->showfilename);
 
    }



    public function SendAutoMailByEmailConfiguration(){

        $emailconfigs=TblEmailConfig::join('tbl_print_header','tbl_email_config.print_temp','=','tbl_print_header.Tempid')->where('tbl_email_config.table_name','=',$this->tran_table)->WhereNotNull('tbl_email_config.group_id')->groupby( 'tbl_print_header.crystal','tbl_email_config.group_id', 'tbl_email_config.email_subject' , 'tbl_email_config.email_body', 'tbl_email_config.send_exec','tbl_email_config.send_cust','tbl_email_config.conj')->select('crystal','group_id','email_subject','email_body','send_exec','send_cust','conj')->get();
     
 
        $data=array();

        foreach( $emailconfigs as  $emailconfig){

            if(empty($emailconfig->email_subject) ||  empty($emailconfig->email_body) ){
                continue;
            }

            $email_subject=$emailconfig->email_subject;


            $email_body=$emailconfig->email_body;


            if(trim($emailconfig->send_exec)=="False" && trim($emailconfig->send_cust)=="False"){
                continue;
            }

            $this->group_id=$emailconfig->group_id;

            $this->conjunction=trim($emailconfig->conj);

            $this->report_name=trim($emailconfig->crystal);

            $this->downloadInvoiceFromUrl();
 
            $validation_array= $this->ValidateEmailConfigurationGroupFromData();
            $isvalid= $validation_array['isvalid'];
            $condition_emails=$validation_array['emails'];
            
                if(count($condition_emails)==0){
                    $this->to_email="";
                }
                else{
                    $this->to_email=implode(',',   $condition_emails);;
                }
                
            
            if(  $isvalid==true){

                $this->subject=   $email_subject;

                $this->body=  $email_body;

                if(trim($emailconfig->send_exec)=="True" ){ 

                  $salesman_mail=  $this->getMailToSalesman();

                  if(!empty( $salesman_mail)){

                    $this->addEmailAtToMail($salesman_mail);

                  }

                }

                if(trim($emailconfig->send_cust)=="True"){


                   $customer_mail= $this->getMailToCustomer();

                   if(!empty(  $customer_mail)){

                      $this->addEmailAtToMail( $customer_mail);


                   }

                }


                array_push($data,array('to'=>$this->to_email,'subject'=>$this->subject,'body'=>$this->body,'filename'=>$this->filename,'showfilename'=>$this->showfilename));

               

            } 
        }


        return $data;


    }

        public function ValidateEmailConfigurationGroupFromData(){ 

            // $data_row=DB::table($this->tran_table)->where('Id',$this->data_id)->first();

            // $data_row_array=(array) $data_row;

            // $data_row_array= array_change_key_case(  $data_row_array,CASE_LOWER);
 
            $first_email_config=   TblEmailConfig::where('id',$this->config_id)->select( 'email','whatsapp_no','send_exec','send_cust','conj','conditions_query','email_subject','email_body')->first();
            
            $email_subject=  $first_email_config->email_subject;

            $email_body=$first_email_config->email_body;

             $send_email_string= $first_email_config->email;

           if(!empty(   $send_email_string)){
            $send_email_array=explode(',', $send_email_string);
           }
           else{
            $send_email_array=array(); 
           }

           $send_whatsapp_string=$first_email_config->whatsapp_no;


           if(!empty($send_whatsapp_string)){

            $send_whatsapp_array=explode(',',$send_whatsapp_string);
           }
           else{
            $send_whatsapp_array=array();
           }


           $send_to_salesman=false;

           if( trim($first_email_config->send_exec)=="True"  ){
              $send_to_salesman=true;
           }


           $send_to_customer=false;

           if( trim($first_email_config->send_cust)=="True"){

               $send_to_customer=true;
           }
 
           $conditions_query_string= $first_email_config->conditions_query;
 

            $conditions_result=DB::select( $conditions_query_string);

            
            $all_results=json_decode(json_encode( $conditions_result),true);

        
            $table_ids_data=array(); 
 
            $salesman_ids=array_column(    $all_results,'salesman');
            $salesman_details= Salesmen::whereIn('Id',   $salesman_ids)->select( 'Id','salesmanphone as phone','emailid as email')->get();

            $salesman_data=array();


            foreach(  $salesman_details as   $salesman_detail){

                $salesman_data[$salesman_detail['Id']]=array('phone'=>trim($salesman_detail['phone']),'email'=>trim($salesman_detail['email']));

            }

            $customer_ids=array_column(    $all_results,'cust_id');
            
            $customer_details=Customer::whereIn('Id',   $customer_ids)->select('Id','whatsappno as phone','despatchemailid as email')->get();

            $customer_data=array();


            foreach( $customer_details as  $customer_detail){

                $customer_data[$customer_detail['Id']]=array('phone'=>trim($customer_detail['phone']),'email'=>trim($customer_detail['email']));

            }

 
            foreach($all_results as $all_result){

                $table_id_emails=array();

                $table_id_whatsapp=array();

                $table_id_emails=array_merge(  $table_id_emails,   $send_email_array);
                $table_id_whatsapp=array_merge(      $table_id_whatsapp,  $send_whatsapp_array);
 
                // $all_result= array_change_key_case( $all_result,CASE_LOWER);

                $table_id=$all_result['id'];
 
                unset($all_result['id']);

                $other_data=$all_result;
 
                // array_push($table_ids_data,   $table_id);
                $table_ids_data[$table_id]=array(); 

                $salesman_id=  (array_key_exists('salesman',$all_result)?$all_result['salesman']:'');

                $customer_id=(array_key_exists('cust_id',$all_result)?$all_result['cust_id']:'');

                

                if(   $send_to_salesman==true && !empty($salesman_id)  && array_key_exists($salesman_id, $salesman_data) ){

               
                    if( !empty($salesman_data[$salesman_id]['email'])){
                        array_push( $table_id_emails,$salesman_data[$salesman_id]['email']);
                    } 

                            
                    if(!empty($salesman_data[$salesman_id]['phone'])){

                        array_push(  $table_id_whatsapp ,$salesman_data[$salesman_id]['phone']);

                    }

                } 

                if( trim($first_email_config->send_cust)=="True" && !empty( $customer_id) && array_key_exists(   $customer_id, $customer_data) ){

                
                    if(!empty( $customer_data[   $customer_id]['email'])){
                        array_push($table_id_emails, $customer_data[   $customer_id]['email'] );

                    }

                    if(!empty( $customer_data[   $customer_id]['phone'] )){
                        array_push($table_id_whatsapp, $customer_data[   $customer_id]['phone']);

                    } 
                } 


                foreach($table_id_emails as $single_email_string){
                    $search_index=0;
                    if(str_contains($single_email_string,'#')==true){

                        $table_id_emails[$search_index]=$this->formatTextWithFieldValues( $single_email_string, $other_data);

                    } 
                    $search_index++;

                }


                foreach($table_id_whatsapp as $single_whatsapp_string){
                    $search_index=0;
                    if(str_contains($single_whatsapp_string,'#')==true){

                        $table_id_whatsapp[$search_index]=$this->formatTextWithFieldValues( $single_whatsapp_string, $other_data);

                    } 
                    $search_index++;

                }
 
                $table_ids_data[$table_id]['emails']=$table_id_emails;

                $table_ids_data[$table_id]['whatsapp']=$table_id_whatsapp;
               
                $table_ids_data[$table_id]['email_subject']=$this->formatTextWithFieldValues( $email_subject, $other_data);
              
                $table_ids_data[$table_id]['email_body']=$this->formatTextWithFieldValues( $email_body, $other_data);
         
              
            } 
 
              return    $table_ids_data;
 
            
        }
 

        public function getMailMobNumToCustomer(){

                
                if(!Schema::hasColumn($this->tran_table, 'cust_id')) {
                    return NULL;
                }
     
                $custid=DB::table($this->tran_table)->where('Id',$this->data_id)->value('cust_id');
 

                $email_mob_detail=Customer::where("Id", $custid)->select('email','whatsappno','cust_id')->first();
 

                if(empty( $email_mob_detail)  || Storage::exists('public/invoices_pdf/'.$this->filename)==false){
                    return NULL;
                }

                $customer_name= $email_mob_detail['cust_id'];

                $customer_name_array=explode(" ",   $customer_name); 

                $fname=$customer_name_array[0];

                $lname=(array_key_exists(1,$customer_name_array)?$customer_name_array[1]:"");

                  return   array('email_id'=>trim($email_mob_detail['email']),'mob_num'=>trim($email_mob_detail['whatsappno']),'first_name'=>$fname,'last_name'=>  $lname);
        }


        public function getMailMobNumToSalesman(){


            if(!Schema::hasColumn($this->tran_table, 'salesman')) {
                return NULL;
            }

                $salesmenid=DB::table($this->tran_table)->where('Id',$this->data_id)->value('salesman');

                $email_num_detail=Salesmen::where('Id', $salesmenid)->select('emailid','salesmanphone','name')->first();
 

                if(empty(   $email_num_detail) || Storage::exists('public/invoices_pdf/'.$this->filename)==false){
                    return NULL;
                }

                $salesman_name=    $email_num_detail['name'];

                $name_array=explode(" ",  $salesman_name);

                $first_name=$name_array[0];

                $last_name=(array_key_exists(1,$name_array)?$name_array[1]:"");
  
                 return    array('email_id'=> $email_num_detail['emailid'],'mob_num'=>  $email_num_detail['salesmanphone'],'first_name'=>$first_name,'last_name'=>$last_name);
 
            
        }


        public function addEmailAtToMail($mailname){

            $tomailstring=$this->to_email;

            if(!empty(  $tomailstring)){
                $toemail_array=explode(",", $tomailstring);
            }
            else{
                $toemail_array=array();
            } 

            array_push($toemail_array,$mailname);

            $this->to_email=implode(',',$toemail_array);

 
        }



        public function SetSendTranDataMailsFromArray($mails_array){
 
                if(count($mails_array)==0)
                return; 

                foreach($mails_array as $single_mail){

                    $this->to_email=$single_mail['to'];
                    $this->subject=$single_mail['subject'];
                    $this->body=$single_mail['body'];
                    $this->filename=$single_mail['filename'];
                    $this->showfilename=$single_mail['showfilename'];
                    if(array_key_exists('txn_id',$single_mail)){
                        $this->txn_id=$single_mail['txn_id'];
                    }

                    if(array_key_exists('schedular_id',$single_mail)){
                        $this->schedular_id=$single_mail['schedular_id'];
                    }


                    if(array_key_exists('db_name',$single_mail)){
                        $this->db_name=$single_mail['db_name'];
                    }
 
                  
                    $this->SendTranDataEmail();
 
                }
            


        }


        public function formatEmailsArraySubjectAndBody($emails_array){

            $index=0;
            foreach($emails_array as  $single_array){

                $emails_array[$index]['subject']= $this->formatTextWithFieldValues($single_array['subject']) ;
              
                $emails_array[$index]['body']= $this->formatTextWithFieldValues($single_array['body']) ;

                $index++;
            }
 
            return  $emails_array;

        }



        public function formatTextWithFieldValues($textmessage,$data_row_array=NULL){
 

            if(empty($data_row_array)){
 
                $data_row=DB::table($this->tran_table)->where('Id',$this->data_id)->first();

                $data_row_array=(array) $data_row;
 
            } 

  
            foreach($data_row_array as $data_key=>$data_value){

                $searchtext='#'.$data_key;  

                if(strpos($textmessage, $searchtext)===false){
                    continue;
                }

                $textmessage=str_replace($searchtext,$data_value, $textmessage);

            }

         

            return     $textmessage;

        }


        public function getInvoicePdfUrl(){

            $reportserver_url=TblPrintHeader::where('Txn_Name',$this->tran_table)->value('link');

            if(empty($reportserver_url)){

                return NULL;
            }
 
            $docno=DB::table($this->tran_table)->where('Id',$this->data_id)->value('docno');

            $reportserver_url=  $reportserver_url."/".$docno."?id=".$this->data_id."&reportfilename=".$this->report_name."&databasename=".$this->db_name;
 

            return   $reportserver_url;

        }

        public function getEmailAndWhatsappSchedularData(){
            

            try{

         
 
          $emailschedulars=  EmailSchedular::join('tbl_email_config', 'email_schedular.email_configuration_id','=','tbl_email_config.id')->select( 'tbl_email_config.send_mail' ,'tbl_email_config.email_subject','tbl_email_config.email_body','tbl_email_config.whatsapp_template_id','tbl_email_config.table_name' , 'tbl_email_config.print_temp',
        'tbl_email_config.id as config_id',  'email_schedular.schedule','email_schedular.send_time','email_schedular.send_datetime'
        ,'email_schedular.send_weekdays'   ,'email_schedular.send_months'  ,'email_schedular.send_month_day','email_schedular.id','email_sended_ids' ,'email_schedular.lastrun_datetime'
        )->where( 'tbl_email_config.is_manual',0)->orderby('email_schedular.id','desc')->get();
        // ->where('email_schedular.id',4)
        // $billdate=date("Y-m-d",strtotime('now'));

        //  $billdate="2022-11-26"; 

        $all_downloadinvoices_array=array();

        $all_emails_array=array();

        $all_whatsapp_array=array();

        $schedular_sended_ids=array();

 
        foreach($emailschedulars as $emailschedular){

            $tablename=trim($emailschedular->table_name);

            $this->schedular_id=$emailschedular->id;
            $schedular_sended_ids[ $this->schedular_id]=array();
            $this->schedule=$emailschedular->schedule;

            $this->schedular_last_run=$emailschedular->lastrun_datetime;

            $send_mail_allowed=(trim($emailschedular->send_mail)=='True'?true:false);

            if(!empty($emailschedular->email_sended_ids)){
                $this->schedular_sended_ids=explode(',',$emailschedular->email_sended_ids );;
            }
            else{
                $this->schedular_sended_ids=array();
            }


            $this->schedular_timings=array('send_time'=>$emailschedular->send_time,'send_datetime'=>$emailschedular->send_datetime,'send_weekdays'=>$emailschedular->send_weekdays,'send_months'=>$emailschedular->send_months,'send_month_day'=>$emailschedular->send_month_day);
            $this->tran_table=  $tablename;
            // $docdate_exists= $this->checkDocDateFieldPresent();
 
            $can_ran_schedular= $this->CheckToRunSchedular();
 
 
            if(  $can_ran_schedular==false){
                continue;
            } 
 
            // $table_ids= DB::table($tablename)->when(   $docdate_exists==true,function($query)use($billdate){
            //     $query->where('Docdate','>=',$billdate)->pluck('Id');
            // })->pluck('Id');
             
            // if(count($table_ids)==0){
            //     continue;
            // } 
            $print_temp_id=$emailschedular->print_temp;

            $whatsapp_template_id =$emailschedular->whatsapp_template_id;
            

            // $email_subject=$emailschedular->email_subject;

            // $email_body=$emailschedular->email_body;

            if(!empty( $whatsapp_template_id)){
                $whatsapp_template_id=  TableSmsHeader::where('tempid', $whatsapp_template_id )->value('msg_txt');

                $whatsapp_template_id=trim($whatsapp_template_id);
            }

         
            $this->config_id=$emailschedular->config_id; 

           $validated_result= $this->ValidateEmailConfigurationGroupFromData();
 

           $report_name=TblPrintHeader::where('Tempid', $print_temp_id)->value('crystal');
           $this->report_name=   $report_name;

       

           foreach(  $validated_result as $table_id=>$send_data){

                    $send_email_singles=array();

                    $send_whatsapp_singles=array();
 
                    $this->data_id=$table_id;

                    // if($this->data_id!=21031){
                    //     continue;
                    // }

                    if(in_array($this->data_id,  $this->schedular_sended_ids)){
                        continue;
                    } 


                    array_push($schedular_sended_ids[ $this->schedular_id] ,$this->data_id);  


                    $send_emails=$send_data['emails'];

                    $send_whatsapp_no=$send_data['whatsapp'];

                    $invoice_url= $this->getInvoicePdfUrl(); 

                    $download_detail= $this->downloadInvoiceFromUrl();   

                    
                    array_push(  $all_downloadinvoices_array,array('file_url'=>    $invoice_url,'file_name'=>$download_detail['file_name']));
 
          
                    // $invoice_exists=  Storage::disk('public')->exists("invoices_pdf/". $download_detail['file_name']);
          
                    // if($invoice_exists==true){
          
                    // Storage::disk('public')->delete("invoices_pdf/". $download_detail['file_name']);
                    // }
          
                                                    
                    // $invoice_url_contents = file_get_contents($invoice_url);
                                        
                    // Storage::disk('public')->put('invoices_pdf/'.$download_detail['file_name'],       $invoice_url_contents); 
          
                    $pdf_downloaded_url=asset('storage/invoices_pdf/'. $download_detail['file_name']); 
 

                    $email_subject=$send_data['email_subject'];

                    $email_body=$send_data['email_body'];

                    if(   $send_mail_allowed==false){
                        goto send_whatsapp_messages;
                    }

                    $send_email_string=implode(',',$send_emails);
 
                    
                    $send_email_msg=array('to'=> $send_email_string,'subject'=>$email_subject,'body'=>$email_body,'filename'=>$download_detail['file_name'],'showfilename'=>$download_detail['file_name'],'schedular_id'=>$this->schedular_id,'txn_id'=>$this->data_id,'db_name'=>$this->db_name);
               
                     array_push($all_emails_array, $send_email_msg);
 
    
                    // $send_whatsapp_singles
    
                    send_whatsapp_messages:
    
                    foreach($send_whatsapp_no as $whatsapp_no){
    
                        array_push($send_whatsapp_singles,array('template_id'=>$whatsapp_template_id ,'mob_num'=>$whatsapp_no,'first_name'=>'Anonymus','last_name'=>'Anonymus','gender'=>'male','pdf_link'=>$pdf_downloaded_url,'db_name'=>$this->db_name,'txn_id'=>$this->data_id,'schedular_id'=>$this->schedular_id));
    
                    } 
    
                      $all_whatsapp_array=array_merge(    $all_whatsapp_array,$send_whatsapp_singles);
 
      
           } 

 
 
        }
 
          dump($schedular_sended_ids);
        $this->updateSchedularSendedIds($schedular_sended_ids); 

        }
        catch(\Exception $e){
            dd($e->getMessage());
        }
        
 
       return  array( 'invoices_data'=>$all_downloadinvoices_array,'emails_data'=>$all_emails_array,'whatsapp_data'=>  $all_whatsapp_array) ;
    }


    public function CheckToRunSchedular(){

        // Log::info($this->schedular_id." ".$this->schedule." ".$this->schedular_last_run);
 
        $schedular_id=$this->schedular_id;

        if(!empty($this->schedular_last_run)){
            // $last_run_datetime=date("Y-m-d H:i",strtotime($this->schedular_last_run));

            $last_run_datetime=trim($this->schedular_last_run) ;
        }
        else{
            $last_run_datetime=NULL;
        }

        
    
        $timings=$this->schedular_timings;

        $currentdate=date("Y-m-d",strtotime('now'));
 
        $currenttime=date("Y-m-d H:i",strtotime('now'));

        $currentweekday=date("w",strtotime('now'));

        $currentmonth=date('n',strtotime('now'));

        $currentmonthday=date('j',strtotime('now'));
 

        // hourly start
        if($this->schedule=="Hourly"){


            $send_time_array=explode(":",$timings['send_time']);

            $send_time_hours=$send_time_array[0];
            

            $send_time_mins=$send_time_array[1]; 

            $sendtime=date("H:i",strtotime($timings['send_time']));

            if( empty($last_run_datetime)){
 
                EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                return true;

            }
            else{
 
                $new_rundatetime=date('Y-m-d H:i',strtotime('+'.$send_time_hours.' hour +'.$send_time_mins.' minutes',strtotime($last_run_datetime)));

 
                if(   $currenttime>     $new_rundatetime){

                    EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                    return true;

                }
                else{
 

                    return false;

                }
 
            }
 
// hourly ends
        }
        else if($this->schedule=="Daily"){

          $sendtime=date("H:i",strtotime($timings['send_time']))  ;

          $sendtodaydatetime=date("Y-m-d ".$sendtime,strtotime('now'));

 

          if( empty($last_run_datetime)  &&  $currenttime>=$sendtodaydatetime){
            EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
       
            return true;

        }
        else{


            $date_lastrun=date('Y-m-d', strtotime($last_run_datetime));

            if(   $date_lastrun< $currentdate &&  $currenttime>=$sendtodaydatetime ){
 
                EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
       
                 return true;

            }
            else{
 
                return false;

            }
 
        }

        //   dd(   $sendtodaydatetime);
 

        }
        else if($this->schedule=="Days"){

            $send_weekdays_string= trim($timings['send_weekdays']);

            $send_weekdays_array=explode(',',  $send_weekdays_string);

            if(!in_array(  $currentweekday, $send_weekdays_array)){
                return false;
            }

            $sendtime=date("H:i",strtotime($timings['send_time']))  ;

            $sendtodaydatetime=date("Y-m-d ".$sendtime,strtotime('now'));
 
            if( empty($last_run_datetime)  &&  $currenttime>=$sendtodaydatetime){
                EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                return true;
    
            }
            else{
    
    
                $date_lastrun=date('Y-m-d', strtotime($last_run_datetime));
    
                if(   $date_lastrun< $currentdate &&  $currenttime>=$sendtodaydatetime ){
     
                    EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                     return true;
    
                }
                else{
     
                    return false;
    
                }
     
            }
     

        }
        else if($this->schedule=="Months"){ 
            
            $send_months_string= trim($timings['send_months']);

            $send_months_array=explode(',',  $send_months_string);

            if(!in_array(  $currentmonth, $send_months_array)){
                return false;
            }

            $send_monthday=trim($timings['send_month_day']);

            if(   $currentmonthday!= $send_monthday){
                return false;
            }
 
            $sendtime=date("H:i",strtotime($timings['send_time']))  ;

            $sendtodaydatetime=date("Y-m-d ".$sendtime,strtotime('now'));

            if( empty($last_run_datetime)  &&  $currenttime>=$sendtodaydatetime){
                EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                return true;
    
            }
            else{
    
    
                $date_lastrun=date('Y-m-d', strtotime($last_run_datetime));
    
                if(   $date_lastrun< $currentdate &&  $currenttime>=$sendtodaydatetime ){
     
                    EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                     return true;
    
                }
                else{
     
                    return false;
    
                }
     
            }
 
        }
        else if($this->schedule=="Specific"){
 
            $senddatetime=date("Y-m-d H:i" ,strtotime($timings['send_datetime']));
 
            if( empty($last_run_datetime)  &&  $currenttime>= $senddatetime){
 
                EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                return true;
    
            }
            else{
     
                $datetime_lastrun=date('Y-m-d H:i:s', strtotime($last_run_datetime));
    
                if(   $datetime_lastrun< $currenttime &&  $currenttime>=$senddatetime ){
     
                    EmailSchedular::where('id', $schedular_id)->update(['lastrun_datetime'=> $currenttime]);
           
                     return true;
    
                }
                else{
     
                    return false;
    
                }
     
            }
 
        }
 
        return false;

    } 


    public function updateSchedularSendedIds($sended_ids){


        if(count($sended_ids)==0){
            return ;
        }


        foreach($sended_ids as $schedular_id=>$table_ids){

            $email_sended_ids_string=EmailSchedular::where('id',$schedular_id)->value('email_sended_ids');

            if(empty( $email_sended_ids_string)){
                $email_sended_ids_array=array();
            }
            else{
                $email_sended_ids_array=explode(",",$email_sended_ids_string); 
            }

            $email_sended_ids_array=array_merge(  $email_sended_ids_array ,$table_ids);

            EmailSchedular::where('id',$schedular_id)->update(['email_sended_ids'=>implode(',', $email_sended_ids_array)]);

 
        }

    }


    public function checkDocDateFieldPresent(){

       $docdate_exists= FieldsMaster::where('Table_Name',$this->tran_table)->where('Field_Name','docdate')->exists();

       return $docdate_exists;

    }

}


?>