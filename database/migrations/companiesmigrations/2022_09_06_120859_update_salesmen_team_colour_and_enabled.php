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

        if(Schema::hasColumn('SalesMen', 'team')) {

            DB::table('SalesMen')->whereNull('team')->update(['teamcolour'=>'#556ee6']);

            
            DB::table('SalesMen')->where('team','LIKE','Ashish')->update(['teamcolour'=>'#fcccba']);

            
            DB::table('SalesMen')->where('team','LIKE','Umesh Asher')->update(['teamcolour'=>'#f8f366']);

            
            
            DB::table('SalesMen')->where('team','LIKE','Manoj')->update(['teamcolour'=>'#fde725']);

            
            DB::table('SalesMen')->where('team','LIKE','Lavanya')->update(['teamcolour'=>'#9fda3a']);

            
            DB::table('SalesMen')->where('team','LIKE','Dev')->update(['teamcolour'=>'#9a81f4']);

            
            DB::table('SalesMen')->where('team','LIKE','OSS')->update(['teamcolour'=>'#613ee2']);


 
            DB::table('SalesMen')->whereNotNull('team')->update(['enabled'=>'yes']);

            
 
            DB::table('SalesMen')->whereNull('team')->update(['enabled'=>'no']);

            
            DB::table('SalesMen')->where(function($query){

                $query->where('name','LIKE','ankit%');

                $query->orwhere('name','LIKE','%suchita%');

                
                $query->orwhere('name','LIKE','ecom%');

            })->update(['enabled'=>'yes']);
               
        }
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
