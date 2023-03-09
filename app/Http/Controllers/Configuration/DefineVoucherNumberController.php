<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VchType;
use Illuminate\Support\Facades\Log;
use App\Models\VchNumbering;

class DefineVoucherNumberController extends Controller
{
     public function index($companyname){ 


      $vchtypes=VchType::orderby('Name','asc')->get();;
        return view('configuration.definevouchernumbers',compact('companyname','vchtypes'));

     }


     public function addSubVoucherType(Request $request){


          $parent=$request->parent;

          $subtypename=$request->subtypename;

          $vchtype=new VchType;

          $vchtype->Parent=  $parent;

          $vchtype->Name=    $subtypename;

          $vchtype->save();

          $subvchtypes=VchType::where('Parent',    $parent)->get();


           return response()->json(['status'=>'success','message'=>'Sub Voucher Type Added successfully','subvchtypes'=> $subvchtypes]);
 


     }


     public function getSubVoucherTypes($companyname){

          
          $subvchtypes=VchType::orderby('Id','desc')->get()->toArray();


          return response()->json(['data'=>$subvchtypes]);



     }


     public function updateSubVoucherType($companyname , Request $request){
 

          $allrequests=$request->all();

         $action= $request->action;
         $id=$request->Id;
         $name=$request->Name;

         $vchtypefound=VchType::find( $id);

         if($action=="delete"){
          $vchtypefound->subvchtypes()->delete();

          $vchtypefound->delete();

         }
         else{
          $vchtypefound->update(['Name'=> $name]);
         } 
          return response()->json( $allrequests);

     }



     public function getVoucherNumbers($companyname,$vouchertypeid){

          $vchnumbers=VchNumbering::where('VchTypeId',$vouchertypeid)->get()->toArray();

          return response()->json(['data'=>  $vchnumbers]);
     }


     public function addVoucherNumberToVoucherType($companyname,Request $request){

          $prefix=$request->prefix;
          $number=$request->number;
          $suffix=$request->suffix;
          $vchtypeid=$request->voucher_type;

          VchNumbering::insert(array( 
               'VchTypeId'=>    $vchtypeid
               ,'Prefix'=> $prefix
               ,'Number'=>   $number
               ,'Suffix' =>     $suffix
          ));
          
          return response()->json(['status'=>'success','message'=>'Voucher Number Added successfully']);

     }



     public function updateVoucherNumber($companyname,Request $request){
          $allrequests=$request->all();
          $action=$request->action;
          $id=$request->Id; 

     
         $vchnumbering= VchNumbering::find($id); 
          if($action=="edit"){
               $vchnumbering->update(['Prefix'=>$request->Prefix
               ,'Number'=>$request->Number
               ,'Suffix'=>$request->Suffix]); 
          }
          else{
               $vchnumbering->delete();
          }
          
          return response()->json( $allrequests);
     }
}
