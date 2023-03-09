<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvAcc extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $table='Inv_Acc';

    protected $primaryKey="Id";


    protected $fillable=['Txn_Id','TempName','tablename'];

}
