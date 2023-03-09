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
        Schema::create('email_schedular', function (Blueprint $table) {
            $table->id();
            $table->smallinteger('email_configuration_id');
            $table->enum('schedule',['Hourly','Daily','Days','Months','Specific'])->nullable(); 
            $table->time('send_time')->nullable();
            $table->datetime('send_datetime')->nullable(); 
            $table->string('send_weekdays',200)->nullable();
            $table->string('send_months',200)->nullable();
            $table->smallinteger('send_month_day')->nullable(); 
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
        Schema::dropIfExists('email_schedular');
    }
};
