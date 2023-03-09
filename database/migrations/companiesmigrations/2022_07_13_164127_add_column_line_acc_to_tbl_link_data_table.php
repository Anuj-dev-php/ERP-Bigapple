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
        Schema::table('tbl_link_data', function (Blueprint $table) {
             
            $table->integer('line_acc')->nullable();
            
            $table->integer('cust_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_link_data', function (Blueprint $table) {

            $table->dropColumn('line_acc');

            $table->integer('cust_id')->nullable(false)->change(); 

        });
    }
};
