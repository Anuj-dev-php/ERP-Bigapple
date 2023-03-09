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
        Schema::table('tbl_user', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_user', 'password')){
                $table->string('password',100)->nullable();
               }

            if (!Schema::hasColumn('tbl_user', 'email_password')){
             $table->string('email_password')->nullable();
            }

            if (!Schema::hasColumn('tbl_user', 'mob_num')){
                $table->string('mob_num')->nullable();
               } 

               if (Schema::hasColumn('tbl_user', 'pwd')){
                  $table->dropColumn('pwd');
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
        Schema::table('tbl_user', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_user', 'password')){
                $table->dropColumn('password');
               }

            if (Schema::hasColumn('tbl_user', 'email_password')){
                $table->dropColumn('email_password');
            }

            if (Schema::hasColumn('tbl_user', 'mob_num')){
                $table->dropColumn('mob_num');
               }

               
               if (!Schema::hasColumn('tbl_user', 'pwd')){
                  $table->string('pwd',150)->nullable();

               }


        });
    }
};
