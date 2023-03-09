<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'Customers';
    public $timestamps=false; 


    public static function getAccountIdFromCustomerId($custid){

        return Self::where('Id',$custid)->value('Acc_id');;

    }


    public static function getCustomerIdFromAccountId($accid){

        
        return Self::where('Acc_id',$accid)->value('Id');;

    }
}
