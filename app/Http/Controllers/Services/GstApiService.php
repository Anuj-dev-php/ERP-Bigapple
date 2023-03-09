<?php
namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Cache;
use App\Models\Customer;
use App\Models\ProductMaster;
use DB;
use App\Models\TblAuditData;
use App\Models\GstApiCredentials;
use App\Models\GstError;

class GstApiService{


    public $gst_server_url;
    public $subscription_key;
    public $gstin;
    public $data;
    public $username;
    public $password;
    public $appkey;
    public $force_refresh_token;
    public $gst_invoice_auth_token;
    public $gst_invoice_certificate_file; 
    public $data_id;
    public $gst_invoice_sek_key; 
    public $delete_ids;
    public $tran_table;
    public $check_gst_no;
 

    public function __construct(){

        $gst_credential_array=  GstApiCredentials::where('active',1)->first()->toArray();

        $this->gst_server_url= trim($gst_credential_array['server_url']);
        $this->subscription_key= trim($gst_credential_array['subscription_key']);
        $this->gstin= trim($gst_credential_array['gstin']);
        $this->username= trim($gst_credential_array['username']);
        $this->password= trim($gst_credential_array['password']);
        $this->appkey="V3g1Sk9RUFBWQU9WRURpbmZPMXlZTE44Z3lUVDRLTE8=";
        $this->force_refresh_token= trim($gst_credential_array['force_refresh_token']);
        $this->gst_invoice_certificate_file= trim($gst_credential_array['gst_invoice_certificate_file']);

    }




    public function setGstInvoiceAuthToken(){
 
                $gst_invoice_auth_token= Cache::get('gst_invoice_auth_token');

                if(!empty( $gst_invoice_auth_token)){

                    $this->gst_invoice_auth_token=$gst_invoice_auth_token;

                    $sek_invoice_key=Cache::get('gst_invoice_sek_key');

                    if(!empty( $sek_invoice_key)){
    
                        $this->gst_invoice_sek_key=  $sek_invoice_key;
    
                    }

                    return array('status'=>true,'message'=>'Already present','auth_token'=>$gst_invoice_auth_token,'sek_key'=>$sek_invoice_key);
                }
 

            $payload= base64_encode("{\"UserName\":\"".$this->username."\",\"Password\":\"".$this->password."\",\"AppKey\":\"".$this->appkey."\",\"ForceRefreshAccessToken\":".$this->force_refresh_token."}");
    

            $certificatefilepath=storage_path('app/gst_certificates/'.$this->gst_invoice_certificate_file);

            $key=file_get_contents( $certificatefilepath);

            openssl_public_encrypt(   $payload,$crypted,$key );

    
            $this->data=base64_encode($crypted);
             
            $curl = curl_init();

            curl_setopt(  $curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt(  $curl, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->gst_server_url.'/eivital/v1.04/auth',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"Data":"'.$this->data.'"}',
            CURLOPT_HTTPHEADER => array(
                'Ocp-Apim-Subscription-Key: '.$this->subscription_key,
                'Gstin: '.$this->gstin,
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);
  
            if (   $response  === FALSE) { 
               return array('status'=>false,'message'=> curl_error($curl));
            }  
 
            curl_close($curl);



            if(empty($response)){
               return array('status'=>false,'message'=>'No Message');
            }


 
           $json_response_array=json_decode($response,true);

 
           if( $json_response_array==null   && !array_key_exists('Status',$json_response_array)){
               return array('status'=>false,'message'=>'Incorrect Message');
           }
 
           if($json_response_array["Status"]==0){
              return array('status'=>false,'message'=>$json_response_array["ErrorDetails"][0]['ErrorMessage'],'code'=>$json_response_array["ErrorDetails"][0]['ErrorCode']);

           }

           
           if($json_response_array["Status"]==1){


            $token_expiry_time=$json_response_array["Data"]["TokenExpiry"];

            $time_now = strtotime('now');
            $expiry_time= strtotime( $token_expiry_time);

            $no_of_seconds=  $expiry_time-$time_now;
      
             Cache::put('gst_invoice_auth_token',$json_response_array["Data"]["AuthToken"] ,  $no_of_seconds);
             
              $this->gst_invoice_auth_token=$json_response_array["Data"]["AuthToken"];
 
            //   base64_decode(
              $this->gst_invoice_sek_key=  $json_response_array["Data"]["Sek"] ;

              Cache::put('gst_invoice_sek_key',  $this->gst_invoice_sek_key ,  $no_of_seconds);

             return array('status'=>true,'message'=>'GST Invoice Auth Token generated successfully' ,'auth_token'=>$json_response_array["Data"]["AuthToken"],'sek_key'=>$this->gst_invoice_sek_key );
            }
 
    }



    public function generateIRN($tranaccountid){

         $tabledata=DB::table($this->tran_table)->where('Id',$this->data_id)->first();
         $tabledata=(array)$tabledata; 
         $tabledata= array_change_key_case($tabledata,CASE_LOWER); 
 
         $companymaster=DB::table("Companymaster")->where('Id', $tabledata['fromcompany'])->first();

         $companymaster_data=(array)  $companymaster;
         $companymaster_data= array_change_key_case($companymaster_data,CASE_LOWER); 

         $customer_data= Customer::where('Id', $tabledata['cust_id'])->first()->toArray();
         $customer_data= array_change_key_case( $customer_data,CASE_LOWER);

 

         $assval=$tabledata['gross_amount']-$tabledata['discount']+$tabledata['lotcharge'];

         $assval=(int)  $assval;

         $details_data=DB::table('GSI_det')->where('fk_Id',$this->data_id)->get()->toArray();
         $details_data=(array)    $details_data;

         $detailindex=0;
         foreach( $details_data as  $details_single_data){
            $details_data[$detailindex]= array_change_key_case( (array)$details_single_data,CASE_LOWER); 
            $detailindex++;
         }
 

         $detail_product_ids=array_column( $details_data,'product');
          

          $products_used=  ProductMaster::whereIn('Id', $detail_product_ids)->pluck('product code','Id') ;
          
         $details_data_array=[];


         $sno=1;
         foreach($details_data as $details_data_single){
            $details_data_single=(array)$details_data_single;

            $total_item_val= $details_data_single['assessiblvalu']+ $details_data_single['sgstamount']+ $details_data_single['cgstamout']+$details_data_single['igstamoutn'];
 
            $totamt= round( ($details_data_single['amount']+$details_data_single['transport']),2);

            array_push($details_data_array,  [
               "SlNo" => (string)  $sno, 
               // generate for each row of detail table data
               "Unit"=>"NOS",
               "IsServc" =>  ($tranaccountid==146?"Y":"N"), 
               // if on submit form tranaccountid is 146 then Y else N
               "HsnCd" =>  $products_used[$details_data_single['product']] , 
               //get [product code] field value from dbo.productmaster where dbo.gsi_det.product=dbo.product_master.id
               "Qty" =>round( $details_data_single['quantity'],2), 
               //get quantity field
               "UnitPrice" => round( $details_data_single['rate'],2), 
            //  rate
               "TotAmt" =>  $totamt, 
               // amount
               "AssAmt" => round( ($details_data_single['assessiblvalu']+$details_data_single['transport']),2), 
               // assisblvalue
               "GstRt" => round(  ($details_data_single['tax']>$details_data_single['igstpercentage']?$details_data_single['tax']:$details_data_single['igstpercentage']),2),  
               
            //  Greater of the 2 values of tax & igstpercentage from dbo.gsi_det
               
               "IgstAmt"=>  round($details_data_single['igstamoutn'],2),
               //igstamoutn
            "cgstAmt"=> round( $details_data_single['cgstamout'],2),
            //cgstamout
            "sgstAmt"=> round( $details_data_single['sgstamount'],2),
            //sgstamount
               "TotItemVal" => round( ($total_item_val+$details_data_single['transport']),2), 
            // assessiblvalu+sgstamount+cgstamout+igstamoutn
            "Discount"=>round( $details_data_single['amount']*($details_data_single['disc']/100),2)
               ] );

               $sno++;

         }


         if($this->tran_table=="GSR" || $this->tran_table=="GSRA"){
            $docdetail_type="CRN";
         }
         else{

            $docdetail_type="INV";
         }

         $invoice_docno=trim($tabledata['docno']);
 
            $data_array= [
               "Version" => "1.1", 
               "TranDtls" => [
                     "TaxSch" => "GST", 
                     "SupTyp" => "B2B", 
                  //   if taxtype=17 and excised=13 then SEZWOP else B2B 
                  ], 
               "DocDtls" => [
                        "Typ" => "INV", 
                     //  same as above
                        "No" => $invoice_docno,
                     //  get docno field value 
                        "Dt" =>date("d/m/Y",strtotime($tabledata['docdate']))
                     //  get docdate field value

                     ], 
               "SellerDtls" => [
                           "Gstin" =>   trim($companymaster_data['gstno']), 
                           //  fromcompany field go to COmpanymaster and get gstno as gst number and use it here 
                           "LglNm" => trim($companymaster_data['companyname']),  
                           //fromcompany field go to COmpanymaster and get company name
                           "Addr1" => trim($companymaster_data['addressline1']),  
                           //fromcompany field go to COmpanymaster and get addressline1 field value
                           "Loc" =>$companymaster_data['addressline3'] , 
                           //fromcompany field go to COmpanymaster and get addressline3 field value
                           "Pin" => (int)  trim($companymaster_data['pincode']), 
                           //fromcompany field go to COmpanymaster and get pin code
                           "Stcd" => substr($companymaster_data['gstno'],0,2),  
                           // fetch first 2 digits of gst number
                        ], 
               "BuyerDtls" => [
                              "Gstin" =>     trim($customer_data['gstno']), 
                              // from customer table using cust_id get gstno
                              "LglNm" =>   $customer_data['cust_id'], 
                                 // from customer table using cust_id get fieldname=cust_id value 
                              "Pos" =>  substr(trim($customer_data['gstno']),0,2), 
                              // fetch first 2 digits of gst number using cust_id
                              "Addr1" =>     trim($customer_data['address line1']),   
                              // from customer table using cust_id get [address line1]
                              "Loc" => trim($customer_data['address line3']),   
                              // from customer table using cust_id get [address line3]
                              "Pin" => (int)trim($customer_data['pincode']), 
                              // from customer table using cust_id get [pincode]
                              "Stcd" =>  substr(trim($customer_data['gstno']),0,2), 
                              // fetch first 2 digits of gst number using cust_id
                              
                           ], 
                        "ItemList" => $details_data_array, 
                              "ValDtls" => [
                                             "AssVal" =>    $assval, 
                                             //gross amount -discount+iotcharge
                                             "TotInvVal" =>round($tabledata['net_amount'],2),
                                             //net_amount
                                             "IgstVal"=> round($tabledata['exciseamt'],2),
                                             //exciseamt
                                             "cgstVal"=>round($tabledata['cgstamount'],2),
                                             //cgstamount
                                             "sgstVal"=>round($tabledata['taxamt'],2)
                                             //taxamt
                                             
                                          ], 
                                    
                              ]; 
 
                      
            $data_json=json_encode($data_array); 

            Log::info(   $data_json);
 
            $finalsekkey=openssl_decrypt(base64_decode( $this->gst_invoice_sek_key),"AES-256-ECB",base64_decode( $this->appkey), OPENSSL_RAW_DATA);

            $encrypted_data=openssl_encrypt( $data_json,"AES-256-ECB",$finalsekkey, OPENSSL_RAW_DATA);

            $encrypted_data=base64_encode($encrypted_data);

            $curl = curl_init();
            
            curl_setopt(  $curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt(  $curl, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt_array($curl, array(
              CURLOPT_URL =>   $this->gst_server_url.'/eicore/v1.03/Invoice',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
            "Data": "'.$encrypted_data.'"
            }',
              CURLOPT_HTTPHEADER => array(
               'Ocp-Apim-Subscription-Key: '.$this->subscription_key,
               'Gstin: '.$this->gstin,
                'Content-Type: application/json',
                'user_name: '.$this->username,
                'AuthToken: '.$this->gst_invoice_auth_token
              ),
            ));
            
            $response = curl_exec($curl);

            
            if (   $response  === FALSE) { 
               return array('status'=>false,'message'=> curl_error($curl));
            }  
            
            curl_close($curl); 

            if(empty($response)){
               return array('status'=>false,'message'=>'Null Message');
            }

            $json_response_array=json_decode($response,true);
 

            if(!array_key_exists('Status',$json_response_array)){
                return array('status'=>false,'message'=>'Incorrect Message');
            }
 
 
            if($json_response_array["Status"]==0){
             return array('status'=>false,'message'=>$json_response_array["ErrorDetails"][0]["ErrorMessage"],'code'=>$json_response_array["ErrorDetails"][0]["ErrorCode"],'doc_no'=> $invoice_docno );
 
            }

            if($json_response_array["Status"]==1){

                  return array('status'=>true,"data"=>$json_response_array["Data"]);


            }
             

    }
 


    public function saveIrnGeneratedDetails($response_data){

           $finalsekkey=openssl_decrypt(base64_decode( $this->gst_invoice_sek_key),"AES-256-ECB",base64_decode( $this->appkey), OPENSSL_RAW_DATA);

            $json_response=openssl_decrypt(base64_decode( $response_data),"AES-256-ECB", $finalsekkey, OPENSSL_RAW_DATA);

            $response_array=json_decode(   $json_response,true);
      
            DB::table('GSI')->where('Id',$this->data_id)->update([
               'acknowledgement_no'=>$response_array['AckNo'],
               'acknowledgement_date'=>$response_array['AckDt'],
               'irn'=>$response_array['Irn'] ,
               'signed_invoice'=>$response_array['SignedInvoice'] ,
               'signed_qr_code'=>$response_array['SignedQRCode'] ,
            ]);

 
    }


    public function validateForGstApi(){

       $table_data=  DB::table($this->tran_table)->where('Id',$this->data_id)->select('cust_id','fromcompany','docdate')->first();

        $customer_data=  Customer::where('Id', $table_data->cust_id)->select('gstno','cust_id','address line1','address line3','pincode')->first()->toArray();

       
        if(empty($customer_data['gstno']) || empty($customer_data['cust_id']) || empty(trim($customer_data['address line1']))  || empty(trim($customer_data['address line3']))  || empty(trim($customer_data['pincode'])) ){
          
         return false;
        }

        $frombilldate=date("Y-m-d",strtotime('2022-10-01'));

        $docdate=date("Y-m-d",strtotime($table_data->docdate));

        if($docdate< $frombilldate){
           return false;
        }



        $company_data=DB::table('Companymaster')->where('Id', $table_data->fromcompany)->select('gstno','companyname','addressline1','addressline3','pincode')->first();


        if(empty( $company_data->gstno) || empty( trim($company_data->companyname))  || empty( trim($company_data->addressline1))  || empty( trim($company_data->addressline3))  || empty( trim($company_data->pincode))    ){
          
         return false;
        }
        
        return true;

    }


    public function cancelGstIrnGenerated(){

      $delete_ids=$this->delete_ids;

      $gsi_invoices=DB::table($this->tran_table)->whereIn('Id',$delete_ids)->select('irn','Id','docno')->get();

      $finalsekkey=openssl_decrypt(base64_decode( $this->gst_invoice_sek_key),"AES-256-ECB",base64_decode( $this->appkey), OPENSSL_RAW_DATA);

      $deleted_irn_ids=array();

      $not_deleted_doc_nos=array();
         foreach(  $gsi_invoices as  $gsi_invoice){   

            if(empty($gsi_invoice->irn)){
               
               array_push(    $deleted_irn_ids,$gsi_invoice->Id);

               continue;
            }

                           
               $cancel_irn=["Irn"=>trim($gsi_invoice->irn),"CnlRsn"=>'1',"CnlRem"=>"Wrong entry"];
               $cancel_irn_json=json_encode($cancel_irn);
       
               $encrypted_data=openssl_encrypt($cancel_irn_json,"AES-256-ECB",$finalsekkey, OPENSSL_RAW_DATA);
               
               $encrypted_data=base64_encode( $encrypted_data);

               $curl = curl_init();
               
            curl_setopt(  $curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt(  $curl, CURLOPT_SSL_VERIFYPEER, 0);

               curl_setopt_array($curl, array(
               CURLOPT_URL => $this->gst_server_url.'/eicore/v1.03/Invoice/Cancel',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => 'POST',
               CURLOPT_POSTFIELDS =>'{"Data":"'.$encrypted_data.'"}',
               CURLOPT_HTTPHEADER => array(
                  'Ocp-Apim-Subscription-Key: '.$this->subscription_key,
                  'Gstin: '.$this->gstin,
                   'Content-Type: application/json',
                   'user_name: '.$this->username,
                   'AuthToken: '.$this->gst_invoice_auth_token
               ),
               ));

               $response = curl_exec($curl);

               curl_close($curl);

               $response_array=json_decode(   $response,true);

               if($response_array['Status']==1){

                  $cancel_response_json=openssl_decrypt(base64_decode( $response_array['Data']),"AES-256-ECB", $finalsekkey, OPENSSL_RAW_DATA);

                  $cancel_response_json_array=json_decode($cancel_response_json,true);

                  TblAuditData::where('table_name','GSI')->where('docno',$gsi_invoice->docno)->update(['deleted_irn'=> $cancel_response_json_array['Irn'],'deleted_irn_datetime'=>$cancel_response_json_array['CancelDate']]);

                  array_push(    $deleted_irn_ids,$gsi_invoice->Id);

                 } 
                 else{
                  array_push($not_deleted_doc_nos,$gsi_invoice->docno);
                 }

         }


         return    array('deleted_irn_ids'=>$deleted_irn_ids ,'not_deleted_doc_nos'=>$not_deleted_doc_nos);

    }


    public function checkGstNumberValidity(){

      $curl = curl_init();
      
      curl_setopt(  $curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt(  $curl, CURLOPT_SSL_VERIFYPEER, 0);

         curl_setopt_array($curl, array(
         CURLOPT_URL =>  $this->gst_server_url.'/eivital/v1.03/Master/gstin/'.$this->check_gst_no,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'GET',
         CURLOPT_HTTPHEADER => array(
            'Ocp-Apim-Subscription-Key: '.$this->subscription_key,
            'Gstin: '.$this->gstin,
             'Content-Type: application/json',
             'user_name: '.$this->username,
             'AuthToken: '.$this->gst_invoice_auth_token
         ),
         ));

         $response = curl_exec($curl);

         curl_close($curl);

         if(empty($response)){
            return false;
         }
 
         $response_json_array=json_decode(  $response,true);


         if(    $response_json_array['Status']==1)
         {
            return true;
         }
         else{
            return false;
         }

    }

    public function addGstErrorMessage($userid,$result){

      GstError::insert(['user_id'=>$userid,'table_name'=>$this->tran_table,'error_code'=>(array_key_exists('code',$result)?$result['code']:NULL),'error_message'=>$result['message'],'doc_no'=>$result['doc_no']]);


    }


    public function CheckIrnAlreadyGenerated(){

      
      $irn=DB::table($this->tran_table)->where('Id',$this->data_id)->value('irn');

      if(!empty(  $irn)){
         return true;
      }
      else{
         return false;
      }
  
 
    }
}


?>