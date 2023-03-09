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
        DB::table('tbl_main_menu')->where('Menu_name','Design Sms Format')->where('parent',49)->where('main_parent',3)->update(['url'=>'design-sms-format']);
   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        DB::table('tbl_main_menu')->where('Menu_name','Design Sms Format')->where('parent',49)->where('main_parent',3)->update(['url'=>'smsprint.aspx']);
    }
};
