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
        

        Schema::table('GSR', function (Blueprint $table) {

            if(!Schema::hasColumn('GSR', 'acknowledgement_no')) {
            $table->string('acknowledgement_no',100)->nullable();
            }
            if(!Schema::hasColumn('GSR', 'acknowledgement_date')) {
            $table->dateTime('acknowledgement_date' )->nullable();
            }
            if(!Schema::hasColumn('GSR', 'irn')) {
            $table->text('irn')->nullable();
            }
            if(!Schema::hasColumn('GSR', 'signed_invoice')) {
            $table->text('signed_invoice')->nullable();
            }
            if(!Schema::hasColumn('GSR', 'signed_qr_code')) {
            $table->text('signed_qr_code')->nullable();
            }
            //
        });


        if(Schema::hasTable('GSRA')){
            

        Schema::table('GSRA', function (Blueprint $table) {

            if(!Schema::hasColumn('GSRA', 'acknowledgement_no')) {
            $table->string('acknowledgement_no',100)->nullable();
            }
            if(!Schema::hasColumn('GSRA', 'acknowledgement_date')) {
            $table->dateTime('acknowledgement_date' )->nullable();
            }
            if(!Schema::hasColumn('GSRA', 'irn')) {
            $table->text('irn')->nullable();
            }
            if(!Schema::hasColumn('GSRA', 'signed_invoice')) {
            $table->text('signed_invoice')->nullable();
            }
            if(!Schema::hasColumn('GSRA', 'signed_qr_code')) {
            $table->text('signed_qr_code')->nullable();
            }
            //
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
       
        
        if(Schema::hasTable('GSR')){
        Schema::table('GSR', function (Blueprint $table) {
           
            if(Schema::hasColumn('GSR', 'acknowledgement_no')) {
                $table->dropColumn( 'acknowledgement_no');
            }

            if(Schema::hasColumn('GSR', 'acknowledgement_date')) {
                $table->dropColumn( 'acknowledgement_date');
            }

            if(Schema::hasColumn('GSR', 'irn')) {
                $table->dropColumn( 'irn');
            }

            if(Schema::hasColumn('GSR', 'signed_invoice')) {
                $table->dropColumn( 'signed_invoice');
            }

            if(Schema::hasColumn('GSR', 'signed_qr_code')) {
                $table->dropColumn( 'signed_qr_code');
            }
            
        });

    }




        if(Schema::hasTable('GSRA')){
        Schema::table('GSRA', function (Blueprint $table) {
           
            if(Schema::hasColumn('GSRA', 'acknowledgement_no')) {
                $table->dropColumn( 'acknowledgement_no');
            }

            if(Schema::hasColumn('GSRA', 'acknowledgement_date')) {
                $table->dropColumn( 'acknowledgement_date');
            }

            if(Schema::hasColumn('GSRA', 'irn')) {
                $table->dropColumn( 'irn');
            }

            if(Schema::hasColumn('GSRA', 'signed_invoice')) {
                $table->dropColumn( 'signed_invoice');
            }

            if(Schema::hasColumn('GSRA', 'signed_qr_code')) {
                $table->dropColumn( 'signed_qr_code');
            }
            
        });

    }


    }
};
