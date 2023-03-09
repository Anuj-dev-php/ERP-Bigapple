<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSended extends Model
{
    use HasFactory;
    protected $connection = 'default';
    protected $table="whatsapp_sended";
    public $timestamps=true; 
    protected $fillable=['first_name','last_name','gender','mob_num','whatsapp_template_id','document_link','status','error_msg','db_name','txn_id','schedular_id'];
}
