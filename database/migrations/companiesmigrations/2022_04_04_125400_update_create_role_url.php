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
        DB::table('tbl_main_menu')->where('id',53)->where('Menu_name','Create Role')->update(['url'=>'roles-menu']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('tbl_main_menu')->where('id',53)->where('Menu_name','Create Role')->update(['url'=>'rolesMenu.aspx']);
    }
};
