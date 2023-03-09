<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleReports extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table="roles_reports";
    protected $fillable=['Role_id','Rpt_id'];
}
