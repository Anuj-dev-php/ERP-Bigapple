<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use App\Models\Code;
use Illuminate\Support\Facades\Log;

class DefineDocumentNumberController extends Controller
{
  
     public function index($companyname){
 
      $tables=TableMaster::where('Table_Name','not like','%_det')->orderby('Table_Name','asc')->pluck('table_label','Table_Name');

        return view("configuration.definedocumentnumber",compact( 'companyname','tables'));


     }


     public function getTransactionTableCodes($companyname,$tablename){


          $codes=Code::where('table_name',$tablename)->get()->toArray();
          $index=0;
          foreach($codes as $code){
               $prefix=str_replace('-','',$code['prefix']);

               $suffix=str_replace('-','',$code['suffix']);

               $codes[$index]['prefix']=$prefix;

               $codes[$index]['suffix']=$suffix;
 
               $index++;
          }


          return response()->json(['data'=>$codes]);



     }



     public function submitDocumentNumber(Request $request){
 
          $id=$request->id;
          $prefix=$request->prefix;
          $code=$request->code;
          $suffix=$request->suffix;

          $allrequests=$request->all(); 

          if(empty($prefix) || empty($code) || empty($suffix)){
               return;
          }


         Code::where('id',  $id)->update(['prefix'=>$prefix,'code'=>$code,'suffix'=>  $suffix]);

         return response()->json($allrequests);
          

     }
}
