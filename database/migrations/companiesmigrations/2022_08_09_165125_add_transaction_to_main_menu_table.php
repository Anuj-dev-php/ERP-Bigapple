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
    
          $rowexists=  DB::table('tbl_main_menu')->where(['Menu_name'=>'Transactions','parent'=>0,'main_parent'=>0])->exists();


        if( $rowexists==false){
            DB::table('tbl_main_menu')->insert(['Menu_name'=>'Transactions','parent'=>0,'main_parent'=>0]);

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     
    }
};
