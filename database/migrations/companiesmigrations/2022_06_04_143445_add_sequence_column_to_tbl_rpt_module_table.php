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
        Schema::table('tbl_rpt_module', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_rpt_module', 'sequence')){
             $table->integer('sequence')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_rpt_module', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_rpt_module', 'sequence')){

                $table->dropColumn('sequence');

            }
        });
    }
};
