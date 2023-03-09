<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMailConfig extends Model
{
    use HasFactory;

    
    protected $connection = 'default';
    protected $table="tbl_mail_config";

    
}
