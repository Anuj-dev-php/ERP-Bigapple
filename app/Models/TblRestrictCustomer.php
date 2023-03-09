<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblRestrictCustomer extends Model
{
    use HasFactory;
    protected $table="tbl_restrict_customers";
    protected $fillable=['role_id','party_type_id'];
    public $timestamps=false;
}
