<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 
use App\Models\FieldsMaster;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {  

      $noofrows= FieldsMaster::where('Table_Name','tbl_currancy')->count();

      if($noofrows==0){
        
        FieldsMaster::insert([ 'Id'=>1
        ,'Field_Id'=>'F1F'
        ,'Table_Name'=>'tbl_currancy'
        ,'Field_Name'=>'id'
        ,'Field_Type'=>'integer'
        ,'Field_Size'=>200
        ,'Field_Function'=>1
        ,'Field_Value'=>''
        ,'Tab_Id'=>'header'
        ,'Created_By'=>''
        ,'From_Table'=>''
        ,'Allow Null'=>'True'
        ,'Is Primary'=>'False'
        ,'Scr Field'=>''
        ,'Display Field'=>''
        ,'Map Field'=>''
        ,'Detail Table'=>''
        ,'Key Field'=>''
        ,'Formula Field'=>''
        ,'Tab Seq'=>1
        ,'Searchable'=>'False'
        ,'Width'=>200
        ,'fld_label'=>'ID'
        ,'add_type'=>''
        ,'get_tot'=>'False'
        ,'Align'=>'Left'
        ,'fld_unique'=>'False'
          ]);



          FieldsMaster::insert([ 'Id'=>2
          ,'Field_Id'=>'F2F'
          ,'Table_Name'=>'tbl_currancy'
          ,'Field_Name'=>'currname'
          ,'Field_Type'=>'varchar'
          ,'Field_Size'=>50
          ,'Field_Function'=>1
          ,'Field_Value'=>''
          ,'Tab_Id'=>'header'
          ,'Created_By'=>''
          ,'From_Table'=>''
          ,'Allow Null'=>'True'
          ,'Is Primary'=>'False'
          ,'Scr Field'=>''
          ,'Display Field'=>''
          ,'Map Field'=>''
          ,'Detail Table'=>''
          ,'Key Field'=>''
          ,'Formula Field'=>''
          ,'Tab Seq'=>1
          ,'Searchable'=>'False'
          ,'Width'=>200
          ,'fld_label'=>'Currency'
          ,'add_type'=>''
          ,'get_tot'=>'False'
          ,'Align'=>'Left'
          ,'fld_unique'=>'False'
            ]);




            
          FieldsMaster::insert([ 'Id'=>3
          ,'Field_Id'=>'F3F'
          ,'Table_Name'=>'tbl_currancy'
          ,'Field_Name'=>'change'
          ,'Field_Type'=>'varchar'
          ,'Field_Size'=>50
          ,'Field_Function'=>1
          ,'Field_Value'=>''
          ,'Tab_Id'=>'header'
          ,'Created_By'=>''
          ,'From_Table'=>''
          ,'Allow Null'=>'True'
          ,'Is Primary'=>'False'
          ,'Scr Field'=>''
          ,'Display Field'=>''
          ,'Map Field'=>''
          ,'Detail Table'=>''
          ,'Key Field'=>''
          ,'Formula Field'=>''
          ,'Tab Seq'=>1
          ,'Searchable'=>'False'
          ,'Width'=>200
          ,'fld_label'=>'Decimal Change'
          ,'add_type'=>''
          ,'get_tot'=>'False'
          ,'Align'=>'Left'
          ,'fld_unique'=>'False'
            ]);



            
            
          FieldsMaster::insert([ 'Id'=>4
          ,'Field_Id'=>'F4F'
          ,'Table_Name'=>'tbl_currancy'
          ,'Field_Name'=>'symbol'
          ,'Field_Type'=>'varchar'
          ,'Field_Size'=>50
          ,'Field_Function'=>1
          ,'Field_Value'=>''
          ,'Tab_Id'=>'header'
          ,'Created_By'=>''
          ,'From_Table'=>''
          ,'Allow Null'=>'True'
          ,'Is Primary'=>'False'
          ,'Scr Field'=>''
          ,'Display Field'=>''
          ,'Map Field'=>''
          ,'Detail Table'=>''
          ,'Key Field'=>''
          ,'Formula Field'=>''
          ,'Tab Seq'=>1
          ,'Searchable'=>'False'
          ,'Width'=>200
          ,'fld_label'=>'Dymbol'
          ,'add_type'=>''
          ,'get_tot'=>'False'
          ,'Align'=>'Left'
          ,'fld_unique'=>'False'
            ]);
 
            
          FieldsMaster::insert([ 'Id'=>5
          ,'Field_Id'=>'F5F'
          ,'Table_Name'=>'tbl_currancy'
          ,'Field_Name'=>'decpts'
          ,'Field_Type'=>'integer'
          ,'Field_Size'=>200
          ,'Field_Function'=>1
          ,'Field_Value'=>''
          ,'Tab_Id'=>'header'
          ,'Created_By'=>''
          ,'From_Table'=>''
          ,'Allow Null'=>'True'
          ,'Is Primary'=>'False'
          ,'Scr Field'=>''
          ,'Display Field'=>''
          ,'Map Field'=>''
          ,'Detail Table'=>''
          ,'Key Field'=>''
          ,'Formula Field'=>''
          ,'Tab Seq'=>1
          ,'Searchable'=>'False'
          ,'Width'=>200
          ,'fld_label'=>'Dymbol'
          ,'add_type'=>''
          ,'get_tot'=>'False'
          ,'Align'=>'Left'
          ,'fld_unique'=>'False'
            ]);
            
      }
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        FieldsMaster::where('Table_Name','tbl_currancy')->delete();
    }
};
