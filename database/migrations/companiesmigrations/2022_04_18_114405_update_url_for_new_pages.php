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
        DB::table('tbl_main_menu')->where('Menu_name','Voucher Scheduler')->where('parent',3)->where('main_parent',3)->update(['url'=>'voucher-scheduler']);
 
        DB::table('tbl_main_menu')->where('Menu_name','Field Conditions')->where('parent',3)->where('main_parent',3)->update(['url'=>'field-conditions']);
 
        DB::table('tbl_main_menu')->where('Menu_name','Email Configuration')->where('parent',3)->where('main_parent',3)->update(['url'=>'email-configuration']);
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('tbl_main_menu')->where('Menu_name','Voucher Scheduler')->where('parent',3)->where('main_parent',3)->update(['url'=>'VoucherSchedule.aspx']);
 
        
        DB::table('tbl_main_menu')->where('Menu_name','Field Conditions')->where('parent',3)->where('main_parent',3)->update(['url'=>'fieldcond.aspx']);

        
        DB::table('tbl_main_menu')->where('Menu_name','Email Configuration')->where('parent',3)->where('main_parent',3)->update(['url'=>'emailconfig.aspx']);
 

    }
};
