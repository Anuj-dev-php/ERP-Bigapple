<?php
namespace App\Http\Controllers\Services;

use  App\Models\User;
use App\Models\UserLocation;
use App\Models\UserCustomer;
use App\Models\UserSalesmen;
use App\Models\TblRestrictCustomer;
use App\Models\UserEmployee;
use App\Models\UserProducts;
use App\Models\ProductMaster;
use App\Models\FieldsMaster;
use App\Models\FieldCondition;
use Session;
use DB;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;
use App\Models\TblUserDiv;
use App\Models\TblUserPC;
use App\Models\TblUserCC;

class Function4FilterService{

    public $user;
    public $search="";
    public $fromtable;
    public $tablename;
    public $fieldname;
    public $fieldval;
    public $detail_tablename;

    public function setUserAndFromTable($currentuser,$frmtable){

        $this->user=$currentuser;
        $this->fromtable=$frmtable;

    }

    public function getUserDataRestrictionIds(){

        $ids=array();

        if(strcasecmp($this->fromtable,'Location')==0 ){ 
           $ids= UserLocation::where('uid',$this->user->id)->pluck('Loc')->toArray(); 

        }
        else if(strcasecmp($this->fromtable,'Customers')==0 ){

            $ids= UserCustomer::where('uid',$this->user->id)->pluck('cst')->toArray();
     
        }
        else if(strcasecmp($this->fromtable,'SalesMen')==0 ){
            $ids=UserSalesmen::where('uid',$this->user->id)->pluck('s_exe')->toArray();
    
        }
        else if(strcasecmp($this->fromtable,'tbl_party_type')==0 ){

            $ids=TblRestrictCustomer::where('uid',$this->user->id)->pluck('party_type_id')->toArray();


        }
        else if(strcasecmp($this->fromtable,'tbl_empmaster')==0){

           $ids= UserEmployee::where('uid',$this->user->id)->pluck('empid')->toArray();

        }
        else if(strcasecmp($this->fromtable,'Division')==0 ){

            $ids= TblUserDiv::where('uid',$this->user->id)->pluck('div')->toArray();
           
        }
        else if(strcasecmp($this->fromtable,'Costcentre')==0 ){

            $ids=TblUserCC::where('uid',$this->user->id)->pluck('cc')->toArray();
           
          
        }
        else if(strcasecmp($this->fromtable,'profitcentre')==0 ){

            $ids=TblUserPC::where('uid',$this->user->id)->pluck('pc')->toArray();
          
        }
        else if(strcasecmp($this->fromtable,'Product_master')==0){

            $productids=UserProducts::where('uid',$this->user->id)->pluck('prd_grp')->toArray();

            $givenproductids= $productids;

            $noofchildren=ProductMaster::whereIn('parent',$productids)->get()->count();
 
            $childrenids=array(); 
    
            while($noofchildren>0){
    
                $productids=  ProductMaster::whereIn('parent',$productids)->get()->pluck('Id')->toArray();
    
    
                $childrenids=array_merge( $childrenids,  $productids);
    
                
               $noofchildren=ProductMaster::whereIn('parent',$productids)->get()->count();
    
    
            }
    
    
            $ids=array_merge( $givenproductids, $childrenids);

 
        }
        
        return $ids;

    }



    public function setTableNameFieldNameAndFieldVal($tablename ,$fieldname,$fieldval){


        $this->fieldname=$fieldname;

        $this->fieldval=$fieldval;

        $this->tablename=$tablename;

    }


    public function getFunction4RelatedRestrictedFieldValueCondition(){
  
       
       $fieldconditions= FieldCondition::where(['table_name'=>$this->tablename, 'field_name'=>$this->fieldname])->select( 'condition','field_value','rest_field','rest_value','id')->get();
 
       $resultarray=array();


       foreach($fieldconditions as $fieldcondition){

            $conditionstring= $fieldcondition->condition;

            $restfieldname= $fieldcondition->rest_field;

            $restfieldvalue=$fieldcondition->rest_value;

            if(
                is_numeric($this->fieldval)
                &&
                (
                (trim($conditionstring)=="=" &&  $this->fieldval== $fieldcondition->field_value)
                ||
                (trim($conditionstring)=="<>" &&  $this->fieldval!=$fieldcondition->field_value)
                ||
                (trim($conditionstring)=="<" &&  $this->fieldval<$fieldcondition->field_value)
                ||
                (trim($conditionstring)==">" &&  $this->fieldval>$fieldcondition->field_value)
                )
                
                ){ 

                 $fielddisplayvalue=$this->getFunction4FieldValueUsingId($restfieldname,$restfieldvalue);

                 array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>$fielddisplayvalue,'rest_field_value'=>$restfieldvalue));
                } 
                else if( trim($conditionstring)=="starts_with"    && strpos($this->fieldval ,$fieldcondition->field_value)==0 ){
                    $fielddisplayvalue=$this->getFunction4FieldValueUsingId($restfieldname,$restfieldvalue);

                    array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>$fielddisplayvalue,'rest_field_value'=>$restfieldvalue));
                 

                }
                else if( trim($conditionstring)=="contains"    && strpos($this->fieldval ,$fieldcondition->field_value)!==false){
                    $fielddisplayvalue=$this->getFunction4FieldValueUsingId($restfieldname,$restfieldvalue);

                    array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>$fielddisplayvalue,'rest_field_value'=>$restfieldvalue));
                 

                }
                else if( trim($conditionstring)=="ends_with"    && str_ends_with($this->fieldval ,$fieldcondition->field_value)==true){
                    $fielddisplayvalue=$this->getFunction4FieldValueUsingId($restfieldname,$restfieldvalue);

                    array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>$fielddisplayvalue,'rest_field_value'=>$restfieldvalue));
                 
                }
                else if( trim($conditionstring)=="like"    &&  $this->fieldval==$fieldcondition->field_value  ){
                    $fielddisplayvalue=$this->getFunction4FieldValueUsingId($restfieldname,$restfieldvalue);

                    array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>$fielddisplayvalue,'rest_field_value'=>$restfieldvalue));
                 
                }
                else if( trim($conditionstring)=="notlike"    &&  $this->fieldval==$fieldcondition->field_value  ){
                    $fielddisplayvalue=$this->getFunction4FieldValueUsingId($restfieldname,$restfieldvalue);

                    array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>$fielddisplayvalue,'rest_field_value'=>$restfieldvalue));
                 

                }
                else{

                    array_push(  $resultarray  ,array('rest_field_name'=> $restfieldname,'rest_field_display'=>'','rest_field_value'=>''));
                 

                }
           

       }



       return  $resultarray;

    }




    public function getFunction4FieldValueUsingId($restfieldname,$restfieldvalue,$is_detail=false){


        if($is_detail==true){
            $tablename=$this->detail_tablename;
        }
        else{
            $tablename=$this->tablename;
        } 
 

        $fromtabledetail=FieldsMaster::where('Table_Name',    $tablename)->where('Field_Name', $restfieldname)->select('From_Table','Scr Field','Display Field' )->first();

        $fromtablename=$fromtabledetail->From_Table;

        $scrfield=  $fromtabledetail->{'Scr Field'};

        $displayfield=$fromtabledetail->{'Display Field'};

        $fielddisplay=DB::table($fromtablename)->where( $scrfield,$restfieldvalue)->value($displayfield);

        return trim($fielddisplay);

 
    }



    public function getDefaultOption($foundfieldvalue,$filteredids,$isofcustomers){

     $defaultvalue=DB::table('tbl_user_keyvalue')->where(['table_name'=>$this->tablename,'key_fld'=>$this->fieldname])->value('default_value');

     if(empty($defaultvalue)){
         return NULL;
     }

     $hasfieldvalue=false;

     if(!empty($foundfieldvalue)){
        $hasfieldvalue=true;
     }

     $hasfilteredids=false;


     if(count($filteredids)>0){
        $hasfilteredids=true;
     }


  
     $fromtabledetail=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Name',$this->fieldname)->select('fld_label','Field_Name','From_Table','Scr Field','Display Field')->first();

 
      $displayvalue= DB::table( $fromtabledetail->From_Table)->where( trim($fromtabledetail->{'Scr Field'}),trim($defaultvalue))
      ->when( $hasfieldvalue,function($query)use($foundfieldvalue){

        $query->whereRaw($foundfieldvalue);
      })
      ->when($hasfilteredids,function($query)use($filteredids){
        $query->whereIn('id',$filteredids);

      })
      ->when($isofcustomers,function($query){
        $query->where('Status','=','3');

      })
      ->value($fromtabledetail->{'Display Field'});

    if(empty($displayvalue)){
        return NULL;
    }

      $responsearray=array("field_display"=>   $displayvalue ,'field_value'=>$defaultvalue);

      return    $responsearray;

    }


    public function getFunction4Options(){


         $fromtabledetail=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Name', $this->fieldname)->select('From_Table','Scr Field','Display Field','Field_Value')->first();

        
        $options=array();

        if(!empty(  $fromtabledetail)){

            $foundfieldvalue=$fromtabledetail->Field_Value;

            $fieldvalueexists=empty($foundfieldvalue)?false:true;

                if( $fieldvalueexists){
                    $foundfieldvalue=str_replace('*',"'",$foundfieldvalue);

                } 

             $scrfield=$fromtabledetail->{'Scr Field'};
             $fromtablename=$fromtabledetail->From_Table;

             $hasfilteredids=false;
             $filteredids=array(); 

             if(strcasecmp(trim($scrfield),'id')==0){

                $currentuser=Auth::user(); 

                $this->setUserAndFromTable($currentuser,$fromtablename);

                $filteredids=$this->getUserDataRestrictionIds();
                $hasfilteredids=(count($filteredids)>0?true:false);

             }
             
            //  check for if customers type then filter out customers for status 3

            $isofcustomers=false;

            if($fromtablename=="Customers"){
                $isofcustomers=true;

            }

            $tablerows=DB::table($fromtablename)->where($fromtabledetail->{'Display Field'},'LIKE' ,'%'.$this->search.'%')->when($fieldvalueexists,function($query)use($foundfieldvalue){
                $query->whereRaw($foundfieldvalue);
            })->when($hasfilteredids,function($query)use($filteredids){
                $query->whereIn('id',$filteredids);
            })->when($isofcustomers,function($query){
                $query->where('Status','=','3');

            })->whereNotNull($scrfield)->where($scrfield,'<>','')->orderby( $fromtabledetail->{'Display Field'},'asc')->limit(10)->pluck( $fromtabledetail->{'Display Field'},$scrfield);
 

            foreach($tablerows as $key=>$value){
 
                    array_push(  $options,array('id'=>$key,'text'=>$value));
             
            }

        } 
       

        return     $options;

    }



    public function getTableFunction4CheckOptions(){
        $fromtabledetails=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Function',4)->select('fld_label','Field_Name','From_Table','Scr Field','Display Field','Field_Value')->get();
 
        $responsearray=array();

       foreach($fromtabledetails as $fromtabledetail){

           $foundfieldvalue=$fromtabledetail['Field_Value'];

           $fieldvalueexists=empty($foundfieldvalue)?false:true;

           if( $fieldvalueexists){
               $foundfieldvalue=str_replace('*',"'",$foundfieldvalue);

           }

           $hasfilteredids=false;
           $filteredids=array();

           if(strcasecmp(trim($fromtabledetail->{'Scr Field'}),'id')==0  ){

              $currentuser=Auth::user();  
              $this->setUserAndFromTable($currentuser,$fromtabledetail->From_Table);
              $filteredids=$this->getUserDataRestrictionIds();
              $hasfilteredids=(count($filteredids)>0?true:false);

           } 

           $isofcustomers=false;

           if($fromtabledetail->From_Table=="Customers"){
               $isofcustomers=true;

           }
 
           $this->fieldname=strtolower($fromtabledetail->Field_Name);

           // check if defaultoption exists if yes then give it to them
             $defaultoption=$this->getDefaultOption($foundfieldvalue,$filteredids,$isofcustomers);
              $hasdefaultvalue=false;
             

             if(!empty($defaultoption)){

               $hasdefaultvalue=true;

               $defaultid=$defaultoption['field_value'];

             }

          $noofoptions= DB::table($fromtabledetail->From_Table)->when($fieldvalueexists,function($query)use($foundfieldvalue){

           $query->whereRaw($foundfieldvalue);

          })->when($hasfilteredids,function($query)use($filteredids){
           $query->whereIn('id',$filteredids);

          })->when($isofcustomers,function($query){
           $query->where('Status','=','3');

              })->count(); 

          if( $noofoptions==1){
            
             $singleoption= DB::table($fromtabledetail->From_Table)->when($fieldvalueexists,function($query)use($foundfieldvalue){

               $query->whereRaw($foundfieldvalue);
   
              })
              ->when($hasfilteredids,function($query)use($filteredids){
               $query->whereIn('id',$filteredids);

              })
              ->when($isofcustomers,function($query){
               $query->where('Status','=','3');
   
                  })
              ->select($fromtabledetail->{'Scr Field'},$fromtabledetail->{'Display Field'})->first() ;
           
             $responsearray[strtolower($fromtabledetail->Field_Name)]=array('noofoptions'=>$noofoptions , 'single_id'=>$singleoption->{$fromtabledetail->{'Scr Field'}},'single_text'=>$singleoption->{$fromtabledetail->{'Display Field'}});

          }
          else{
         


             if(empty( $defaultoption)){
                 
                  $resultarray=array('noofoptions'=>$noofoptions , 'single_id'=>'','single_text'=>'' );

             }
             else{
                 
               $resultarray=array('noofoptions'=>$noofoptions , 'single_id'=>'','single_text'=>'' ,'default_id'=> $defaultoption['field_value'],'default_text'=>$defaultoption['field_display']);

             }

             $responsearray[strtolower($fromtabledetail->Field_Name)]=$resultarray;


          }

       } 

       return $responsearray;
    }



    public function getAllFilteredFieldsWithValues(){

       $fieldnames= FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Function',4)->whereIn('From_Table',array('Location','Customers','SalesMen','tbl_party_type','tbl_empmaster','Product_master','Division','profitcentre','Costcentre'))->select('From_Table','Field_Name')->get()->toArray();
 
       try{
        $result=array();

        foreach($fieldnames as $fieldname){
 
         $this->fromtable=$fieldname['From_Table'];
         
         $restrictedids=(array) $this->getUserDataRestrictionIds();

         if(count( $restrictedids)>0){
            array_push($result,array("field_name"=>$fieldname['Field_Name'],'field_filter_values'=>$restrictedids));
 
         }
      
         // $result[$fieldname['Field_Name']]= $restrictedids;
 
        } 
        return $result;
 
       }
       catch(\Exception $e){

        Log::info($e->getMessage());
        
        Log::info($e->getLine());

       }
    
  
    }



    public function getTableAllFunction4FieldValuesInArray(){

        $fromtabledetails=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Function',4)->select( 'Field_Name','From_Table','Scr Field','Display Field' )->get();
 
        $responsearray=array();

       foreach($fromtabledetails as $fromtabledetail){
 
           $tabledisplay_values=DB::table($fromtabledetail->From_Table)->pluck( $fromtabledetail->{'Display Field'},$fromtabledetail->{'Scr Field'} )->toArray();
           $responsearray[$fromtabledetail->Field_Name]= $tabledisplay_values;
 
       }  
       return        $responsearray;

    }
    

    
}


?>