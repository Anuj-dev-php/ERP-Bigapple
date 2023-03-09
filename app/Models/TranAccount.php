<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranAccount extends Model
{
    use HasFactory;
    protected $table="Tran_Account";
    public $timestamps=false;
    protected $primaryKey = 'Id';
    protected $fillable=[  'TemplateId'
    ,'Description'
    ,'VchType'
    ,'VchSubTypes'
    ,'Account'
    ,'Transaction'
    ,'is_default'];
    

    public function tranaccdet(){
        return  $this->hasMany(TranAccDet::class,'TempId','Id');
    }

}
