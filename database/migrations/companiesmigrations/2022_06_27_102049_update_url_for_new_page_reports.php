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
        //
        DB::table('tbl_main_menu')->where('Menu_name','Sub Ledger')->where('parent',19)->where('main_parent',2)->update(['url'=>'subledger']);
        DB::table('tbl_main_menu')->where('Menu_name','General Ledger')->where('parent',19)->where('main_parent',2)->update(['url'=>'general-ledger']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::table('tbl_main_menu')->where('Menu_name','Sub Ledger')->where('parent',19)->where('main_parent',2)->update(['url'=>'subledger.aspx?book=ledger&rpt_name=Sub Ledger']);
        DB::table('tbl_main_menu')->where('Menu_name','General Ledger')->where('parent',19)->where('main_parent',2)->update(['url'=>'Report1.aspx']);
    }
};
