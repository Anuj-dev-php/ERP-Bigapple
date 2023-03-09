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
        Schema::table('tbl_exrates', function (Blueprint $table) {
            
            if (!Schema::hasColumn('tbl_exrates', 'curr_id')){
                $table->integer('curr_id')->nullable();
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
        Schema::table('tbl_exrates', function (Blueprint $table) {

            
            if (Schema::hasColumn('tbl_exrates', 'curr_id')){

                    $table->dropColumn('curr_id');
            }
            //
        });
    }
};
