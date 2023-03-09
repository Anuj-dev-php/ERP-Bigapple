<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldsMaster extends Model
{
    protected $table = 'fields_master';
    use HasFactory;
    protected $fillable=[
        'Id'
        ,'Field_Id'
        ,'Table_Name'
        ,'Field_Name'
        ,'Field_Type'
        ,'Field_Size'
        ,'Field_Function'
        ,'Field_Value'
        ,'Tab_Id'
        ,'Created_By'
        ,'From_Table'
        ,'Allow Null'
        ,'Is Primary'
        ,'Scr Field'
        ,'Display Field'
        ,'Map Field'
        ,'Detail Table'
        ,'Key Field'
        ,'Formula Field'
        ,'Tab Seq'
        ,'Searchable'
        ,'Width'
        ,'fld_label'
        ,'add_type'
        ,'get_tot'
        ,'Align'
        ,'fld_unique'
        ,'fld_post'
        ,'fld_dp_kfld'
        ,'fld_dp_cfld'
        ,'ist_acc_bal'
        ,'no_dec'
        ,'rd_only'
        ,'lookup_flds'
        ,'lookup_labels'
        ,'view_order'
        ,'view_hide'
        ,'mul_line'
        ,'fld_dp_cfld2'
        ,'fld_dp_kfld2'
        ,'lbl_width'
        ,'min_char' ];

        public $timestamps=false;
        protected $primaryKey="Id";
 
    
}
