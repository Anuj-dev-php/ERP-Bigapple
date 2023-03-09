<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblRptModuleDet extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $fillable=['rmid','rptid','sequence'];

    protected $primaryKey='id';

    protected $table='tbl_rpt_module_det';

    
}
