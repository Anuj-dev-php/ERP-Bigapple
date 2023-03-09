<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProducts extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_prod';
    public $timestamps = false;
}
