<?php

namespace App\Http\Controllers\Configuration;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\TableSmsHeader;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use App\Models\EmailSchedular;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\TblEmailConfig;

class DesignSmsFormatController extends AppBaseController
{
    public function index($companyname){
        $tablemaster = TableMaster::orderby('table_label','asc')->select("id","Table_Name","table_label")->get();
        $fieldmaster = FieldsMaster::select("id","Table_Name","fld_label")->get();
        $smsheader = TableSmsHeader::orderby('tempid','desc')->get();
        return view("configuration.designsmsformat",compact('tablemaster','fieldmaster','smsheader','companyname'));
         
    }

    public function add(Request $request){
        if($request->id){
           TableSmsHeader::where("tempid",$request->id)->update([
              "tempname" => $request->SmsTemplate,
              "txn_name" => $request->SelectTransaction,
              "msg_txt" => trim($request->MessageText)
           ]);
           return  response()->json(["status"=>"success" ,"message"=>"Whatsapp configuration updated successfully "]);
        }else{

            $tempidexists= TableSmsHeader::where('txn_name',trim($request->SelectTransaction))->exists();
 
           $TableSmsHeader = new TableSmsHeader;
           $TableSmsHeader->tempname = $request->SmsTemplate;
           $TableSmsHeader->txn_name = $request->SelectTransaction;
           $TableSmsHeader->msg_txt = trim($request->MessageText);
           $TableSmsHeader->save();
           return  response()->json(["status"=>"success" ,"message"=>"Whatsapp configuration saved successfully "]);
        }
    }

    public function delete($companyname,$id){
     
            TableSmsHeader::where("tempid",$id)->delete();
              return  response()->json(["status"=>"success" ,"message"=>"Whatsapp configuration deleted successfully "]);
          
     }

    public function field($companyname,$name){
        $fieldmaster = FieldsMaster::orderby('fld_label','asc')->select("id","Field_Name","fld_label")->where("Table_Name",$name)->get();
        echo json_encode($fieldmaster);
    }


    public function deleteSmsFormats($companyname,Request $request){

       $tempids=$request->tempids;
        
        TableSmsHeader::whereIn("tempid", $tempids)->delete();

        return response()->json(['status'=>'success','message'=>"Whatsapp Formats Deleted successfully"]);


    }

    public function emailSchedular($companyname,Request $request){

        $tablemaster = TableMaster::orderby('table_label','asc')->select("id","Table_Name","table_label")->get();

        $msg="";

        if($request->method()=="POST"){
 

            $email_conf=$request->email_configuration;
            $schedule=$request->schedule;
            $send_datetime_string=$request->send_datetime;
            $weekdays=(isset($request->weekdays)?$request->weekdays:array());
            $months=(isset($request->months)?$request->months:array());
            $month_day=$request->month_day;
            $send_time =$request->send_time;
            $id=$request->id;

            if(empty( $id)){
                $msg="Email Schedule Saved successfully";

            }
            else{
                $msg="Email Schedule Updated successfully";
            }
        
            if( $schedule=="Hourly" || $schedule=="Daily" ){
                EmailSchedular::updateOrCreate( ['id'=> $id] , ['email_configuration_id'=> $email_conf,'schedule'=> $schedule,'send_time'=>$send_time ,'send_datetime'=>NULL,'send_weekdays'=>NULL,'send_months'=>NULL,'send_month_day'=>NULL,'lastrun_datetime'=>NULL]);
            }
            else if($schedule=="Days"){
                EmailSchedular::updateOrCreate( ['id'=> $id] , ['email_configuration_id'=> $email_conf,'schedule'=> $schedule,'send_time'=>$send_time ,'send_datetime'=>NULL,'send_weekdays'=>implode(',',$weekdays),'send_months'=>NULL,'send_month_day'=>NULL,'lastrun_datetime'=>NULL]);
           
            }
            else if($schedule=="Months"){
                EmailSchedular::updateOrCreate( ['id'=> $id] ,['email_configuration_id'=> $email_conf,'schedule'=> $schedule,'send_time'=>$send_time ,'send_datetime'=>NULL,'send_weekdays'=>NULL,'send_months'=> implode(',',$months) ,'send_month_day'=> $month_day,'lastrun_datetime'=>NULL]);
         
            }
            else if($schedule=="Specific"){

                $senddatetime_array=explode(" ",  $send_datetime_string);
                $senddatetime_datestring=$senddatetime_array[0];

                $senddatetime_datestring=formatDateInYmd($senddatetime_datestring);
                $senddatetime_time=$senddatetime_array[1];

                $send_datetime_string=$senddatetime_datestring." ".$senddatetime_time;
 
                EmailSchedular::updateOrCreate( ['id'=> $id] ,['email_configuration_id'=> $email_conf,'schedule'=> $schedule,'send_time'=>NULL ,'send_datetime'=>$send_datetime_string,'send_weekdays'=>NULL,'send_months'=>NULL,'send_month_day'=>NULL,'lastrun_datetime'=>NULL]);
         
            }
 
         return redirect()->back()->with('message',$msg);
        }
      
        $emailschedulars=EmailSchedular::join('tbl_email_config', 'email_schedular.email_configuration_id','=','tbl_email_config.id')->select('tbl_email_config.email_configuration_name' ,
        'email_schedular.email_configuration_id',  'email_schedular.schedule','email_schedular.send_time','email_schedular.send_datetime'
        ,'email_schedular.send_weekdays'   ,'email_schedular.send_months'  ,'email_schedular.send_month_day','email_schedular.id'
        )->orderby('email_schedular.id','desc')->paginate(8);
  

        $email_configs= TblEmailConfig::where('is_manual',0)->pluck('email_configuration_name','id')->toArray();
 

        return view('configuration.emailschedular',compact('tablemaster','companyname','emailschedulars','email_configs')); ;

    }


    public function getTableIds($companyname,Request $request){

        $request_data=$request->all();

       
        $search_term= (array_key_exists('searchTerm',$request_data)?$request_data['searchTerm']:NULL ); 

        $tablename= $request_data['data']['table_name'];

        $ids_array= DB::table($tablename)->when(!empty($search_term),function($query)use( $search_term){
            $query->where('Id','Like','%'.$search_term.'%');

        })->orderby('Id','desc')->select('Id as id','Id as text')->limit(5)->get()->toArray();

         return response()->json($ids_array);
 
    }


    public function deleteEmailSchedulars($companyname,Request $request){
  
       $ids= $request->tempids; 

       EmailSchedular::whereIn('id', $ids)->delete(); 
       
       return response()->json(['status'=>'success','message'=>'Email Schedular deleted successfully']);
 
    }
}