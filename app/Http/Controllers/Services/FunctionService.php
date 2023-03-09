<?php
namespace App\Http\Controllers\Services;

use App\Models\FieldsMaster;
use DB;
use App\Models\Code;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\User;
use App\Models\Uom;


class FunctionService{
    public $tablename;
    public $fieldname;
    public $scrfield;
    public $fieldval;
    public $search="";
    public $currency;
    public $dategiven;


    public function getFunction2FieldValues(){

       $fieldvaluedetail=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Name',  $this->fieldname)->select('Field_Value')->first();
 
        if(!empty( $fieldvaluedetail->Field_Value)){
            $fieldvaluearray=explode(',',  $fieldvaluedetail->Field_Value);
        }
        else{
            $fieldvaluearray=array();
        }
  
        $responsearray=array();

        $index=0;
        foreach($fieldvaluearray as $fieldval){
            array_push($responsearray,array('id'=>$fieldval,'text'=>$fieldval));
            $index++;

        }

        return  $responsearray;

    }


    public function getFunction2_OptionDetail(){


       $fieldvaluedetails=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Function',2)->select('Field_Name','Field_Value')->get();

        $responsearray=array();

        foreach( $fieldvaluedetails as  $fieldvaluedetail){

            if(!empty( $fieldvaluedetail->Field_Value)){
                $fieldvaluearray=explode(',',  $fieldvaluedetail->Field_Value);
            }
            else{
                $fieldvaluearray=array();
            }

            if(count($fieldvaluearray)==1){
                $responsearray[  strtolower($fieldvaluedetail->Field_Name) ]=array('noofoptions'=>count($fieldvaluearray),'single_id'=>$fieldvaluearray[0],'single_text'=>$fieldvaluearray[0]);

            }
            else{
                $responsearray[  strtolower($fieldvaluedetail->Field_Name)]=array('noofoptions'=>count($fieldvaluearray),'single_id'=>'','single_text'=>'');

            }

        

        }

        return      $responsearray;
    }



    public function getFunction3FieldValues(){

        $fielddetail=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Name' ,$this->fieldname)->first();

        
        $query1detail=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Name',$fielddetail->{'Scr Field'})->first(); 
 
        $fieldvalues=array();


        if( !empty(trim($query1detail->From_Table))  && !empty(trim($query1detail->{'Scr Field'}))){
    
            $fromtable=trim($query1detail->From_Table);

            $scrfield=trim($query1detail->{'Scr Field'});

            $mapfield=trim($fielddetail->{'Map Field'});  

            $fieldvalues=DB::table( $fromtable)->where(rtrim($scrfield), rtrim($this->fieldval))->select($mapfield.' as id',$mapfield.' as text')->get()->toArray();
             
            
        }

        return   $fieldvalues;
      
    }



    public function getFunction3FieldValueCheckOptions(){

        $responsearray=array();
        
        $fielddetails=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Function',3)->where('SCR Field', $this->scrfield)->where('Field_Name','<>',$this->scrfield)->get();
        
        foreach($fielddetails as $fielddetail){

            $fieldvalue=''; 

            $query1detail=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Name',$fielddetail->{'Scr Field'})->first(); 
            $noofoptions=0;
            if( !empty(trim($query1detail->From_Table))  && !empty(trim($query1detail->{'Scr Field'}))){
    
                $fromtable=trim($query1detail->From_Table);
    
                $scrfield=trim($query1detail->{'Scr Field'});
    
                $mapfield=trim($fielddetail->{'Map Field'});  
 
                $fieldvaluedetails=DB::table( $fromtable)->where(rtrim($scrfield), rtrim($this->fieldval))->select($mapfield)->pluck($mapfield);
                $noofoptions=count($fieldvaluedetails);
                
                $fieldvalue=  $fieldvaluedetails[0] ;  

                // if(is_numeric($fieldvalue)){
                //     $fieldvalue= sprintf('%0.2f',$fieldvalue);
                   
                // }
                
            }
         
        
            array_push($responsearray,array('field_name'=>strtolower($fielddetail->Field_Name),'field_value'=>$fieldvalue,'noofoptions'=>  $noofoptions));
        
        }


        return $responsearray;

    }



    public function getFunction5Codes(){

        $completecodes=Code::where(['table_name'=> $this->tablename,'Field'=> $this->fieldname])->select( DB::raw("CONCAT(prefix ,code  ,suffix) AS completecodes"))->pluck('completecodes');

        $codearray=array();

        foreach($completecodes as $completecode){
            array_push(  $codearray ,array('id'=>$completecode , 'text'=>$completecode) );

        }

        return   $codearray;

    }


    public function getFunction5CodeCheckOptions(){

        $tablefields=FieldsMaster::where('Table_Name',$this->tablename)->where('Field_Function',5)->pluck('Field_Name');

        $responsearray=array();

        foreach( $tablefields as $fieldname){
            $noofcodes=Code::where(['table_name'=> $this->tablename,'Field'=> $fieldname])->count();
            $singleid='';
            $singletext='';

            if( $noofcodes==1){
                $codevalue=Code::where(['table_name'=> $this->tablename,'Field'=> $fieldname])->select(DB::raw("CONCAT(prefix ,code  ,suffix) AS completecodes"))->value('completecodes');
                $singleid=$codevalue;
                $singletext=$codevalue;
            }

            $responsearray[strtolower($fieldname)]=array('noofoptions'=>$noofcodes,'single_id'=>$singleid,'single_text'=>$singletext);


         }

         return  $responsearray;

    }



    public function getFunction14CurrencyOptions(){

        $currencies=Currency::orderby('currname','asc')->select('id','currname')->get()->toArray();

        $noofcuurency=count($currencies);

      
        if($noofcuurency==1){
            $responsearray=array('noofcurrency'=>$noofcuurency,'single_id'=> $currencies[0]['id'],'single_text'=>$currencies[0]['currname']);

        }
        else{
            $responsearray=array('noofcurrency'=>$noofcuurency,'single_id'=>'','single_text'=>'','data'=>$currencies);

        }


    }


    public function getFunction14AllCurrencies(){
 
        $currencies=Currency::where('currname','LIKE','%'. $this->search.'%')->orderby('currname','asc')->select('id','currname as text')->get()->toArray();

        return    $currencies;
    }


    public function getFunction18UsernameById(){
        $usernamestring='';

        $username=User::where('id',$this->fieldval)->select('user_id')->value('user_id');

        if(!empty(  $username)){
            $usernamestring=$username;
        }


        return $usernamestring; 
    }


    public function geFunction14CurrencyNameById(){

        $currencynamestring='';

         $currname= Currency::where('id',$this->fieldval)->value('currname');

         if(!empty( $currname)){
            $currencynamestring=$currname;

         }
         return   $currencynamestring;
    }

    public function getFunction16UomNameById(){

        $uomnamestring='';

        $uomname= Uom::where('id', $this->fieldval)->value('name');
        
        if(!empty($uomname)){
            $uomnamestring=$uomname;
        }

        return   $uomnamestring;
    }
 

}