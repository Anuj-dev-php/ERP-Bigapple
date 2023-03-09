<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_loc';
    public $timestamps=false;
    protected $fillable=['uid','Loc'];
}
