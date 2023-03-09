<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableModule;
use App\Models\TableModuleDet;
use App\Models\TableMaster;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\Role;
use App\Models\TblMy;
use App\Models\TblMyRpt;
use App\Models\Reports1;
use App\Models\VchType;
use App\Models\TblMyAcc;
use App\Models\TblMyaccRpt;
use App\Models\MainMenu; 
use App\Models\TblRptModule;
use App\Models\TblRptModuleDet;
use App\Models\TblCompanyNews;
use App\Models\User;
use App\Models\TblInternet; 
use App\Models\TblTransactionFields;
use App\Models\FieldsMaster;
use Session;

class CreateModuleController extends Controller
{
    
    public function index($companyname){
 

        $modules=TableModule::orderby('mname','asc')->orderby('sequence','asc')->get();

        $roles=Role::orderby('role_name','asc')->get();

        $reportmodules=TblRptModule::orderby('rmname','asc')->select('id','rmname')->get();

        $users=User::orderby('user_id','asc')->pluck('user_id');


        $emailconfs=TblInternet::orderby('id','desc')->get();


        $transactiontables=TableMaster::where('Table_Name','not like','%_det')->orderby('table_label','asc')->pluck('table_label','Table_Name');


        return view('configuration.createmodule',compact('companyname','modules','roles','reportmodules','users','emailconfs','transactiontables'));
        

    }


    public function getAllModules(){

        $modules=TableModule::orderby('mname','asc')->get()->toArray();

        return response()->json(['data'=>$modules]);
    }


    public function updateModuleName(Request $request){

        $allrequest=$request->all();

        $id=$request->id;

        $mname=$request->mname;

        $sequence=empty($request->sequence)?NULL:$request->sequence;

        TableModule::where('id',$id)->update(['mname'=>$mname,'sequence'=>$sequence]);
         

        return response()->json(  $allrequest);

    }



    public function getModuleTransactions($companyname,$moduleid){

      $selected=  TableMaster::whereIn('Id',function($query)use($moduleid){
            $query->from('tbl_module_det')->selectRaw('txn_id')->where('mid',$moduleid);

        })->where('Tab_Id','<>','Details')->orderby('table_label','asc')->select('Id as id','table_label as text')->get();

        
      $unselected=  TableMaster::whereNotIn('Id',function($query)use($moduleid){
        $query->from('tbl_module_det')->selectRaw('txn_id')->where('mid',$moduleid);

    })->where('Tab_Id','<>','Details')->orderby('table_label','asc')->select('Id as id','table_label as text')->get();


 
        return response()->json(["selected"=>$selected,"unselected"=>$unselected]);


    }




    public function submitModuleTransactions(Request $request){
 
        $module=$request->module;
 

        DB::beginTransaction();

            
        $transactionselected=json_decode($request->module_transaction_selected ); 

        try{
            
            TableModuleDet::where('mid',$module)->delete();


            $insertarray=array();


            $index=1;
            foreach($transactionselected as $transaction){ 

                array_push( $insertarray,array('mid'=>$module,'txn_id'=>$transaction,'sequence'=>$index));

                $index++;
            }



            
            $transactionschunk=array_chunk( $insertarray,100);
    
            foreach(  $transactionschunk as $transactionsinglechunk){
    
                TableModuleDet::insert($transactionsinglechunk);
    
            } 

            DB::commit();

        }
        catch(\Exception $e){ 

            DB::rollback();
        } 
     
        return  response()->json(["status"=>"success" ,"message"=>"Module Transactions Updated successfully"]); 
 

    }


    public function createModuleName(Request $request){

        $modulename=$request->modulename;

        $sequence=empty($request->sequence)?NULL:$request->sequence;

        $module_exists=TableModule::where('mname',$modulename)->exists();

        if(     $module_exists==true){

            return response()->json(['status'=>'failure','message'=>'Module already exists by Name of '.$modulename]);
        }

        TableModule::insert(['mname'=>$modulename,'sequence'=> $sequence]);
        return response()->json(['status'=>'success','message'=>'Module create successfully']);

    }



    public function getModuleTransactionsForSequence($companyname,$moduleid){

      $txns=TableModuleDet::join('table_master','table_master.Id','=','tbl_module_det.txn_id')->where('tbl_module_det.mid',$moduleid)->orderby('tbl_module_det.sequence','asc')->select('tbl_module_det.id','tbl_module_det.sequence','table_master.Table_Name as txn' )->get();

     return response()->json( $txns );
    }



    public function submitModuleTxnSequence(Request $request){

        $allrequests=$request->all(); 
        $id=$request->id;
        $sequence=empty($request->sequence)?NULL:$request->sequence;
        TableModuleDet::where('id',$id)->update(['sequence'=>$sequence]);
        
        return response()->json( $allrequests); 
    }


    public function getRoleTransactionAtModules($companyname,$roleid){

        
        $tableids = TableMaster::join('roles_map', 'table_master.id', '=', 'roles_map.Tran_Id')->where('roles_map.RoleName', $roleid)
        ->where(function ($query) {
            $query->where('Insert_Roles', 'yes')->orwhere('Edit_Roles', 'yes')->orwhere('Delete_Roles', 'yes')->orwhere('View_Roles', 'yes')->orwhere('Print_Roles', 'yes')->orwhere('masters', 'yes')->orwhere('history', 'yes')->orwhere('amend', 'yes')->orwhere('copy', 'yes');
        })
        ->orderBy('table_master.table_label', 'ASC')
        ->pluck( 'table_master.Id');


        $selected=TableMaster::whereIn('Id',function($query)use( $roleid,$tableids){

            $query->from('tbl_my')->selectRaw('txn_id')->where('role_id',$roleid);

        })->whereIn('Id',$tableids)->orderby('table_label','asc')->select('Id as id','table_label as text')->get();



        $unselected=TableMaster::whereNotIn('Id',function($query)use( $roleid,$tableids){

            $query->from('tbl_my')->selectRaw('txn_id')->where('role_id',$roleid);

        })->whereIn('Id',$tableids)->orderby('table_label','asc')->select('Id as id','table_label as text')->get();

        return response()->json(['selected'=>$selected,'unselected'=>$unselected]);


    }


    public function submitModuleRoleTransactions(Request $request){
 

        $role=$request->role;;
        $txns_selected=json_decode($request->role_txns_selected,true);

        DB::beginTransaction();

        try{

            TblMy::where('role_id',$role)->delete();
 
            $insertarray=array();
    
            foreach( $txns_selected as $txn){
                array_push( $insertarray,array('role_id'=>$role,'txn_id'=>$txn,'Type'=>'','url'=>''));
    
            }
    
            TblMy::insert($insertarray);

            DB::commit();

        }
        catch(\Exception $e){
            DB::rollback();
        }

     
        return response()->json(['status'=>'success' ,'message'=>'Txn Shortcut updated successfully']);
    }



    public function getModuleRoleReportShortcuts($companyname,$roleid){


       $selected= Reports1::whereIn('reportid',function($query)use($roleid){
            
            $query->from('tbl_my_rpt')->selectRaw('report_id')->where('role_id',$roleid);

        })->orderby('reportname','asc')->select('reportid as id','reportname as text')->get();

        $unselected= Reports1::whereNotIn('reportid',function($query)use($roleid){
            
            $query->from('tbl_my_rpt')->selectRaw('report_id')->where('role_id',$roleid);

        })->orderby('reportname','asc')->select('reportid as id','reportname as text')->get();


        return response()->json(['selected'=>$selected,'unselected'=>$unselected]);
 

    }


    public function submitModuleRoleReportShortcuts(Request $request){
 
        $role=$request->role;
        $reportselected=json_decode($request->role_reportshortcut_selected,true);

        DB::beginTransaction();

        try{

            TblMyRpt::where('role_id',$role)->delete();

            $insertarray=array(); 

            foreach( $reportselected as $report){
                array_push($insertarray,array('role_id'=>  $role,'report_id'=>$report));
            }


            TblMyRpt::insert($insertarray);

            DB::commit();
        }
        catch(\Exception $e){

            DB::rollback();

        }

        return response()->json(['status'=>'success','message'=>'Report Shortcuts updated successfully']);

    }



    public function getModuleRoleVoucherTypes($companyname,$roleid){
 


    $selected=VchType::whereIn('Id',function($query)use($roleid){
        
        $query->from('tbl_my_acc')->selectRaw('vch_id')->where('role_id',$roleid);

    })->orderby('Name','asc')->select('Id as id','Name as text')->get();

    

    $unselected=VchType::whereNotIn('Id',function($query)use($roleid){
        
        $query->from('tbl_my_acc')->selectRaw('vch_id')->where('role_id',$roleid);

    })->orderby('Name','asc')->select('Id as id','Name as text')->get();
 

    return response()->json(['selected'=>$selected,'unselected'=>$unselected]);


    }



    public function submitModuleRoleAcShortcuts(Request $request){
 

        $role=$request->role;

        $acshortcuts=json_decode($request->role_acshortcut_selected,true);

        DB::beginTransaction();

        try{
            
        TblMyAcc::where('role_id',$role)->delete();

        $insertarray=array();

        foreach($acshortcuts as $vchid){

            array_push($insertarray,array('role_id'=>  $role,'vch_id'=>$vchid));

        }


        TblMyAcc::insert($insertarray);

            DB::commit();
        }
        catch(\Exception $e){

            DB::rollback();

        }
 
        return response()->json(['status'=>'success','message'=>'Ac Shortcuts updated successfully']);

    }


    public function getModuleAcReportShortcuts($companyname,$roleid){

     $selected=MainMenu::where(['parent'=>19,'main_parent'=>19])->whereIn('id',function($query)use($roleid){
            
            $query->from('tbl_myacc_rpt')->selectRaw('menu_rpt_id')->where('role_id',$roleid);

        })->select('id','Menu_name as text')->get();


        $unselected=MainMenu::where(['parent'=>19,'main_parent'=>19])->whereNotIn('id',function($query)use($roleid){
            
            $query->from('tbl_myacc_rpt')->selectRaw('menu_rpt_id')->where('role_id',$roleid);

        })->select('id','Menu_name as text')->get();
        

        return response()->json(['selected'=>$selected,'unselected'=>$unselected]);
    }


    public function submitModuleRoleAcReportShortcuts(Request $request){

        $role=$request->role;

        $menuselected=json_decode($request->role_acreportshortcut_selected,true);

        DB::beginTransaction();


        try{
            TblMyaccRpt::where('role_id',$role)->delete();


            $insertarray=array();

            foreach(  $menuselected as $menu){

                array_push($insertarray,array('role_id'=>$role,'menu_rpt_id'=>$menu));

            }

            TblMyaccRpt::insert($insertarray);

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
        }

      
        return response()->json(['status'=>'success','message'=>'AC Report Shortcut saved successfully']);

    }


    public function updateModuleTxnSequences(Request $request){

        $ids=$request->ids;

        $index=1;
        foreach($ids as $id){

            TableModuleDet::where('id',$id)->update(['sequence'=>$index]);

            $index++;
        }
 
        return response()->json(['status'=>'success','message'=>'Module Transaction Sequence changed successfully']);

    }


    public function addModuleReportByName(Request $request){

        $mname=$request->mname;
        $sequence=empty($request->sequence)?NULL:$request->sequence;

       $moduleexists= TblRptModule::where('rmname', $mname)->exists();

       if(   $moduleexists==true){
           return response()->json(['status'=>'fail','message'=>'Report Module already exists by this name']);
       }


       TblRptModule::insert(['rmname'=>$mname,'sequence'=>$sequence]);


       return response()->json(['status'=>'success','message'=>'Report Module created successfully']);
 
    }


    public function getModuleReportModules($companyname){

      $modules=  TblRptModule::orderby('sequence','asc')->select('rmname','sequence','id')->get();

      return response()->json( $modules);

    }


    public function updateReportModuleSequences(Request $request){ 


        $ids=$request->ids; 
        $index=1;
        foreach($ids as $id){ 
            TblRptModule::where('id',$id)->update(['sequence'=>$index]);
            $index++;
        }


        return response()->json(['status'=>'success','message'=>'Report Module sequence updated successfully']);
    }



    public function updateModuleReportModuleName(Request $request){
    
        $allrequests=$request->all();

        $rmname=$request->rmname;

        $id=$request->id; 

        if( $rmname==null || trim($rmname)==''   ){
            return;
        }

        TblRptModule::where('id', $id)->update(['rmname'=>$rmname]);
  

        return response()->json($allrequests);


    }


    public function getReportModuleReports($companyname,$rmid){

     $selected=Reports1::whereIn('reportid',function($query)use($rmid){
        $query->from('tbl_rpt_module_det')->selectRaw('rptid')->where('rmid',$rmid);

     })->orderby('reportname','asc')->select('reportid as id','reportname as text')->get();


     $unselected=Reports1::whereNotIn('reportid',function($query)use($rmid){
        $query->from('tbl_rpt_module_det')->selectRaw('rptid')->where('rmid',$rmid);

     })->orderby('reportname','asc')->select('reportid as id','reportname as text')->get();

 
     return response()->json(['selected'=>$selected,'unselected'=>$unselected]);

    }


    public function submitAddReportReports(Request $request){
 
 
          $reportmodule=$request->reportmodule;

          $reportselected=json_decode($request->module_addreport_report_selected,true);
  
          DB::beginTransaction();
  
          try{
            TblRptModuleDet::where('rmid',$reportmodule)->delete();
  
  
              $insertarray=array();

                $index=1;
              foreach( $reportselected as $report){
  
                  array_push($insertarray,array('rmid'=>$reportmodule,'rptid'=>$report,'sequence'=>$index));
                $index++;
              }
  
              TblRptModuleDet::insert($insertarray);
  
              DB::commit();
          }
          catch(\Exception $e){
              DB::rollback();
          }
  
        
          return response()->json(['status'=>'success','message'=>'Add Report to Module saved successfully']);
          

    }


    public function getReportModuleSequenceRpts($companyname,$reportmoduleid){

        TblRptModuleDet::where('rmid',$reportmoduleid)->select()->get();

        $reports=Reports1::join('tbl_rpt_module_det','reports1.reportid','=','tbl_rpt_module_det.rptid')->orderby('sequence','asc')->select('tbl_rpt_module_det.id','tbl_rpt_module_det.sequence','reports1.reportname')->get();
        return response()->json($reports);
    }

    public function updateReportModuleRptsSequences(Request $request){

        $ids=$request->ids;

        $index=1;

        $rpts=array();
 
        foreach($ids as $id){
            TblRptModuleDet::where('id',$id)->update(['sequence'=>$index]);
            $index++;

        }
        
        return response()->json(['status'=>'success','message'=>'Report Module Reports sequence updated successfully']);
          

    }

    public function submitCompanyNews(Request $request){
  

        if(isset($request->id)){


            if($request->action=='edit'){
             

            $datestring=$request->date ;

            $datearray=explode("-", $datestring);

            $newdatestring=$datearray[2].'-'.$datearray[1].'-'.$datearray[0];
            TblCompanyNews::where('id',$request->id)->update(['News'=>$request->news,'date'=>$newdatestring ,'display'=>$request->display]);
              
        }
        else if($request->action=='delete'){
         TblCompanyNews::where('id',$request->id)->delete();

        }

            $allrequest=$request->all();

            return response()->json($allrequest);
 
        }
        else{
            TblCompanyNews::insert(['News'=>$request->news,'date'=>$request->date ]);


            return response()->json(['status'=>'success','message'=>'Company News Added successfully']);
 

        } 

    }

    public function getModuleCompanyNews(Request $request){
 

      $news=TblCompanyNews::orderby('id','desc')->get();

      return response()->json($news); 

    }


    public function submitModuleEmailConfiguration(Request $request){
 

        $data=$request->data;
        

       $newone= TblInternet::create(['get_user'=>$data['user'],'mailid'=>$data['email'],'pwd'=>$data['pwd'],'smtp'=>$data['host'],'port'=>$data['port']]);

        $data['id']=$newone->id;

        return response()->json(['status'=>'success','message'=>'Email Configuration Added successfully','data'=>$data]);
 
    }



    public function updateModuleEmailConfiguration(Request $request){

        $allrequests=$request->all(); 
 
        if($request->action=='edit'){

            TblInternet::where('id',$request->id)->update(['mailid'=>$request->mail,'pwd'=>$request->password,'smtp'=>$request->smtp,'port'=>$request->port]);
 
        }
        else if($request->action=='delete'){
            TblInternet::where('id',$request->id)->delete();
        } 

        return response()->json($allrequests);

    }


    public function getAllEmailConfs(){

       $emailconfs= TblInternet::orderby('id','desc')->get();


       return response()->json(['data'=>$emailconfs]);

    }


    public function getModuleMenusWithSequence(){

        $menus=MainMenu::where('parent',0)->orderby('sequence','asc')->get();
        return response()->json($menus);

    }


    public function updateModuleMenuSequence(Request $request){
        $ids=$request->ids;

        $index=1;
 
 
        foreach($ids as $id){ 
            MainMenu::where('id',$id)->update(['sequence'=>$index]);
            $index++;

        }
        
        return response()->json(['status'=>'success','message'=>'Menu Sequence changed successfully']);
       
    }


    public function getModuleTransactionFieldsSelectedUnselected($companyname,$tablename){

        // TblTransactionFields

        $selected=FieldsMaster::where('Table_Name',$tablename)->whereIn('Field_Name',function($query)use($tablename){
            $query->from('tbl_transaction_fields')->selectRaw('field_name')->where('transaction_table',$tablename) ;
        })->where('Tab_Id','<>','None')->orderBy('fld_label','asc')->select('fields_master.Field_Name as name','fld_label as label')->get();

        
        $unselected=FieldsMaster::where('Table_Name',$tablename)->whereNotIn('Field_Name',function($query)use($tablename){
            $query->from('tbl_transaction_fields')->selectRaw('field_name')->where('transaction_table',$tablename) ;
        })->where('Tab_Id','<>','None')->orderBy('fld_label','asc')->select('fields_master.Field_Name as name','fld_label as label')->get();
 
        return response()->json(['selected'=>$selected,'unselected'=>$unselected]);


    }



    public function submitModuleTransactionFields(Request $request){

        $transaction=$request->transaction;

        $role=$request->role;

        $transactionfields=json_decode($request->transaction_fields_selected_hf,true);

        TblTransactionFields::where('role',  $role)->where('transaction_table',$transaction)->delete();
        
        $insertarray=array();

        foreach(   $transactionfields as    $transactionfield){

            array_push(     $insertarray,array( 'role'=>$role,'transaction_table'=>$transaction,'field_name'=> $transactionfield));

        }

        TblTransactionFields::insert($insertarray);
 
        return  response()->json(["status"=>"success" ,"message"=>"Transaction Fields saved successfully "]); 
    }

    public function getTransactionTableFieldsWithSequence(Request $request){

        if(empty($request->role_id)){
            $role=Session::get('role_id');
        }
        else{
            $role=$request->role_id;
        }
       
        $tranid=$request->tranid;

       

        $fields=FieldsMaster::join('tbl_transaction_fields',function($join){

            $join->on('fields_master.Table_Name','=','tbl_transaction_fields.transaction_table');
            
            $join->on('fields_master.Field_Name','=','tbl_transaction_fields.field_name');

        })->where('tbl_transaction_fields.transaction_table', $tranid)->orderby('tbl_transaction_fields.sequence','asc')->select('fields_master.fld_label as  label' ,'tbl_transaction_fields.sequence','tbl_transaction_fields.Id as id'  )->get(); 


        return response()->json(['fields'=>$fields]);

    }


    public function updateTxnFieldSequence(Request $request){

        $ids=$request->ids;

        $index=1; 
        foreach($ids as $id){ 
            TblTransactionFields::where('id',$id)->update(['sequence'=>$index]);
            $index++;

        }
        
        return response()->json(['status'=>'success','message'=>'Table Fields sequence changed successfully']);

    }
}
