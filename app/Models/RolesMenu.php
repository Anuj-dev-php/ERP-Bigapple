<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesMenu extends Model
{
    protected $table = 'roles_menu';
    public $timestamps = false;
    protected $fillable=['role_id','menu_name'];
    use HasFactory;
}
