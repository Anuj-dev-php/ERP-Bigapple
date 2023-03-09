<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMonthLock extends Model
{
    use HasFactory;
    protected $table = 'tbl_role_month_lock'; 
    protected $fillable=['from_date','to_date','role_id','month'];
    public $timestamps = true;
}
