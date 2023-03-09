<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblEmailConfig extends Model
{
    use HasFactory;
    protected $table="tbl_email_config"; 
    public $timestamps = false;
    protected $fillable=['email_configuration_name','whatsapp_template_id', 'whatsapp_no','table_name','field_name','cond','cond_val','conj','Email','print_temp','send_mail','send_exec','send_cust','group_id','updated_at','created_at'];
}
