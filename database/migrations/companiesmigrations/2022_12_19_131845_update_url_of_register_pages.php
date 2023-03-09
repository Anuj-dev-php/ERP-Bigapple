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

        DB::table('tbl_main_menu')->where('parent',30)->where('main_parent',2)->where('Menu_name','Creditnote Register')->update(['url'=>'creditnote-register']);
    
        DB::table('tbl_main_menu')->where('parent',30)->where('main_parent',2)->where('Menu_name','Debitnote Register')->update(['url'=>'debitnote-register']);
   
        
        DB::table('tbl_main_menu')->where('parent',30)->where('main_parent',2)->where('Menu_name','Journal Register')->update(['url'=>'journal-register']);
   
          
        DB::table('tbl_main_menu')->where('parent',30)->where('main_parent',2)->where('Menu_name','Op Balance Register')->update(['url'=>'openingbalance-register']);
  
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
