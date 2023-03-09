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
        Schema::table('VchDet', function (Blueprint $table) {
             $table->dropColumn('project');
             $table->smallInteger('division')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('VchDet', function (Blueprint $table) {
            $table->dropColumn('division');
        });
    }
};
