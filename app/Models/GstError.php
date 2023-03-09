<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstError extends Model
{
    use HasFactory;
    protected $table="gst_errors";
 
    protected $fillable=['user_id','table_name' ,'error_code','error_message'];
}
