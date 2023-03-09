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
        Schema::create('tbl_transaction_fields', function (Blueprint $table) {
            $table->increments('Id');; 
            $table->smallInteger('role');
            $table->string('transaction_table',100);
            $table->string('field_name',100);
            $table->smallInteger('sequence')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_transaction_fields');
    }
};
