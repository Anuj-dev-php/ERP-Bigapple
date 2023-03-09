<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainMenu extends Model
{
    use HasFactory;
    // protected $connection = 'sqlsrv_2';
    protected $table = 'tbl_main_menu';
    public $timestamps = false;
    protected $fillable=['Menu_name','parent','main_parent','url'];

}
