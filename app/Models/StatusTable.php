<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTable extends Model
{
    use HasFactory;
    protected $table="Status_Table";
    public $timestamps = false;

    public static function getStatusNameFromStatusId($statusid){

        return Self::where('id',$statusid)->value('StatusName');
        
    }
}
