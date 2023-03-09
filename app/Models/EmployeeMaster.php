<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMaster extends Model
{
    use HasFactory;
    protected $table = 'tbl_empmaster'; 
    public $timestamps = false;
}
