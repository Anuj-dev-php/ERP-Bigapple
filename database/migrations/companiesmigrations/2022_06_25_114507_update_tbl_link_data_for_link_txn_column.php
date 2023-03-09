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
         
            DB::table('tbl_link_data')->where('txn_id','gso')->update(['link_txn'=>'gsi']);

            DB::table('tbl_link_data')->where('txn_id','gsq')->update(['link_txn'=>'gso']);
            
            DB::table('tbl_link_data')->where('txn_id','gsi')->update(['link_txn'=>'DeliveryNote']);
            
            DB::table('tbl_link_data')->where('txn_id','DeliveryNote')->update(['link_txn'=>'gsr']);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
            DB::table('tbl_link_data')->where('txn_id','gso')->update(['link_txn'=>NULL]);
 
    }
};
