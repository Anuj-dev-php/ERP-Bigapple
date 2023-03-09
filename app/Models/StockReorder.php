<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReorder extends Model
{
    use HasFactory;
    
    public $timestamps=false;

    
    protected $table="stockreorder";

    protected $primaryKey="Id";

    


}
