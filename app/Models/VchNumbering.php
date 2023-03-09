<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VchNumbering extends Model
{
    use HasFactory;
    protected $table="VchNumbering";
    public $timestamps=false;
    protected $fillable=[ 'VchTypeId','Prefix'
    ,'Number'
    ,'Suffix'];

    protected $primaryKey="Id"; 
}
