<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports1 extends Model
{
    use HasFactory;
    public $timetstamps=false;

    protected $table='reports1';

    protected $primaryKey='id';

    protected $fillable=['role_id','report_id'];

    
}
