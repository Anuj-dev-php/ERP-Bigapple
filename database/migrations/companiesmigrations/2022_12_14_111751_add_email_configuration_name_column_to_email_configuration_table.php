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
        Schema::table('tbl_email_config', function (Blueprint $table) {

            $table->string('email_configuration_name',500)->nullable(); 
            $table->smallInteger('whatsapp_template_id')->nullable();
            $table->string('whatsapp_no',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_email_config', function (Blueprint $table) {
            $table->dropColumn('email_configuration_name');
            $table->dropColumn('whatsapp_template_id');
            $table->dropColumn('whatsapp_no');

        });
    }
};
