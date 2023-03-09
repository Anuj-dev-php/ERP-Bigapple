<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use App\Repositories\RoleMapRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\TableMasterRepository;
use App\Http\Requests\Configuration\CreateRoleRequest;
use App\Models\RolesMap;
use App\Models\Role;
use App\Models\User;
use App\Helper\Helper;
use App\Models\TableModule;
use App\Models\TableModuleDet;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use App\Models\FieldLevel;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\InboxTab;
use App\Models\RoleInboxTabHiding;
use App\Models\Master;
use App\Models\RoleMasterRestrictions;
use App\Models\MainMenu;
use App\Models\RolesMenu;
use App\Models\VchType;
use  App\Models\RolesAccount;
use App\Models\UserCompany;
use Illuminate\Support\Facades\Hash;
use App\Models\Report;
use App\Models\RoleReports;
use App\Models\TblRoleModule; 
use Config;  
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Illuminate\Support\Facades\Mail;
use Swift_Attachment;
use Storage;
use App\Http\Controllers\Services\EmailTranDataService; 
use Illuminate\Mail\Mailer;
use App\Models\TblRoleStockRateRestriction;
use Illuminate\Support\Facades\Auth; 


class RoleController extends AppBaseController
{
    protected $roleRepository;
    protected $tableMasterRepository;
    protected $roleMapRepository;
    protected $companyRepository;

    public function __construct(
        RoleRepository $roles,
        TableMasterRepository $tableMasters,
        RoleMapRepository $roleMaps,
        CompanyRepository $companies
    ) {
        $this->roleRepository = $roles;
        $this->tableMasterRepository = $tableMasters;
        $this->roleMapRepository = $roleMaps;
        $this->companyRepository = $companies;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($companyname,Request $request)
    {
        $roleDatas = $this->roleRepository->orderBy('role_name', 'ASC')->get();
        $tableMasterDatas = $this->tableMasterRepository->get();
        $companyDatas = $this->companyRepository->get();
        // get Dyanmic role of each company
        // foreach ($companyDatas as $key => $value) {
        //     $dataSwitch = Helper::setCompanyDatabase($value);
        //     // echo $value->db_name;
        //     $value->roles = $this->getAllRole();
        //     $value->users = User::get(['id', 'user_id']); // user_id for User Name
        // }
        // return $companyDatas;
        $inboxtabs=InboxTab::OrderBy('tab_name')->get();
        $masters=Master::OrderBy('master_name','ASC')->get();

        $menus=MainMenu::where('parent',0)->orderBy('Menu_name','asc')->pluck('Menu_name','id');

        $submenus=array();

        $subsubmenus=array();

        foreach($menus as $menuid=>$menuname){

            $fetchsubmenus=MainMenu::where('parent',$menuid)->orderBy('Menu_name','asc')->pluck('Menu_name','id');

            if(count($fetchsubmenus)==0){

                $submenus[$menuid]=array();

                continue;

            }
            else{

                $submenus[$menuid]=     $fetchsubmenus;

                foreach( $submenus[$menuid] as  $submenuid=>$submenuname){

                    $fetchsubsubmenus=MainMenu::where('parent',$submenuid)->orderBy('Menu_name','asc')->pluck('Menu_name','id');

                    if(count($fetchsubsubmenus)==0){
                        $subsubmenus[ $submenuid]=array();
                        continue;
                    }
                    else{
                        $subsubmenus[ $submenuid]= $fetchsubsubmenus;

                    }

                }
            }
        }

        $cmproles=array();

        $currentdbname=   $request->session()->get('company_name');
     

        foreach(  $companyDatas as $cmpdata){
            $dbname=$cmpdata->db_name;

            if(!Helper::checkDatabaseExists(  $dbname)){
                continue;
            } 

             Helper::connectDatabaseByName( $dbname); 

             $roles=Role::orderby('role_name','asc')->pluck('role_name','id');

             $cmproles[$cmpdata->id]=$roles;   
        } 
 
        $users=User::orderby('user_id','asc')->pluck('user_id','id'); 
        
        Helper::connectDatabaseByName( $currentdbname); 

        $accounttypes=VchType::where('Parent',0)->orderBy('Name')->pluck('Name');


        return view('configuration.role', compact('roleDatas', 'tableMasterDatas'  ,'companyDatas','inboxtabs','masters','menus','submenus','subsubmenus' ,'accounttypes','users','cmproles','companyname'));
    }
 

    public function getAllRole()
    {
        $roles = Role::select('id', 'role_name')->get();
        return $roles;
    }

    public function getRolesList()
    {
        $roleDatas = $this->roleRepository->get();
        return $roleDatas;
    }

    public function fetchRoles(Request $request)
    {
        $toReturn = [];
        $tableMasterDatas =TableMaster::orderBy('table_label','asc')->select('table_label','Id')->get();
        $roleid=$request->role_id;  
  
        $rolemaps=RolesMap::where('RoleName',$roleid)->select('Tran_Id','Insert_Roles','Edit_Roles','Delete_Roles','View_Roles','Print_Roles','masters','history','copy','amend')->get();

        $insert=array();
        $edit=array();
        $delete=array();
        $view=array();
        $print=array();
        $master=array();
        $history=array();
        $copy=array();
        $amend=array();


        foreach(   $rolemaps as    $rolemap){
 
            if(trim($rolemap->Insert_Roles)=='yes'){
                array_push($insert,$rolemap->Tran_Id);
            }

            if(trim($rolemap->Edit_Roles)=='yes'){
                array_push($edit,$rolemap->Tran_Id);
            }

            
            if(trim($rolemap->Delete_Roles)=='yes'){
                array_push($delete,$rolemap->Tran_Id);
            }


            
            if(trim($rolemap->View_Roles)=='yes'){
                array_push($view,$rolemap->Tran_Id);
            }


            
            if(trim($rolemap->Print_Roles)=='yes'){
                array_push($print,$rolemap->Tran_Id);
            }

            if(trim($rolemap->masters)=='yes'){
                array_push($master,$rolemap->Tran_Id);
            }

            if(trim($rolemap->history)=='yes'){
                array_push($history,$rolemap->Tran_Id);
            }

            
            if(trim($rolemap->amend)=='yes'){
                array_push($amend,$rolemap->Tran_Id);
            }

            if(trim($rolemap->copy)=='yes'){
                array_push($copy,$rolemap->Tran_Id);
            }


        }
 
        return response()->json(['transactions'=> $tableMasterDatas,'insert'=>$insert,'edit'=>$edit,'delete'=>$delete,'view'=>$view,'print'=>$print,'master'=>$master,'history'=>$history,'copy'=>$copy,'amend'=>$amend ]);
       
    }

    public function roleMaps(Request $request)
    {
 
        $roleid=$request->role_name; 

        $transactiondata=json_decode($request->data,true); 

        $transactionids=$request->transactions;
        
        RolesMap::where('RoleName',$roleid)->whereIn('Tran_Id', $transactionids)->delete();
        
        $trandata=array();

        foreach( $transactiondata as $tranrow){
            array_push( $trandata, array('RoleName'=> $roleid ,'Tran_Id'=>$tranrow['tran_id'] ,'Insert_Roles'=>$tranrow['insert'],'Edit_Roles'=>$tranrow['edit'],'Delete_Roles'=>$tranrow['delete'],'View_Roles'=>$tranrow['view'],'Print_Roles'=>$tranrow['print'],'masters'=>$tranrow['master'],'history'=>$tranrow['history'],'amend'=>$tranrow['amend'],'copy'=>$tranrow['copy']));
        }


         $transactionchunk=array_chunk($trandata,100);

        foreach(  $transactionchunk as   $transactionsinglechunk){

            RolesMap::insert($transactionsinglechunk);
        }
        
        return response()->json(['status'=>'success','message'=>'Role Transactions saved successfully']);
  
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getRole(Request $request)
    {
        // return "ABC";
        $input = collect($request->all());
        // return $input['id'];
        return $this->roleRepository->find($input['id']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    { 
        $id=$request->id;
        $rolename=$request->role_name;

        if(!empty($id)){
            $role=Role::where('id',$id)->first();
            $msg = "Role Updated Successfully";
        }
        else{
            $role=new Role;
            $msg = "Role Saved Successfully";
        }

        $role->role_name=$rolename;
        $role->save();
 

        return response()->json([ 'status'=>'success','message' => $msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return $id;
        // return print_r(response()->$this->roleRepository->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return $this->roleRepository->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function roleTran($companyname, $roleid)
    {

        $isadmin = Role::where('role_name', 'admin')->where('id', $roleid)->exists();

        if ($isadmin == false) {

            $tables = TableMaster::join('roles_map', 'table_master.id', '=', 'roles_map.Tran_Id')->where('roles_map.RoleName', $roleid)
                ->where(function ($query) {
                    $query->where('Insert_Roles', 'yes')->orwhere('Edit_Roles', 'yes')->orwhere('Delete_Roles', 'yes')->orwhere('View_Roles', 'yes')->orwhere('Print_Roles', 'yes')->orwhere('masters', 'yes')->orwhere('history', 'yes')->orwhere('amend', 'yes')->orwhere('copy', 'yes');
                })
                ->orderBy('table_master.table_label', 'ASC')
                ->select('table_master.Table_Name', 'table_master.table_label','table_master.Id')->get();
        } else {

            $tables = TableMaster::orderBy('table_master.table_label', 'ASC')->select('table_master.Table_Name', 'table_master.table_label','table_master.Id')->get();
        }


        return response()->json(["tables" => $tables]);
    }


    public function transactionFields($companyname, $roleid, $tablename)
    {

        $tablenamedet = $tablename . "_det";


        $fields = FieldsMaster::leftjoin('tbl_fld_level', function ($join) use ($roleid) {

            $join->on('fields_master.Table_Name', '=', 'tbl_fld_level.txn_id');

            $join->on('fields_master.Field_Name', '=', 'tbl_fld_level.fld_id');

            $join->where('tbl_fld_level.uid', '=', $roleid);
        })->where('fields_master.Table_Name', $tablename)->select(
            'fields_master.Table_Name',
            'fields_master.Field_Name',
            'fields_master.fld_label',

            DB::raw("ISNULL(hide,'False') as hide"),
            DB::raw("ISNULL(rdol,'False') as rdol")
        )->orderBy('fields_master.fld_label', 'ASC')->get();

        $fields_det = FieldsMaster::leftjoin('tbl_fld_level', function ($join) use ($roleid) {

            $join->on('fields_master.Table_Name', '=', 'tbl_fld_level.txn_id');

            $join->on('fields_master.Field_Name', '=', 'tbl_fld_level.fld_id');

            $join->where('tbl_fld_level.uid', '=', $roleid);
        })->where('fields_master.Table_Name',  $tablenamedet)->select(
            'fields_master.Table_Name',
            'fields_master.Field_Name',
            'fields_master.fld_label',

            DB::raw("ISNULL(hide,'False') as hide"),
            DB::raw("ISNULL(rdol,'False') as rdol")
        )->orderBy('fields_master.fld_label', 'ASC')->get();


        return response()->json(['transactionfields' => $fields, 'transactionfields_det' => $fields_det]);
    }


    public function updateTransactionFields(Request $request)
    {

        if (empty($request->data)  ||  empty($request->role) || empty($request->tablename)) {
            return  response()->json(['message' => "invalid request"]);
        }

        $data = json_decode($request->data, true);

        $tablename = $request->tablename;

        $tablename_det =  $tablename . "_det";

        $role = $request->role;

        $hiddenfields = $data['hidden_fields'];
        $readonlyfields = $data['readonly_fields'];
        $hiddenfields_det = $data['hidden_fields_det'];
        $readonlyfields_det = $data['readonly_fields_det'];


        if (count($hiddenfields) > 0) {

            foreach ($hiddenfields as $field) {
                $fieldlevelexists = FieldLevel::where('uid', $role)->where('txn_id', $tablename)->where('fld_id', $field['field_id'])->exists();

                if ($fieldlevelexists == false) {
                    $newfieldlevel = new FieldLevel;
                    $newfieldlevel->uid = $role;
                    $newfieldlevel->txn_id = $tablename;
                    $newfieldlevel->fld_id = $field['field_id'];
                    $newfieldlevel->hide = ($field['hide'] == true ? 'True' : 'False');
                    $newfieldlevel->rdol = "False";
                    $newfieldlevel->save();
                }
                FieldLevel::where('uid', $role)->where('txn_id', $tablename)->where('fld_id', $field['field_id'])->update(['hide' => ($field['hide'] == true ? 'True' : 'False')]);
            }
        }

        if (count($hiddenfields_det) > 0) {

            foreach ($hiddenfields_det as $field) {

                $fieldlevelexists = FieldLevel::where('uid', $role)->where('txn_id', $tablename_det)->where('fld_id', $field['field_id'])->exists();

                if ($fieldlevelexists == false) {
                    $newfieldlevel = new FieldLevel;
                    $newfieldlevel->uid = $role;
                    $newfieldlevel->txn_id = $tablename_det;
                    $newfieldlevel->fld_id = $field['field_id'];
                    $newfieldlevel->hide = ($field['hide'] == true ? 'True' : 'False');
                    $newfieldlevel->rdol = "False";
                    $newfieldlevel->save();
                }
                FieldLevel::where('uid', $role)->where('txn_id', $tablename_det)->where('fld_id', $field['field_id'])->update(['hide' => ($field['hide'] == true ? 'True' : 'False')]);
            }
        }


        if (count($readonlyfields) > 0) {

            foreach ($readonlyfields as $field) {
                FieldLevel::where('uid', $role)->where('txn_id', $tablename)->where('fld_id', $field['field_id'])->update(['rdol' => ($field['readonly'] == true ? 'True' : 'False')]);
            }
        }


        if (count($readonlyfields_det) > 0) {

            foreach ($readonlyfields_det as $field) {
                FieldLevel::where('uid', $role)->where('txn_id', $tablename_det)->where('fld_id', $field['field_id'])->update(['rdol' => ($field['readonly'] == true ? 'True' : 'False')]);
            }
        }

        return response()->json(['status' => "success"]);
    }


    public function addInboxTabName(Request $request)
    {

        $tabname = $request->inbox_tab_name;

        $alreadyexists = InboxTab::where('tab_name',  $tabname)->exists();

        if ($alreadyexists) {

            return response()->json(['status' => "false", "message" => "Tab Name Already Exists"]);
        }


        $inboxtab = new InboxTab;

        $inboxtab->tab_name = $tabname;

        $inboxtab->save();

        return response()->json(['status' => "success", "message" => "Tab Name created successfully", 'tab' => $inboxtab]);
    }



    public function updateInboxTabName(Request $request)
    {
        $id = $request->id;
        $tabname = $request->tab_name;

        $alreadyexists = InboxTab::where('tab_name',  $tabname)->where('id', '<>', $id)->exists();

        if ($alreadyexists == true) {
            return response()->json(['status' => "false", "message" => "Tab Name already in use , Please enter another name"]);
        }

        $inboxtab = InboxTab::find($id);
        $inboxtab->tab_name = $tabname;
        $inboxtab->save();

        return response()->json(['status' => "success", "message" => "Tab Name updated successfully", 'tab' => $inboxtab]);
    }




    public function submitRoleInboxTabsHiding(Request $request)
    {

        $role = $request->role;

        if (empty($role)) {
            return response()->json(['status' => "fail", "message" => "Please select Role"]);
        }

        $rolename = Role::where('id', $role)->value('role_name');

        $inboxtabshide = $request->inboxtabs_hide;

        RoleInboxTabHiding::where('role_id', $role)->delete();

        foreach ($inboxtabshide as $inboxtab) {
            $roleinboxtab = new RoleInboxTabHiding;
            $roleinboxtab->role_id = $role;
            $roleinboxtab->inbox_tab_id = $inboxtab;
            $roleinboxtab->save();
        }

        return response()->json(['status' => "success", "message" => "Inbox Tabs are Hided successfully"]);
    }



    public function getRoleInboxTabsHiding($companyname, $roleid)
    {

        $inboxtabs=InboxTab::OrderBy('tab_name')->select('tab_name','id')->get();
        $inboxtabshided = RoleInboxTabHiding::where('role_id', $roleid)->pluck('inbox_tab_id');

        return response()->json([ 'inboxtabs'=>$inboxtabs,'inboxtabshided' => $inboxtabshided]);
    }

    public function addMasterName(Request $request)
    {

        $mastername = $request->master_name;

        $alreadyexists = Master::where('master_name', $mastername)->exists();

        if ($alreadyexists == true) {
            return response()->json(['status' => 'false', 'message' => 'Master Name already exists']);
        }

        $master = new Master;
        $master->master_name = $mastername;
        $master->save();

        return response()->json(['status' => 'success', 'message' => 'Master Name added successfully', 'master' => $master]);
    }

    public function updateMasterName(Request $request)
    {

        $id = $request->id;
        $mastername = $request->master_name;
        $alreadyexists = Master::where('master_name', $mastername)->where('id', '<>', $id)->exists();

        if ($alreadyexists == true) {
            return response()->json(['status' => 'failed', 'message' => 'Master Name already exists']);
        }

        $master = Master::where('id', $id)->first();
        $master->master_name = $mastername;
        $master->save();

        return response()->json(['status' => 'success', 'master' => $master, 'message' => 'Master Name Updated Successfully']);
    }


    public function submitRoleMasterRestrictions(Request $request)
    {

        $role = $request->role;
        $masterrestrictions = $request->master_restriction;

        if (empty($role)) {
            return response()->json(['status' => 'false', 'message' => 'Please select role']);
        }



        RoleMasterRestrictions::where('role_id', $role)->delete();

        foreach ($masterrestrictions as  $master) {

            $rolemasterrestrict = new RoleMasterRestrictions;
            $rolemasterrestrict->role_id = $role;
            $rolemasterrestrict->master_id = $master;
            $rolemasterrestrict->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Master Restrictions saved successfully']);
    }


    public function getRoleMasterRestrictions($companyname, $roleid)
    {
        $masters=Master::orderby('master_name','asc')->select('id','master_name')->get();
        $masterids = RoleMasterRestrictions::where('role_id', $roleid)->pluck('master_id');

        return response()->json([ 'masters'=>$masters,'masterrestrictions' => $masterids]);
    }


    public function submitMenuLevel(Request $request){


      $role=$request->role;

      if(empty($role) ||  empty($request->menus)){
          return response()->json(['status'=>'fails','message'=>'Invalid Request']);
      }


      $menulevelmenus=json_decode($request->menus,true);
        $hided=array();
        $shown=array();

      foreach(    $menulevelmenus as $menu){

            if( $menu['hide']==true){
                array_push($hided,$menu['menuid']);
            }
            else{
                array_push($shown,$menu['menuid']);
            }

      }

      RolesMenu::where('role_id',$role)->delete();

      if(count($hided)>0){

        foreach($hided as $hide){
            RolesMenu::create(['role_id'=>$role,'menu_name'=>$hide]);
        }

      }
      return response()->json(['status'=>'success','message'=>'Menus are saved successfully']);
    }

    public function getRoleMenuLevel($companyname,$roleid){

       $menunames= RolesMenu::where('role_id',$roleid)->pluck('menu_name');

        return response()->json(['menus'=> $menunames]);

    }

    public function saveAccountLevel(Request $request){

        $role=$request->role;

        if(empty($role) ){

         return response()->json(['status'=>"fail","message"=>"Please select Role"]);
        }

        $accounttypes=json_decode($request->accountypes);

        $insert=$request->insert;

        $edit=$request->edit;

        $delete=$request->delete;

        $view=$request->view;

        $print=$request->print;
        foreach( $accounttypes as  $account){

            $insertresult=(empty($insert)?'False':(in_array($account,$insert)?'True':'False') );

            $editresult=(empty($edit)?'False':(in_array($account,$edit)?'True':'False') );

            $deleteresult=(empty($delete)?'False':(in_array($account,$delete)?'True':'False') );

            $viewresult=(empty($view)?'False':(in_array($account,$view)?'True':'False') );

            $printresult=(empty($print)?'False':(in_array($account,$print)?'True':'False') );


            $accountrow=RolesAccount::where('RoleName',$role)->where('Tran_Name',$account)->first();

            if(empty($accountrow)){

                DB::table('roles_account')->insert(['RoleName'=>$role,'Tran_Name'=>$account,'Insert_Roles'=>$insertresult,'Edit_Roles'=>$editresult,'Delete_Roles'=>$deleteresult,'View_Roles'=>$viewresult,'Print_Roles'=>$printresult]);
            }
            else{
                DB::table('roles_account')->where('RoleName',$role)->where('Tran_Name',$account)->update(['Insert_Roles'=>$insertresult,'Edit_Roles'=>$editresult,'Delete_Roles'=>$deleteresult,'View_Roles'=>$viewresult,'Print_Roles'=>$printresult]);
            }
        }


        return response()->json(['status'=>"success","message"=>"Account Level saved successfully"]);

    }


    public function getAccountLevel($companyname,$roleid){

        $accountlevels=RolesAccount::where('RoleName',$roleid)->get();

        $insert=array();
        $edit=array();
        $delete=array();
        $view=array();
        $print=array();

        foreach($accountlevels as $accountlevel){

            if(trim($accountlevel->Insert_Roles)=="True"){
                array_push($insert,$accountlevel->Tran_Name);
            }

            if(trim($accountlevel->Edit_Roles)=="True"){
                array_push($edit,$accountlevel->Tran_Name);
            }


            if(trim($accountlevel->Delete_Roles)=="True"){
                array_push($delete,$accountlevel->Tran_Name);
            }

            if(trim($accountlevel->View_Roles)=="True"){
                array_push($view,$accountlevel->Tran_Name);
            }

            if(trim($accountlevel->Print_Roles)=="True"){
                array_push($print,$accountlevel->Tran_Name);
            }
         }

        return   response()->json(["insert"=>$insert,"edit"=>$edit,"delete"=>$delete,"view"=>$view,"print"=>$print]);
    }


    public function roleVouchers($companyname,$roleid){

        $vchtypes=VchType::whereIn('Name',function($query)use($roleid){

            $query->from('roles_account')->selectRaw('Tran_Name')->where('RoleName',$roleid)->where(function($query1){
                $query1->where('Insert_Roles','True')->orwhere('Edit_Roles','True')->orwhere('Delete_Roles','True')->orwhere('View_Roles','True')->orwhere('Print_Roles','True');
            });

        })->orderBy('Name','asc')->select('Id','Name')->get(); 

        return   response()->json(["vchtypes"=>$vchtypes]); 

    }
    
    public function createUpdateUser(Request $request){

        $validator = \Validator::make($request->all(), [
            'username' => 'required_if:formmode,add',
            'userpassword' => 'required_if:formmode,add', 
            'mobilenumber'=>'required_if:formmode,add',
            'useremail'=>'email|nullable' ,
            'userid'=>'required_if:formmode,edit'
        ]);

        if ($validator->fails())
        {
            return response()->json(['status'=>'false','message'=>$validator->errors()->all()]);
        }
         
        $username=$request->username;

        $formmode=$request->formmode;


        if($formmode=="add" ){
            
           $ispresent= User::where('user_id',$username)->exists();

           if($ispresent==true){
               
            return response()->json(['status'=>'false','message'=>"User is already present by UserName ".$username]);

           }

           $newUser=new User;
           $newUser->user_id=$username;

        }
        else{
            
           $newUser=User::where('id',$request->userid)->first();
        }

        $data=json_decode($request->data,true);

        if(count($data)==0){
            return response()->json(['status'=>'false','message'=>"Please select at Least 1 Company for User"]);

        }


        $userpassword=$request->userpassword;
        $useremail=$request->useremail;

        if(!empty($request->emailpassword)){
            $emailpassword= $request->emailpassword;
            
        }
        else{
            $emailpassword='';
        }
        
        $mobnumber=(!empty($request->mobilenumber))?$request->mobilenumber:'';

        $usernickname=(!empty($request->usernickname))?$request->usernickname:'';
        
        if(!empty($userpassword)){
            $newUser->password=Hash::make($userpassword); 
        }


        $newUser->email=$useremail;

        if(!empty( $emailpassword)){
           $newUser->email_password= $emailpassword;
        }
  

        $newUser->mob_num=$mobnumber;
        
        $newUser->Nickname=$usernickname;

        $newUser->save(); 

        $userid=$newUser->id;

        if($formmode=="edit"){
            UserCompany::where('uid',$userid)->delete();

        }

        $usercompanies=array();

        foreach($data as $usercomp){ 
            $cmpid=$usercomp['cmpid'];
            $userrole=(empty($usercomp['userrole'])?NULL:$usercomp['userrole']);
            $userhead=(empty($usercomp['userhead'])?NULL:$usercomp['userhead']); 

            array_push(    $usercompanies,array('uid'=>   $userid ,'compid'=>$cmpid,'roleid'=>  $userrole,'user_head_id'=> $userhead));

        }
        UserCompany::insert( $usercompanies); 

        return response()->json(['status'=>'success','message'=>'User '.($formmode=='add'?'created':'updated')." successfully"]);
  

    }

    public function getUserDetails(User $user){
        
        $usercompanies=$user->usercompanies()->select('compid','roleid','user_head_id')->get();

        return response()->json(['user'=>$user,'usercompanies'=>$usercompanies]);

    }


    public function getAllUsers(){

        $users=User::orderBy('user_id','asc')->pluck('user_id','id');

        return response()->json(['users'=>$users]);

    }


    public function getReportsFromUser($companyname,$role){

      $selected=  Report::whereIn('reportid',function($query) use($role){

        $query->from('roles_reports')->selectRaw('Rpt_id')->where('Role_id',$role);

      })->orderby('reportname','asc')->select('reportid','reportname')->get();


      $unselected= Report::whereNotIn('reportid',function($query) use($role){

        $query->from('roles_reports')->selectRaw('Rpt_id')->where('Role_id',$role);

      })->orderby('reportname','asc')->select('reportid','reportname')->get();

      return response()->json(['selected'=>$selected,'unselected'=>$unselected]);

      
    }

    public function saveRoleReports(Request $request){ 

        $roleid=$request->role_id;

        $reportsselected=json_decode($request->reports_selected );

        RoleReports::where('Role_id',$roleid)->delete();

        $rolereports=[]; 

        foreach(       $reportsselected as $report){
            array_push(   $rolereports,array( 'Role_id'=>$roleid,'Rpt_id'=>$report));  
        }

        $rolereportschunk=array_chunk( $rolereports,400);

        foreach(   $rolereportschunk as $rolereportsinglechunk){

            RoleReports::insert($rolereportsinglechunk);

        }
 
        return  response()->json(["status"=>"success" ,"message"=>"Role Reports saved successfully "]); 

    }

    public function getRoleModules($companyname,$role){


        $selected=TableModule::whereIn('id',function($query)use($role){
            $query->from('tbl_role_module')->selectRaw('module_id')->where('role_id',$role);
        })->orderby('mname','asc')->select('id','mname')->get();

        $unselected=TableModule::whereNotIn('id',function($query)use($role){
            $query->from('tbl_role_module')->selectRaw('module_id')->where('role_id',$role);
        })->orderby('mname','asc')->select('id','mname')->get();

        return response()->json(['selected'=>$selected,'unselected'=>$unselected]);


    }

    public function saveRoleModules(Request $request){
 
        $roleid=$request->role_id;

        $modulesselected=json_decode($request->modules_selected );

        TblRoleModule::where('role_id',$roleid)->delete();

        $rolemodules=[]; 

        foreach(          $modulesselected as $module){
            array_push($rolemodules,array( 'role_id'=>$roleid,'module_id'=>$module));  
        }

        $rolmoduleschunk=array_chunk( $rolemodules,400);

        foreach( $rolmoduleschunk as $rolemodulesinglechunk){

            TblRoleModule::insert($rolemodulesinglechunk);

        }
 
        return  response()->json(["status"=>"success" ,"message"=>"Role Modules saved successfully "]);  

    }


    public function getRoleStockRateRestrictions($companyname,$roleid){

 
       $user_stockrate=  TblRoleStockRateRestriction::where('role_id', $roleid)->first();

       if(empty( $user_stockrate)){

        return response()->json(['rate'=>false,'spec_rate'=>false,'show_amount'=>false]);

       }
       else{

        $rate=($user_stockrate->rate==true?true:false);

        $spec_rate=($user_stockrate->spec_rate==true?true:false);

        $show_amount=($user_stockrate->show_amount==true?true:false);
        
        return response()->json(['rate'=>      $rate,'spec_rate'=>     $spec_rate,'show_amount'=>$show_amount]);

       }
 
    }


    public function saveStockRateRestriction($companyname,Request $request){
 
        extract($request->all());

        $rate=($rate=='true'?1:0);
        $spec_rate=($spec_rate=='true'?1:0);


        $show_amount=($show_amount=='true'?1:0);
 

        $record_exists=  TblRoleStockRateRestriction::where('role_id',$role_id)->exists();

        if($record_exists==true){

            TblRoleStockRateRestriction::where('role_id',$role_id)->update(['rate'=>    $rate,'spec_rate'=>$spec_rate,'show_amount'=>    $show_amount]);

        }
        else{

            TblRoleStockRateRestriction::create(['role_id'=>$role_id ,'rate'=>    $rate,'spec_rate'=>$spec_rate,'show_amount'=>    $show_amount]);

        }
 
     
        return response()->json(['status'=>'success' ,'rate'=>      $rate,'spec_rate'=>     $spec_rate,'show_amount'=>    $show_amount ,'message'=>"Stock Rate Restriction updated successfully"]);
 

    }

}
