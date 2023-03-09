<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranAccDet extends Model
{
    use HasFactory;
    protected $table="TranAccDet"; 
    protected $fillable=['TempId','By/To','AccName','Formula'];
}
