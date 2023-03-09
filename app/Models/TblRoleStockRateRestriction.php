<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblRoleStockRateRestriction extends Model
{
    use HasFactory;
    
    protected $table="tbl_role_stock_rate_restriction";
    protected $primaryKey = 'Id'; 
    public $timestamps = true;
    protected $fillable=[  'role_id'
    ,'rate'
    ,'spec_rate'
    ,'show_amounts'
    ,'created_at'
    ,'updated_at' ];
}
