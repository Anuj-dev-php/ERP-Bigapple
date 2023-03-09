<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMyRpt extends Model
{
    use HasFactory;
    protected $table='tbl_my_rpt';
    public $timestamps=false;
    protected $primaryKey='id';
    protected $fillable=['role_id','report_id'];
}
