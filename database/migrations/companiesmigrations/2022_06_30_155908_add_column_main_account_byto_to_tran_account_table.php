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
        Schema::table('Tran_Account', function (Blueprint $table) {
             
            $table->string('mainaccount_byto',10)->default('');
            $table->string('mainaccount_formula',100)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Tran_Account', function (Blueprint $table) {
        
            $table->dropColumn('mainaccount_byto');
            $table->dropColumn('mainaccount_formula');
        });
    }
};
