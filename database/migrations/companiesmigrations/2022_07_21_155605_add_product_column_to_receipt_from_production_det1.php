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
        Schema::table('receiptfromproduction_det1', function (Blueprint $table) {

           if(!Schema::hasColumn('receiptfromproduction_det1', 'product')) {

                 $table->string('product')->nullable();
           }

            //
        });

       $products= DB::table('receiptfromproduction_det')->select('fk_id','product')->get();

       foreach(    $products as     $product){

        DB::table('receiptfromproduction_det1')->where('fk_id',$product->fk_id)->update(['product'=>$product->product]);
       }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receiptfromproduction_det1', function (Blueprint $table) {
             $table->dropColumn('product');
        });
    }
};
