<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblPrintHeader;
use App\Models\TableMaster;

class CreateFormatController extends Controller
{
    public function index($companyname){ 

       $printheaders= TblPrintHeader::orderby('Tempid','desc')->get();

        return view('configuration.createformat',compact('companyname','printheaders'));
    }


    public function addUpdateCreateFormat($companyname,$tempid=null){

        if(!empty($tempid)){
            $template=  TblPrintHeader::find($tempid);
 

        }
        else{
            $template=null;  
        }

        $transactions=TableMaster::orderby('table_label','asc')->get()->pluck('table_label','Table_Name');
 
 
        return view('configuration.addeditcreateformat',compact('companyname','template' ,'transactions'));
    }


    public function submitCreateFormat($companyname,Request $request){

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

       $printborders=isset($request->print_borders)?"True":"False";

       $crystaltemplate=empty($request->crystaltemplate)?NULL:$request->crystaltemplate; 

       $link=empty($request->link)?NULL:$request->link;

       if(isset($request->tempid)){

        TblPrintHeader::where('Tempid',$request->tempid)->update(array('TempName'=> $templatename   ,'Txn_Name'=>  $transactiontable
        ,'Head_Size'=>     $headersize
        ,'Body_Size'=>        $bodysize
        ,'Footer_Size'=>   $footersize
        ,'Max_Body_lines'=>   $maxlinesbody
        ,'Height'=> $height
        ,'Width'=> $width
        ,'print_cols'=>$printbodycolumns
        ,'print_border'=>   $printborders
        ,'crystal'=>  $crystaltemplate
        ,'link'=>$link)
    );
        $msg="Format updated successfully";
       }
       else{
        TblPrintHeader::insert(array('TempName'=> $templatename   ,'Txn_Name'=>  $transactiontable
        ,'Head_Size'=>     $headersize
        ,'Body_Size'=>        $bodysize
        ,'Footer_Size'=>   $footersize
        ,'Max_Body_lines'=>   $maxlinesbody
        ,'Height'=> $height
        ,'Width'=> $width
        ,'print_cols'=>$printbodycolumns
        ,'print_border'=>   $printborders
        ,'crystal'=>  $crystaltemplate));
        $msg="Format created successfully";

       }



       return redirect('/'.$companyname."/create-format")->with('message',$msg);
 
    }



    public function deleteCreateFormat(Request $request){

        $tempids=$request->tempids;

        TblPrintHeader::whereIn('Tempid',$tempids)->delete();

        return response()->json(['status'=>'success','message'=>'Format Deleted successfully']); 

    }
}
