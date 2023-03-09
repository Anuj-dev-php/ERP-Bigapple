<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Account extends Model
{
    use HasFactory;
    protected $table= "accounts";
    public $timestamps = false;


    public static function getAccountNameFromAccId($accid){

        return Self::where('Id',$accid)->value('ACName');

    }


    public static function getAccountBalanceFromAccId($accid){

        return Self::where('Id')->value('Bal');
    }

 
    // public function parent()
    // {
    //     return $this->belongsTo('App\Models\Account', 'Parent2')->where('Parent2', 0)->with('parent');
    // }

    // public function children()
    // {
    //     return $this->hasMany('App\Models\Account', 'Parent2')->with('children');
    // }


    public static function buildTree($parentChildText)
    {
        // foreach ($parentIds as $parentKey => $parentId) {

        $branch = array();
        // if (empty($elements)) {
        $sql = new Account();
        $elements = $sql->select('accounts.ACName', 'accounts.Id', 'accounts.Parent2')
            ->whereIn('accounts.ACName', $parentChildText)
            ->reorder('accounts.Id', 'asc')
            ->get()->toArray();
        // }

        // foreach ($elements as $eleKey => $element) {
        //     if ($element['Id'] == $parentId) {
        //         $children = self::buildTree($element['Parent2']);
        //         if ($children) {
        //             $element['children'] = $children;
        //         }

        //         $branch = $element;
        //     }
        // }

        // echo '<pre>';print_r($elements);exit;
        return $elements;
        // }
    }

    // static function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    //     $sort_col = array();
    //     foreach ($arr as $key => $row) {
    //         $sort_col[$key] = $row[$col];
    //     }

    //     array_multisort($sort_col, $dir, $arr);

    //     return $arr;
    // }



   public  static function getAllAccIds($accountName)
    {
        // print_r($accountName);exit;
        $data = Account::whereIn('ACName',$accountName)->orderBy('Id')->pluck('Id');
        return $data;
    }


    public static function getChildAccounts($parentid){
 

       return  Self::where('Parent2', '<>',0)->where('Parent2',$parentid)->orderby('ACName','asc')->select('ACName as account_name','Id as id','Parent2 as parent','G-A as ga')->get()->toArray();

    }
}
