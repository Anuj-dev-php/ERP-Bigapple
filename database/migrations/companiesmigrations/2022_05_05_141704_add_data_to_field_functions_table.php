<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data=[
            ['Id'=>1,'Name'=>'To be entered'],
            ['Id'=>2,'Name'=>'Select From List'],
            ['Id'=>3,'Name'=>'Auto Populate'] ,
            ['Id'=>4,'Name'=>'Key Field'],
            ['Id'=>5,'Name'=>'Auto Generate'],
            ['Id'=>6 ,'Name'=>'Calendar'] ,
            ['Id'=>8,'Name'=>'File Upload'] ,
            ['Id'=>11,'Name'=>'Formula'] ,
            ['Id'=>14,'Name'=>'currency'] ,
            ['Id'=>15,'Name'=>'Exchange Rate' ] ,   
             ['Id'=>16,'Name'=>'UOM' ] ,
             ['Id'=>17,'Name'=>'Batch No'] ,
             ['Id'=>18,'Name'=>'Users List'] ,
             ['Id'=>19 , 'Name'=>'Check Box List' ] ,
             ['Id'=>20 ,'Name'=>'Logged User'] ,
             ['Id'=>21,'Name'=>'Previous Value' ] ,
             ['Id'=>22,'Name'=>'Account Balance'] ,  
             ['Id'=>24 ,'Name'=>'Auto Populate from Header'] ,
             ['Id'=>26, 'Name'=>'Label'],
             ['Id'=>27,'Name'=>'System time'] ,
             ['Id'=>30,'Name'=>'Auto Populate Multikey'] ,
             ['Id'=>31,'Name'=>'Date time'] ,
             ['Id'=>33,'Name'=>'Lookup'] ,
             ['Id'=>34,'Name'=>'To be entered Multiline'] ,
             ['Id'=>35,'Name'=>'Lookup Body'] ,
             ['Id'=>40,'Name'=>'Image Upload'] ,
             ['Id'=>41,'Name'=>'Other Exc Rate'] ,
             ['Id'=>45,'Name'=>'Avg Rate'] 

        ];  
        
       DB::table('field_functions')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('field_functions')->delete();
    }
};
