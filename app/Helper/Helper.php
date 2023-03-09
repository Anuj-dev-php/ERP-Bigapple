<?php

namespace App\Helper;

use Auth;
use App\Repositories\CompanyRepository;
use App\Models\Company;
use Illuminate\Support\Facades\Config;
use App\Models\UserCompany;
use DB;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function getAuthDatabase($request)
    {
        Session::put('company_name', $request->company_name);
        return $request->company_name ?? "BLPL21";
    }

    public static function setAuthDatabase($request)
    {
        $companyfromurl= $request->company_name;
        
        $currentcompany=Session::get('company_name'); 

        if(empty($companyfromurl)){
            Session::forget('company_name'); 
            Config::set('database.default','default'); 
            return \DB::reconnect('default');
        } 

        Session::put('company_name',$companyfromurl); 

  

       $company_detail= Company::where('db_name',$companyfromurl)->select('comp_name','fs_date','fe_date')->first();

       Session::put('comp_name', $company_detail->comp_name);

       Session::put('fs_date',$company_detail->fs_date);
       
       Session::put('fe_date',$company_detail->fe_date);

        $user=Auth::user(); 

        $roleid=Company::join('tbl_user_comp','tbl_company.id','=','tbl_user_comp.compid')->where('tbl_user_comp.uid',$user->id)->where('tbl_company.db_name',$companyfromurl)->select('tbl_user_comp.roleid')->value('roleid');
  
        Session::put('role_id',  $roleid); 

        Config::set('database.connections.sqlsrv.database', $companyfromurl);
        Config::set('database.connections.sqlsrv.username' );
        Config::set('database.connections.sqlsrv.password' );
        Config::set('database.default','sqlsrv');
        return \DB::reconnect('sqlsrv');
 
     
    }

    public static function setCompanyDatabase($request)
    {
        Config::set('database.connections.sqlsrv.database', $request->db_name);
        Config::set('database.connections.sqlsrv.username' );
        Config::set('database.connections.sqlsrv.password' );
        return \DB::reconnect('sqlsrv');
    }


    public static function getUserCompRole()
    {
        $companyId = Company::where('db_name', Session::get('company_name'))->value('id');
        $userComp = UserCompany::where('uid', \Auth::user()->id)->where('compid', $companyId)
            ->value('roleid');
        return $userComp;
    }


    public static function getCheckedData($input, $tndId)
    {
        if (!empty($input)) {
            $inputArray = explode("_", $input);
            if (!empty($inputArray)) {
                if ($inputArray[0] == $tndId) {
                    return $inputArray[1];
                }
            }
        }

        return "no";
    }

    public static function connectDatabaseByName($dbname)
    {
        Config::set('database.connections.sqlsrv.database', $dbname);
        Config::set('database.connections.sqlsrv.username' );
        Config::set('database.connections.sqlsrv.password' );
        Config::set('database.default','sqlsrv');
        return \DB::reconnect('sqlsrv');
    }


    public static function checkDatabaseExists($dbname)
    {

        $query = "select name from sys.databases WHERE name =?;";
        $db = DB::select($query, [$dbname]);

        if (empty($db)) {
            return false;
        } else {
            return true;
        }
    }



    public static function resetDefaultConnection(){
        Config::set('database.default','default');
        return \DB::reconnect('default');
    }

    public static function uploadFileAtFolder($file, $folder,$newfilename,$allowedextensions=array(),$filesize=0){
        
        $fileextension=$file->extension();
 

        if( count($allowedextensions)>0 && !in_array($fileextension,$allowedextensions)){
            return false;
        }
 
        $newfilename=$newfilename.".".$fileextension;
      
 
        $file->storeAs(
            'transactiondocs', $newfilename,'public'
        );
            
 
        return  $newfilename;
 
    }
}
