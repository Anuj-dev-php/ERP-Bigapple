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
        //
        DB::table('tbl_main_menu')->where('parent',19)->where('main_parent',2)->where('Menu_name','Sales Register')->update(['url'=>'sales-register']);
    
        DB::table('tbl_main_menu')->where('parent',19)->where('main_parent',2)->where('Menu_name','Purchase Register')->update(['url'=>'purchase-register']);
   
        
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
