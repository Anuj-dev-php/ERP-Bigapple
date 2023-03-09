<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbl_roles';
    public $timestamps = false; 


    public static function getAllRolesArray(){

        $roles=Self::pluck('role_name','id');

        return $roles;
    }
}
