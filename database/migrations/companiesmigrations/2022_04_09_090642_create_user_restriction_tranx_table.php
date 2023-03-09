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
        Schema::create('tbl_user_rest_tran_day', function (Blueprint $table) {
            $table->id();
            $table->biginteger('user_id');
            $table->integer('tranx_id' );
            $table->smallinteger('add_days');
            $table->smallinteger('edit_days');
            $table->smallinteger('delete_days');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_user_rest_tran_day');
    }
};
