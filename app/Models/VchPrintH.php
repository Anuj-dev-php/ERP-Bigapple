<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VchPrintH extends Model
{
    use HasFactory;

    protected $table="tbl_vch_printh";
    protected $primaryKey = 'Tempid';
    protected $fillable=[ 'TempName'
    ,'Txn_Name'
    ,'Head_Size'
    ,'Body_Size'
    ,'Footer_Size'
    ,'Max_Body_lines'
    ,'Height'
    ,'Width'
    ,'print_cols'
    ,'crystal'];

    public $timestamps=false;
}
