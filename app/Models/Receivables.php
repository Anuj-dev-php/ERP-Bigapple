<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivables extends Model
{
    use HasFactory;

    protected $table='receivables';
 
    public $timestamps=false;

    protected $fillable=[
        'CustomerId'
        ,'Accid'
        ,'DocDate'
        ,'DocNO'
        ,'Amount'
        ,'TxnId'
        ,'PendingFlag'
        ,'Area'
        ,'Productid'
        ,'lastreceipt'
        ,'reff_no'
        ,'onaccount'
        ,'duedate'
        ,'r_p'
        ,'location'
        ,'dept'
        ,'salesman'
        ,'cur_name'
        ,'org_amt'
        ,'Exc_rate'
        ,'linearr2'
    ];



  public static function getDocDateFromDocno($docno){
      return   Self::whereNull('reff_no')->where('DocNO',$docno)->value('DocDate');
  }
}
