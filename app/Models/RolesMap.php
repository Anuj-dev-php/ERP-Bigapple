<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesMap extends Model
{
    use HasFactory;
    protected $table = 'roles_map';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];
 

    protected $fillable=['RoleName','Tran_Id','Insert_Roles','Edit_Roles','Delete_Roles','View_Roles','Print_Roles','masters','history','amend','copy'];


    public static function getRoleTransactionActions($role,$tranid){
        
        $roleaction=Self::where(['RoleName'=>$role,'Tran_Id'=>$tranid])->first();

        $response=array("insert"=>false ,"edit"=>false,'delete'=>false,'view'=>false,'print'=>false,'masters'=>false,'history'=>false,'amend'=>false,'copy'=>false);


        if(empty( $roleaction)){
            return  $response;
        }

        $response['insert']=( trim($roleaction['Insert_Roles'])=="yes")?true:false;
        
        $response['edit']=( trim($roleaction['Edit_Roles'])=="yes")?true:false;

        $response['delete']=( trim($roleaction['Delete_Roles'])=="yes")?true:false;

        $response['view']=( trim($roleaction['View_Roles'])=="yes")?true:false;
        
        $response['print']=( trim($roleaction['Print_Roles'])=="yes")?true:false;
        
        $response['masters']=( trim($roleaction['masters'])=="yes")?true:false;

        $response['history']=( trim($roleaction['history'])=="yes")?true:false;
        
        $response['amend']=( trim($roleaction['amend'])=="yes")?true:false;
        
        $response['copy']=( trim($roleaction['copy'])=="yes")?true:false;
 
        return $response;

    }


}
