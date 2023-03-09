<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPartyType extends Model
{
    use HasFactory;
    protected $table="tbl_party_type"; 
    public $timestamps=false;
}
