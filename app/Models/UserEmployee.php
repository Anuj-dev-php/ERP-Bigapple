<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmployee extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_emp';
    public $timestamps=false;
    protected $fillable=['uid','empid'];

}
