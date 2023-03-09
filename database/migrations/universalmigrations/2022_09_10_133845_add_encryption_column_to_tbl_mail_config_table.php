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
        Schema::table('tbl_mail_config', function (Blueprint $table) {

            if (!Schema::hasColumn('tbl_mail_config', 'encryption')){
                 $table->string('encryption',30)->nullable();
            }
        });

        DB::table('tbl_mail_config')->update(['encryption'=>'tls']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_mail_config', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_mail_config', 'encryption')){
                $table->dropColumn('encryption');
            }
        });
    }
};
