<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TableMaster;
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

        TableMaster::insert(['Table_Name'=>'tbl_empmaster','table_label'=>'Employee Master']);

        FieldsMaster::insert(['Id'=>1
        ,'Field_Id'=>'F1F'
        ,'Table_Name'=>'tbl_empmaster'
        ,'Field_Name'=>'Id'
        ,'Field_Type'=>'integer'
        ,'Field_Size'=>0
        ,'Field_Function'=>12 
        ,'Tab_Id'=>'None' 
        ,'Allow Null'=>'False'
        ,'Is Primary'=>'True' 
        ,'Formula Field'=>''
        ,'Tab Seq'=>0
        ,'Searchable'=>'False'
        ,'Width'=>40
        ,'fld_label'=>'Id'
        ,'fld_unique'=>NULL
        ,'fld_post'=>'False'
        ,'lbl_width'=>150
        ,'min_char'=>NULL    ]);
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        FieldsMaster::where(['Table_Name'=>'tbl_empmaster'])->delete();
        TableMaster::where(['Table_Name'=>'tbl_empmaster'])->delete();
    }
};
