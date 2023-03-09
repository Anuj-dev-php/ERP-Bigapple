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
        Schema::table('tbl_company_news', function (Blueprint $table) {
             
            if (!Schema::hasColumn('tbl_company_news', 'date')){

                $table->date('date')->nullable();
                $table->boolean('display')->default(0);
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
        Schema::table('tbl_company_news', function (Blueprint $table) {
             

            if (Schema::hasColumn('tbl_company_news', 'date')){

                $table->dropColumn('date');
                $table->dropColumn('display');
            }

        });
    }
};
