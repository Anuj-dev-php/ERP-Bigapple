<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblAuditData extends Model
{
    use HasFactory;
    
    protected $table='tbl_audit_data';
 

    protected $fillable=['user_id','table_name','docno','docdate','cust_id','salesman','location','product','qty','rate','amount','grossamt','netamt','operation','base_id','servertime'];
 
    
    public $timestamps=false;

}
