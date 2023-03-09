<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappCustomField extends Model
{
    use HasFactory; 
    protected $table="whatsapp_custom_fields";
    public $timestamps=true; 
    protected $fillable=[ 'email_config_id','custom_field_id','custom_field_name'];
}
