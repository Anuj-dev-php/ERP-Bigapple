<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('tbl_main_menu')->where(['parent'=>3,'main_parent'=>3,'id'=>170])->update(['url'=>'email-whatsapp-scheduler','Menu_name'=>'Email & Whatsapp Scheduler']);
 
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
