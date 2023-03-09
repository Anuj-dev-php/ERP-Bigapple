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
        Schema::table('tbl_company', function (Blueprint $table) {
            
            $table->string('pfy_db',50)->nullable();
            
            $table->string('sfy_db',50)->nullable();


        });

        DB::table('tbl_company')->where('db_name','BLPL1')->update(['pfy_db'=>'BLPLAudit','sfy_db'=>'BLPL2']);;
        
        DB::table('tbl_company')->where('db_name','BLPLAudit')->update([ 'sfy_db'=>'BLPL1']);;

        
        DB::table('tbl_company')->where('db_name','BLPL2')->update([ 'pfy_db'=>'BLPL1','sfy_db'=>'BLPL21']);;
        
        DB::table('tbl_company')->where('db_name','BLPL21')->update([ 'pfy_db'=>'BLPL2','sfy_db'=>'BLPL211']);;
        
        DB::table('tbl_company')->where('db_name','BLPL211')->update([ 'pfy_db'=>'BLPL21','sfy_db'=>'BLPL2111']);;

        
        DB::table('tbl_company')->where('db_name','BLPL2111')->update([ 'pfy_db'=>'BLPL211','sfy_db'=>NULL]);;


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_company', function (Blueprint $table) {
            //
            $table->dropColumn('pfy_db');
            $table->dropColumn('sfy_db');

        });
    }
};
