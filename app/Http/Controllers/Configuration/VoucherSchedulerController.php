<?php

namespace App\Http\Controllers\Configuration;

// use App\Http\Controllers\Controller;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\VchMain;
use App\Models\VoucherScheduler;
use Illuminate\Support\Facades\Log;

class VoucherSchedulerController extends AppBaseController
{
   public function index($companyname){ 
      $voucherscheduler = VoucherScheduler::all();
      return view("configuration.voucherscheduler",compact('companyname','voucherscheduler'));
   }

   public function add(Request $request){ 

     $vchno= VchMain::where('Id',$request->VoucherNumber)->value('VchNo'); 

      if($request->id){
         // $voucherscheduler = VoucherScheduler::find($request->id);
         VoucherScheduler::where("id",$request->id)->update([
            "VoucherNumber" =>  $vchno,
            "StartDate" => $request->StartDate,
            "EndDate" => $request->EndDate,
            "Frequency" => $request->Frequency
         ]);
         return  response()->json(["status"=>"success" ,"message"=>"Voucher scheduler updated successfully "]);
      }else{
         $voucherscheduler = new VoucherScheduler;
         $voucherscheduler->VoucherNumber =  $vchno;
         $voucherscheduler->StartDate = $request->StartDate;
         $voucherscheduler->EndDate = $request->EndDate;
         $voucherscheduler->Frequency = $request->Frequency;
         $voucherscheduler->save();
         return  response()->json(["status"=>"success" ,"message"=>"Voucher scheduler saved successfully "]);
      }
   }

   public function delete($companyname,$id){
      if($id > 0){
         VoucherScheduler::where("ID",$id)->delete();
         return  response()->json(["status"=>"success" ,"message"=>"Voucher scheduler deleted successfully "]);
      }    
   }
 
   public function searchVoucherNumbers(Request $request){


      $searchterm=empty($request->searchTerm)?'':$request->searchTerm;

      $vchmain = VchMain::where('VchNo','LIKE','%'. $searchterm.'%')->take(5)->select('Id as id', 'VchNo as text')->get()->toArray();

      return response()->json( $vchmain);
   }


   public function getvoucherSchedulerDetail($companyname,$id){

    $voucherscheduler=  VoucherScheduler::where("id",$id)->first(); 

    $vchno= VchMain::where('VchNo',$voucherscheduler->VoucherNumber)->value('Id'); 
    
 
    return response()->json(['voucherscheduler'=> $voucherscheduler ,"voucher_number_id"=>$vchno]);
    

   }


   public function deleteVoucherSchedulers(Request $request){

      $voucherids=$request->vouchers;
      VoucherScheduler::whereIn("id", $voucherids)->delete(); 
      return response()->json(['status'=>'success','message'=>"Voucher Schedulers Deleted Successfully"]);

   }

}