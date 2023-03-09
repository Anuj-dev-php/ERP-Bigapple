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
        Schema::create('tbl_voucher_scheduler', function (Blueprint $table) {
            $table->id(); 
            $table->string("VoucherNumber",50);
            $table->date("StartDate");
            $table->date("EndDate");
            $table->string("Frequency",50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_voucher_scheduler');
    }
};
