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
        Schema::table('tbl_email_config', function (Blueprint $table) {

            if(!Schema::hasColumn('tbl_email_config', 'email_subject')) {
                $table->string('email_subject',100)->nullable();
                }
                
            if(!Schema::hasColumn('tbl_email_config', 'email_body')) {
                $table->text('email_body')->nullable();;
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
        Schema::table('tbl_email_config', function (Blueprint $table) {
             
            
            if(Schema::hasColumn('tbl_email_config', 'email_subject')) {
                
                $table->dropColumn( 'email_subject');
                
                $table->dropColumn( 'email_body');

            }
        });
    }
};
