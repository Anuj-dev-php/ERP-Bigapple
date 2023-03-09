<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMyaccRpt extends Model
{
    use HasFactory;

    protected $table='tbl_myacc_rpt';

    protected $fillable=['role_id','menu_rpt_id'];

    protected $primaryKey='id';

    public $timestamps=false;

}
