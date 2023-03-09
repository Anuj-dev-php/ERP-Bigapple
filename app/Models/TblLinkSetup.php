<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblLinkSetup extends Model
{
    use HasFactory;

    protected $table="tbl_link_setup";

    public $timestamps=false;


    protected $primaryKey="Id";



}
