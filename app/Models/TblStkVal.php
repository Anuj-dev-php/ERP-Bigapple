<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblStkVal extends Model
{
    use HasFactory;
    
    public $timestamps=false;

    protected $table="tbl_stk_val";

    protected $primaryKey="id";
  
}
