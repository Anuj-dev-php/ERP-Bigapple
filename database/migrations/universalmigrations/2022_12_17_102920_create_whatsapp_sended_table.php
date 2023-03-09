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
        Schema::create('whatsapp_sended', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',200);
            $table->string('last_name',200);
            $table->string('gender',200);
            $table->string('mob_num',200);
            $table->string('whatsapp_template_id',200)->nullable();
            $table->string('document_link',700)->nullable();
            $table->enum('status',['failed','processed']);
            $table->text('error_msg')->nullable();
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
        Schema::dropIfExists('whatsapp_sended');
    }
};
