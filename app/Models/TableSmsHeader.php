<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSmsHeader extends Model
{
    use HasFactory;
    protected $table = 'tbl_sms_header';
    public $timestamps = false; 
    protected $guarded = [];
 

    protected $fillable=['tempname','txn_name','msg_txt'];
}
