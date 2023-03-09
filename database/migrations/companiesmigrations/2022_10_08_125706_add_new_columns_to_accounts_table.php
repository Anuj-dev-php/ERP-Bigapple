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
        Schema::table('accounts', function (Blueprint $table) {
             $table->decimal('Fc_OpBal',18,2)->nullable();
             $table->decimal('Fc_Debits',18,2)->nullable();
             $table->decimal('Fc_Credits',18,2)->nullable();
             $table->decimal('Fc_Bal',20,2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {

             $table->dropColumn('Fc_OpBal');
            
            $table->dropColumn('Fc_Debits');

            $table->dropColumn('Fc_Credits');
   
            $table->dropColumn('Fc_Bal');
 
            //
        });
    }
};
