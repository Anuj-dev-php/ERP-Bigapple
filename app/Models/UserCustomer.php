<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomer extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_cst';
    public $timestamps=false;
    protected $fillable=['uid','cst'];
}
