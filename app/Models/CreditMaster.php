<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditMaster extends Model
{
    use HasFactory;

    protected $table='creditmaster';
    
    public $timestamps=false;
}
