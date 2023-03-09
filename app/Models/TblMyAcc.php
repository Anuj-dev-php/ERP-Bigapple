<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMyAcc extends Model
{
    use HasFactory;

    protected $table='tbl_my_acc';
    public $timestamps=false;

    protected $primaryKey='id';

    protected $fillable=['role_id','vch_id'];
}

