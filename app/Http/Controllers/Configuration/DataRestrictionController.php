<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use App\Models\User; 
use App\Helper\Helper;
use DB;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use App\Models\UserLocation;
use App\Models\ProductMaster;
use App\Models\UserProducts;
use Illuminate\Support\Collection;
use App\Models\UserCustomer;
use App\Models\Customer;
use App\Models\Salesmen;
use App\Models\UserSalesmen;
use App\Models\EmployeeMaster;
use App\Models\UserEmployee;
use App\Models\UserStatus;
use App\Models\StatusTable;
use App\Models\UserRestrictionTranxDay;
use App\Models\UserRestrictionVchDays;
use App\Models\Role;
use App\Models\RoleMonthLock;
use App\Models\TblPartyType;
use App\Models\TblRestrictCustomer;
use App\Models\Division;
use App\Models\Costcentre;
use App\Models\ProfitCentre;

class DataRestrictionController extends Controller
{
    

    public function index()
    {

        $users = User::OrderBy('user_id','ASC')->get();


       
        return view('configuration.data_restriction', compact('users'));
    }


    public function getUserLocations($companyname,$userid){

         $unselected=  Location::whereNotIn('id',function($query)use($userid){
         $query->from('tbl_user_loc')->selectRaw('Loc')->where('uid',$userid);})->OrderBy('location','ASC')->select('location','id')->get(); 

         $selected=  Location::whereIn('id',function($query)use($userid){
         $query->from('tbl_user_loc')->selectRaw('Loc')->where('uid',$userid);})->OrderBy('location','ASC')->select('location','id')->get();
 
        return response()->json(['unselected'=>$unselected,'selected'=>$selected]);
 

    }


    public function saveUserLocations($companyname,Request $request){

        $userid=$request->user_id;
        
        $selectedlocations=json_decode($request->location_selected); 

        UserLocation::where('uid',$userid)->delete();

        $newlocations=array();

        foreach($selectedlocations as $location){
          array_push($newlocations,array('uid'=>$userid,'Loc'=>$location));
        }

       $inserted= UserLocation::insert($newlocations); 
        return response()->json(['status'=>'success','message'=>"User Locations saved successfully"]);

    }

    public function getUserProducts($companyname,$userid){


        $selected=ProductMaster::whereIn('Id',function($query)use($userid){   $query->from('tbl_user_prod')->selectRaw('prd_grp')->where('uid',$userid);
         })->select('Id','Product')->get();


         $unselected=ProductMaster::whereNotIn('Id',function($query)use($userid){   $query->from('tbl_user_prod')->selectRaw('prd_grp')->where('uid',$userid);
         })->select('Id','Product')->get();

         return  response()->json(["selected"=>$selected,"unselected"=>$unselected]);


    }


    public function saveUserProducts(Request $request){

        $userid=$request->user_id;

        $products_selected=json_decode($request->products_selected); 

        UserProducts::where('uid',$userid)->delete();

        $newprds=[];
        
        foreach( $products_selected as  $product){
            array_push($newprds,array( 'uid'=>$userid,'prd_grp'=>$product)); 
        } 
 
         $prdchunks=array_chunk( $newprds,400);


         foreach($prdchunks as $prchunk){

            UserProducts::insert($prchunk);

         }
 

        return  response()->json(["status"=>"success",'message'=>'User Products saved successfully']);


    }


    public function getUserCustomers($companyname,$userid){ 

       $selected=Customer::whereIn('Id',function($query) use($userid){
        $query->from('tbl_user_cst')->selectRaw('cst')->where('uid',$userid);  
       })->orderBy('cust_id','ASC')->select('Id','cust_id')->get();

       $unselected=Customer::whereNotIn('Id',function($query) use($userid){
        $query->from('tbl_user_cst')->selectRaw('cst')->where('uid',$userid);  
       })->orderBy('cust_id','ASC')->select('Id','cust_id')->get();

       return  response()->json(["selected"=>  $selected ,"unselected"=>  $unselected]); 
    }


    public function saveUserCustomers(Request $request){

        $userid=$request->user_id;

        $customerselected=json_decode($request->customers_selected);

        UserCustomer::where('uid',$userid)->delete();

        $usercustomers=[];

        foreach( $customerselected as $customer){
            array_push($usercustomers,array('uid'=>$userid,'cst'=>$customer)); 
        }

        $customerchunk=array_chunk($usercustomers,400);

        foreach(   $customerchunk as $custchunk){

            UserCustomer::insert($custchunk);

        }
 
        return  response()->json(["status"=>"success" ,"message"=>"User Customers saved successfully "]); 

    }


    public function getUserSalesman($companyname,$userid){

        $selected=Salesmen::whereIn('Id',function($query) use($userid){      $query->from('tbl_user_sexe')->selectRaw('s_exe')->where('uid',$userid); })->orderBy('Name','asc')->select('Id','Name')->get();

        $unselected=Salesmen::whereNotIn('Id',function($query) use($userid){      $query->from('tbl_user_sexe')->selectRaw('s_exe')->where('uid',$userid); })->orderBy('Name','asc')->select('Id','Name')->get();
 
        return response()->json(["selected"=>$selected,"unselected"=>$unselected]); 

    }


    public function saveUserSalesman(Request $request){
 
        $userid=$request->user_id;

        $salemmanselected=json_decode($request->salesmen_selected );

        UserSalesmen::where('uid',$userid)->delete();

        $usersalesmen=[];

        $maxid=UserSalesmen::max('id');
 

        $nextmaxid=$maxid+1;

        foreach( $salemmanselected as $salesman){
            array_push(    $usersalesmen,array( 'id'=>$nextmaxid,'uid'=>$userid,'s_exe'=>$salesman)); 
            $nextmaxid++;
        }

        $salesmanchunk=array_chunk( $usersalesmen,400);

        foreach(    $salesmanchunk as $saleschunk){

            UserSalesmen::insert($saleschunk);

        }
 
        return  response()->json(["status"=>"success" ,"message"=>"User Salesman saved successfully "]); 

    }

        public function getUserEmployees($companyname,$userid){
 
            $selected=EmployeeMaster::whereIn('ID',function($query)use($userid){    $query->from('tbl_user_emp')->selectRaw('empid')->where('uid',$userid); })->orderBy('EmployeeName','asc')->select('ID','EmployeeName')->get();

            $unselected=EmployeeMaster::whereNotIn('ID',function($query)use($userid){    $query->from('tbl_user_emp')->selectRaw('empid')->where('uid',$userid); })->orderBy('EmployeeName','asc')->select('ID','EmployeeName')->get();

            return  response()->json(["selected"=>$selected,"unselected"=>$unselected]); 

        }



        public function saveUserEmployees(Request $request){

            $userid=$request->user_id;

            $employeesselected=json_decode($request->employees_selected );
    
            UserEmployee::where('uid',$userid)->delete();
    
            $useremployees=[];
     
    
            foreach(  $employeesselected as $employee){
                array_push( $useremployees,array( 'uid'=>$userid,'empid'=>$employee));  
            }
    
            $employeeschunk=array_chunk( $useremployees,400);
    
            foreach(     $employeeschunk as $employeesinglechunk){
    
                UserEmployee::insert($employeesinglechunk);
    
            }
     
            return  response()->json(["status"=>"success" ,"message"=>"User Employees saved successfully "]); 


        }

        public function getStatusFromUserAndTablename(Request $request){
            $userid=$request->userid;
            $tablename=$request->tablename;

            $selected=StatusTable::whereIn('id',function($query)use($userid,$tablename){     $query->from('tbl_user_status')->selectRaw('sts')->where('uid',$userid)->where('table_name',$tablename); })->select('id','StatusName')->get();
           
            $unselected=StatusTable::whereNotIn('id',function($query)use($userid,$tablename){     $query->from('tbl_user_status')->selectRaw('sts')->where('uid',$userid)->where('table_name',$tablename); })->select('id','StatusName')->get();
 
            return  response()->json(["unselected"=>$unselected,"selected"=>$selected]); 

        }


        public function saveStatusFromUserAndTablename(Request $request){ 
        

            $status_selected=json_decode($request->editstatus_selected);

            $userid=$request->user_id;

            $tablename=$request->table_name;

            UserStatus::where('uid',$userid)->where('table_name',$tablename)->delete();

            $selected_status=array();

            foreach($status_selected as $status){
                array_push(  $selected_status ,array('uid'=>$userid,'table_name'=>$tablename,'sts'=>$status)); 
            }

            $status_chunk=array_chunk( $selected_status,400);

            foreach( $status_chunk as $stauschunk){
                UserStatus::insert($stauschunk);

            }  
            return  response()->json(["status"=>"success","message"=>"User Status saved successfully"]); 

        }


        public function getRestrictTranxDaysFromUserAndTablename(Request $request){
            $userid=$request->userid;
            $tableid=$request->tableid;
 

            $days=array("add_days"=>'','edit_days'=>'','delete_days'=>'');

            $daysdetails=UserRestrictionTranxDay::where('user_id',$userid)->where('tranx_id',  $tableid)->first();

            if(  !empty( $daysdetails)){
                $days['add_days']=$daysdetails->add_days;
                $days['edit_days']=$daysdetails->edit_days;
                $days['delete_days']=$daysdetails->delete_days;

            }

            return response()->json(["days"=>$days]); 

        }

        public function saveRestrictTranxDaysFromUserAndTablename(Request $request){

            $userid=$request->user_id;
            $tableid=$request->table_id;
            $addafter=$request->add_after_days;
            $deleteafter=$request->delete_after_days;
            $editafter=$request->edit_after_days;

            $tranxday=UserRestrictionTranxDay::where('user_id', $userid)->where('tranx_id',  $tableid)->first();

            if(empty( $tranxday)){
                $tranxday=new UserRestrictionTranxDay;
                $tranxday->user_id=$userid;
                $tranxday->tranx_id= $tableid; 
            }

            $tranxday->add_days=(empty($addafter) || $addafter<0)?0: $addafter;
            
            $tranxday->delete_days=  (empty($deleteafter ) || $deleteafter<0)?0:$deleteafter; 

            $tranxday->edit_days=     (empty($editafter) || $editafter<0)?0:$editafter;
            $tranxday->save();

            return response()->json(["status"=>"success","message"=>"Restriction Transaction Days saved successfully"]); 


        }


        public function getRestrictVoucherDays(Request $request){

            $userid=$request->userid;

            $vchid=$request->vchid;

           $vchdays= UserRestrictionVchDays::where(['user_id'=>$userid,'vch_id'=>$vchid])->first();

           $vcharray=array('add_days'=>'' ,'edit_days'=>'','delete_days'=>'');

           if(!empty($vchdays)){
            $vcharray['add_days']=$vchdays->add_days;
            $vcharray['edit_days']=$vchdays->edit_days;
            $vcharray['delete_days']=$vchdays->delete_days;
           }

           return response()->json(['vch_days'=>$vcharray]); 
        }


        public function saveRestrictVoucherDays(Request $request){
 
            $userid=$request->user_id;
            $vchid=$request->vch_id;
            $addafter=$request->add_after_days;
            $deleteafter=$request->delete_after_days;
            $editafter=$request->edit_after_days;

            $vchday=UserRestrictionVchDays::where('user_id', $userid)->where('vch_id',  $vchid)->first();

            if(empty(   $vchday)){
                $vchday=new UserRestrictionVchDays;
                $vchday->user_id=$userid;
                $vchday->vch_id= $vchid; 
            }

            $vchday->add_days=(empty($addafter) || $addafter<0)?0: $addafter;
            
            $vchday->delete_days=(empty($deleteafter ) || $deleteafter<0)?0:$deleteafter; 

            $vchday->edit_days=(empty($editafter) || $editafter<0)?0:$editafter;
            $vchday->save();

            return response()->json(["status"=>"success","message"=>"Restriction Voucher Days saved successfully"]);  

        }

        public function getRestrictRolesByMonth($companyname,$month){
  
            $selected=Role::whereIn('id',function($query)use( $month){  $query->from('tbl_role_month_lock')->selectRaw('role_id')->where('month',$month); })->select('id','role_name')->orderby('role_name','asc')->get();;

            $unselected=Role::whereNotIn('id',function($query)use( $month){  $query->from('tbl_role_month_lock')->selectRaw('role_id')->where('month',$month); })->select('id','role_name')->orderby('role_name','asc')->get();;

            return response()->json(["selected"=>$selected,"unselected"=>$unselected]);

        }


        public function saveRestrictRolesByMonth(Request $request){


            $selected=json_decode($request->monthlocking_selected);

            $month=$request->month;
            
            RoleMonthLock::where('month',$month)->delete();

            $blockedroles=array();  

            $year=date("Y"); 
            $fromdate=date("Ymd 00:00:00",strtotime($year.'-'.$month.'-01'));
            $noofdays=cal_days_in_month(CAL_GREGORIAN,$month,$year);
           $todate=date("Ymd 23:59:59",strtotime($year.'-'.$month.'-'. $noofdays));
 

            foreach($selected as $select){
                array_push($blockedroles,array('role_id'=>$select , 'month'=>$month , 'from_date'=>$fromdate ,'to_date'=>$todate));
            } 
            RoleMonthLock::insert($blockedroles); 

            return response()->json(['status'=>'success','message'=>'Month wise Roles Restricted successfully']);
  

        }


        public function getRestrictCustomersFromUser($companyname,User $user){
            $userid=$user->id;

            $selected=TblPartyType::whereIn('Id',function($query)use($userid){   $query->from('tbl_restrict_customers')->selectRaw('party_type_id')->where('uid',$userid);  })->orderby('Ptype','asc')->select('Id','Ptype')->get();


            $unselected=TblPartyType::whereNotIn('Id',function($query)use($userid){   $query->from('tbl_restrict_customers')->selectRaw('party_type_id')->where('uid',$userid);  })->orderby('Ptype','asc')->select('Id','Ptype')->get();
 
            return response()->json(['selected'=>$selected,'unselected'=>$unselected]);

        }

        public function saveRestrictCustomersForUser(Request $request){
  
            $userid=$request->user_id;

            $customersselected=json_decode($request->restrictcustomers_selected,true );
    
            TblRestrictCustomer::where('uid',$userid)->delete();
    
            $usercustomers=array(); 
 
            foreach(  $customersselected as $customer){

                array_push(   $usercustomers,array( 'uid'=>$userid,'party_type_id'=>$customer));  
            }
       
    
            $customerschunk=array_chunk(  $usercustomers,400);
    
            foreach( $customerschunk as $customersinglechunk){
    
                TblRestrictCustomer::insert($customersinglechunk);
    
            }
     
            return  response()->json(["status"=>"success" ,"message"=>"User Restricted Customers saved successfully "]); 

        } 


        public function getUserDivisions($companyname,$userid){


            $selected=Division::whereIn('Id',function($query)use($userid){ $query->from('tbl_user_div')->selectRaw('div')->where('uid',$userid); })->orderby('division','asc')->select('Id','division')->get();
            
            $unselected=Division::whereNotIn('Id',function($query)use($userid){ $query->from('tbl_user_div')->selectRaw('div')->where('uid',$userid); })->orderby('division','asc')->select('Id','division')->get();
            
          
            return response()->json(['selected'=>$selected,'unselected'=>$unselected]);


        }


        public function saveUserDivisions(Request $request){

            $user=$request->user;

            $divisionselected=json_decode($request->division_selected,true);

            DB::table('tbl_user_div')->where('uid',$user)->delete();
 
            foreach(  $divisionselected as   $division){
                DB::table('tbl_user_div')->insert(['uid'=>$user,'div'=>$division]);

            }
 
            return  response()->json(["status"=>"success" ,"message"=>"User Division Saved Successfully"]); 
 
        }


        public function getUserCostCenters($companyname,$userid){


            $selected=Costcentre::whereIn('Id',function($query)use($userid){ $query->from('tbl_user_cc')->selectRaw('cc')->where('uid',$userid);  })->orderby('Name','asc')->select('Id','Name')->get();

            $unselected=Costcentre::whereNotIn('Id',function($query)use($userid){ $query->from('tbl_user_cc')->selectRaw('cc')->where('uid',$userid);  })->orderby('Name','asc')->select('Id','Name')->get();
 
 
            return response()->json(['selected'=>$selected,'unselected'=>$unselected]);



        }


        public function saveUserCostCenters(Request $request){

            $costcenter_selected=json_decode($request->costcenter_selected,true);

            $user=$request->user;

            DB::table("tbl_user_cc")->where('uid',$user)->delete();

            foreach( $costcenter_selected as  $costcenter){
                DB::table("tbl_user_cc")->insert(['uid'=>$user,'cc'=>$costcenter]);

            }
            
            return  response()->json(["status"=>"success" ,"message"=>"User Cost Centre Saved Successfully"]); 

        }



        public function getUserProfitCenters($companyname,$userid){

            $selected=ProfitCentre::whereIn('Id',function($query)use($userid){ $query->from('tbl_user_pc')->selectRaw('pc')->where('uid',$userid);  })->orderby('Name','asc')->select('Id','Name')->get();

            $unselected=ProfitCentre::whereNotIn('Id',function($query)use($userid){ $query->from('tbl_user_pc')->selectRaw('pc')->where('uid',$userid);  })->orderby('Name','asc')->select('Id','Name')->get();
 

            return response()->json(['selected'=>$selected,"unselected"=>  $unselected]);
  
        }


        public function saveUserProfitCenters(Request $request){

            $user=$request->user; 
            $profitcenters= json_decode($request->profitcenter_selected,true);

            DB::table('tbl_user_pc')->where('uid',  $user)->delete();
 
            foreach( $profitcenters as  $profitcenter){

                DB::table('tbl_user_pc')->insert(['uid'=> $user,'pc'=>$profitcenter]);
            }

            return response()->json(['status'=>true,'message'=>'User Profit Centres saved successfully']);

        }
   
}
