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
           
            $table->decimal('used_qty',18,2)->default(0);;

            $table->string('reff_no',200)->nullable()->change();
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

            $table->dropColumn('used_qty');

            $table->integer('reff_no')->nullable()->change();
            //
        });
    }
};
