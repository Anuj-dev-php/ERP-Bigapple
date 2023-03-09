<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblCompanyNews extends Model
{
    use HasFactory;

    protected $table='tbl_company_news';

    protected $fillable=['News','date','display'];

    public $timestamps=false;

    
}
