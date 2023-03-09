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
        DB::table('tbl_main_menu')->where('Parent',23)->where('main_parent',2)->where('Menu_name','Cash Book')->update(['url'=>'cash-book-report']);
        DB::table('tbl_main_menu')->where('Parent',23)->where('main_parent',2)->where('Menu_name','Bank Book')->update(['url'=>'bank-book-report']);
        DB::table('tbl_main_menu')->where('Parent',23)->where('main_parent',2)->where('Menu_name','Petty Cash Book')->update(['url'=>'petty-cash-book-report']);
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
