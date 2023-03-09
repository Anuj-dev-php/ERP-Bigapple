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
        Schema::table('GSI', function (Blueprint $table) {

            if(!Schema::hasColumn('GSI', 'acknowledgement_no')) {
            $table->string('acknowledgement_no',100)->nullable();
            }
            if(!Schema::hasColumn('GSI', 'acknowledgement_date')) {
            $table->dateTime('acknowledgement_date' )->nullable();
            }
            if(!Schema::hasColumn('GSI', 'irn')) {
            $table->text('irn')->nullable();
            }
            if(!Schema::hasColumn('GSI', 'signed_invoice')) {
            $table->text('signed_invoice')->nullable();
            }
            if(!Schema::hasColumn('GSI', 'signed_qr_code')) {
            $table->text('signed_qr_code')->nullable();
            }
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GSI', function (Blueprint $table) {
           
            if(Schema::hasColumn('GSI', 'acknowledgement_no')) {
                $table->dropColumn( 'acknowledgement_no');
            }

            if(Schema::hasColumn('GSI', 'acknowledgement_date')) {
                $table->dropColumn( 'acknowledgement_date');
            }

            if(Schema::hasColumn('GSI', 'irn')) {
                $table->dropColumn( 'irn');
            }

            if(Schema::hasColumn('GSI', 'signed_invoice')) {
                $table->dropColumn( 'signed_invoice');
            }

            if(Schema::hasColumn('GSI', 'signed_qr_code')) {
                $table->dropColumn( 'signed_qr_code');
            }
            
        });
    }
};
