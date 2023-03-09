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
        Schema::table('tbl_print_header', function (Blueprint $table) {
            
            
           if(!Schema::hasColumn('tbl_print_header', 'link')) {

             $table->string('link',200)->nullable();

           }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_print_header', function (Blueprint $table) {

            if(Schema::hasColumn('tbl_print_header', 'link')) {
               $table->dropColumn('link');
            }
        });
    }
};
