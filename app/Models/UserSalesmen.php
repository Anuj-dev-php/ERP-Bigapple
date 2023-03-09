<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSalesmen extends Model
{
    use HasFactory;
    protected $table="tbl_user_sexe";
    public $timestamps = false;
    protected $fillable=['uid','s_exe'];
}
