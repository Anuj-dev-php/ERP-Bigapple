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
        Schema::create('whatsapp_custom_fields', function (Blueprint $table) {
            $table->id(); 
            $table->biginteger('email_config_id');
            $table->biginteger('custom_field_id');
            $table->string('custom_field_name',400);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_custom_fields');
    }
};
