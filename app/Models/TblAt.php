<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblAt extends Model
{
    use HasFactory;

    
    protected $table='tbl_at';
 
    protected $fillable=['Txn','opr','uid','stime','ntime','rec_id'];


    public $timestamps=false;


}
