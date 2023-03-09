<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldLevel extends Model
{
    use HasFactory;
    protected $table = 'tbl_fld_level';
    protected $fillable = ['hide','rdol'];
    public $timestamps = false;
}
