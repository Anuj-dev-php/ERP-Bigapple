<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\TableMaster;
use  App\Helper\Helper;
use Session;
use Illuminate\Support\Facades\Log;
use App\Models\FieldsMaster;
use DB;
use App\Http\Controllers\Services\TableFieldService;

class CopyTransactionController extends Controller
{

    protected $tablefieldservice;


    public function __construct(TableFieldService $tfs){

        $this->tablefieldservice=$tfs;
    }
     
    public function index($companyname){

        $companies=Company::orderby('comp_name','asc')->get();
        return view("configuration.copytransaction",compact('companyname','companies'));
    }



    public function getCompanyTransactions($companyname){
       
        $currentcompanyname= Session::get('company_name');

       $dbexists= Helper::checkDatabaseExists($companyname);

       if($dbexists==false){
           return response()->json([]);
       }

        Helper::connectDatabaseByName($companyname);

        $tables=  TableMaster::orderby('table_label','asc')->select('Id','Table_Name','table_label')->get(); 

        
        Helper::connectDatabaseByName( $currentcompanyname); 

        return response()->json( $tables); 

    }


    public function submitCopyTransactions(Request $request){
 

        $transaction=$request->transaction;

        $transactionname=$request->newtransaction_name;

        $transactionlabel=$request->newtransaction_label;

        $tableexists= TableMaster::where('Table_Name', $transactionname)->exists();


        if($tableexists==true){
            return redirect()->back()->with('error_message','Please enter another New Transaction Name , '.  $transactionname." is already in use");
        } 

        DB::beginTransaction();

        try{

            $this->tablefieldservice->tran_id=$transaction;
            $this->tablefieldservice->newtablename= $transactionname;
            $this->tablefieldservice->newtablelabel=$transactionlabel;
            $this->tablefieldservice->copyNewTable();



            
        // $tablefound=TableMaster::find($transaction); 

        // $newtransaction= $tablefound->replicate();

        // $newtransaction->Table_Name=    $transactionname;

        // $newtransaction->table_label=  $transactionlabel;

        // $newtransaction->{'Parent Table'}='None';

        // $newtransaction->save(); 

        // $tablefields=FieldsMaster::where('Table_Name', $tablefound->Table_Name)->orderby('Id','asc')->get();

        // $index=1;
 
        // foreach($tablefields as $tablefield){

        //     $newtablefield=$tablefield->replicate();
        //     $newtablefield->Table_Name= $transactionname; 
        //     $newtablefield->Id=$index;
        //     $newtablefield->save();
        //     $index++;
 
        // }
 

        // DB::statement("select * into ".$transactionname." from ".$tablefound->Table_Name." where 1=2");

        // // check if det table exists

        // $tablenamewithdet=   $tablefound->Table_Name.'_det';

        // $tablefound1=TableMaster::where('Table_Name',$tablenamewithdet)->first();


        // if(!empty($tablefound1)){
 

        //     $newtransactiondet= $tablefound1->replicate();

        //     $newtransactiondet->Table_Name= $transactionname."_det"; 
              
        //     $newtransactiondet->table_label=  $transactionlabel." Det";

        //     $newtransactiondet->{'Parent Table'}=$transactionname;

        //     $newtransactiondet->save();  


        //     $tablefieldsdet=FieldsMaster::where('Table_Name',$tablenamewithdet)->orderby('Id','asc')->get();

        //     $index=1;
     
        //     foreach( $tablefieldsdet as $tablefield){
    
        //         $newtablefield=$tablefield->replicate();
        //         $newtablefield->Table_Name= $transactionname."_det"; 
        //         $newtablefield->Id=$index;
        //         $newtablefield->save();
        //         $index++;
     
        //     }

        //    DB::statement("select * into ".$transactionname."_det  from ".$tablenamewithdet." where 1=2"); 
        // }   
        DB::commit();
       }
       catch(\Exception $e){
        DB::rollback();
       }

        return redirect()->back()->with('message','New Transaction created successfully'); 

    }
}
