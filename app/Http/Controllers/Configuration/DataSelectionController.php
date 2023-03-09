<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\Log;
use DB;
use App\Http\Controllers\Services\Function4FilterService;

class DataSelectionController extends Controller
{
     
    protected $function4filterservice;


    public function __construct(Function4FilterService $ffs){

        $this->function4filterservice=$ffs;

    }



    public function index($companyname,Request $request){

        $tables=TableMaster::pluck('table_label','Table_Name');

        $dataselections= DB::table('tbl_user_keyvalue')->orderby('id','desc')->get();
 
     
        return view("configuration.dataselection",compact('companyname','tables','dataselections'));
    }


    public function getFunction4FieldsFromTable($companyname,Request $request){
 
           
        $tranid=$request->tran_id;
 

        $fields=FieldsMaster::where('Table_Name','LIKE',  $tranid)->where('Field_Function',4)->orderby('fld_label','asc')->select('Field_Name','fld_label')->get();
 
 
        return response()->json(['fields'=>$fields]);

    }


    public function submitDataSelection(Request $request){

        $dataselectionid=$request->data_selection_id;


        $transaction=$request->transaction;

        $keyfield=$request->keyfield;

        $defaultvalue=$request->defaultvalue;

        $user=\Auth::user();
   

        if(empty( $dataselectionid)){

           $alreadyexists= DB::table('tbl_user_keyvalue')->where(['table_name'=> $transaction,'key_fld'=> $keyfield])->exists();

            if(   $alreadyexists==true){
                
               return redirect()->back()->with('message',"Data Selection already exists for Field ". $keyfield);

            }
 

            DB::table('tbl_user_keyvalue')->insert(['table_name'=> $transaction,'key_fld'=> $keyfield,'username'=> $user->user_id,'default_value'=>$defaultvalue]);
            $msg="added";
        }
        else{
            DB::table('tbl_user_keyvalue')->where('id',  $dataselectionid)->update(['table_name'=> $transaction,'key_fld'=> $keyfield,'username'=> $user->user_id,'default_value'=>$defaultvalue]);
            $msg="updated";
           }
    
        return redirect()->back()->with('message',"Data Selection ".$msg." successfully");


    }


    public function getAllDataSelection(){
        
        $dataselections= DB::table('tbl_user_keyvalue')->orderby('id','desc')->get();

        $response=array('data'=>$dataselections);

        return response()->json($response);
    }


    public function editDataSelectionById($companyname,$dataselectionid){

        $data=DB::table('tbl_user_keyvalue')->where('id',$dataselectionid)->first();

       $fieldlabel= FieldsMaster::where('Field_Name',$data->key_fld)->value('fld_label');
 
       $data->field_label= $fieldlabel;

       $this->function4filterservice->tablename=$data->table_name;

        $data->default_value_displayname= $this->function4filterservice->getFunction4FieldValueUsingId($data->key_fld,$data->default_value);
 
        return response()->json($data);
  
    }


    public function getFunction4KeyFieldAllValues(Request $request){
 
        $trantable=$request->data['trantable'];

        $fieldname=$request->data['fieldname'];

        
        $search=$request->searchTerm;

 

        $fieldfound=FieldsMaster::where(['Table_Name'=>$trantable,'Field_Name'=>$fieldname])->select('From_Table','Display Field','Scr Field')->first();;
         

        $values=DB::table($fieldfound->From_Table)->where($fieldfound->{'Display Field'},'LIKE' ,'%'.$search.'%')->limit(10)->pluck($fieldfound->{'Display Field'}, $fieldfound->{'Scr Field'});
        $options=array();

        foreach(  $values as $key=>$value){
            array_push(  $options,array('id'=>$key,'text'=>$value));

        }

        return response()->json( $options); 


    }


    public function deleteUserKeyValue($companyname,$id){

 
         DB::table('tbl_user_keyvalue')->where('id',$id)->delete();


         return redirect()->back()->with('message','Data Selection Deleted successfully');;

    }
}
