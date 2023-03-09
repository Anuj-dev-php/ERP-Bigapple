<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldFunction extends Model
{
    use HasFactory;
    protected $table="field_functions";
    public $timestamps=false;
    protected   $primaryKey="Id"; 

}
