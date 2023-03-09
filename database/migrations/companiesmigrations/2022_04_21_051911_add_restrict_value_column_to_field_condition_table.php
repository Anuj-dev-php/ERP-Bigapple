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
        Schema::table('tbl_fld_cond', function (Blueprint $table) {
            
            if (!Schema::hasColumn('tbl_fld_cond', 'rest_value')){

                $table->string('rest_value',100)->nullable();

                $table->string('condition','50')->nullable();
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
        Schema::table('tbl_fld_cond', function (Blueprint $table) {
             
            
            if (Schema::hasColumn('tbl_fld_cond', 'rest_value')){
                $table->dropColumn('rest_value'); 
            }

            
            if (Schema::hasColumn('tbl_fld_cond', 'condition')){
                $table->dropColumn('condition'); 
            }
        });
    }
};
