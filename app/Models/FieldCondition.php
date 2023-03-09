<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class FieldCondition extends Model
{
    use HasFactory;
    protected $table="tbl_fld_cond";
    protected $fillable=['table_name','field_name', 'condition','field_value','rest_field','rest_value'];



    public function getValueNameByValueIdFromFieldTable($tablename,$fieldname,$valueid){
 

       $fielddetail= FieldsMaster::where(['Table_Name'=>$tablename,'Field_Name'=>$fieldname,'Field_Function'=>4])->select('Scr Field','Display Field','From_Table')->first()->toArray();

       if(empty($fielddetail)){
           return '';
       } 

  
      $fieldname= DB::table( $fielddetail['From_Table']  )->where($fielddetail['Scr Field'],$valueid)->value($fielddetail['Display Field']);
 

      return $fieldname;
 
    }


    public function getFieldLabelFromFieldName($tablename,$fieldname){

      $fieldlabel =FieldsMaster::where('Table_Name',$tablename)->where('Field_Function',4)->where('Field_Name',$fieldname)->value('fld_label');


      if(empty(   $fieldlabel)){
          return '';
      }

      return   $fieldlabel;

    }

 

}
