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
        //

        DB::table('tbl_print_header')->where('Txn_Name','GLPI')->update(['crystal'=>'GLPI.rpt']);
        

        DB::table('tbl_print_header')->where('Txn_Name','GLPIA')->update(['crystal'=>'GLPIA.rpt']);

        
        DB::table('tbl_print_header')->where('Txn_Name','GSI')->update(['crystal'=>'GSI.rpt']);
        
        
        DB::table('tbl_print_header')->where('Txn_Name','GSO')->update(['crystal'=>'GSO.rpt']);

        
        DB::table('tbl_print_header')->where('Txn_Name','GSQ')->update(['crystal'=>'GSQ.rpt']);

        
        DB::table('tbl_print_header')->where('Txn_Name','GSR')->update(['crystal'=>'GSR.rpt']);









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
