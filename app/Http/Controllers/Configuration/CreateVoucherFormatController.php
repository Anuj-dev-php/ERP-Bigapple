<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VchPrintH;
use App\Models\VchType;

class CreateVoucherFormatController extends Controller
{
    public function index($companyname){

 
        $printheaders=VchPrintH::join('VchTypes','tbl_vch_printh.Txn_Name','=','VchTypes.Id')->select('tbl_vch_printh.*','VchTypes.Name as txnname')->orderby('Tempid','desc')->get();
        return view('configuration.createvoucherformat',compact('companyname','printheaders'));

    }
 
    public function addEditVoucherFormat($companyname,$tempid=null){

        if(!empty($tempid)){
            $template=  VchPrintH::find($tempid);
 

        }
        else{
            $template=null;  
        }

        $transactions=VchType::orderby('Name','asc')->get()->pluck('Name','Id');
 
 
        return view('configuration.addeditcreatevoucherformat',compact('companyname','template' ,'transactions'));
    }

     
    
    public function submitCreateVoucherFormat($companyname,Request $request){

        $templatename=$request->template_name;
        $headersize=$request->header_size; 
        $transactiontable=$request->transaction;
        $bodysize=$request->body_size;
        $footersize=$request->footer_size;
        $maxlinesbody=$request->maxlines_body;
        $crystaltemplate=$request->crystaltemplate;
        $height=$request->height;
        $width=$request->width;
      
        $printbodycolumns=isset($request->print_body_columns) ?"True":"False"; 
        
       $crystaltemplate=empty($request->crystaltemplate)?NULL:$request->crystaltemplate; 

       if(isset($request->tempid)){

        VchPrintH::where('Tempid',$request->tempid)->update(array('TempName'=> $templatename   ,'Txn_Name'=>  $transactiontable
        ,'Head_Size'=>     $headersize
        ,'Body_Size'=>        $bodysize
        ,'Footer_Size'=>   $footersize
        ,'Max_Body_lines'=>   $maxlinesbody
        ,'Height'=> $height
        ,'Width'=> $width
        ,'print_cols'=>$printbodycolumns 
        ,'crystal'=>  $crystaltemplate));
        $msg="Voucher Format updated successfully";
       }
       else{
        VchPrintH::insert(array('TempName'=> $templatename   ,'Txn_Name'=>  $transactiontable
        ,'Head_Size'=>     $headersize
        ,'Body_Size'=>        $bodysize
        ,'Footer_Size'=>   $footersize
        ,'Max_Body_lines'=>   $maxlinesbody
        ,'Height'=> $height
        ,'Width'=> $width
        ,'print_cols'=>$printbodycolumns 
        ,'crystal'=>  $crystaltemplate));
        $msg="Voucher Format created successfully";

       } 
       return redirect('/'.$companyname."/create-voucher-format")->with('message',$msg);
 
    }


    public function deleteCreateVoucherFormat(Request $request){

        $tempids=$request->tempids;

        VchPrintH::whereIn('Tempid',$tempids)->delete();

        return response()->json(['status'=>'success','message'=>'Voucher Format Deleted successfully']); 

    }





}
