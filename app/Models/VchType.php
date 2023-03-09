<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VchType extends Model
{
    use HasFactory;
    protected $table = "VchTypes";
    public $timestamps=false;
    protected $primaryKey = 'Id';
    protected $fillable=['Name','Parent'];


    public function subvchtypes(){
        return $this->hasMany(VchType::class,'Parent','Id');
    }
}
