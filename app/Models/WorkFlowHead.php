<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkflowDet;

class WorkFlowHead extends Model
{
    use HasFactory;

    protected $table="Workflow_Head";
    public $timestamps=false;
    protected $primaryKey="id";
    protected $fillable=['RoleName','TranId'];


    public static function checkExistsByRoleTable($roleid,$tranid){

      $roletranexists=  Self::where('RoleName',$roleid)->where('TranId',$tranid)->exists();

      return  $roletranexists;

    }


    public static function getLinkInvAccSettings($roleid,$tranid){
       
       $settings=Self::where('RoleName',$roleid)->where('TranId',$tranid)->select('link_up','inv_up','acc_up','Savestatusid')->first();


       if(empty($settings)){
           return array('link_up'=>true,'inv_up'=>true,'acc_up'=>true,'savestatusid'=>NULL);
       }

       $result=array();

       if(trim($settings->link_up)=="True"){
        $result['link_up']=true;
       }
       else{
        $result['link_up']=false;
       }

       
       if(trim($settings->inv_up)=="True"){
        $result['inv_up']=true;
       }
       else{
        $result['inv_up']=false;
       }

       
       if(trim($settings->acc_up)=="True"){
        $result['acc_up']=true;
       }
       else{
        $result['acc_up']=false;
       }


       $result['savestatusid']=$settings->Savestatusid;



        return $result;
    }


    public static function checkWorkflowExistsForTransaction($tranid){

        $workflowexists=Self::where('TranId',$tranid)->exists();

        return  $workflowexists;

    }



    public function workflowdets(){

        
        return $this->hasMany(WorkflowDet::class,'id','fk_id') ;


    }
 

}
