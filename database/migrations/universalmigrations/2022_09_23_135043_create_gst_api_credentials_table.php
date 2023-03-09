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
        Schema::create('gst_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();
            $table->text('server_url')->nullable();
            $table->text('subscription_key')->nullable();
            $table->text('gstin')->nullable();
            $table->text('username')->nullable();
            $table->text('password')->nullable();
            $table->text('force_refresh_token')->nullable();
            $table->string('gst_invoice_certificate_file',200)->nullable();
            $table->boolean('active')->default(0); 
        });

        DB::table('gst_api_credentials')->insert([
            'name'=>'Sandbox',
            'server_url'=>'https://developers.eraahi.com/eInvoiceGateway',
            'active'=>1,
            'subscription_key'=>'AL9J6S5d1U2V6o4t8k',
            'gstin'=>'07AGAPA5363L002',
            'username'=>'AL001',
            'password'=>'Alankit@123',
            'force_refresh_token'=>'true',
            'gst_invoice_certificate_file'=>'einv_sandbox.cer'
 
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gst_api_credentials');
    }
};
