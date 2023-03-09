<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMy extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table='tbl_my';
    protected $primaryKey='id';
    protected $fillable=['role_id','txn_id','Type','url'];
    
}
