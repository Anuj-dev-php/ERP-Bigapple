<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

       if(!Schema::hasTable('accounts2')){
        Schema::create('accounts2', function (Blueprint $table) {
            $table->id();
            $table->string('ACName',150); 
            $table->char('G-A',10)->nullable();
            $table->char('Parent2',10)->nullable();
            $table->decimal('OpBal',18,2)->nullable();
            $table->decimal('Debits',18,2)->nullable();
            $table->decimal('Credits',18,2)->nullable();
            $table->decimal('Bal',20,2)->nullable();
            $table->integer('SplType' ) ;
            $table->integer('SelType' )->nullable() ;
            $table->string('accdesc',200)->nullable() ;
            $table->integer('Parent')->nullable() ;
            $table->decimal('Fc_OpBal',18,2)->nullable() ;
            $table->decimal('Fc_Debits',18,2)->nullable() ;
            $table->decimal('Fc_Credits',18,2)->nullable() ;
            $table->decimal('Fc_Bal',20,2)->nullable() ;
 
        });
       }


     
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account2');
    }
};
