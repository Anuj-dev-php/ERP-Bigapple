<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaster extends Model
{
    use HasFactory;
    protected $table = 'Product_master';
    public $timestamps = false;

    
    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'Id');
    }

    public function allchildren()
    {
        return $this->hasMany(self::class, 'parent', 'Id')->with('allchildren');;
    }


    public static function getChildProducts($parentid){

        return Self::where('parent',$parentid)->orderby('Product','asc')->select('Id as product_id','Product as product_name')->get()->toArray();



    }


}
