<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSended extends Model
{
    use HasFactory;
    protected $connection = 'default';

    protected $table = 'email_sended'; 
    public $timestamps = true;
  
    protected $fillable=['to','subject','body','show_filename','filepath','status','error_msg','schedular_id','txn_id','db_name'];



}
