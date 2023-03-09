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
        Schema::create('role_inbox_tab_hidings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id') ;
            $table->integer('inbox_tab_id') ;
            $table->timestamps();
        });

        Schema::table('role_inbox_tab_hidings', function (Blueprint $table) {

            $table->foreign('role_id')->references('id')->on('tbl_roles');
            $table->foreign('inbox_tab_id')->references('id')->on('inbox_tabs');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_inbox_tab_hidings');
    }
};
