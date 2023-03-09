<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPrintHeader extends Model
{
    use HasFactory;
    protected $table="tbl_print_header";
    protected $primaryKey = 'Tempid';

    protected $fillable=['TempName'   ,'Txn_Name'
    ,'Head_Size'
    ,'Body_Size'
    ,'Footer_Size'
    ,'Max_Body_lines'
    ,'Height'
    ,'Width'
    ,'print_cols'
    ,'print_border'
    ,'crystal'];

    public $timestamps=false;

}
