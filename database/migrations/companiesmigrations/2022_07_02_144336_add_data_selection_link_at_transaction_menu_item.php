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
        DB::table('tbl_main_menu')->insert(['Menu_name'=>'Data Selection','url'=>'data-selection','parent'=>47,'main_parent'=>3]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('tbl_main_menu')->where(['Menu_name'=>'Data Selection','url'=>'data-selection','parent'=>47,'main_parent'=>3])->delete();
    }
};
