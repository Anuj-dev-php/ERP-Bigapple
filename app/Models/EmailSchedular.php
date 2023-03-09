<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSchedular extends Model
{
    use HasFactory;
    protected $table = 'email_schedular'; 
    public $timestamps = true;
 
    protected $fillable=['email_configuration_id','schedule','send_time','send_datetime','send_weekdays','send_months','send_month_day','email_sended_ids','whatsapp_sended_ids','lastrun_datetime'];
}
