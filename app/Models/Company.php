<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'default';
    protected $table = "tbl_company";
    protected $guarded = [];
    public $timestamps = false;
    use HasFactory;

    static function getStartEndDate($currentDbName){
        $sql = new Company();
        $data = $sql->select('tbl_company.fs_date','tbl_company.fe_date','tbl_company.comp_name')
        ->where('db_name',$currentDbName)
        ->first();

        return $data;
    }
}


