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
        
        DB::table('fields_master')->where(['Field_Name'=>'taxamt','Table_Name'=>'GSI'])->update(['Field_Value'=>'assessiblvalu_IS_GSI_det*sgstpercentage_IS_GSI_det/100+(transport_IS_GSI_det*(sgstpercentage_IS_GSI_det/100))']);
   
        DB::table('fields_master')->where(['Field_Name'=>'exciseamt','Table_Name'=>'GSI'])->update(['Field_Value'=>'assessiblvalu_IS_GSI_det*igstpercentage_IS_GSI_det/100+(transport_IS_GSI_det*(igstpercentage_IS_GSI_det/100))']);
   
   
        DB::table('fields_master')->where(['Field_Name'=>'Discount','Table_Name'=>'GSI'])->update(['Field_Value'=>'amount_IS_GSI_det*Disc_IS_GSI_det/100']);


        DB::table('fields_master')->where(['Field_Name'=>'Gross_Amount','Table_Name'=>'GSI'])->update(['Field_Value'=>'amount_IS_GSI_det']);

        
        DB::table('fields_master')->where(['Field_Name'=>'Net_Amount','Table_Name'=>'GSI'])->update(['Field_Value'=>'(assessiblvalu_IS_GSI_det)+
        (assessiblvalu_IS_GSI_det*sgstpercentage_IS_GSI_det/100+(transport_IS_GSI_det*(sgstpercentage_IS_GSI_det/100)))+
        (assessiblvalu_IS_GSI_det*igstpercentage_IS_GSI_det/100+(transport_IS_GSI_det*(igstpercentage_IS_GSI_det/100)))+
        (transport_IS_GSI_det)+
        (assessiblvalu_IS_GSI_det*cgstpercentage_IS_GSI_det/100+(transport_IS_GSI_det*(cgstpercentage_IS_GSI_det/100)))' ,'Field_Function'=>11]);

                
        DB::table('fields_master')->where(['Field_Name'=>'lotcharge','Table_Name'=>'GSI'])->update(['Field_Value'=>'transport_IS_GSI_det']);

        DB::table('fields_master')->where(['Field_Name'=>'cgstamount','Table_Name'=>'GSI'])->update(['Field_Value'=>'assessiblvalu_IS_GSI_det*cgstpercentage_IS_GSI_det/100+(transport_IS_GSI_det*(cgstpercentage_IS_GSI_det/100))']);
     
     
    //  of table gsi det
     
        DB::table('fields_master')->where(['Field_Name'=>'sgstpercentage','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'(tax_IS_GSI_det)/2']);
  
        DB::table('fields_master')->where(['Field_Name'=>'sgstamount','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'(sgstpercentage_IS_GSI_det/100)*(assessiblvalu_IS_GSI_det+transport_IS_GSI_det)']);

        DB::table('fields_master')->where(['Field_Name'=>'cgstpercentage','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'(tax_IS_GSI_det)/2']);
       
       
        DB::table('fields_master')->where(['Field_Name'=>'cgstamout','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'(cgstpercentage_IS_GSI_det/100)*(assessiblvalu_IS_GSI_det+transport_IS_GSI_det)']);
      
               
        DB::table('fields_master')->where(['Field_Name'=>'discamt','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'(amount_IS_GSI_det*(disc_IS_GSI_det/100))']);
      

            
        DB::table('fields_master')->where(['Field_Name'=>'assessiblvalu','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'amount_IS_GSI_det-(amount_IS_GSI_det*(disc_IS_GSI_det/100))']);
      
     
        DB::table('fields_master')->where(['Field_Name'=>'igstamoutn','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'(igstpercentage_IS_GSI_det/100)*(assessiblvalu_IS_GSI_det+transport_IS_GSI_det)']);
      
   
        DB::table('fields_master')->where(['Field_Name'=>'amount','Table_Name'=>'GSI_det'])->update(['Field_Value'=>'quantity_IS_GSI_det*rate_IS_GSI_det']);
      

 
   
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
