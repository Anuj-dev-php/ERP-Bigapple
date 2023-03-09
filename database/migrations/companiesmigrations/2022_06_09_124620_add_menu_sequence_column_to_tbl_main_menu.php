<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MainMenu;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_main_menu', function (Blueprint $table) {

            
            if (!Schema::hasColumn('tbl_main_menu', 'sequence')){

               $table->smallInteger('sequence')->nullable();

            }

            //
        });

        MainMenu::insert(['Menu_name'=>'Transactions','parent'=>0,'main_parent'=>0,'url'=>NULL]);
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_main_menu', function (Blueprint $table) {

            
            if (Schema::hasColumn('tbl_main_menu', 'sequence')){
                $table->dropColumn('sequence');

            }
            //
        });

        MainMenu::where(['Menu_name'=>'Transactions','parent'=>0,'main_parent'=>0])->delete();
    }
};
