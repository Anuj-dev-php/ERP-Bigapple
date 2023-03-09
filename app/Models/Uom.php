<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;
    protected $table='uom';
    public $timestamps = false;
    protected $primaryKey="id";
    protected $fillable=['name'];


}
