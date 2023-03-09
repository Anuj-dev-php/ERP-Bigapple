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
        Schema::create('email_sended', function (Blueprint $table) {
            $table->id();
            $table->string('to',100);
            $table->string('subject',500); 
            $table->text('body');  
            $table->string('show_filename',200)->nullable(); 
             $table->string('filepath',1000)->nullable();   
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
        Schema::dropIfExists('email_sended');
    }
};
