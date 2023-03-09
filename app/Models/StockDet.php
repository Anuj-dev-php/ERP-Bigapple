<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDet extends Model
{
    use HasFactory;

    public $timestamps=false;

    protected $table="Stock_Det";

    protected $primaryKey="Id";

    protected $fillable=['DocNo','DocDate','PartyId','Location','BatchNo','Pk','Fk','Prodid','Qty','CRate'];

 
}
