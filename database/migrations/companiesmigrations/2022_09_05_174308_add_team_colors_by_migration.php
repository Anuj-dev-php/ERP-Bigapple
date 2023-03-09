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
 
      DB::table('fields_master')->insert(array('Id'=>10,'Field_Id'=>'F10F','Table_Name'=>'SalesMen','Field_Name'=>'target',
    'Field_Type'=>'decimal','Field_Size'=>18,'Field_Function'=>1,'Field_Value'=>NULL,'Tab_Id'=>'Header','Created_By'=>'admin','From_Table'=>'','Allow Null'=>'TRUE','Is Primary'=>'FALSE','Scr Field'=>'','Display Field'=>'','Map Field'=>'','Detail Table'=>'','Key Field'=>'','Formula Field'=>'','Tab Seq'=>NULL,'Searchable'=>'TRUE','Width'=>200,'fld_label'=>'Target','add_type'=>'Plus','get_tot'=>'False','Align'=>'Left','fld_unique'=>'False','fld_post'=>'False','fld_dp_kfld'=>'',
    'fld_dp_cfld'=>'','ist_acc_bal'=>'','no_dec'=>'2','rd_only'=>'False','lookup_flds'=>'','lookup_labels'=>'','view_order'=>0,'view_hide'=>'','mul_line'=>'False','fld_dp_cfld2'=>'','fld_dp_kfld2'=>'','lbl_width'=>46,'min_char'=>0
    ));

    DB::table('fields_master')->insert(array('Id'=>11,'Field_Id'=>'F11F','Table_Name'=>'SalesMen','Field_Name'=>'teamcolour',
    'Field_Type'=>'varchar','Field_Size'=>200,'Field_Function'=>1,'Field_Value'=>NULL,'Tab_Id'=>'Header','Created_By'=>'admin','From_Table'=>'','Allow Null'=>'TRUE','Is Primary'=>'FALSE','Scr Field'=>'','Display Field'=>'','Map Field'=>'','Detail Table'=>'','Key Field'=>'','Formula Field'=>'','Tab Seq'=>NULL,'Searchable'=>'TRUE','Width'=>200,'fld_label'=>'Team Colour #','add_type'=>'Plus','get_tot'=>'False','Align'=>'Left','fld_unique'=>'False','fld_post'=>'False','fld_dp_kfld'=>'',
    'fld_dp_cfld'=>'','ist_acc_bal'=>'','no_dec'=>NULL,'rd_only'=>'False','lookup_flds'=>'','lookup_labels'=>'','view_order'=>0,'view_hide'=>'','mul_line'=>'False','fld_dp_cfld2'=>'','fld_dp_kfld2'=>'','lbl_width'=>46,'min_char'=>0
    ));

    DB::table('fields_master')->insert(array('Id'=>12,'Field_Id'=>'F12F','Table_Name'=>'SalesMen','Field_Name'=>'enabled',
    'Field_Type'=>'varchar','Field_Size'=>200,'Field_Function'=>2,'Field_Value'=>"yes,no",'Tab_Id'=>'Header','Created_By'=>'admin','From_Table'=>'','Allow Null'=>'TRUE','Is Primary'=>'FALSE','Scr Field'=>'','Display Field'=>'','Map Field'=>'','Detail Table'=>'','Key Field'=>'','Formula Field'=>'','Tab Seq'=>NULL,'Searchable'=>'TRUE','Width'=>200,'fld_label'=>'enabled','add_type'=>'Plus','get_tot'=>'False','Align'=>'Left','fld_unique'=>'False','fld_post'=>'False','fld_dp_kfld'=>'',
    'fld_dp_cfld'=>'','ist_acc_bal'=>'','no_dec'=>NULL,'rd_only'=>'False','lookup_flds'=>'','lookup_labels'=>'','view_order'=>0,'view_hide'=>'','mul_line'=>'False','fld_dp_cfld2'=>'','fld_dp_kfld2'=>'','lbl_width'=>46,'min_char'=>0
    ));

 
    Schema::table("SalesMen", function (Blueprint $table){
        
        if(!Schema::hasColumn('SalesMen', 'target')) {
                
                $table->decimal('target',18,2)->nullable(); 

        }
    });

    if(!Schema::hasColumn('SalesMen', 'teamcolour')) {
    
       DB::statement("ALTER TABLE SalesMen ADD  teamcolour varchar(200) NULL;");
    }

    

    if(!Schema::hasColumn('SalesMen', 'enabled')) {
    DB::statement("ALTER TABLE SalesMen ADD  enabled varchar(200) NULL;");
    }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      
      if(Schema::hasColumn('SalesMen', 'teamcolour')) {
        DB::statement("ALTER TABLE  SalesMen DROP COLUMN  teamcolour;");
      }

        
        if(Schema::hasColumn('SalesMen', 'enabled')) {
        
        DB::statement("ALTER TABLE  SalesMen DROP COLUMN  enabled;");

        }


        Schema::table("SalesMen", function (Blueprint $table){
            
            if(Schema::hasColumn('SalesMen', 'target')) {
               $table->dropColumn('target'); 
            }
        });

        
      DB::table('fields_master')->whereIn('Field_Name',array('target','teamcolour','enabled'))->delete();


 
    }
};
