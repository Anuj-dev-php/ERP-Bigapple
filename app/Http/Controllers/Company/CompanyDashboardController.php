<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\TblAt;
use DB;
use Session;
use App\Models\RolesMap;
use App\Models\TableMaster;
use App\Models\TblMy;
use App\Models\Reports1;
use App\Models\TblMyRpt;
use Illuminate\Support\Facades\Log;
use App\Models\TblCompanyNews;
use App\Http\Controllers\Services\ReportService;


class CompanyDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
        $user=Auth::user();
        
        TblAt::insert(['Txn'=>'Login','opr'=>'Login','uid'=>$user->id,'stime'=>date('m/d/Y h:i:s A',strtotime('now'))]); 

        $report_service=new ReportService;

        $report_service->clearAllReportsCacheInputs();
  
        $roleid=Session::get('role_id');


        if($user->id!=1){
            $userwhere="user_id=".$user->id." and ";
        }
        else{
            $userwhere=""; 
        }
 
        $userid= $user->id; 

        if($userid==1){
            $is_admin=true;
        }
        else{
            $is_admin=false;
        }
 
        $lastdatas= DB::select("select top 10  docno as 'Doc No',dbo.tbl_audit_data.table_name as Table_Name  ,cast(docdate as date) As 'Doc Date', dbo.customers.cust_id as Name,dbo.salesmen.Name as Salesman
        from dbo.tbl_audit_data
        inner join dbo.customers on dbo.tbl_audit_data.cust_id=dbo.customers.id
        inner join dbo.salesmen on dbo.tbl_audit_data.salesman=dbo.SalesMen.id
        where   $userwhere docno not in (select docno from dbo.tbl_audit_data where operation like 'delete%')
        group by docno ,dbo.tbl_audit_data.docdate,dbo.customers.cust_id,dbo.salesmen.name,dbo.tbl_audit_data.table_name
        order by 'Doc Date' desc");

        $tablenames=array_unique(array_column( $lastdatas,'Table_Name'));
 
        $tablename_ids= TableMaster::whereIn('Table_Name', $tablenames)->pluck('Id','Table_Name');

            $roleid=Session::get('role_id');

            $companyname=Session::get("company_name");

            foreach(   $lastdatas as    $lastdata){

                $foundtableid=$tablename_ids[$lastdata->Table_Name];
                $rolemap=RolesMap::getRoleTransactionActions(  $roleid,$foundtableid);

               $founddataid= DB::table($lastdata->Table_Name)->where('docno',$lastdata->{'Doc No'})->value('Id');

                    if( !empty($founddataid) && ($rolemap['edit']==true || $rolemap['view']==true) ){
                            $lastdata->editurl=url('/')."/".$companyname."/edit-transaction-table-single-data/".$lastdata->Table_Name."/". $foundtableid."/".$founddataid;
                    }
                    else{
                            $lastdata->editurl="";

                    }
                
            } 

            $roleid=Session::get('role_id');

            $txnshortcut_ids= TblMy::where('role_id',  $roleid)->pluck('txn_id')->toArray();

            $txnreport_ids=TblMyRpt::where('role_id',  $roleid)->pluck('report_id')->toArray();

            $tablelinks=TableMaster::whereIn('Id',$txnshortcut_ids)->select('Id','table_label as tablelabel','Table_Name as tablename')->get();

            $reportlinks= Reports1::whereIn('reportid',  $txnreport_ids)->select('reportid as Id','reportname')->get();
            

             $companynews= TblCompanyNews::orderby('date','desc')->limit(3)->get();


            $show_ageing_receivables=in_array($roleid,array(1,2,11,12,27));
          
            return view('company.',compact('lastdatas','tablelinks','reportlinks','companyname','companynews','is_admin' ,'show_ageing_receivables' ));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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


    public function getDashboardChartsData($companyname){

        $user=Auth::user(); 
        $userid= $user->id;

 
        $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

        $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

 
// get the value for individual sales

         
                 return response()->json([    ]);

    }

    
    public function getExpenseChartsData(){
                    $user=Auth::user(); 
                    $userid= $user->id;

            
                    $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

                    $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

                    
                    $expenses_data_ytd=DB::select("select dbo.accounts.acname, CASE WHEN sum(amount)>0 THEN sum(amount) ELSE 0 END as amount
                    from dbo.vchdet 
                    inner join dbo.accounts on dbo.vchdet.acid=dbo.accounts.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_1 LEFT OUTER JOIN
                                        dbo.accounts AS accounts_3 LEFT OUTER JOIN
                                        dbo.accounts AS accounts_4 ON accounts_3.Parent like accounts_4.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_5 ON accounts_4.Parent like accounts_5.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_6 ON accounts_5.Parent like accounts_6.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_7 ON accounts_6.Parent like accounts_7.Id RIGHT OUTER JOIN
                                        dbo.accounts AS accounts_2 ON accounts_3.Id like accounts_2.Parent ON accounts_1.Parent like accounts_2.Id ON dbo.accounts.Parent like accounts_1.Id					  
                                        where accounts_1.acname like '%expense%' or accounts_2.acname like '%expense%' or accounts_3.acname like '%expense%' or accounts_4.acname like '%expense%' or accounts.acname like '%expense%' or accounts_5.acname like '%expense%' or accounts_6.acname like '%expense%' or accounts_7.acname like '%expense%'
                    group by dbo.accounts.acname 
                    order by amount desc");

                    
                $expenses_data_ytd=json_decode(json_encode( $expenses_data_ytd),true);

                
                $expenses_data_ytd_names=array_column($expenses_data_ytd,'acname');
                $expenses_data_ytd_values=array_column($expenses_data_ytd,'amount');

                $expenses_data_ytd_sum=array_sum( $expenses_data_ytd_values);

                
                $expenses_data_values_ytd= array();

                foreach( $expenses_data_ytd_values as  $single_value){

                $calc_val=(float) sprintf('%0.1f',($single_value/$expenses_data_ytd_sum *100));

                    array_push( $expenses_data_values_ytd,    $calc_val);

                }





                $expenses_data_qtd=DB::select("select dbo.accounts.acname,CASE WHEN sum(dbo.vchdet.amount)>0 THEN sum(dbo.vchdet.amount) ELSE 0 END as amount
                from dbo.vchdet 
                inner join dbo.accounts on dbo.vchdet.acid=dbo.accounts.Id 
                inner join dbo.vchmain on dbo.vchdet.MainId=dbo.vchmain.id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_1 LEFT OUTER JOIN
                                        dbo.accounts AS accounts_3 LEFT OUTER JOIN
                                        dbo.accounts AS accounts_4 ON accounts_3.Parent like accounts_4.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_5 ON accounts_4.Parent like accounts_5.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_6 ON accounts_5.Parent like accounts_6.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_7 ON accounts_6.Parent like accounts_7.Id RIGHT OUTER JOIN
                                        dbo.accounts AS accounts_2 ON accounts_3.Id like accounts_2.Parent ON accounts_1.Parent like accounts_2.Id ON dbo.accounts.Parent like accounts_1.Id					  
                                        where accounts_1.acname like '%expense%' or accounts_2.acname like '%expense%' or accounts_3.acname like '%expense%' or accounts_4.acname like '%expense%' or accounts.acname like '%expense%' or accounts_5.acname like '%expense%' or accounts_6.acname like '%expense%' or accounts_7.acname like '%expense%'
                                        and datediff(qq, dbo.vchmain.VchDate, getdate()) = 0 
                group by dbo.accounts.acname 
                order by amount desc");

                
                $expenses_data_qtd=json_decode(json_encode( $expenses_data_qtd),true);


                $expenses_data_qtd_names=array_column($expenses_data_qtd,'acname');
                $expenses_data_qtd_values=array_column($expenses_data_qtd,'amount');

                $expenses_data_qtd_sum=array_sum( $expenses_data_qtd_values);

                
                $expenses_data_values_qtd= array();

                foreach( $expenses_data_qtd_values as  $single_value){

                $calc_val=(float) sprintf('%0.1f',($single_value/$expenses_data_qtd_sum *100));

                    array_push( $expenses_data_values_qtd,    $calc_val);

                }



            

                
                $expenses_data_mtd=DB::select("select dbo.accounts.acname,CASE WHEN sum(dbo.vchdet.amount)>0 THEN sum(dbo.vchdet.amount) ELSE 0 END as amount
                from dbo.vchdet 
                inner join dbo.accounts on dbo.vchdet.acid=dbo.accounts.Id 
                inner join dbo.vchmain on dbo.vchdet.MainId=dbo.vchmain.id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_1 LEFT OUTER JOIN
                                        dbo.accounts AS accounts_3 LEFT OUTER JOIN
                                        dbo.accounts AS accounts_4 ON accounts_3.Parent like accounts_4.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_5 ON accounts_4.Parent like accounts_5.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_6 ON accounts_5.Parent like accounts_6.Id LEFT OUTER JOIN
                                        dbo.accounts AS accounts_7 ON accounts_6.Parent like accounts_7.Id RIGHT OUTER JOIN
                                        dbo.accounts AS accounts_2 ON accounts_3.Id like accounts_2.Parent ON accounts_1.Parent like accounts_2.Id ON dbo.accounts.Parent like accounts_1.Id					  
                                        where accounts_1.acname like '%expense%' or accounts_2.acname like '%expense%' or accounts_3.acname like '%expense%' or accounts_4.acname like '%expense%' or accounts.acname like '%expense%' or accounts_5.acname like '%expense%' or accounts_6.acname like '%expense%' or accounts_7.acname like '%expense%'
                                        and datediff(qq, dbo.vchmain.VchDate, getdate()) = 0 
                group by dbo.accounts.acname 
                order by amount desc  ");



                
                $expenses_data_mtd=json_decode(json_encode( $expenses_data_mtd),true);

                $expenses_data_mtd_names=array_column($expenses_data_mtd,'acname');


                $expenses_data_mtd_values=array_column($expenses_data_mtd,'amount');


                $expenses_data_mtd_sum=array_sum($expenses_data_mtd_values);


                $expenses_data_values_mtd= array();

                foreach( $expenses_data_mtd_values as  $single_value){

                $calc_val=(float) sprintf('%0.1f',($single_value/$expenses_data_mtd_sum *100));

                    array_push( $expenses_data_values_mtd,    $calc_val);

                }


                $expense_charts=array('mtd'=>array('names'=>$expenses_data_mtd_names,'values'=>  $expenses_data_values_mtd),'qtd'=>array('names'=> $expenses_data_qtd_names,'values'=> $expenses_data_values_qtd),'ytd'=>array('names'=>$expenses_data_ytd_names,'values'=> $expenses_data_values_ytd));

                return response()->json(['data'=>  $expense_charts]);


    }


    public function getIndividualSalesChartsData(){

        $user=Auth::user(); 
        $userid= $user->id;
         
        $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

        $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

        if($firstcondition==false && $secondcondition==false){
            $individualsales_data=DB::select("select sum(a1.mtd) as MTD, sum(a1.QTD) as QTD, sum(a1.ytd) as YTD, (sum(a1.mtd)/sum(a1.monthlytarget))*100 as 'MTD%', (sum(a1.QTD)/sum(a1.quarterlytarget))*100 as 'QTD%', (sum(a1.ytd)/sum(a1.yearlytarget))*100  as 'YTD%'
            from (select sm.id, sum(sm.target) as MonthlyTarget, sum(sm.target)*3 as QuarterlyTarget, sum(sm.target)*12 as YearlyTarget, abc.mtd as MTD, abc.qtd as QTD, abc.ytd as YTD
            from dbo.salesmen sm
            left join (Select x.id, Sum(m.amount) MTD, Sum(z.amount) QTD, Sum(y.amount) YTD
            From dbo.sales t inner join dbo.salesmen x on t.salesman=x.id 
                                       full join dbo.sales y
                                       on y.docno = t.docno
                                       left join dbo.sales m
                                       on m.docno = t.docno
                                          and datediff(month, m.docdate, getdate()) = 0 
                                          and m.docdate <= t.docdate  
                                         left join dbo.sales z
                                          on z.docno = t.docno
                                          and datediff(qq, z.docdate, getdate()) = 0 
                                          and z.docdate <= t.docdate where x.enabled='yes'
                                          group by x.id) abc on abc.id=sm.id
            where sm.enabled='yes'
            group by sm.id, abc.mtd, abc.qtd, abc.ytd) a1                     
            ");
        }
        else if($firstcondition==true && $seconcondition==true){
            
           $individualsales_data=DB::select("select sum(a1.mtd) as MTD, sum(a1.QTD) as QTD, sum(a1.ytd) as YTD, (sum(a1.mtd)/sum(a1.monthlytarget))*100 as 'MTD%', (sum(a1.QTD)/sum(a1.quarterlytarget))*100 as 'QTD%', (sum(a1.ytd)/sum(a1.yearlytarget))*100  as 'YTD%'
           from (select sm.id, sum(sm.target) as MonthlyTarget, sum(sm.target)*3 as QuarterlyTarget, sum(sm.target)*12 as YearlyTarget, abc.mtd as MTD, abc.qtd as QTD, abc.ytd as YTD
           from dbo.salesmen sm
           left join (Select x.id, Sum(m.amount) MTD, Sum(z.amount) QTD, Sum(y.amount) YTD
           From dbo.sales t inner join dbo.salesmen x on t.salesman=x.id 
                                      full join dbo.sales y
                                      on y.docno = t.docno
                                      left join dbo.sales m
                                      on m.docno = t.docno
                                         and datediff(month, m.docdate, getdate()) = 0 
                                         and m.docdate <= t.docdate  
                                        left join dbo.sales z
                                         on z.docno = t.docno
                                         and datediff(qq, z.docdate, getdate()) = 0 
                                         and z.docdate <= t.docdate
                                         where t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") and x.enabled='yes'
                                         group by x.id) abc on abc.id=sm.id
           group by sm.id, abc.mtd, abc.qtd, abc.ytd) a1
           ");
            
        }
        else if($firstcondition==true && $secondcondition==false){

           $individualsales_data=DB::select(" select sum(a1.mtd) as MTD, sum(a1.QTD) as QTD, sum(a1.ytd) as YTD, (sum(a1.mtd)/sum(a1.monthlytarget))*100 as 'MTD%', (sum(a1.QTD)/sum(a1.quarterlytarget))*100 as 'QTD%', (sum(a1.ytd)/sum(a1.yearlytarget))*100  as 'YTD%'
           from (select sm.id, sum(sm.target) as MonthlyTarget, sum(sm.target)*3 as QuarterlyTarget, sum(sm.target)*12 as YearlyTarget, abc.mtd as MTD, abc.qtd as QTD, abc.ytd as YTD
           from dbo.salesmen sm
           left join (Select x.id, Sum(m.amount) MTD, Sum(z.amount) QTD, Sum(y.amount) YTD
           From dbo.sales t inner join dbo.salesmen x on t.salesman=x.id 
                                      full join dbo.sales y
                                      on y.docno = t.docno
                                      left join dbo.sales m
                                      on m.docno = t.docno
                                         and datediff(month, m.docdate, getdate()) = 0 
                                         and m.docdate <= t.docdate  
                                        left join dbo.sales z
                                         on z.docno = t.docno
                                         and datediff(qq, z.docdate, getdate()) = 0 
                                         and z.docdate <= t.docdate
                                         where t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and x.enabled='yes'
                                         group by x.id) abc on abc.id=sm.id
           where sm.id in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") 
           and sm.enabled='yes'
           group by sm.id, abc.mtd, abc.qtd, abc.ytd
           ) a1
           
            ");

        }
        else if($firstcondition==false && $secondcondition==true){

           
           $individualsales_data=DB::select("select sum(a1.mtd) as MTD, sum(a1.QTD) as QTD, sum(a1.ytd) as YTD, (sum(a1.mtd)/sum(a1.monthlytarget))*100 as 'MTD%', (sum(a1.QTD)/sum(a1.quarterlytarget))*100 as 'QTD%', (sum(a1.ytd)/sum(a1.yearlytarget))*100  as 'YTD%'
           from (select sm.id, sum(sm.target) as MonthlyTarget, sum(sm.target)*3 as QuarterlyTarget, sum(sm.target)*12 as YearlyTarget, abc.mtd as MTD, abc.qtd as QTD, abc.ytd as YTD
           from dbo.salesmen sm
           left join (Select x.id, Sum(m.amount) MTD, Sum(z.amount) QTD, Sum(y.amount) YTD
           From dbo.sales t inner join dbo.salesmen x on t.salesman=x.id 
                                      full join dbo.sales y
                                      on y.docno = t.docno
                                      left join dbo.sales m
                                      on m.docno = t.docno
                                         and datediff(month, m.docdate, getdate()) = 0 
                                         and m.docdate <= t.docdate  
                                        left join dbo.sales z
                                         on z.docno = t.docno
                                         and datediff(qq, z.docdate, getdate()) = 0 
                                         and z.docdate <= t.docdate
                                         where  t.division in (select div from dbo.tbl_user_div where uid=".$userid.") and x.enabled='yes'
                                         group by x.id) abc on abc.id=sm.id
           group by sm.id, abc.mtd, abc.qtd, abc.ytd
           ) a1                    
           ");

           

        }

        
        $individual_charts=json_decode(json_encode( $individualsales_data),true);

        $individual_charts=  $individual_charts[0];
     
        $individual_mtd=(!empty($individual_charts['MTD'])?$individual_charts['MTD']:0);

        $individual_qtd=(!empty($individual_charts['QTD'])?$individual_charts['QTD']:0);

        $individual_ytd=(!empty($individual_charts['YTD'])?$individual_charts['YTD']:0);

        $individual_mtd= sprintf('%0.2f',$individual_mtd/100000);
        $individual_qtd= sprintf('%0.2f',$individual_qtd/100000);
        $individual_ytd= sprintf('%0.2f',$individual_ytd/100000);

        
        $individual_mtd_per=(!empty($individual_charts['MTD%'])?$individual_charts['MTD%']:0);
        $individual_qtd_per=(!empty($individual_charts['QTD%'])?$individual_charts['QTD%']:0);
        $individual_ytd_per=(!empty($individual_charts['YTD%'])?$individual_charts['YTD%']:0);

        $charts_individual=array();

        $charts_individual['MTD']= $individual_mtd ;
        $charts_individual['QTD']=  $individual_qtd ;
        $charts_individual['YTD']=  $individual_ytd ;
        $charts_individual['MTD_PER']=sprintf('%0.2f',$individual_mtd_per);
        $charts_individual['QTD_PER']=sprintf('%0.2f',$individual_qtd_per);
        $charts_individual['YTD_PER']=sprintf('%0.2f',$individual_ytd_per);


        return response()->json(['data'=>$charts_individual]);


    }


    public function getDivisionalSalesChartsData(){


        $user=Auth::user(); 
        $userid= $user->id;

 
        $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

        $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();


        

        if($firstcondition==false && $secondcondition==false){
 
 
            $data_divisionalsales=DB::select("Select x.division, Sum(m.Gross_Amount-m.Discount) as MTD, Sum(z.Gross_Amount-z.Discount) as QTD, Sum(y.Gross_Amount-y.Discount) as YTD
            From dbo.gsi t inner join dbo.Division x on t.division=x.id
               full join dbo.gsi y
                  on y.id = t.id
                  left join dbo.gsi m
                  on m.id = t.id
                     and datediff(month, m.docdate, getdate()) = 0 
                     and m.docdate <= t.docdate 
                    left join dbo.gsi z
                     on z.id = t.id
                     and datediff(qq, z.docdate, getdate()) = 0 
                     and z.docdate <= t.docdate  
                     group by x.division
            ");
        }
        else if($firstcondition==true && $secondcondition==true){
 
            $data_divisionalsales=DB::select("Select x.division, Sum(m.Gross_Amount-m.Discount) as MTD, Sum(z.Gross_Amount-z.Discount) as QTD, Sum(y.Gross_Amount-y.Discount) as YTD
            From dbo.gsi t inner join dbo.Division x on t.division=x.id
               full join dbo.gsi y
                  on y.id = t.id
                  left join dbo.gsi m
                  on m.id = t.id
                     and datediff(month, m.docdate, getdate()) = 0 and m.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")
                     and m.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                     and m.docdate <= t.docdate 
                    left join dbo.gsi z
                     on z.id = t.id
                     and datediff(qq, z.docdate, getdate()) = 0 and z.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")
                     and z.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                     and z.docdate <= t.docdate 
                     where t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                     group by x.division
            ");


        }
        else if($firstcondition==true && $secondcondition==false){

             
            $data_divisionalsales=DB::select("Select x.division, Sum(m.Gross_Amount-m.Discount) as MTD, Sum(z.Gross_Amount-z.Discount) as QTD, Sum(y.Gross_Amount-y.Discount) as YTD
            From dbo.gsi t inner join dbo.Division x on t.division=x.id
               full join dbo.gsi y
                  on y.id = t.id
                  left join dbo.gsi m
                  on m.id = t.id
                     and datediff(month, m.docdate, getdate()) = 0 and m.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")
                     and m.docdate <= t.docdate 
                    left join dbo.gsi z
                     on z.id = t.id
                     and datediff(qq, z.docdate, getdate()) = 0 and z.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")
                     and z.docdate <= t.docdate  
                     where t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") 
                     group by x.division
            ");

        }
        else if($firstcondition==false && $secondcondition==true){
 
            $data_divisionalsales=DB::select("Select x.division, Sum(m.Gross_Amount-m.Discount) as MTD, Sum(z.Gross_Amount-z.Discount) as QTD, Sum(y.Gross_Amount-y.Discount) as YTD
            From dbo.gsi t inner join dbo.Division x on t.division=x.id
               full join dbo.gsi y
                  on y.id = t.id
                  left join dbo.gsi m
                  on m.id = t.id
                     and datediff(month, m.docdate, getdate()) = 0 and m.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                     and m.docdate <= t.docdate 
                    left join dbo.gsi z
                     on z.id = t.id
                     and datediff(qq, z.docdate, getdate()) = 0 and z.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                     and z.docdate <= t.docdate  
                     where t.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                     group by x.division
            ");

        }

  
                 $data_array=json_decode(json_encode($data_divisionalsales),true);
 
                 $divisions=array_column($data_array,'division');

                 $mtds_values=array_column($data_array,'MTD');

                 $mtds=array();
                 
                 $total_mtds=(float) sprintf('%0.2f',array_sum( $mtds_values));
 

                 foreach($mtds_values as $mtd_value){

                    if($total_mtds>0){
                        $mtd_per=sprintf('%0.2f',(($mtd_value/$total_mtds)*100));
                         array_push( $mtds,$mtd_per);
                    }
                    else{
                        array_push( $mtds,0);
                    }

                

                 }
 
 
                 $qtd_values=array_column($data_array,"QTD");

                 $total_qtds=(float) sprintf('%0.2f',array_sum( $qtd_values));
 

                 $qtds=array();

                 foreach( $qtd_values as  $qtd_value){

                    if($total_qtds>0){
                        $qtd_per=sprintf('%0.2f',(($qtd_value/$total_qtds)*100));

                        array_push( $qtds,$qtd_per);
                    }
                    else{
                        
                        array_push( $qtds,0);
                    }
                    
               

                 }

                 $ytd_values=array_column($data_array,"YTD");


                 $total_ytds= sprintf('%0.2f',array_sum(  $ytd_values));

                 $ytds=array();

                 foreach( $ytd_values as  $ytd_value){

                    if($total_ytds>0){
                        $ytd_per=sprintf('%0.2f',(($ytd_value/$total_ytds)*100));

                        array_push(  $ytds,$ytd_per);
                    }
                    else{
                        array_push(  $ytds,0);
                    }

                   

                 }


                 $divisional_charts=array("divisions"=>$divisions,"qtds"=>$qtds,"total_qtds"=> $total_qtds,"ytds"=>    $ytds ,"total_ytds"=>$total_ytds,"mtds"=> $mtds,"total_mtds"=> $total_mtds);
 
                 return response()->json(['data'=>$divisional_charts]);
 
    }



    public function getSalesChartsData(){
        $user=Auth::user(); 
        $userid= $user->id;

 
        $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

        $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

              
        if($firstcondition==false && $secondcondition==false){

                    

            $sales_data=DB::select("select dbo.SalesMen.team, datename(month,dbo.sales.docdate) as Month, sum(amount) as Sales from dbo.sales inner join dbo.SalesMen on dbo.sales.salesman=dbo.SalesMen.id where dbo.salesmen.enabled='yes'
            group by dbo.salesmen.team, datename(month,dbo.sales.docdate)
            ");

         }
         else if($firstcondition==true && $secondcondition==true){
            $sales_data=DB::select("select dbo.SalesMen.team, datename(month,dbo.sales.docdate) as Month, sum(amount) as Sales from dbo.sales inner join dbo.SalesMen on dbo.sales.salesman=dbo.SalesMen.id where dbo.salesmen.team is not null and dbo.sales.salesman in (select s_exe from dbo.tbl_user_sexe where uid=262) and dbo.sales.division in (select div from dbo.tbl_user_div where uid=262) 
            group by dbo.salesmen.team, datename(month,dbo.sales.docdate) ");
         }
         else if($firstcondition==true && $secondcondition==false){
            $sales_data=DB::select("select dbo.SalesMen.team, datename(month,dbo.sales.docdate) as Month, sum(amount) as Sales from dbo.sales inner join dbo.SalesMen on dbo.sales.salesman=dbo.SalesMen.id where dbo.salesmen.team is not null and  dbo.sales.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") 
            group by dbo.salesmen.team, datename(month,dbo.sales.docdate)
            ");
         }
         else if($firstcondition==false && $secondcondition==true){
            $sales_data=DB::select("select dbo.SalesMen.team, datename(month,dbo.sales.docdate) as Month, sum(amount) as Sales from dbo.sales inner join dbo.SalesMen on dbo.sales.salesman=dbo.SalesMen.id where dbo.salesmen.team is not null and dbo.sales.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
            group by dbo.salesmen.team, datename(month,dbo.sales.docdate)
            ");
         }



         $sales_data=json_decode(json_encode( $sales_data),true);


         $sales_colours=DB::table('SalesMen')->orderby('name','asc')->select('teamcolour','Name as teamname')->pluck('teamcolour' ,'teamname')->toArray();

         $series=array();
         $categories=array();
       
         foreach(   $sales_data as $single_sales_data){

            if(!in_array($single_sales_data['Month'], $categories)){
                array_push($categories,$single_sales_data['Month']);
            }

            $teamname=(empty($single_sales_data['team'])?'No name':trim($single_sales_data['team']));

            $sales=(array_key_exists('Sales',$single_sales_data)?$single_sales_data['Sales']:0) ;

            $sales_no=sprintf('%0.2f',($sales/100000));

            if(!array_key_exists($teamname,$series)){

                $series[$teamname]=array();

            }

            $sales_no=($sales_no<0?0:$sales_no);

             array_push(    $series[$teamname],  $sales_no);



         }

            $data_series=array();

            $colors=array();


         foreach($series as $series_key=>$series_value){

            array_push(  $data_series,array('name'=>trim($series_key),'data'=>$series_value));

         }


         $sales_colours=DB::table('SalesMen')->orderby('name','asc')->select('teamcolour','team as teamname')->pluck('teamcolour' ,'teamname')->toArray();


         foreach($data_series as $data_single){

             if(array_key_exists( $data_single['name'],$sales_colours) && $data_single['name']!="No name"){

                $teamfoundcolor=   $sales_colours[$data_single['name']];
             }
             else{
                $teamfoundcolor="#556ee6";
             }

             array_push($colors,  $teamfoundcolor);

         } 


          $sales_charts=array('categories'=>$categories,'series'=>  $data_series,'colors'=>$colors);


          return response()->json(['data'=>  $sales_charts]);




    }



    public function getNoOfPendingQuotes(){
                
                $user=Auth::user(); 
                $userid= $user->id;

        
                $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

                $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

           if( $firstcondition==false &&  $secondcondition==false){

                $pending_quotations=DB::select("select sum(net_amount) as net_amount
                from dbo.gsq a inner join 
                (
                SELECT distinct(doc_no)
                FROM         dbo.tbl_link_data AS p 
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) ) b on a.docno like b.doc_no  inner join dbo.SalesMen c on c.id=a.salesman and c.enabled='yes'");

             }
             else  if( $firstcondition==true &&  $secondcondition==true){

                $pending_quotations=DB::select("select sum(net_amount) as net_amount
                from dbo.gsq a inner join 
                (
                SELECT distinct(doc_no)
                FROM         dbo.tbl_link_data AS p 
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) ) b on a.docno like b.doc_no  and a.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and a.division in (select div from dbo.tbl_user_div where uid=".$userid.") inner join dbo.SalesMen c on c.id=a.salesman and c.enabled='yes'
                ");


             }
             else  if( $firstcondition==true &&  $secondcondition==false){

                
                $pending_quotations=DB::select("select sum(net_amount)  as net_amount
                from dbo.gsq a inner join 
                (
                SELECT distinct(doc_no)
                FROM         dbo.tbl_link_data AS p 
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) ) b on a.docno like b.doc_no  and a.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".  $userid.") inner join dbo.SalesMen c on c.id=a.salesman and c.enabled='yes'
                ");


            }
            else  if( $firstcondition==false &&  $secondcondition==true){

                $pending_quotations=DB::select("select sum(net_amount)  as net_amount
                from dbo.gsq a inner join 
                (
                SELECT distinct(doc_no)
                FROM         dbo.tbl_link_data AS p 
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) ) b on a.docno like b.doc_no  and a.division in (select div from dbo.tbl_user_div where uid=".  $userid.") inner join dbo.SalesMen c on c.id=a.salesman and c.enabled='yes'
                ");



            }
 
            $pending_quote=json_decode(json_encode( $pending_quotations),true);
 

            if(count($pending_quote)==1 && array_key_exists('net_amount',$pending_quote[0])){
                $pending_quote=    $pending_quote[0]['net_amount'];

                $pending_quote=   sprintf('%0.2f',($pending_quote/100000));   
            }
            else{
                $pending_quote=0.00;
            }


            return response()->json(['noofpendingquotes'=>$pending_quote]);

    }



    public function getNoOfPendingOrders(){
              
        $user=Auth::user(); 
        $userid= $user->id;


        $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

        $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

 
            if( $firstcondition==false &&  $secondcondition==false){

                $pending_orders=DB::select("SELECT  count(distinct(p.doc_no)) as nooforders
                FROM         dbo.tbl_link_data AS p 
                inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gso') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) 
                ");


            }
            else   if( $firstcondition==true &&  $secondcondition==true){

                

                $pending_orders=DB::select("SELECT  count(distinct(p.doc_no)) as nooforders
                FROM         dbo.tbl_link_data AS p 
                Inner join dbo.gso t on p.doc_no like t.docno
                inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gso') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                ");

            }
            else if( $firstcondition==true &&  $secondcondition==false){

                $pending_orders=DB::select("SELECT  count(distinct(p.doc_no)) as nooforders
                FROM         dbo.tbl_link_data AS p 
                Inner join dbo.gso t on p.doc_no like t.docno
                inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gso') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")
                ");


            }
            else if( $firstcondition==false &&  $secondcondition==true){

                $pending_orders=DB::select("SELECT  count(distinct(p.doc_no)) as nooforders 
                FROM         dbo.tbl_link_data AS p 
                Inner join dbo.gso t on p.doc_no like t.docno
                inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gso') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no)) 
                IS NULL) and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                ");


            }


            $pending_orders=json_decode(json_encode(  $pending_orders),true);
 
            if(count($pending_orders)==1){
                $no_of_pendingorders=$pending_orders[0]['nooforders'];
            }
            else{
                $no_of_pendingorders=0;
            }


            return response()->json(['noofpendingorders'=>  $no_of_pendingorders]);

    }


    public function getNoOfPendingInvoices(){

                    
                    $user=Auth::user(); 
                    $userid= $user->id;

                    $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

                    $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

                if( $firstcondition==false &&  $secondcondition==false){

                    $pending_invoices=DB::select("SELECT  count(distinct(p.doc_no))  as noofinvoices
                    FROM         dbo.tbl_link_data AS p 
                    inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                    where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsi') AND 
                                                    ((SELECT   SUM(qty) AS Expr1
                                                        FROM         dbo.tbl_link_data AS c1
                                                        WHERE     (p.Id = reff_no)) 
                    IS NULL)
                    ");

                }
                else   if( $firstcondition==true &&  $secondcondition==true){

                    $pending_invoices=DB::select("SELECT  count(distinct(p.doc_no))  as noofinvoices
                    FROM         dbo.tbl_link_data AS p 
                    Inner join dbo.gsi t on p.doc_no like t.docno
                    inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                    where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsi') AND 
                                                    ((SELECT   SUM(qty) AS Expr1
                                                        FROM         dbo.tbl_link_data AS c1
                                                        WHERE     (p.Id = reff_no)) 
                    IS NULL) and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and t.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                    ");

                }
                else   if( $firstcondition==true &&  $secondcondition==false){

                    $pending_invoices=DB::select("SELECT  count(distinct(p.doc_no))  as noofinvoices
                    FROM         dbo.tbl_link_data AS p 
                    Inner join dbo.gsi t on p.doc_no like t.docno
                    inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                    where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsi') AND 
                                                    ((SELECT   SUM(qty) AS Expr1
                                                        FROM         dbo.tbl_link_data AS c1
                                                        WHERE     (p.Id = reff_no)) 
                    IS NULL) and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")  
                    ");

                }
                else   if( $firstcondition==false &&  $secondcondition==true){

                    $pending_invoices=DB::select("SELECT  count(distinct(p.doc_no)) as noofinvoices
                    FROM         dbo.tbl_link_data AS p 
                    Inner join dbo.gsi t on p.doc_no like t.docno
                    inner join dbo.SalesMen c on c.id=p.salesman and c.enabled='yes'
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.salesman
                    where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsi') AND 
                                                    ((SELECT   SUM(qty) AS Expr1
                                                        FROM         dbo.tbl_link_data AS c1
                                                        WHERE     (p.Id = reff_no)) 
                    IS NULL) and t.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                    ");

                }


                $pending_invoices=json_decode(json_encode(   $pending_invoices),true);
                

                if(count(  $pending_invoices)==1 ){

                    $no_of_pendinginvoices= $pending_invoices[0]['noofinvoices'];

                }
                else{
                    $no_of_pendinginvoices=0;
                }

           return response()->json(['noofpendinginvoices'=>$no_of_pendinginvoices]);


    }


    public function getNoOfAgeingReceivablesAndAmount(){
                    $user=Auth::user(); 
                    $userid= $user->id;

            
                    $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

                    $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();

               if( $firstcondition==false &&  $secondcondition==false){

                    $receivable_details=DB::select(" select count(p.id) as 'No of Inv', sum(balamount) as Amount
                   from dbo.Receivable p
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                         where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90
                    ");
    
                }
                else   if( $firstcondition==true &&  $secondcondition==true){
    
                    $receivable_details=DB::select("select count(p.id) as 'No of Inv', sum(balamount) as Amount
                    from dbo.Receivable p
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                    where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90 and s.enabled='yes' and p.smid in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and p.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                    ");
    
                }
                else   if( $firstcondition==true &&  $secondcondition==false){
    
                    $receivable_details=DB::select("select count(p.id) as 'No of Inv', sum(balamount) as Amount
                    from dbo.Receivable p
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                    where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90 and s.enabled='yes' and p.smid in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") 
                    ");
    
                }
                else   if( $firstcondition==false &&  $secondcondition==true){
    
                    $receivable_details=DB::select("select count(p.id) as 'No of Inv', sum(balamount) as Amount
                    from dbo.Receivable p
                    LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                    where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90 and s.enabled='yes' and p.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                     ");
    
                }
    
    
                $receivable_details=json_decode(json_encode(   $receivable_details),true); 

                if(count(  $receivable_details)==1 ){
    
                    $no_of_ageingreceivables= (empty($receivable_details[0]['No of Inv'])?0:$receivable_details[0]['No of Inv']);
    
                    $ageingreceivable_amount= (empty($receivable_details[0]['Amount'])?0:$receivable_details[0]['Amount']);
    
                    $ageingreceivable_amount= sprintf('%0.2f',$ageingreceivable_amount/100000);
    
                }
                else{
                   
                    $no_of_ageingreceivables=0;
                    $ageingreceivable_amount= 0;
    
                }

                return response()->json(['no_of_ageing_receivables_and_amount'=> $no_of_ageingreceivables.",".$ageingreceivable_amount]);

    }



    public function getNoOfPendingRmaCases(){
        

        $pending_rma_cases=DB::select("select count(id) as no_of_pending_rma_cases
                from dbo.warranty_transaction where case_status='open'
            ");

            $pending_rma_cases=json_decode(json_encode($pending_rma_cases),true);

            if(count(    $pending_rma_cases)==1){
                $no_of_pending_rma_cases=    $pending_rma_cases[0]['no_of_pending_rma_cases'];
            }
            else{
                $no_of_pending_rma_cases=0;
            }

            return response()->json(['no_of_pending_rma_cases'=>$no_of_pending_rma_cases]);

    }


    public function getDashboardPendingData($companyname,$purpose){

        $user=Auth::user(); 
        $userid= $user->id;


        $firstcondition= DB::table('tbl_user_sexe')->where('uid',$userid)->exists();

        $secondcondition= DB::table('tbl_user_div')->where('uid',$userid)->exists();



        if($purpose=="pendingquotes"){

            if(   $firstcondition==false &&   $secondcondition==false){
                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes'
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");
            }
            else if(   $firstcondition==true &&   $secondcondition==true){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p 
                inner join dbo.gsq on p.doc_no like dbo.gsq.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1)) and p.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and s.enabled='yes' and dbo.gsq.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                group by p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id, s.name, s.emailid,  p.txn_main_id 
                ");


            }
            else if($firstcondition==true &&   $secondcondition==false){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date,getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p 
                inner join dbo.gsq on p.doc_no like dbo.gsq.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1)) and p.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and s.enabled='yes' 
                group by p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id, s.name, s.emailid,  p.txn_main_id 
                ");

            }
            else if($firstcondition==false &&   $secondcondition==true){
                
                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p 
                inner join dbo.gsq on p.doc_no like dbo.gsq.docno
                LEFT OUTER JOIN          dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1)) and s.enabled='yes' and dbo.gsq.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                group by p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id, s.name, s.emailid,  p.txn_main_id 
                ");

            } 

        }
        else if($purpose=="pendingorders"){

            if(   $firstcondition==false &&   $secondcondition==false){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes'
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");

            }
            else   if(   $firstcondition==true &&   $secondcondition==true){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p Inner join dbo.gso t on p.doc_no like t.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") and s.enabled='yes'
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");

            }
            else   if(   $firstcondition==true &&   $secondcondition==false){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p Inner join dbo.gso t on p.doc_no like t.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and s.enabled='yes'
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");

            }
            else   if(   $firstcondition==false &&   $secondcondition==true){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date,getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p Inner join dbo.gso t on p.doc_no like t.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") and s.enabled='yes'
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");

            }

        }
        else if($purpose=="pendinginvoices"){

            if(   $firstcondition==false &&   $secondcondition==false){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes'
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");
            }
            else   if(   $firstcondition==true &&   $secondcondition==true){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age ,  p.txn_main_id 
                FROM         dbo.tbl_link_data AS p Inner join dbo.gsi t on p.doc_no like t.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes' and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                ORDER BY doc_date desc
                ");

            }
            else   if(   $firstcondition==true &&   $secondcondition==false){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) 
                AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                         p.doc_date, getdate()) AS age , p.txn_main_id 
                FROM         dbo.tbl_link_data AS p Inner join dbo.gsi t on p.doc_no like t.docno
                LEFT OUTER JOIN
                                         dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                         dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                         dbo.SalesMen AS s ON s.Id = p.salesman
                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                             ((SELECT   SUM(qty) AS Expr1
                                                 FROM         dbo.tbl_link_data AS c1
                                                 WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes' and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") 
                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id
                ORDER BY doc_date desc
                ");

            }
            else   if(   $firstcondition==false &&   $secondcondition==true){
                       $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                            p.doc_date, getdate())AS age , p.txn_main_id 
                            FROM         dbo.tbl_link_data AS p Inner join dbo.gsi t on p.doc_no like t.docno
                            LEFT OUTER JOIN
                                            dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                            dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                            dbo.SalesMen AS s ON s.Id = p.salesman
                            where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                                ((SELECT   SUM(qty) AS Expr1
                                                    FROM         dbo.tbl_link_data AS c1
                                                    WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) 
                            IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes' and t.division in (select div from dbo.tbl_user_div where uid=".$userid.") 
                            GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id,  p.txn_main_id 
                            ORDER BY doc_date desc
                            ");

            }
  

        }
        else if($purpose=="ageingreceivables"){


            if(   $firstcondition==false &&   $secondcondition==false){

                $table_data=DB::select("select p.[customer name] as 'Customer', p.Salesman as 'Salesman',  p.gsino as 'GSI No', p.gsidt as 'GST Dt', p.amount as 'GSI Amt', p.balamount as Balance, p.transporter as 'Transporter', p.docketno as 'Docket No', p.dateofdelivery as 'Date of Delivery', p.duedays as 'Due Days' ,  p.txn_main_id 
                from dbo.Receivable p
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90
                ");
            }
            else  if(   $firstcondition==true &&   $secondcondition==true){

                $table_data=DB::select("SELECT   TOP (100) PERCENT p.txn_id, p.doc_no, p.doc_date, p.location, p.cust_id, c.cust_id AS Customer, SUM(p.qty) AS Qty, SUM(p.amount) AS Amt, s.Name, s.emailid, DATEDIFF(dd,
                                                p.doc_date, getdate()) AS age ,  p.txn_main_id 
                                FROM         dbo.tbl_link_data AS p Inner join dbo.gsi t on p.doc_no like t.docno
                                LEFT OUTER JOIN
                                                dbo.Customers AS c ON p.cust_id = c.Id INNER JOIN
                                                dbo.Product_master AS v ON	 p.product = v.Id LEFT OUTER JOIN
                                                dbo.SalesMen AS s ON s.Id = p.salesman
                                where  (p.location > 0) AND (p.reff_no IS NULL) AND (p.txn_id = 'gsq') AND 
                                                    ((SELECT   SUM(qty) AS Expr1
                                                        FROM         dbo.tbl_link_data AS c1
                                                        WHERE     (p.Id = reff_no) AND (product = p.product) AND (location = p.location)) IS NULL) AND (p.doc_date < (getdate()+1))  and s.enabled='yes' and t.salesman in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.") 
                                GROUP BY p.doc_no, p.location, p.cust_id, s.Name, s.emailid, p.doc_date, c.cust_id, p.txn_id ,  p.txn_main_id 
                                ORDER BY doc_date desc
                                ");

            }
            else  if(   $firstcondition==true &&   $secondcondition==false){

                $table_data=DB::select("select p.[customer name] as 'Customer', p.Salesman as 'Salesman',  p.gsino as 'GSI No', p.gsidt as 'GST Dt', p.amount as 'GSI Amt', p.balamount as Balance, p.transporter as 'Transporter', p.docketno as 'Docket No', p.dateofdelivery as 'Date of Delivery', p.duedays as 'Due Days' ,  p.txn_main_id 
                from dbo.Receivable p
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90 and s.enabled='yes' and p.smid in (select s_exe from dbo.tbl_user_sexe where uid=".$userid.")
                ");
            }
            else  if(   $firstcondition==false &&   $secondcondition==true){

                $table_data=DB::select("select p.[customer name] as 'Customer', p.Salesman as 'Salesman',  p.gsino as 'GSI No', p.gsidt as 'GST Dt', p.amount as 'GSI Amt', p.balamount as Balance, p.transporter as 'Transporter', p.docketno as 'Docket No', p.dateofdelivery as 'Date of Delivery', p.duedays as 'Due Days' ,  p.txn_main_id 
                from dbo.Receivable p
                LEFT OUTER JOIN dbo.SalesMen AS s ON s.Id = p.smid
                where (p.PDCAmount>0 or p.pdcamount2>0 or p.pdcamount3>0 or p.DifferenceAmount>0 or p.differenceamount<0) and (p.BalAmount>100) and p.DueDays>90 and s.enabled='yes' and p.division in (select div from dbo.tbl_user_div where uid=".$userid.")
                ");

            }

        }
        else if($purpose=="rmacases"){

            $table_data=DB::select("select w.voucher as 'RMA No', w.date as 'RMA Date', c.cust_id as 'Customer Name', s.name as Salesman, count(fk_id) as 'No of RMA Lights' from dbo.warranty_transaction w
            inner join dbo.customers c on w.cust_id=c.Id
            inner join dbo.SalesMen s on w.salesman_id2=s.id
            inner join dbo.warranty_transaction_det wd on w.id=wd.fk_id
            group by fk_id, w.voucher,w.date,c.cust_id,s.name
            order by w.date
            ");
            

        }

        $table_data=json_decode(json_encode(   $table_data),true);

        $table_columns=array_keys($table_data[0]);


        $column_html="<th>#</th>";

        foreach(   $table_columns as $single_column){

            if($single_column=='txn_id' || $single_column=='txn_main_id'   || $single_column=='location' || $single_column=='emailid'  || $single_column=='cust_id'){
                continue;
            }

            $column_html= $column_html."<th>".ucfirst($single_column)."</th>";


        }
        if($purpose!='rmacases'){
            $column_html= $column_html."<th>View Details</th>";
        }

        $table_html="";

        $sno=1;
        foreach(    $table_data as $single_data){

            $single_row_html="<tr><td>".$sno."</td>";

            foreach(   $table_columns as $single_column){

                if($single_column=='txn_id' || $single_column=='txn_main_id'   || $single_column=='location' || $single_column=='emailid' || $single_column=='cust_id'){
                    continue;
                }

                if($single_column=='doc_date' || $single_column=='RMA Date'){
                    $single_row_html= $single_row_html."<td>".date("d/m/Y",strtotime($single_data[$single_column]))."</td>";
                }
                else{
                    $single_row_html= $single_row_html."<td>".$single_data[$single_column]."</td>"; 
                }

            }
            
            if($purpose!='rmacases'){ 
             
                 $tableid=TableMaster::where('Table_Name',trim($single_data['txn_id']))->value('Id');
  
                  $editurl=url('/')."/".Session::get('company_name').'/edit-transaction-table-single-data/'.$single_data['txn_id'].'/'. $tableid.'/'.$single_data['txn_main_id'];
                $single_row_html= $single_row_html."<td><a class='btn btn-primary' target='_blank' href='".$editurl."'>View Details</a></td>"; 
            }
 
            $single_row_html= $single_row_html."</tr>";

            $table_html= $table_html. $single_row_html;

            $sno++;
 
        }

  
 
        return response()->json(['column_html'=>$column_html,'table_html'=>$table_html]);



    }

    public function getSearchedTableTransactions($companyname,$searchedtext){


        $roleid=Session::get('role_id');
        $user=Auth::User();

        if($user->id!=1){
            $userwhere="user_id=".$user->id." and ";
        }
        else{
            $userwhere=""; 
        }
 
  

       $lastdatas= DB::select("select top 10  docno as 'Doc No',dbo.tbl_audit_data.table_name as Table_Name  ,cast(docdate as date) As 'Doc Date', dbo.customers.cust_id as Name,dbo.salesmen.Name as Salesman
        from dbo.tbl_audit_data
        left join dbo.customers on dbo.tbl_audit_data.cust_id=dbo.customers.id
        left join dbo.salesmen on dbo.tbl_audit_data.salesman=dbo.SalesMen.id
        where   $userwhere  docno Like '%".$searchedtext."%'  and docno not in (select docno from dbo.tbl_audit_data where operation like 'delete%')
        group by docno ,dbo.tbl_audit_data.docdate,dbo.customers.cust_id,dbo.salesmen.name,dbo.tbl_audit_data.table_name
        order by 'Doc Date' desc");

        $tablenames=array_unique(array_column( $lastdatas,'Table_Name'));
 
        $tablename_ids= TableMaster::whereIn('Table_Name', $tablenames)->pluck('Id','Table_Name');
 
        $data_response=array();

        foreach(   $lastdatas as    $lastdata){

            $foundtableid=$tablename_ids[$lastdata->Table_Name];
            $rolemap=RolesMap::getRoleTransactionActions(  $roleid,$foundtableid);

           $founddataid= DB::table($lastdata->Table_Name)->where('docno',$lastdata->{'Doc No'})->value('Id');

                if( !empty($founddataid) && ($rolemap['edit']==true || $rolemap['view']==true) ){
                       $edit_url=url('/')."/".$companyname."/edit-transaction-table-single-data/".$lastdata->Table_Name."/". $foundtableid."/".$founddataid;
                }
                else{
                    $edit_url="";

                } 
                array_push(   $data_response,array('docno'=>$lastdata->{'Doc No'},'url'=>$edit_url));
        }  

 

        return response()->json( $data_response);

    }


}
