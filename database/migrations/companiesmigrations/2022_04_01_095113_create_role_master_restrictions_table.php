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
        Schema::create('role_master_restrictions', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->biginteger('master_id');
            $table->timestamps();
        });

        Schema::table('role_master_restrictions', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('tbl_roles');
            $table->foreign('master_id')->references('id')->on('masters');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_master_restrictions');
    }
};
