<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucherscheduler extends Model
{
    use HasFactory;
    protected $table = 'tbl_voucher_scheduler';
    public $timestamps = false; 
    protected $guarded = [];
 

    protected $fillable=['VoucherNumber','StartDate','EndDate','Frequency'];
}
