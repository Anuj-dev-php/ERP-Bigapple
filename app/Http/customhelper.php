<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

function LogMessage($e,$requestarray=array()){


    // if(count($requestarray)>0){
    //     $requeststring=json_encode($requestarray);
    // }
    // else{
    //     $requeststring='';
    // }

    $msg=$e->getMessage(); 
    $line=$e->getLine();
    $file=$e->getFile();  

    Log::info(    $msg);
    Log::info( "Line=".$line." File=".    $file );
    $msg=substr(   $msg,20);
     DB::table('error_log')->insert(['filename'=> $file,'linenumber'=>$line,'message'=>$msg,'request'=>'']);
 
}


function formatDate($datestring){

    if(empty($datestring)){
        return '';
    }
    else{
        
       $datecreated=date("d/m/Y",strtotime($datestring));
       return    $datecreated;

    }


}

function formatDateInYmd($datestring,$seperator="-"){

    $date_array=explode("-",$datestring);
    $date_array=array_reverse($date_array);

    $newdatstring=implode($seperator,$date_array);

    return   $newdatstring;


}


function formatDateInDmy($datestring,$seperator="-"){

    if(empty($datestring)){
        return '';
    }
    else{
        
       $datecreated=date("d".$seperator."m".$seperator."Y",strtotime($datestring));
       return    $datecreated;

    }


}
 
function reportCorrect($given_string){

    $replaces=array('&'=>' and ');

    foreach( $replaces as  $replace_key=>$replace_val){

        $given_string=str_replace($replace_key,$replace_val,$given_string);

    }


    return  $given_string;
}


function makeReportFileName($format,$report_name,$start_date,$end_date){

    $download_file_name=$report_name."_".str_replace("-","",$start_date)."_".str_replace("-","",$end_date).".".strtolower($format);

    return     $download_file_name;

}

   

?>