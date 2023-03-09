<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblRptModule extends Model
{
    use HasFactory;

    protected $table='tbl_rpt_module';

    protected $fillable=['mname','mord','sequence'];

    public $timestamps=false;

    
}
