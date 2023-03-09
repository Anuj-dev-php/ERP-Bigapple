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
         DB::table('VchTypes')->insert(['Name'=>'Accounts Tree','Parent'=>0,'Spltype'=>NULL]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vch_types', function (Blueprint $table) {

            DB::table('VchTypes')->where('Name','Accounts Tree')->delete();
           
        });
    }
};
