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


      $reorder_table_exists=  DB::table('table_master')->where( 'Table_Name','stockreorder')->exists();


      if($reorder_table_exists==false){
        $stockreorder_id=   DB::table('table_master')->insert([
            'Table_Id' => NULL,
            'Table_Name' => 'stockreorder',
            'Field_Name' => 'Stock Reorder Qty',
            'Created_By' => 'admin',
            'Tab_Id' => 'Header',
            'Parent Table' => NULL,
            'Stock Operation' => 'None',
            'Account' => NULL,
            'AcOpr' => NULL,
            'LinkId' => '0',
            'Status' => '1',
            'ADeduct' => '0',
            'Receivable' => NULL,
            'table_label' => 'Stock Reorder Qty',
            'txn_class' => 'Masters',
            'cr_chk' => 'False     ',
            'bd_chk' => 'False     ',
            't_type' => NULL,
            'ngt_chk' => 'False     ',
            'qty_zero' => 'False     ',
            'auto_bill' => 'False     ',
            'direct_print' => 'False     ',
            'direct_sms' => 'False     ',
        ]);

        DB::table('fields_master')->insert(
                 [
                'Id' => 1,
                'Field_Id' => 'F1F',
                'Table_Name' => 'stockreorder',
                'Field_Name' => 'Id',
                'Field_Type' => 'integer',
                'Field_Size' => '0',
                'Field_Function' => '12',
                'Field_Value' => NULL,
                'Tab_Id' => 'None',
                'Created_By' => 'admin',
                'From_Table' => NULL,
                'Allow Null' => 'False',
                'Is Primary' => 'True',
                'Scr Field' => NULL,
                'Display Field' => NULL,
                'Map Field' => NULL,
                'Detail Table' => NULL,
                'Key Field' => NULL,
                'Formula Field' => '',
                'Tab Seq' => '0',
                'Searchable' => 'False',
                'Width' => '40',
                'fld_label' => 'Id',
                'add_type' => NULL,
                'get_tot' => NULL,
                'Align' => NULL,
                'fld_unique' => NULL,
                'fld_post' => 'False     ',
                'fld_dp_kfld' => NULL,
                'fld_dp_cfld' => NULL,
                'ist_acc_bal' => NULL,
                'no_dec' => NULL,
                'rd_only' => NULL,
                'lookup_flds' => NULL,
                'lookup_labels' => NULL,
                'view_order' => NULL,
                'view_hide' => NULL,
                'mul_line' => NULL,
                'fld_dp_cfld2' => NULL,
                'fld_dp_kfld2' => NULL,
                'lbl_width' => '150',
                'min_char' => NULL,
             ]
              
        );

  
                    DB::table('fields_master')->insert(
                        [
                            'Id' => 2,
                            'Field_Id' => 'F2F',
                            'Table_Name' => 'stockreorder',
                            'Field_Name' => 'product',
                            'Field_Type' => 'integer',
                            'Field_Size' => '0',
                            'Field_Function' => '4',
                            'Field_Value' => NULL,
                            'Tab_Id' => 'Header',
                            'Created_By' => 'admin',
                            'From_Table' => 'Product_Master',
                            'Allow Null' => 'True',
                            'Is Primary' => 'False',
                            'Scr Field' => 'Id',
                            'Display Field' => 'Product',
                            'Map Field' => '',
                            'Detail Table' => '',
                            'Key Field' => '',
                            'Formula Field' => '',
                            'Tab Seq' => NULL,
                            'Searchable' => 'True',
                            'Width' => '200',
                            'fld_label' => 'Product',
                            'add_type' => 'Plus      ',
                            'get_tot' => 'False     ',
                            'Align' => 'Left      ',
                            'fld_unique' => 'False     ',
                            'fld_post' => 'False     ',
                            'fld_dp_kfld' => '',
                            'fld_dp_cfld' => '',
                            'ist_acc_bal' => '',
                            'no_dec' => NULL,
                            'rd_only' => 'False     ',
                            'lookup_flds' => '',
                            'lookup_labels' => '',
                            'view_order' => '0',
                            'view_hide' => '          ',
                            'mul_line' => 'False     ',
                            'fld_dp_cfld2' => '',
                            'fld_dp_kfld2' => '',
                            'lbl_width' => '46',
                            'min_char' => '0',
                               ]
                               
                           );



                           DB::table('fields_master')->insert(
                            [
                                'Id' => 3,
                                'Field_Id' => 'F3F',
                                'Table_Name' => 'stockreorder',
                                'Field_Name' => 'location',
                                'Field_Type' => 'integer',
                                'Field_Size' => '0',
                                'Field_Function' => '4',
                                'Field_Value' => NULL,
                                'Tab_Id' => 'Header',
                                'Created_By' => 'admin',
                                'From_Table' => 'Location',
                                'Allow Null' => 'True',
                                'Is Primary' => 'False',
                                'Scr Field' => 'Id',
                                'Display Field' => 'location',
                                'Map Field' => '',
                                'Detail Table' => '',
                                'Key Field' => '',
                                'Formula Field' => '',
                                'Tab Seq' => NULL,
                                'Searchable' => 'True',
                                'Width' => '200',
                                'fld_label' => 'Location',
                                'add_type' => 'Plus      ',
                                'get_tot' => 'False     ',
                                'Align' => 'Left      ',
                                'fld_unique' => 'False     ',
                                'fld_post' => 'False     ',
                                'fld_dp_kfld' => '',
                                'fld_dp_cfld' => '',
                                'ist_acc_bal' => '',
                                'no_dec' => NULL,
                                'rd_only' => 'False     ',
                                'lookup_flds' => '',
                                'lookup_labels' => '',
                                'view_order' => '0',
                                'view_hide' => '          ',
                                'mul_line' => 'False     ',
                                'fld_dp_cfld2' => '',
                                'fld_dp_kfld2' => '',
                                'lbl_width' => '46',
                                'min_char' => '0',
                                   ]
                                   
                               );



                               
                           DB::table('fields_master')->insert(
                            [
                                'Id' => 4,
                                'Field_Id' => 'F4F',
                                'Table_Name' => 'stockreorder',
                                'Field_Name' => 'quantity',
                                'Field_Type' => 'decimal',
                                'Field_Size' => '18',
                                'Field_Function' => '1',
                                'Field_Value' => NULL,
                                'Tab_Id' => 'Header',
                                'Created_By' => 'admin',
                                'From_Table' => '',
                                'Allow Null' => 'True',
                                'Is Primary' => 'False',
                                'Scr Field' => '',
                                'Display Field' => '',
                                'Map Field' => '',
                                'Detail Table' => '',
                                'Key Field' => '',
                                'Formula Field' => '',
                                'Tab Seq' => NULL,
                                'Searchable' => 'True',
                                'Width' => '200',
                                'fld_label' => 'Qty',
                                'add_type' => 'Plus      ',
                                'get_tot' => 'False     ',
                                'Align' => 'Left      ',
                                'fld_unique' => 'False     ',
                                'fld_post' => 'False     ',
                                'fld_dp_kfld' => '',
                                'fld_dp_cfld' => '',
                                'ist_acc_bal' => '',
                                'no_dec' => '2         ',
                                'rd_only' => 'False     ',
                                'lookup_flds' => '',
                                'lookup_labels' => '',
                                'view_order' => '0',
                                'view_hide' => '          ',
                                'mul_line' => 'False     ',
                                'fld_dp_cfld2' => '',
                                'fld_dp_kfld2' => '',
                                'lbl_width' => '46',
                                'min_char' => '0',
                                   ]
                                   
                               );


      }

      
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
};
