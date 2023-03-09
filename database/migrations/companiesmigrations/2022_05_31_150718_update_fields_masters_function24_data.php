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
         $fields=  FieldsMaster::where('Field_Function',24)->select('Key Field','Scr Field','Map Field','Field_Name','Id','Table_Name')->get();


         foreach(   $fields as    $field){

            $keyfield=$field->{'Key Field'};

            $fieldname=$field->Field_Name;

            $tablename=$field->Table_Name;

            $id=$field->Id;

            $newscrfield=$keyfield;
 

            FieldsMaster::where(['Id'=>$id,'Table_Name'=>$tablename,'Field_Name'=>$fieldname])->update(['Scr Field'=>$newscrfield ]);

         }


        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
