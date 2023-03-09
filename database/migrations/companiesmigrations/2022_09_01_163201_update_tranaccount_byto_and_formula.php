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
       

        DB::table('Tran_Account')->whereIn('Id',array(82,146))->update(['mainaccount_byto'=>'By','mainaccount_formula'=>'net_amount']);

        DB::table('Tran_Account')->whereIn('Id',array(115,147,148,114))->update(['mainaccount_byto'=>'To','mainaccount_formula'=>'net_amount']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
