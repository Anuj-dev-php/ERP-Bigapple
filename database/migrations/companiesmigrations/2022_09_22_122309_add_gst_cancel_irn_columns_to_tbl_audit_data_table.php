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
        Schema::table('tbl_audit_data', function (Blueprint $table) {
            if(!Schema::hasColumn('tbl_audit_data', 'deleted_irn')) {
             $table->text('deleted_irn')->nullable();
            }
            if(!Schema::hasColumn('tbl_audit_data', 'deleted_irn_datetime')) {
             $table->dateTime('deleted_irn_datetime')->nullable();
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
        Schema::table('tbl_audit_data', function (Blueprint $table) {
           
            if(Schema::hasColumn('tbl_audit_data', 'deleted_irn')) {
                $table->dropColumn('deleted_irn');
            }

            
            if(Schema::hasColumn('tbl_audit_data', 'deleted_irn_datetime')) {
                $table->dropColumn('deleted_irn_datetime');
            }



        });
    }
};
