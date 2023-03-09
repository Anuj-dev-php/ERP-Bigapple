<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\FieldCondition;

class FieldConditionController extends Controller
{
     public function index($companyname){
        
      $transactions= TableMaster::orderby('table_label','asc')->get()->pluck( 'table_label','Table_Name');
 

        return view("configuration.fieldconditions",compact('companyname','transactions'));
     }


     public function edit(){


        return view("configuration.editfieldconditions");
     }


     public function searchTransactions(Request $request){
         $searchterm=$request->searchTerm;
         $exceptid=$request->except_id;

        $transactions= TableMaster::where('table_label','LIKE','%'.$searchterm.'%')->when(!empty($exceptid),function($query)use($exceptid){
            $query->where('Table_Name','<>',$exceptid);
        })->take(10)->select('table_label as text','Id as id')->get()->toArray();

        return response()->json($transactions);


     }


     public function getTransactionFields(Request $request){

      $trantable=$request->tran_table;

      $fields=FieldsMaster::where(function($query)use($trantable){
         
         $query->where('Table_Name',$trantable)->orwhere('Table_Name',$trantable."_det");

      })->where('Field_Function',4)->select('fld_label as  label','Field_Name as name')->get();

       $conditionfields= FieldCondition::where('table_name',$trantable)->select('field_name','field_value','rest_field','rest_value','condition')->get()->toArray();
       
       $valuesarray=array();
       $fieldconditions=array();

       $fieldcondition=new FieldCondition;

      if(!empty($conditionfields)){
         $index=0;

         foreach($conditionfields as $conditionfield){
 
            $fieldconditions[$index]['field_name']= $conditionfield['field_name'];
            $fieldconditions[$index]['condition']= $conditionfield['condition'];
    
    

            $fieldvaluelabel= $fieldcondition->getValueNameByValueIdFromFieldTable($trantable,$conditionfield['field_name'],$conditionfield['field_value']);
    
    
            $fieldconditions[$index]['field_value_label']=$fieldvaluelabel;
            $fieldconditions[$index]['field_value_id']=$conditionfield['field_value'];

             $fieldconditions[$index]['rest_field']=$conditionfield['rest_field'];
    
             if(!empty($conditionfield['rest_value'])){
               $fieldconditions[$index]['rest_value_id']=$conditionfield['rest_value'];
                $restvaluelabel=$fieldcondition->getValueNameByValueIdFromFieldTable($trantable,$conditionfield['rest_field'],$conditionfield['rest_value']);
                $fieldconditions[$index]['rest_value_label']= $restvaluelabel ; 
             } 
             else{
               $fieldconditions[$index]['rest_value_label']=NULL;
               $fieldconditions[$index]['rest_value_id']=NULL;
             }


             $index++;

         }

      
      } 
 
      if(count( $fieldconditions)>0){
         $fieldconditionhtml=view('configuration.fieldconditiontredit', compact('fields','fieldconditions'))->render(); 
               
            return response()->json(['fields'=>$fields,'fieldconditionhtml'=>$fieldconditionhtml,"conditionfields"=>$conditionfields,'fieldconditions'=>$fieldconditions]);
      }
      else{
         
         return response()->json(['fields'=>$fields,'fieldconditionhtml'=>"not found"]);
      }
 
 


     }


     public function getFieldValues($compnayname,Request $request){
 
       
         $fieldname=$request->data['fieldname'];

         $tranname=$request->data['tablename'];

         $searchterm=$request->searchTerm;

         $exceptid=$request->except_id; 

        $fieldmaster= FieldsMaster::where('Table_Name',$tranname)->where('Field_Name',$fieldname)->where('Field_Function',4)->select('Display Field','Scr Field','From_Table')->first()->toArray();
       

        $fieldvalues=DB::table($fieldmaster['From_Table'])->where($fieldmaster['Display Field'],'LIKE','%'.$searchterm.'%')->when(!empty($exceptid),function($query)use($exceptid,$fieldmaster){
            $query->where($fieldmaster['Scr Field'],'<>',$exceptid);
        })->orderby($fieldmaster['Display Field'],'asc')->take(10)->select($fieldmaster['Scr Field'].' as id',$fieldmaster['Display Field'].' as text')->get()->toArray();

        return response()->json($fieldvalues);
 
     }



     public function getNewTransactionFieldRow($compayname,Request $request){
        $rownum=$request->row;
        $transaction=$request->transaction;
        $fields=  FieldsMaster::where(function($query)use($transaction){
         
         $query->where('Table_Name', $transaction)->orwhere('Table_Name', $transaction."_det"); 
        })->where('Field_Function',4)->select('fld_label as  label','Field_Name as name')->get();


          $html=view("configuration.fieldconditiontr",compact('rownum','fields'))->render();

      return response()->json($html);

     }
 

     public function saveTransactionFieldValues($companyname,Request $request){
 
         $transaction=$request->transaction;

         $fieldnames=$request->field_name;

         $conditions=$request->condition;

         $valueids=$request->value; 

         $restrictfieldnames=empty($request->restrict_field)?array():$request->restrict_field;

         $restrictvalueids=empty($request->restrict_value)?array():$request->restrict_value;


         FieldCondition::where(['table_name'=>$transaction])->delete();

         if(!empty($fieldnames)){
            
         $fieldconditions=array();

         $newFieldCondition=new FieldCondition;

         $index=0;
         foreach($fieldnames as $fieldname){

            $fieldvalueid=$valueids[$index]; 
            
           $rest_field=(empty($restrictfieldnames[$index])?NULL:$restrictfieldnames[$index]); 

           $rest_value=( array_key_exists($index,$restrictvalueids )?$restrictvalueids[$index]:NULL); 

            array_push($fieldconditions,array('table_name'=>$transaction,'field_name'=>$fieldname,'condition'=> $conditions[$index],'field_value'=>$fieldvalueid,'rest_field'=>  $rest_field,'rest_value'=>$rest_value));
            $index++;
         }

            FieldCondition::insert($fieldconditions);
            
         }


            return redirect('/'.$companyname.'/field-conditions')->with('message', 'Field Condition saved successfully');

     }


     public function checkFieldConditionView(){
         

      $trantable="newsalesinvoice";

      $fields=FieldsMaster::where('Table_Name',$trantable)->where('Field_Function',4)->select('fld_label as  label','Field_Name as name')->get();

       $conditionfields= FieldCondition::where('table_name',$trantable)->select('field_name','field_value','rest_field','rest_value','condition')->first()->toArray();

       $fieldconditions=array();

       $fieldcondition=new FieldCondition;

      if(!empty($conditionfields)){
         $index=0;

         foreach($conditionfields as $conditionfield){
 
            $fieldconditions[$index]['field_name']= $conditionfields['field_name'];
            $fieldconditions[$index]['condition']= $conditionfields['condition'];
    
    
            $fieldvaluelabel= $fieldcondition->getValueNameByValueIdFromFieldTable($trantable,$conditionfields['field_name'],$conditionfields['field_value']);
    
    
            $fieldconditions[$index]['field_value']="<option value='".$conditionfields['field_value']."' selected='selected'>".$fieldvaluelabel."</option>";
            $fieldconditions[$index]['field_value_id']=$conditionfields['field_value'];

             $fieldconditions[$index]['rest_field']=$conditionfields['rest_field'];
    
             if(!empty($conditionfields['rest_value'])){
               $fieldconditions[$index]['rest_value_id']=$conditionfields['rest_value'];
                $restvaluelabel=$fieldcondition->getValueNameByValueIdFromFieldTable($trantable,$conditionfields['rest_field'],$conditionfields['rest_value']);
                $fieldconditions[$index]['rest_value']="<option value='".$conditionfields['rest_value']."' selected='selected'>". $restvaluelabel."</option>"; 
             } 
             else{
               $fieldconditions[$index]['rest_value']=NULL;
               $fieldconditions[$index]['rest_value_id']=NULL;
             }


             $index++;

         }

      
      } 
 
 
       return view('configuration.fieldconditiontredit', compact('fields','fieldconditions')); 
  


     }


     public function getAllTransactionFields(Request $request){

      $trantable=$request->tran_table;

      $fields=FieldsMaster::where(function($query)use($trantable){
         
         $query->where('Table_Name',$trantable)->orwhere('Table_Name',$trantable."_det");

      })->select('fld_label as  label','Field_Name as name')->get();

         return response()->json($fields);

     }
}
