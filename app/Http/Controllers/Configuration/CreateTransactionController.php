<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use Illuminate\Support\Facades\Log;
use App\Models\FieldsMaster; 
use App\Models\FieldFunction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Code;
use Session;
use App\Http\Controllers\Services\TableFieldService;

class CreateTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $tablefieldservice;


    public function __construct(TableFieldService $tfservice){

        $this->tablefieldservice=$tfservice;

    }



    public function index($companyname,Request $request)
    {


       if(!empty($request->searchtext)){

        Session::forget("createtransaction_search");

        Session::put("createtransaction_search",$request->searchtext);

       }
       else if($request->method()=="POST"){
           
        Session::forget("createtransaction_search");

       }

       $searchtext=Session::get("createtransaction_search");

        $transactions=TableMaster::orderby('id','asc')->where(function($query)use(   $searchtext){

            $query->where('Table_Name','LIKE','%'.$searchtext.'%')->orwhere('table_label','LIKE','%'.$searchtext.'%')->orwhere('Id','LIKE','%'.$searchtext.'%');

        })->paginate(10);
  
        return view("configuration.createtransactions",compact('companyname','transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($companyname)
    {
         
        $parent_transactions=TableMaster::orderby('table_label','asc')->get()->pluck('table_label','Table_Name');
   
 
        return view("configuration.addeditcreatetransaction",compact('companyname','parent_transactions' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($companyname,Request $request)
    {
 
        $transactionid=$request->tranid; 

        $tablename=$request->table_name;

        if(empty($transactionid)){

            $tablenameexists= TableMaster::where('Table_Name',$tablename)->exists(); 

            if( $tablenameexists){
            
                return redirect('/'.$companyname.'/add-edit-createtransaction'.(!empty($transactionid)?'/'.$transactionid:''))->with('error_message',"Please Enter Another Table Name , Table Name already exists");
    
            }
    
        
        }
 
        $tabid=$request->tab_id;

        $data=array('Table_Name'=>   $tablename
        ,'Field_Name' =>$request->table_label
        ,'Tab_Id'=>$tabid
        ,'Parent Table'=>empty($request->parent_txn)?NULL:$request->parent_txn 
        ,'Stock Operation'  =>$request->stock_operation 
        ,'Status' =>1
        ,'Receivable'=>$request->receivable
        ,'table_label'=>$request->table_label
        ,'txn_class'=>(empty($request->txn_class)?NULL:$request->txn_class)
        ,'cr_chk'=>(empty($request->cr_chk)?'False':'True')
        ,'bd_chk'=>(empty($request->bd_chk)?'False':'True')
        ,'ADeduct'=>(empty($request->a_deduct)?0:1)
        ,'ngt_chk'=>(empty($request->ngt_chk)?'False':'True')
        ,'qty_zero'=>(empty($request->qty_zero)?'False':'True')
        ,'auto_bill'=>(empty($request->auto_bill)?'False':'True')
        ,'direct_print'=>(empty($request->direct_print)?'False':"True")
        ,'direct_sms'=>(empty($request->direct_sms)?'False':'True'),
        'LinkId'=>0);

        if(empty( $transactionid)){ 

            $user=Auth::user();
 
            $data['Created_By']=trim($user->user_id);

            TableMaster::insert($data);


            $this->tablefieldservice->tablename=$tablename;
            $this->tablefieldservice->tabid=$tabid;

            $user=Auth::user();
            $this->tablefieldservice->user=$user;
            
            $this->tablefieldservice->createNewTable();
             
            $msg="created";
        }
        else{  
            unset($data['Table_Name']);
            unset($data['Field_Name']);
            TableMaster::where('Id',$transactionid)->update($data);
            $msg="updated";
        }
     

        return redirect('/'.$companyname.'/create-transactions')->with('message',"Transaction ".$msg." successfully");
         
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
    public function edit( $companyname,$transactionid)
    {
        $transaction=TableMaster::find($transactionid); 
         
        $parent_transactions=TableMaster::orderby('table_label','asc')->get()->pluck('table_label','Table_Name');

        return view("configuration.addeditcreatetransaction",compact('companyname','transaction','parent_transactions'));
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
    public function destroy(Request $request)
    {
       $ids=$request->ids;


       $tablenames=TableMaster::whereIn('Id',$ids)->pluck('Table_Name');

       foreach( $tablenames as  $tablename){

        Schema::dropIfExists($tablename);
       } 
       
       TableMaster::whereIn('Id',$ids)->delete();



       return response()->json(['status'=>'success','message'=>'Transactions Deleted successfully']);
    }



    public function viewTransactionFields($companyname,$tranid,Request $request){

        $searchtext=$request->searchtext;


        if(!empty($searchtext)){
            Session::forget('viewtransactionfields_searchtext');
            Session::put('viewtransactionfields_searchtext',$searchtext); 
        }
        else if($request->method()=="POST"){
            Session::forget('viewtransactionfields_searchtext');
        }

        $hassearchtext=false;
        $sessionsearchtext="";

        if(!empty(Session::get('viewtransactionfields_searchtext'))){
            $sessionsearchtext=Session::get('viewtransactionfields_searchtext');
            $hassearchtext=true;
        }
 
 
       $tablename= TableMaster::where('Id',$tranid)->value('Table_Name');

        $fields=FieldsMaster::where('Table_Name',$tablename)->when($hassearchtext,function($query)use(  $sessionsearchtext){
            $query->where(function($query1)use(  $sessionsearchtext){
            $query1->where('Field_Name','LIKE','%'. $sessionsearchtext.'%')->orwhere('fld_label','LIKE','%'. $sessionsearchtext.'%');
            });

        })->orderby('Id','asc')->paginate(10);
 
 
        return view("configuration.viewfields",compact('fields','companyname','tablename','tranid'));


    }


    public function addEditTransactionField($companyname,$tranid,$fieldname=null){
 
 
        $tabledetail=TableMaster::where('Id',$tranid)->select('Table_Name','Parent Table')->first();

        $tablename=  $tabledetail->Table_Name;

        $parenttable=((trim($tabledetail->{'Parent Table'})=='None' || $tabledetail->{'Parent Table'})==''?'':$tabledetail->{'Parent Table'});
 

        $tranfield=FieldsMaster::where('Table_Name', $tablename)->where('Field_Name',$fieldname)->first();
 
        $trantables=TableMaster::orderby('table_label','asc')->pluck('table_label','Table_Name');
 
        if (strpos( $tablename, '_det') !== false) {
            $autopopulatefromheader=true;
        }
        else{
            $autopopulatefromheader=false;
        }
 
        
        $functions=FieldFunction::when(!$autopopulatefromheader,function($query){
            $query->where("Id","<>",24);

        })->orderby('Name','asc')->pluck('Name','Id'); 

 

        if( !empty( $tranfield) &&  !empty($tranfield->From_Table)  &&  ( $tranfield->Field_Function==4 || $tranfield->Field_Function==33 || $tranfield->Field_Function==35)  ){
            $fromtablefields=FieldsMaster::orderby('fld_label','asc')->select("id","Field_Name","fld_label")->where("Table_Name",$tranfield->From_Table)->get();
            $keyfields=array("from_table"=>$tranfield->From_Table,'display_field'=>$tranfield->{'Display Field'},'scr_field'=>$tranfield->{'Scr Field'},'table_fields'=>$fromtablefields);
        }
        else{ 
            $keyfields=array("from_table"=>'','display_field'=>'','scr_field'=>'','table_fields'=>'');
        } 

        if( !empty( $tranfield)  && $tranfield->Field_Function==5 ){ 

           $codefound= Code::where('table_name',$tranfield->Table_Name)->where('Field',$tranfield->Field_Name)->first(); 

            $autogenerate=array('prefix'=>$codefound->prefix,'code'=>$codefound->code,'suffix'=>$codefound->suffix);
        }
        else{

            $autogenerate=array('prefix'=>'','code'=>'','suffix'=>'');
        }

        if( !empty( $tranfield)  &&  ( $tranfield->Field_Function==3  ||$tranfield->Field_Function==30 )   ){ 

            
            $fromtablefields=FieldsMaster::where('Table_Name', $tranfield->From_Table )->orderby('fld_label','asc')->get()->pluck('fld_label','Field_Name');
  
            $autopopulate=array( 'from_table'=> $tranfield->From_Table ,'key_field'=>$tranfield->{'Scr Field'},'mapping_field'=>$tranfield->{'Map Field'},'from_table_fields'=> $fromtablefields);
         
        }
        else{
            $autopopulate=array('from_table'=>'' , 'key_field'=>'','mapping_field'=>'','from_table_fields'=>''); 
        } 

        $autopopulatekeyfields=FieldsMaster::where('Table_Name', $tablename)->where(function($query){
            $query->where('Field_Function',4);
            $query->orwhere('Field_Function',14); 

        })->orderby('fld_label','asc')->get()->pluck('fld_label','Field_Name');

 
       
        if( !empty( $tranfield)  && $tranfield->Field_Function==24 ){ 


            $function24scrfield=FieldsMaster::where('Field_Name',$tranfield->{'Scr Field'})->select('From_Table','Scr Field')->first();
             
 
            $fromtablefields=FieldsMaster::where('Table_Name', $function24scrfield->From_Table )->get()->pluck('fld_label','Field_Name');

 
            $autopopulatefromheader=array( 'from_table'=> $function24scrfield->From_Table ,'scr_field'=>$tranfield->{'Scr Field'},'mapping_field'=>$tranfield->{'Map Field'},'from_table_fields'=> $fromtablefields);
        

        }
        else{

            $autopopulatefromheader=array( 'from_table'=> '' ,'scr_field'=>'','mapping_field'=>'','from_table_fields'=>'');
           
        } 
 
        $autopopulatefromheaderkeyfields=FieldsMaster::where('Table_Name',$parenttable)->where(function($query){
            $query->where('Field_Function',4);
            $query->orwhere('Field_Function',14); 
        })->orderby('fld_label','asc')->get()->pluck('fld_label','Field_Name');

 
        return view("configuration.addedittranfield",compact('companyname','functions','tranid','tranfield','tablename','trantables','keyfields','autogenerate','autopopulatefromheader','autopopulatekeyfields','autopopulate','autopopulatefromheaderkeyfields' ));
 
    }


    public function submitTranField($companyname,Request $request){ 
 
 
        $tranid=$request->tranid;
 
        $tablename=TableMaster::where('Id',$tranid)->value('Table_Name');
        
        $user=Auth::user(); 
 

          $data= array (
            'Field_Name' => strtolower($request->Field_Name),
            'fld_label' => $request->fld_label,
            'Field_Type' =>  $request->Field_Type,
            'Field_Size' =>empty($request->Field_Size)?'':$request->Field_Size,
            'no_dec' => $request->no_dec,
            'Width' => $request->Width,
            'Align' =>$request->Align,
            'add_type' => $request->add_type,
            'Field_Function' =>  $request->Field_Function,
            'Field_Value' => $request->Field_Value,
            'Tab_Id' => $request->Tab_Id,
            'Tab Seq' => $request->Tab_Seq,
            'lbl_width' => '46',
            'Is Primary' => $request->Is_Primary,
            'Allow Null' => $request->Allow_Null,
            'Searchable' => $request->Searchable,
            'fld_unique' => $request->fld_unique,
            'fld_post' => $request->fld_post,
            'rd_only' => $request->rd_only,
            'mul_line' => $request->mul_line, 
            'Table_Name'=>$tablename ,
            'get_tot'=>(isset($request->get_tot)?'True':'False'),


          )  ;

 

        $tranfieldid=$request->tranfieldid;

       if( !empty($tranfieldid) ){
 
           

        $newtranfield=FieldsMaster::where('Field_Name',$tranfieldid)->where('Table_Name',$tablename)->first();
 

        $data['Field_Name']=$newtranfield->Field_Name;

        $data['Field_Type']=$newtranfield->Field_Type;

        if(trim($newtranfield->{'Allow Null'})=='True'){
            $data['Allow Null']="True";
        }

        $data['Field_Function']=$newtranfield->Field_Function;
 
       }
       else{
        $newtranfield=new FieldsMaster;
        $lastid=FieldsMaster::where('Table_Name',$tablename)->max('Id'); 
        $newid=  $lastid+1; 
        $fieldid="F".$newid."F";
        $data['Id']=$newid;
        $data['Field_Id']= $fieldid;  

        $fieldalreadyexists=FieldsMaster::where(['Table_Name'=>$tablename,'Field_Name'=>$data['Field_Name']])->exists();

        if(  $fieldalreadyexists){
            return redirect()->back()->with('error_message',$data['Field_Name']." Field Name already exists , Please enter another Field Name");
        }


       }
 
 
        if($data['Field_Function']==1){
            $data['From_Table']='';
            $data['Scr Field']='';
            $data['Display Field']='';
            $data['Map Field']='';
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 
        } 
        else  if($data['Field_Function']==4 || $data['Field_Function']==33 || $data['Field_Function']==35 ){

            $data['From_Table']=$request->keyfield_fromtable;
            $data['Scr Field']=$request->keyfield_selectfield;
            $data['Display Field']=$request->keyfield_displayfield;
            $data['Map Field']='';
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 


        }
        else  if($data['Field_Function']==5){

            $data['From_Table']='';
            $data['Scr Field']='';
            $data['Display Field']='';
            $data['Map Field']='';
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']='';  
        }
        else  if($data['Field_Function']==3 ){

            $data['From_Table']=$request->autopopulate_fromtable;
            $data['Scr Field']=$request->autopopulate_keyfield;
            $data['Display Field']='';
            $data['Map Field']=$request->autopopulate_mapfield;
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 


        }

        else if(    $data['Field_Function']==30 ){

            $data['From_Table']=$request->autopopulate_fromtable;
            $data['Scr Field']=$request->autopopulate_keyfield;
            $data['Display Field']=$request->autopopulate_mapfield;
            $data['Map Field']='';
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 

        }
        else  if($data['Field_Function']==24){

            $data['From_Table']=$request->autopopulatefromheader_fromtable;
            $data['Scr Field']=$request->autopopulatefromheader_keyfield;
            $data['Display Field']='';
            $data['Map Field']=$request->autopopulatefromheader_mapfield;
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 


        }
        else  if($data['Field_Function']==14){

            $data['From_Table']='tbl_currancy';
            $data['Scr Field']='id';
            $data['Display Field']='curr_name';
            $data['Map Field']='';
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 

        }
        else{
            $data['From_Table']='';
            $data['Scr Field']='';
            $data['Display Field']='';
            $data['Map Field']='';
            $data['Detail Table']='';
            $data['Key Field']='';
            $data['Formula Field']='';
            $data['fld_dp_kfld']='';
            $data['fld_dp_cfld']='';
            $data['ist_acc_bal']='';
            $data['lookup_flds']='';
            $data['lookup_labels']='';
            $data['view_order']='';
            $data['view_hide']='';
            $data['fld_dp_cfld2']='';
            $data['fld_dp_kfld2']='';
            $data['min_char']=''; 
        }

        DB::beginTransaction();

        try{ 

            if(!empty($tranfieldid)){
 
 
                FieldsMaster::where('Field_Name',$tranfieldid)->where('Table_Name',$tablename)->update($data);
                $msg="updated";

                $this->tablefieldservice->data=$data;

                 $this->tablefieldservice->UpdateTransactionTableField( );

                
                //   check in auto generate and update code code 
                if($data['Field_Function']==5){
                
                 $codefound= Code::where('table_name', $data['Table_Name'])->where('Field',$data['Field_Name'])->first(); 

                 $codefound->update(array('table_name'=>$data['Table_Name'],'Field'=>$data['Field_Name'],'prefix'=>$request->autogenerate_prefix,'code'=>$request->autogenerate_code,'suffix'=>$request->autogenerate_suffix));
 
                }
                
               }
               else{

               
                $data['Created_By']=trim($user->user_id);
                $newfieldadded=$newtranfield->insert($data);
                $msg="created";
               
                $this->tablefieldservice->data=$data;
                  $this->tablefieldservice->AddTransactionTableField( $data);


                //   check in auto generate and add code

                if($data['Field_Function']==5){
       

                    Code::insert(array('table_name'=>$data['Table_Name'],'Field'=>$data['Field_Name'],'prefix'=>$request->autogenerate_prefix,'code'=>$request->autogenerate_code,'suffix'=>$request->autogenerate_suffix));


                }


               } 
             
            DB::commit();
            

       return redirect('/'.$companyname.'/view-transaction-fields/'.$tranid)->with('message','Field '.$msg.' successfully');

        }
        catch (\Exception $e) {

            Log::info($e->getMessage());
            DB::rollback(); 
        }
         
    }


    public function AddTransactionTableField($data){

        

        Schema::table($data['Table_Name'], function (Blueprint $table) use($data){
  
            if($data['Field_Type']=="integer" &&      $data['Allow Null']=="False" ){
                $table->integer($data['Field_Name']);
            }
            else    if($data['Field_Type']=="integer" &&      $data['Allow Null']=="True" ){
                $table->integer($data['Field_Name'])->nullable();
            }
            else if($data['Field_Type']=="datetime"   &&      $data['Allow Null']=="False"){
                $table->datetime($data['Field_Name']);
            }
            else if($data['Field_Type']=="datetime"   &&      $data['Allow Null']=="True"){
                $table->datetime($data['Field_Name'])->nullable();
            }
            else if($data['Field_Type']=="varchar"   &&      $data['Allow Null']=="False"){ 

                DB::statement("ALTER TABLE ".$data['Table_Name']." ADD ".$data['Field_Name']." varchar(".$data['Field_Size'].") NOT NULL;");


            }
            else if($data['Field_Type']=="varchar"   &&      $data['Allow Null']=="True"){
              

                DB::statement("ALTER TABLE ".$data['Table_Name']." ADD ".$data['Field_Name']." varchar(".$data['Field_Size'].") NULL;");

            }
            else if($data['Field_Type']=="decimal"   &&      $data['Allow Null']=="False"){

                $table->decimal($data['Field_Name'],$data['Field_Size'],empty($data['no_dec'])?0:$data['no_dec']);

            }
            else if($data['Field_Type']=="decimal"   &&      $data['Allow Null']=="True"){

                $table->decimal($data['Field_Name'],$data['Field_Size'],empty($data['no_dec'])?0:$data['no_dec'])->nullable();

            }
            else if($data['Field_Type']=="nchar"   &&      $data['Allow Null']=="False"){
  
                DB::statement("ALTER TABLE ".$data['Table_Name']." ADD ".$data['Field_Name']." nchar(".$data['Field_Size'].") NOT NULL;");

            }
            else if($data['Field_Type']=="nchar"   &&      $data['Allow Null']=="True"){

                
                DB::statement("ALTER TABLE ".$data['Table_Name']." ADD ".$data['Field_Name']." nchar(".$data['Field_Size'].") NULL;");
   
            } 
 

        });  

    }


    public function UpdateTransactionTableField($data){
 
        
        Schema::table($data['Table_Name'], function (Blueprint $table) use($data){
  
              if($data['Field_Type']=="integer" &&      $data['Allow Null']=="True" ){ 
                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." int  NULL;");

            }
            else if($data['Field_Type']=="datetime"   &&      $data['Allow Null']=="True"){

                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." datetime  NULL;");
            }
            else if($data['Field_Type']=="varchar"   &&      $data['Allow Null']=="True"){
              

                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." varchar(".$data['Field_Size'].") NULL;");

            }
            else if($data['Field_Type']=="varchar"   &&      $data['Allow Null']=="False"){

                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." varchar(".$data['Field_Size'].") ;");


            }
            else if($data['Field_Type']=="decimal"   &&      $data['Allow Null']=="False"){
                
                $noofdec=empty($data['no_dec'])?2:$data['no_dec'];
                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." decimal(".$data['Field_Size'].",".$noofdec.") ;");
   
 

            }
            else if($data['Field_Type']=="decimal"   &&      $data['Allow Null']=="True"){
 

                $noofdec=empty($data['no_dec'])?2:$data['no_dec'];

                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." decimal(".$data['Field_Size'].",".$noofdec.") NULL;");
   

            }
            else if($data['Field_Type']=="nchar"   &&      $data['Allow Null']=="False"){
  
                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." nchar(".$data['Field_Size'].");");

            }
            else if($data['Field_Type']=="nchar"   &&      $data['Allow Null']=="True"){

                
                DB::statement("ALTER TABLE ".$data['Table_Name']." ALTER COLUMN ".$data['Field_Name']." nchar(".$data['Field_Size'].") NULL;");
   
            } 
        });  

    }


        public function getAutopopulateMappingFields(Request $request){
 
            $tablename=$request->tablename;
            $keyfield=$request->keyfield;
            $fromheader=$request->fromheader;

            if( $fromheader==1 && strpos( $tablename,"_det")!==false){
                $tablename=TableMaster::where('Table_Name', $tablename)->value('Parent Table');

            }
  

            $fromtable=FieldsMaster::where(['Table_Name'=> $tablename,'Field_Name'=> $keyfield ])->value('From_Table');
 

            $mappingfields=FieldsMaster::where('Table_Name',  $fromtable)->orderby('fld_label','asc')->get()->pluck('fld_label','Field_Name');
             

            return response()->json(['fromtable'=>$fromtable,'mappingfields'=>$mappingfields]); 
        }


        public function deleteTransactionFields(Request $request){
 

           $fieldids= $request->fieldids;
           $tablename=$request->tablename; 
        
           $fields=FieldsMaster::where('Table_Name',$tablename)->whereIn('Field_Name',    $fieldids)->get();

                    
                    DB::beginTransaction();

                    try{ 
                        
                    FieldsMaster::where('Table_Name',$tablename)->whereIn('Field_Name',    $fieldids)->delete();

                    foreach(   $fields as    $field){ 

                        DB::statement("ALTER TABLE ".$tablename." DROP COLUMN ".$field->Field_Name.";");

                    }

                    
                    DB::commit();
                    
                    return response()->json(['status'=>'success','message'=>'Fields Deleted successfully']);

                    }
                    catch(\Exception $e){

                        DB::rollback(); 

                        return response()->json(['status'=>'error','message'=>'Error Ocuured in Field Deletion']);

                    } 

        }
}
