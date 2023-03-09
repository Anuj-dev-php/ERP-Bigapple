<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;
    protected $table="tbl_user_status";
    public $timestamps = false;
    protected $fillable=['uid','sts','table_name'];

}
