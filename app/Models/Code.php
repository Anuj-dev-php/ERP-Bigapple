<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table="code";

    protected $fillable=['table_name','Field','prefix','code','suffix'];

    protected $primaryKey="id";

}
