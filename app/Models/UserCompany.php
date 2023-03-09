<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{
    protected $table = "tbl_user_comp";
    protected $connection = 'default';
    public $timestamps = false;

    protected $guarded = [];
    use HasFactory;

    
}
