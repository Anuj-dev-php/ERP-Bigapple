<?php
namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\Log;
use App\Models\TableSmsHeader;
use App\Models\TblMailConfig;
use App\Models\WhatsappSended;

class WhatsAppService{
 
    public $first_name;

    public $last_name;

    public $mob_num;

    public $gender; 

    public $pdf_link;

    public $todok_access_token;

    public $custom_field_name;
    public $custom_fields;
    public $tran_table;
    public $whatsapp_template_id;
    public $db_name=NULL;
    public $txn_id=NULL;
    public $schedular_id=NULL;
 

    public function __construct(){

        $access_token= TblMailConfig::value('whatsapp_access_token');

        if(!empty(  $access_token)){
          $this->todok_access_token=$access_token;
        }
   
        $this->custom_fields=array('invoice_link'=>'918449','payment_gateway'=>'550963');
 


    }

    public function getUserIdFromMobNumber(){

       
        $curl = curl_init();
        $this->mob_num="91".$this->mob_num;
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.todook.io/api/user',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{ "phone": "'.$this->mob_num.'", "first_name": "'.$this->first_name.'", "last_name": "'.$this->last_name.'", "gender": "'.$this->gender.'" }',
          CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'X-ACCESS-TOKEN: '.$this->todok_access_token,
            'Content-Type: application/json'
          ),
        ));
        
        $response_json = curl_exec($curl);

        // $response_json = `{
        //     "success": true,
        //     "contact_created": false,
        //     "data": {
        //         "id": "`.$this->mob_num.`"
        //     }
        // }`; 
        curl_close($curl);

        $error_msg='';
        if (curl_errno(      $curl)) {
          $error_msg = curl_error(      $curl);
 
      }


        $response_array=json_decode( $response_json,true);
 

        if(!empty( $error_msg)){

          WhatsappSended::create(['first_name'=>$this->first_name,
          'last_name'=>$this->last_name,
          'gender'=>$this->gender,
          'mob_num'=>$this->mob_num,
          'whatsapp_template_id'=>NULL,
          'document_link'=>NULL,
          'status'=>'failed',
          'error_msg'=>$error_msg ,
          'db_name'=>$this->db_name,
          'txn_id'=>$this->txn_id,
          'schedular_id'=>$this->schedular_id
        ]);


          return array('status'=>'failure','message'=> $error_msg);
        }
        else if(array_key_exists('error',$response_array)){

          WhatsappSended::create(['first_name'=>$this->first_name,
          'last_name'=>$this->last_name,
          'gender'=>$this->gender,
          'mob_num'=>$this->mob_num,
          'whatsapp_template_id'=>NULL,
          'document_link'=>NULL,
          'status'=>'failed',
          'error_msg'=>$response_array['error']['message'],
          'db_name'=>$this->db_name,
          'txn_id'=>$this->txn_id,
          'schedular_id'=>$this->schedular_id
        ]);


            return array('status'=>'failure','message'=> $response_array['error']['message']);
        }
        else if(array_key_exists('success',$response_array) && array_key_exists('contact_created',$response_array)){
 

            return array('status'=>'success','message'=>"Contact found" ,'mob_num'=> $response_array['data']['id']);
        }


        
        WhatsappSended::create(['first_name'=>$this->first_name,
        'last_name'=>$this->last_name,
        'gender'=>$this->gender,
        'mob_num'=>$this->mob_num,
        'whatsapp_template_id'=>NULL,
        'document_link'=>NULL,
        'status'=>'failed',
        'error_msg'=>'Invalid data received',
        'db_name'=>$this->db_name,
        'txn_id'=>$this->txn_id,
        'schedular_id'=>$this->schedular_id
      ]);



        return array('status'=>'failure','message'=>'Invalid data received');
 
    }



    public function sendPdfLinkOnWhatsApp(){
         
          $curl = curl_init();  
          // $this->mob_num="91".$this->mob_num;
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.todook.io/api/user/'.$this->mob_num.'/custom_fields/'.$this->whatsapp_template_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'value='.$this->pdf_link,
            CURLOPT_HTTPHEADER => array(
              'accept: application/json',
              'X-ACCESS-TOKEN: '.$this->todok_access_token,
              'Content-Type: application/x-www-form-urlencoded'
            ),
          ));

          $response_json = curl_exec($curl); 
          // $response_json =json_encode(array('success'=>true));
 
 
          $error_msg='';
          if (curl_errno(      $curl)) {
            $error_msg = curl_error(      $curl);
 
        }


          
          curl_close($curl); 
  
        $result_array=json_decode(  $response_json,true);

        if(!empty($error_msg)){
          WhatsappSended::create(['first_name'=>$this->first_name,
          'last_name'=>$this->last_name,
          'gender'=>$this->gender,
          'mob_num'=>$this->mob_num,
          'whatsapp_template_id'=>$this->whatsappp_template_id,
          'document_link'=>$this->pdf_link ,
          'status'=>'failed',
          'error_msg'=>$error_msg,
          'db_name'=>$this->db_name,
          'txn_id'=>$this->txn_id,
          'schedular_id'=>$this->schedular_id,
          'db_name'=>$this->db_name,
          'txn_id'=>$this->txn_id,
          'schedular_id'=>$this->schedular_id
        ]);

          return array('status'=>'failure','message'=> $error_msg );
        }
       else if(array_key_exists('success',  $result_array)){
        WhatsappSended::create(['first_name'=>$this->first_name,
        'last_name'=>$this->last_name,
        'gender'=>$this->gender,
        'mob_num'=>$this->mob_num,
        'whatsapp_template_id'=>$this->whatsapp_template_id,
        'document_link'=>$this->pdf_link ,
        'status'=>'processed',
        'db_name'=>$this->db_name,
        'txn_id'=>$this->txn_id,
        'schedular_id'=>$this->schedular_id,
        'db_name'=>$this->db_name,
        'txn_id'=>$this->txn_id,
        'schedular_id'=>$this->schedular_id
      ]);
            return array('status'=>'success','message'=>'Whatsapp Sended successfully');
        }
        else{

          WhatsappSended::create(['first_name'=>$this->first_name,
          'last_name'=>$this->last_name,
          'gender'=>$this->gender,
          'mob_num'=>$this->mob_num,
          'whatsapp_template_id'=>$this->whatsapp_template_id,
          'document_link'=>$this->pdf_link ,
          'status'=>'failed',
          'error_msg'=>   $result_array['error']['message'],
          'db_name'=>$this->db_name,
          'txn_id'=>$this->txn_id,
          'schedular_id'=>$this->schedular_id,
          'db_name'=>$this->db_name,
          'txn_id'=>$this->txn_id,
          'schedular_id'=>$this->schedular_id

        ]);

            return array('status'=>'failure','message'=>     $result_array['error']['message'] );

        } 
 
    }



    public function getWhatsappTemplateId(){

      $msgtext= TableSmsHeader::where('txn_name',$this->tran_table)->value('msg_txt');


      if(!empty($msgtext)){
        return trim($msgtext);
      }
      else{
        return NULL;
      }



    }


    public function getCustomFieldsFromAccount(){
  
      $curl = curl_init(); 
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.todook.io/api/accounts/custom_fields',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'accept: application/json',
          'X-ACCESS-TOKEN: '.$this->todok_access_token,
          'Content-Type: application/json'
        ),
      ));
      
      $response_json = curl_exec($curl);


       $response_array= json_decode(     $response_json , true);

       return   $response_array;


    }


}


?>