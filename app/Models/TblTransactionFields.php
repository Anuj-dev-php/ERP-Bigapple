<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblTransactionFields extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $table="tbl_transaction_fields";

    protected $primaryKey="Id";
 
    protected $fillable=['role','transaction_table','field_name','sequence'];
}
