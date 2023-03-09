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
         DB::table('tbl_main_menu')->where('Menu_name','Copy Transaction')->where('parent',47)->where('main_parent',3)->update(['url'=>'copy-transaction']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('tbl_main_menu')->where('Menu_name','Copy Transaction')->where('parent',47)->where('main_parent',3)->update(['url'=>'CopyTranx.aspx']);
 
    }
};
