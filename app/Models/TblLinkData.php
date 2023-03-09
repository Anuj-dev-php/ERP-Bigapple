<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblLinkData extends Model
{
    use HasFactory;


    protected $table="tbl_link_data";

    public $timestamps=false;
 
    protected $fillable=['txn_id','doc_date','doc_no','location','cust_id','product','batch_no','qty','rate','amount','reff_no','txn_main_id','link_main_id','txn_det_id','link_det_id','due_date','salesman','used_qty'];


    protected $primaryKey="Id";



}
