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
        Schema::table('email_sended', function (Blueprint $table) {
            $table->biginteger('txn_id' )->nullable(); 
            $table->biginteger('schedular_id' )->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_sended', function (Blueprint $table) {
            $table->dropColumn('txn_id' );
            $table->dropColumn('schedular_id' );
        });
    }
};
