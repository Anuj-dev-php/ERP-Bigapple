<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkFlowHead;
use App\Models\StatusTable;
use App\Models\Role;
use Illuminate\Support\Facades\Log; 
use App\Models\WorkFlowDet;
use App\Models\TableMaster;
use DB; 

class DefineWorkflowController extends Controller
{
     public function index($companyname, Request $request){ 
 
         $workflowheads=WorkFlowHead::join('table_master','Workflow_Head.TranId','=','table_master.Id')->select('Workflow_Head.*','table_master.Table_Name')->orderby('id','desc')->get();

         $status=StatusTable::orderby('StatusName','asc')->get()->pluck('StatusName','id')->toArray();

         $rolenames=Role::pluck('role_name','id');


 
  
        return view('configuration.defineworkflow',compact('companyname','workflowheads','status','rolenames'));
     }
 


     public function addEditDefineDefineWorkflow($companyname,$workflowheadid=null){
          
          $roles=Role::orderby('role_name','asc')->get();
          $statuses=StatusTable::orderby('StatusName','asc')->get();

 
          if(!empty($workflowheadid)){
              $workflowhead= WorkFlowHead::where('id',$workflowheadid)->first();
              $tbldetail=TableMaster::where('Id', $workflowhead->TranId)->select('Table_Name','table_label')->first();
              $transactiontable= $tbldetail->Table_Name;
              $transactiontablelabel=$tbldetail->table_label;

          }
          else{
               $workflowhead='';
               $transactiontable='';
               $transactiontablelabel=''; 
          }
 

          if(!empty($workflowheadid)){
               
            $workflowdetails_save= WorkFlowDet::where('fk_id',$workflowheadid)->where('Statustype','Save')->get();

           $firstworkflowsave= $workflowdetails_save->first();
 
               
           if($firstworkflowsave->conjestion=="Only"){

               $savestatuses=StatusTable::orderby('StatusName','asc')->get();

               $inboxstatuses=StatusTable::orderby('StatusName','asc')->get();

           }
           else{
               $savestatuses=StatusTable::where('id',$firstworkflowsave->statusid)->orderby('StatusName','asc')->get();

               $inboxstatuses=StatusTable::where('id',$firstworkflowsave->statusid)->orderby('StatusName','asc')->get();

           }



           $conjunctions=array($firstworkflowsave->conjestion);
            
            $workflowdetails_inbox= WorkFlowDet::where('fk_id',$workflowheadid)->where('Statustype','Inbox')->get();

            $workflowdetails=array('save'=> $workflowdetails_save,'inbox'=>  $workflowdetails_inbox);

          }
          else{
               
               $conjunctions=array('And','Or','Only'); 
               $workflowdetails= '';
               
               $savestatuses=StatusTable::orderby('StatusName','asc')->get();

               $inboxstatuses=StatusTable::orderby('StatusName','asc')->get();



          }

 
          return view('configuration.addeditdefineworkflow',compact('companyname','roles','statuses','workflowheadid','workflowhead','workflowdetails','transactiontable','transactiontablelabel','conjunctions','savestatuses','inboxstatuses'));

     }


     public function getNewWorkflowSaveRow($companyname , $rownum,Request $request){
 
          $selectstatus=$request->selectstatus;

          $selectconjunction=$request->selectconjunction;

          

          if(empty($selectstatus)  ||  $selectconjunction=="Only"){
               $showallstatus=true; 
          }
          else{
               $showallstatus=false;
          }


          $savestatuses=StatusTable::when(!$showallstatus,function($query)use($selectstatus){

               $query->where('id',$selectstatus);

          })->orderby('StatusName','asc')->get();



          if(empty($selectconjunction)){
               
             $conjunctions=array('And','Or','Only'); 
          }
          else if($selectconjunction=="And"){
               
            $conjunctions=array('And');

          }
          else if($selectconjunction=="Or"){
               
            $conjunctions=array('Or');

          }
          else if($selectconjunction=="Only"){
               
            $conjunctions=array('Only');

          }
 

          $savehtml=view("configuration.workflowsavetr",compact('rownum','savestatuses', 'conjunctions' ,'selectstatus','selectconjunction'))->render();

          return response()->json(['savehtml'=>$savehtml ]);

     }


     public function submitDefineWorkflow($companyname,Request $request){


          $allroles=Role::getAllRolesArray();
          $workflowheadid=$request->workflowheadid;

          if(empty( $workflowheadid)){ 
               $workflowhead=new WorkFlowHead;
               $workflowhead->RoleName=$request->role;

               $workflowhead->TranId=$request->transaction;

               $tablemaster= TableMaster::find( $workflowhead->TranId);
               
             $workflowexists=WorkFlowHead::checkExistsByRoleTable($workflowhead->RoleName, $workflowhead->TranId);

             if($workflowexists){

               $rolename=$allroles[ $workflowhead->RoleName];

               return redirect()->back()->with('message',"Workflow already exists for Role-". $rolename." for Table-".$tablemaster->Table_Name);

             }



              $tablefields= $tablemaster->fields()->get()->pluck('Field_Name')->toArray();
          

              if(!in_array('status',$tablefields)  || !in_array('reject_reason',$tablefields) ){

               return redirect()->back()->with('message',$tablemaster->Table_Name." do not have status and reject_reason field");
              }



  

               $msg="added";
          }
          else{
               $workflowhead= WorkFlowHead::find($workflowheadid);
               $msg="updated";
          }

          $workflowhead->Savestatusid=$request->savestatus;
          $workflowhead->Inboxstatus=$request->inboxstatus;
          $workflowhead->Rejectstatus=$request->rejectstatus;
          $workflowhead->link_up=isset($request->update_links)?"True":"False";
          $workflowhead->inv_up=isset($request->update_inventory)?'True':'False';
          $workflowhead->acc_up=isset($request->update_accounts)?'True':'False';
          $workflowhead->save(); 

          $headid=   $workflowhead->id;  
          $index=0;
          $savefields=$request->savefield;
          $savefieldsarray=array();

          $savefieldstatus=$request->savefieldstatus;
          $savecondition=$request->savecondition;
          $savevalue=$request->savevalue;
          $saveconjunction=$request->saveconjunction;

          $tablename=TableMaster::where('Id',$workflowhead->TranId)->value('Table_Name');

          DB::beginTransaction();

          try{

        
          
          if(!empty( $workflowheadid)){
               WorkFlowDet::where('fk_id',$workflowheadid)->delete();

          }

          foreach(  $savefields as   $savefield){

               array_push( $savefieldsarray,array(    'fk_id'=>$headid
               ,'Tranname'=>     $tablename
               ,'Fieldid' =>$savefield
               ,'statusid'=>$savefieldstatus[$index]
               ,'Condition'=> $savecondition[$index]
               ,'Value'=>  $savevalue[$index]
               ,'Statustype' =>"Save"
               ,'conjestion' =>$saveconjunction[$index]
               ,'fld_val'=>NULL ));

               $index++;
          } 

          if(count($savefieldsarray)>0){
             WorkFlowDet::insert($savefieldsarray);
          }

          $inboxfieldsarray=array(); 



          $inboxfields=$request->inboxfield;
          $fieldinboxstatus=$request->fieldinboxstatus;
          $inboxconditions=$request->inboxcondition;
          $inboxvalue=$request->inboxvalue;
          $inboxconjunction=$request->inboxconjunction;
          $index=0;
          if(empty($inboxfields) ){

               goto finalcommit;
          }
          foreach($inboxfields as $inboxfield){               
               array_push( $inboxfieldsarray,array(    'fk_id'=>$headid
               ,'Tranname'=>     $tablename
               ,'Fieldid' =>$inboxfield
               ,'statusid'=> $fieldinboxstatus[$index]
               ,'Condition'=>    $inboxconditions[$index]
               ,'Value'=>  $inboxvalue[$index]
               ,'Statustype' =>"Inbox"
               ,'conjestion' =>$inboxconjunction[$index]
               ,'fld_val'=>NULL ));

               $index++; 
          }


          if(count($inboxfieldsarray)>0){

               WorkFlowDet::insert($inboxfieldsarray);

          }
                finalcommit:
                    DB::commit();
               }
               catch(\Exception $e){

                    Log::info($e->getMessage() );
                    
                    Log::info($e->getLine() );

                    
                    Log::info($e->getFile() );
                    DB::rollback();
               }
 
          return redirect('/'.$companyname.'/define-work-flow')->with('message','Workflow '.$msg.' successfully');
          
     }


     public function getNewWorkflowInboxRow($companyname,$rownum){

          $statuses=StatusTable::orderby('StatusName','asc')->get();

          $inboxhtml=view("configuration.workflowinboxtr",compact('rownum','statuses'))->render();

          return response()->json(['inboxhtml'=>   $inboxhtml]);

     }


     public function deleteDefineWorkflows(Request $request){

          $ids=$request->ids;

          DB::beginTransaction();

          try{

               WorkFlowDet::whereIn('fk_id', $ids)->delete();
               WorkFlowHead::whereIn('id',$ids)->delete();
               DB::commit();
          }
          catch(\Exception $e){
               DB::rollback();

          }

          return response()->json(['status'=>'success','message'=>'Selected Workflows deleted successfully']);


     }
}
