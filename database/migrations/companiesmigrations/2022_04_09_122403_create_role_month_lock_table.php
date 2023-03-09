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
        Schema::create('tbl_role_month_lock', function (Blueprint $table) {
            $table->id();
            $table->datetime('from_date');
            $table->datetime('to_date');
            $table->integer('role_id');
            $table->smallinteger('month');
            $table->timestamps();
        });

        Schema::table('tbl_role_month_lock', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('tbl_roles')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_role_month_lock');
    }
};
