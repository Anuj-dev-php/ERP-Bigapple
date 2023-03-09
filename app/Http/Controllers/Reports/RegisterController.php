<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Services\EditTranDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportExcelCsvView;
use PDF;
use Excel;
use File;
use ZipArchive;
use App\Models\TblLinkData;
use DB;
use App\Http\Controllers\Services\ReportService; 

class RegisterController extends Controller
{
    //
    protected $edittrandataservice;
    protected $reportservice;

    public function __construct(EditTranDataService $edittranservice,ReportService $reportservice){

        $this->edittrandataservice= $edittranservice;
        $this->reportservice=$reportservice;

    }

    public function registerPages($companyname, Request $request){

        $currentURL =url()->full();
        $register_name= '';
        $register_tables= array();

        if (str_contains(   $currentURL , 'creditnote-register')) { 

            $register_name= 'CreditNote Register'; 
        
        }
        else if(str_contains(   $currentURL , 'debitnote-register')){
            $register_name= 'DebitNote Register'; 

            
        }
        else if(str_contains(   $currentURL , 'journal-register')){
            $register_name= 'Journal Register'; 
 
        }
        else if(str_contains(   $currentURL , 'purchase-register')){
            $register_name= 'Purchase Register'; 
         
        }
        else if(str_contains(   $currentURL , 'sales-register')){
            $register_name= 'Sales Register'; 
        
        }


        
        $register_tables=$this->getRegisterTables( $register_name);
        // else if(str_contains(   $currentURL , 'openingbalance-register')){
        //     $register_name= 'Opening Balance Register'; 

        //     $register_tables= array('journal');

        // }
        $user_id=Auth::user()->id;
        $headerfields=array();
        $transactiondata=array();
        $tableid='';
        // Cache::forget(    $user_id."_register_report");
        $tablename='';

        $noofsearchfields=0;
        $searchfunctions=array();

        if(!empty(Cache::get(    $user_id."_register_report"))){
 
            $data_array=json_decode(Cache::get( $user_id."_register_report"),true); 
            if( $data_array['register_name']!=$register_name){
                Cache::forget( $user_id."_register_report");
            }
 
        }

        if($request->method()=='POST'){ 
 
             $tablename=$request->register_table; 
             $tableid=TableMaster::where('Table_Name', $tablename)->value('Id');
             $searchfields= (isset($request->searchfield)?$request->searchfield:array());
            $searchconditions=(isset($request->searchcondition)?$request->searchcondition:array());
            $searchval= (isset($request->searchval)?$request->searchval:array());;
            $searchoperator= (isset($request->searchoperator)?$request->searchoperator:array());;
            // ->where('Tab_Id','<>','None')
                 
            $headerfields= FieldsMaster::where('Table_Name', $tablename)->orderby('id','asc')->select('fields_master.Field_Name','fld_label','Field_Size','Field_Function')->get() ;
 
            $searchfieldfunctions=FieldsMaster::where('Table_Name', $tablename)->whereIn('Field_Name',$searchfields)->pluck('Field_Function','Field_Name')->toArray();

       
            $searchfieldfunctions=(array)$searchfieldfunctions;

            $searchfieldfunctions=array_change_key_case( $searchfieldfunctions,CASE_LOWER); 

            foreach($searchfields as $searchfield){

                array_push($searchfunctions,   $searchfieldfunctions[strtolower($searchfield)] );

            }
 
            Cache::forget(    $user_id."_register_report");
            $this->edittrandataservice->tran_table=   $tablename;
            $this->edittrandataservice->searchfields=$searchfields;
            $this->edittrandataservice->searchvalues=$searchval;
            $this->edittrandataservice->searchfunctions=$searchfunctions;
            $fielddisplayarray=$this->edittrandataservice->getSearchFieldDisplayValues(); 

            $searchfieldarray=array("searchfield"=>$searchfields,"searchcondition"=> $searchconditions,"searchval"=>$searchval,"searchoperator"=> $searchoperator,'searchfunction'=> $searchfunctions,'displayvalues'=> $fielddisplayarray);

            $searchfieldarraystring=json_encode($searchfieldarray);

            // $this->edittrandataservice->exceldownload=true; 
            $noofsearchfields=count($searchfields);

            $transactiondata=$this->edittrandataservice->searchTransactionDataTable( $searchfields,$searchconditions, $searchval,  $searchoperator);
            
             $data_array=array( 'register_name'=>    $register_name,'register_table'=>  $tablename  ,'search_fields'=>$searchfieldarraystring,'tablename'=>$tablename,'tableid'=>$tableid);
 
             Cache::put(    $user_id."_register_report",json_encode(   $data_array));
 
        }
        else if(!empty(Cache::get(    $user_id."_register_report"))){

            $data_array=json_decode(Cache::get( $user_id."_register_report"),true);

            $searchfields_data= json_decode($data_array['search_fields'],true);
 
            $searchfields=    $searchfields_data['searchfield'];

            $noofsearchfields=count($searchfields);
            $searchconditions=    $searchfields_data['searchcondition'];
            $searchval=   $searchfields_data['searchval'];
            $searchoperator=$searchfields_data['searchoperator']; 
            $tablename=$data_array['tablename'];
            $tableid=$data_array['tableid'];
            // ->where('Tab_Id','<>','None')
             $headerfields= FieldsMaster::where('Table_Name', $tablename)->orderby('id','asc')->select('fields_master.Field_Name','fld_label','Field_Size','Field_Function')->get() ;
           
            $fielddisplayarray=     $searchfields_data['displayvalues']; 
            $searchfunctions=  $searchfields_data['searchfunction'];
            $this->edittrandataservice->tran_table=   $tablename;
            $this->edittrandataservice->searchfields=$searchfields;
            $this->edittrandataservice->searchvalues=$searchval;
            $this->edittrandataservice->searchfunctions=$searchfunctions; 
 
            $transactiondata=$this->edittrandataservice->searchTransactionDataTable( $searchfields,$searchconditions, $searchval,  $searchoperator);
          
 
        }
 
 
        return view('reports.register_reports',compact( 'register_name','register_tables','companyname','headerfields','transactiondata','tablename','tableid','noofsearchfields','searchfunctions','currentURL'));
  

    }


    public function getTransactionTableFields($companyname,$tablename){

        $table_fields=  FieldsMaster::where('Table_Name',$tablename)->orderby('fld_label','asc')->select('fld_label' ,'Field_Name','Field_Function')->get();

        return response()->json(['fields'=>  $table_fields]);

    }

    public function addAnotherRegisterReportSearchField($companyname, $tablename,$noofsearch){

    

        $fields=  FieldsMaster::where('Table_Name',$tablename)->orderby('fld_label','asc')->select('fld_label' ,'Field_Name','Field_Function')->get();
 
        $html=view('reports.register_new_row',compact('fields','noofsearch'))->render();
 
    
        return response()->json(['html'=>$html]);

    }


    public function getRegisterReportSearchFields($companyname){

        $user_id=Auth::user()->id;

        if(!empty(Cache::get(    $user_id."_register_report"))){

            $register_report_json=json_decode(Cache::get(    $user_id."_register_report"),true); 

             $search_fields_data= $register_report_json['search_fields'] ; 

             $search_fields_data=json_decode($search_fields_data,true); 
 
             $searchfields= $search_fields_data['searchfield'];
  
             $response_array= array();

             $index=0;

             foreach( $searchfields as $searchfield){
  
                 array_push($response_array,array('searchfield'=>$searchfield,
                 "searchcondition"=>$search_fields_data['searchcondition'][$index],
                 "searchval"=> $search_fields_data['searchval'][$index],
                 "searchoperator"=>$search_fields_data['searchoperator'],
                 "searchfunction"=>$search_fields_data['searchfunction'][$index],
                 "displayvalue"=>$search_fields_data['displayvalues'][$index], 
              ));
  
              $index++;
  
             }   
        }
        else{
            $response_array= array();
        }
  
        
        return response()->json(  $response_array);
    }

    public function resetRegisterReportDataSearch(){

        $user_id=Auth::user()->id;

        Cache::forget(   $user_id."_register_report");

        return redirect()->back();

    }


    public function createRegisterFileForDownload($register_table,$register_report_array,$format,$random_name,$download_file_name){
        $user_id=Auth::user()->id;
        $register_name=   $register_report_array['register_name'];
        // $register_table=$register_report_array['register_table'];
         $search_fields=$register_report_array['search_fields'];
         $table_id=$register_report_array['tableid']; 
 
                       
         $headerfields= FieldsMaster::where('Table_Name',  $register_table)->orderby('id','asc')->select('fields_master.Field_Name','fld_label','Field_Size','Field_Function')->get() ;
  
            $searchfields_data= json_decode(  $search_fields,true);

            $searchfields=    $searchfields_data['searchfield'];

            $noofsearchfields=count($searchfields);
            $searchconditions=    $searchfields_data['searchcondition'];
            $searchval=   $searchfields_data['searchval'];
            $searchoperator=$searchfields_data['searchoperator'];  
  
            $fielddisplayarray=     $searchfields_data['displayvalues']; 
            $searchfunctions=  $searchfields_data['searchfunction'];
            $this->edittrandataservice->tran_table=   $register_table;
            $this->edittrandataservice->searchfields=$searchfields;
            $this->edittrandataservice->searchvalues=$searchval;
            $this->edittrandataservice->searchfunctions=$searchfunctions;  

            $this->edittrandataservice->exceldownload=true;

            $transactiondata=$this->edittrandataservice->searchTransactionDataTable( $searchfields,$searchconditions, $searchval,  $searchoperator);
  
            $data_array=array('register_name'=>$register_name,'register_table'=>$register_table,'table_id'=>$table_id,'headerfields'=>$headerfields,'transactiondata'=>$transactiondata);
 

            // return view('reports.downloadformats.register_report_format',  $data_array);

            if(strtolower($format)=="pdf"){
           
                $pdf = PDF::loadView('reports.downloadformats.register_report_format',   $data_array)->setPaper('a2')->setOrientation('landscape');
                $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
 
              }
              else{
                // in case of xlsx or csv run below
    
    
                   Excel::store(new ExportExcelCsvView( 'reports.downloadformats.register_report_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
    
    
              }


              return   array('random_name'=>$random_name ,'download_name'=>  $download_file_name);

    }


    public function downloadRegisterReport($companyname,$format="xlsx"){

        $user_id=Auth::user()->id;

        $register_report_json=   Cache::get(   $user_id."_register_report");
        $register_report_array=json_decode($register_report_json,true); 

        $register_table=$register_report_array['register_table'];

        $random_name=time()."-".$user_id.".".strtolower($format);

        $download_file_name=strtolower(   $register_report_array['register_name']);

        $download_file_name=str_replace(" ","_", $download_file_name);
        $download_file_name= $download_file_name.".".strtolower($format);  


        $download_result=$this->createRegisterFileForDownload(   $register_table , $register_report_array,$format,  $random_name, $download_file_name);


          return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);
  
    }


    public function downloadAllRegisterReport($companyname,$format='xlsx'){

        $user_id=Auth::user()->id;

        $register_report_json=   Cache::get(   $user_id."_register_report");
        $register_report_array=json_decode($register_report_json,true);

        $random_name=time()."-".$user_id.".".strtolower($format);

        $download_file_name=strtolower(   $register_report_array['register_name']);

        $register_table=$register_report_array['register_table'];
 

        $download_file_name=str_replace(" ","_", $download_file_name);

        $download_file_name= $download_file_name."_all" ;   

        $register_report_array['search_fields']=json_encode(array("searchfield"=>array(),"searchcondition"=>array(),"searchval"=>array(),"searchoperator"=>array(),'searchfunction'=>array(),'displayvalues'=>array())) ;

        $zipfolder=time()."-".  $user_id;
 

         File::makeDirectory(storage_path('app/public/download_reports/'.$zipfolder));  

         $register_table_array=  $this->getRegisterTables($register_report_array['register_name']);

         foreach( $register_table_array as $register_table=>$register_table_lbl){
            $download_result=$this->createRegisterFileForDownload($register_table,$register_report_array,$format,   $zipfolder."/".$register_table.".".strtolower($format), $download_file_name);
 
         }

     
        $zip = new ZipArchive;



        if ($zip->open(storage_path('app/public/download_reports/'.$zipfolder.".zip"), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(storage_path('app/public/download_reports/'.$zipfolder) );
        
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
            
            $zip->close();
        }

         
        return response()->download(storage_path('app/public/download_reports/'. $zipfolder.".zip"),  $download_file_name.".zip");

    }


    public function getRegisterTables($register_name){

        if($register_name=="CreditNote Register"){
            
            $register_tables=  TableMaster::where('txn_class','Like','sales returns')->where('tab_id','LIKE','header')->pluck('table_label','Table_Name')->toArray();
  
        }
        else    if($register_name=="DebitNote Register"){

            $register_tables=  TableMaster::where('txn_class','Like','Purchase returns')->where('tab_id','LIKE','header')->pluck('table_label','Table_Name')->toArray();
   
        }
        else    if($register_name=="Journal Register"){

            $register_tables= TableMaster::where('Table_Name','journal')->pluck('table_label','Table_Name')->toArray();;
 
        }
        else    if($register_name=="Purchase Register"){
            $register_tables=  TableMaster::where('txn_class','Like','Purchase Invoice')->where('tab_id','LIKE','header')->pluck('table_label','Table_Name')->toArray();
   
        }
        else    if($register_name=="Sales Register"){
            $register_tables=  TableMaster::where('txn_class','Like','Sales Invoice')->where('tab_id','LIKE','header')->pluck('table_label','Table_Name')->toArray();
   
        }


        return         $register_tables;
 
    }


    public function downloadSsrsReport(){ 

        $ssrs = new \SSRS\Report('http://server/reportserver/', array('username' => 'thomas', 'password' => 'secureme'));
        $ssrs->listChildren('/Report Folder');


    }


    public function pendingDocuments($companyname,Request $request){
        $user_id=Auth::user()->id;
        $currentURL =url()->full();
        $noofsearchfields=0;

        $headerfields=array('id','doc_date','doc_no','location','cust_id','name','product','qty','rate','used_qty','Bal Qty','Ageing Days');

        $transactiondata_array=array();

        $searchfields=array();
        $searchconditions=array();
        $searchvals=array();
        $searchoperator=''; 

        if($request->method()=='POST'){ 

            $searchfields=(isset($request->searchfield)?$request->searchfield:array());
            $searchconditions=(isset($request->searchcondition)?$request->searchcondition:array());
            $searchvals=(isset($request->searchval)?$request->searchval:array());
            $searchoperator=(isset($request->searchoperator)?$request->searchoperator:array());
            $noofsearchfields=count(  $searchfields);

            $hassearchfields=(count($searchfields)>0?true:false); 

            Cache::forget($user_id."_pending_documents_input");  

            $transactiondata_array= $this->reportservice->getPendingDocumentsData($searchfields,$searchconditions,$searchvals,$searchoperator);
  
            $pending_documents_inputs=array('searchfields'=>$searchfields,'searchconditions'=>$searchconditions,'searchvals'=>$searchvals,'searchoperator'=>$searchoperator,'transactiondata'=>$transactiondata_array);
            
            Cache::put($user_id."_pending_documents_input",$pending_documents_inputs);    

        }
        else if(!empty(Cache::get($user_id."_pending_documents_input"))){

            $pending_documents_input_array= Cache::get($user_id."_pending_documents_input");

            $searchfields= $pending_documents_input_array['searchfields'];

            $noofsearchfields=count(  $searchfields);

            $searchconditions=$pending_documents_input_array['searchconditions'];

            $searchvals=$pending_documents_input_array['searchvals'];

            $searchoperator=$pending_documents_input_array['searchoperator'];

            $transactiondata_array=$pending_documents_input_array['transactiondata'];
 

        }
 
       $transactiondata_collection = collect($transactiondata_array); 
      

       $transactiondata_collection= $this->reportservice->paginate(['company_name'=>$companyname],'company.pending-documents',  $transactiondata_collection, 10 );
      

        return view('reports.pending_documents',compact('currentURL','noofsearchfields','companyname','headerfields','transactiondata_collection','searchfields','searchconditions','searchvals','searchoperator'));

    }

    public function addAnotherPendingDocumentReportSearchField($companyname,$noofsearchfield){

        $headerfields=array('id','doc_date','doc_no','location','cust_id','name','product','qty','rate','used_qty','Bal Qty','Ageing Days');

       $html=view('reports.pending_document_new_row',compact('headerfields','noofsearchfield'))->render();

       
       return response()->json(['html'=>$html]);


    }

    public function resetPendingDocumentsDataSearch(Request $request){

        $user_id=Auth::user()->id;
        Cache::forget($user_id."_pending_documents_input");  

        return redirect()->back()->with('message','Pending Documents reset successfully');

    }

    public function downloadPendingDocuments($companyname,$format="xlsx"){

        $user_id=Auth::user()->id;
        $headerfields=array('id','doc_date','doc_no','location','cust_id','name','product','qty','rate','used_qty','Bal Qty','Ageing Days');

        $pending_documents_array=  Cache::get($user_id."_pending_documents_input");  

        $transaction_data= $pending_documents_array['transactiondata']; 
        
        $download_file_name=strtolower("Pending Documents");

        $download_file_name=str_replace(" ","_", $download_file_name);

        $download_file_name= $download_file_name.".".strtolower($format);   

        //  return view('reports.downloadformats.pending_documents_format',compact('headerfields','transaction_data'));
   
        $random_name=time()."-".$user_id.".".strtolower($format);

        
        $data_array=array('headerfields'=>$headerfields,'transaction_data'=>$transaction_data);

          
        if(strtolower($format)=="pdf"){
           
            $pdf = PDF::loadView('reports.downloadformats.pending_documents_format',   $data_array)->setPaper('a3')->setOrientation('landscape');
            $pdf->save(storage_path('app/public/download_reports')."/". $random_name);

          }
          else{
            // in case of xlsx or csv run below 
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.pending_documents_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
 
          } 
 
 
        // $random_name= $this->createFilePendingDocumentsForDownload($format, $headerfields,   $transaction_data); 
        
        return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);
  
 
        // return view('reports.downloadformats.pending_documents_format',compact('headerfields','transaction_data'));
    }



    public function createFilePendingDocumentsForDownload($format,$headerfields, $transaction_data){

        $user_id=Auth::user()->id;
        $random_name=time()."-".$user_id.".".strtolower($format);
 

        $data_array=array('headerfields'=>$headerfields,'transaction_data'=>$transaction_data);

        
         return view('reports.downloadformats.pending_documents_format',compact('headerfields','transaction_data'));
  

     
        if(strtolower($format)=="pdf"){
           
            $pdf = PDF::loadView('reports.downloadformats.pending_documents_format',   $data_array)->setPaper('a3')->setOrientation('landscape');
            $pdf->save(storage_path('app/public/download_reports')."/". $random_name);

          }
          else{
            // in case of xlsx or csv run below 
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.pending_documents_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
 
          } 

        return    $random_name;

    }


    public function salesmanReport($companyname,Request $request){

        $user_id=Auth::user()->id;
 
     $fieldnames=   FieldsMaster::where('Field_Function',4)->orderby('Field_Name','asc')->select(DB::raw('Field_Name as fields'))->pluck('fields','fields')->toArray();
    
     $fieldnames=array_change_key_case($fieldnames,CASE_LOWER);

     $fieldnames=array_keys($fieldnames); 

     $searchfields=array();

     $noofsearchfields=0;

     $searchconditions=array();

     $searchvals=array();

     $searchoperator='';

     $field_selection='';

     $fieldvalue_selection='';

     $fieldvalue_table='';
     
     $headerfields=array();

     $all_fieldvalues=array();

     $fieldvalue_selection_text='';

     $tabledata=array();

     $detail_tablename=''; 
     $table_fields=array();

     if($request->method()=='POST'){ 

        $field_selection=$request->fields_selection; 

        $fieldvalue_selection=$request->fieldvalue_selection;
        
        $fieldvalue_table=$request->fieldvalue_tables; 

        $this->reportservice->tablename=$fieldvalue_table;
   
        $this->reportservice->fieldname=$field_selection;
        $this->reportservice->fieldvalue=   $fieldvalue_selection;

       $table_fields= FieldsMaster::where('Table_Name',$fieldvalue_table)->orderby('fld_label','asc')->pluck('fld_label','Field_Name')->toArray();
         

       $detail_tablename=TableMaster::where('Parent Table',$fieldvalue_table)->value('Table_Name');

        $fieldvalue_selection_text=  $this->reportservice->getSearchedFunction4TableFieldValueText();
        $headerfields= $this->reportservice->getSalesmanDetailHeaderFields();

        $searchfields=(isset($request->searchfield)?$request->searchfield:array());

        $noofsearchfields=count( $searchfields);

        $searchconditions=(isset($request->searchcondition)?$request->searchcondition:array());

        $searchvals=(isset($request->searchval)?$request->searchval:array());

        $searchoperator=(isset($request->searchoperator)?$request->searchoperator:'');

        $this->reportservice->fieldname=   $field_selection;
        
        $this->reportservice->fieldvalue=    $fieldvalue_selection;

       $field_tables= $this->reportservice->getSearchedFunction4TableUsingValue();

        $salesman_report_input= array('field_selection'=>$field_selection,'fieldvalue_selection'=>$fieldvalue_selection,'fieldvalue_table'=> $fieldvalue_table,'headerfields'=> $headerfields ,
        'searchfields'=>$searchfields,'searchconditions'=>$searchconditions,'searchvals'=>$searchvals,'searchoperator'=>$searchoperator,'detail_tablename'=>   $detail_tablename,'fieldvalue_selection_text'=>     $fieldvalue_selection_text ,'all_tables'=>$field_tables
        );


        Cache::forget($user_id."_salesman_report");
 
        Cache::put($user_id."_salesman_report",  $salesman_report_input);

        $tabledata_query=$this->reportservice->getSearchedTableDataFromFieldQuery($searchfields, $searchconditions, $searchvals,    $searchoperator);
        $tabledata=  $tabledata_query->paginate(10);
     }
     else if(!empty( Cache::get($user_id."_salesman_report"))) { 
 
        $salesman_report_input= Cache::get($user_id."_salesman_report");


        $field_selection= $salesman_report_input['field_selection'];

        $fieldvalue_selection= $salesman_report_input['fieldvalue_selection'];

        $fieldvalue_table= $salesman_report_input['fieldvalue_table'];

        $headerfields =$salesman_report_input['headerfields'];

        $searchfields=$salesman_report_input['searchfields'];

        $searchconditions=$salesman_report_input['searchconditions'];

        $searchvals=$salesman_report_input['searchvals'];

        $searchoperator=$salesman_report_input['searchoperator'];

        $detail_tablename=$salesman_report_input['detail_tablename'];  

        $fieldvalue_selection_text=$salesman_report_input['fieldvalue_selection_text'];  
        $this->reportservice->tablename=$fieldvalue_table;
   
        $table_fields= FieldsMaster::where('Table_Name',$fieldvalue_table)->orderby('fld_label','asc')->pluck('fld_label','Field_Name')->toArray();
         

        $this->reportservice->fieldname=$field_selection;
        $this->reportservice->fieldvalue=   $fieldvalue_selection; 
        $tabledata_query=$this->reportservice->getSearchedTableDataFromFieldQuery($searchfields, $searchconditions, $searchvals,    $searchoperator);
        $tabledata=  $tabledata_query->paginate(10);
 

     }
 
     
        return view('reports.salesman_reports',compact('companyname','fieldnames','field_selection','fieldvalue_selection','fieldvalue_table','searchfields','searchconditions','searchvals','searchoperator','noofsearchfields','headerfields','fieldvalue_selection_text','tabledata','detail_tablename','table_fields'));
    }


    public function getFieldValuesFromFieldNameSearched($companyname,Request $request){

        $fieldname=$request->data['field_name']; 

        $search=$request->searchTerm;
 

        $this->reportservice->searchterm=  $search;

        $this->reportservice->fieldname=$fieldname;
        
        $options=$this->reportservice->getSearchedFunction4TableFieldDetails(); 
 
        return response()->json( $options);

    }


    public function getTableNamesFromSelectedFieldValueForReport($companyname,Request $request){

        // Log::info($request->all());
        // 'field_name' => 'salesman',
        // 'field_value' => '33',

        $fieldname=$request->field_name;

        $fieldvalue=$request->field_value;
        
        $this->reportservice->fieldname=$fieldname;
        
        $this->reportservice->fieldvalue= $fieldvalue;

       $field_tables= $this->reportservice->getSearchedFunction4TableUsingValue();
 
       return response()->json($field_tables);

    }

    public function resetSalesmanReportDataSearch($companyname,Request $request){

        $user_id=Auth::user()->id;
 
        Cache::forget($user_id."_salesman_report");

        return redirect()->back()->with('message','Salesman Report Reset Successfully');

    }

    public function downloadSalesmanReport($companyname,$format="xlsx"){

        
        $user_id=Auth::user()->id; 
       $salesman_report_input= Cache::get($user_id."_salesman_report"); 

        $download_file_name=strtolower("Salesman Report"); 
        $download_file_name=str_replace(" ","_", $download_file_name);
        $download_file_name= $download_file_name.".".strtolower($format); 
        $random_name=time()."-".$user_id.".".strtolower($format);

         $random_name= $this->createSalesmanReportForDownload(  $salesman_report_input,     $random_name,$format);
   
         return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);

 
    }


    public function createSalesmanReportForDownload($salesman_report_input,$random_name,$format){
  
       $field_selection= $salesman_report_input['field_selection'];

       $fieldvalue_selection= $salesman_report_input['fieldvalue_selection'];

       $fieldvalue_table= $salesman_report_input['fieldvalue_table'];

       $headerfields =$salesman_report_input['headerfields'];

       $searchfields=$salesman_report_input['searchfields'];

       $searchconditions=$salesman_report_input['searchconditions'];

       $searchvals=$salesman_report_input['searchvals'];

       $searchoperator=$salesman_report_input['searchoperator'];

       $detail_tablename=$salesman_report_input['detail_tablename'];  

       $fieldvalue_selection_text=$salesman_report_input['fieldvalue_selection_text'];  
       $this->reportservice->tablename=$fieldvalue_table;
  
       $table_fields= FieldsMaster::where('Table_Name',$fieldvalue_table)->orderby('fld_label','asc')->pluck('fld_label','Field_Name')->toArray();
        

       $this->reportservice->fieldname=$field_selection;
       $this->reportservice->fieldvalue=   $fieldvalue_selection; 
       $tabledata_query=$this->reportservice->getSearchedTableDataFromFieldQuery($searchfields, $searchconditions, $searchvals,    $searchoperator);
       $tabledata=  $tabledata_query->get(); 

       $data_array=array('field_selection'=>$field_selection,'fieldvalue_selection_text'=>$fieldvalue_selection_text,'fieldvalue_table'=>$fieldvalue_table,'detail_tablename'=>$detail_tablename ,'headerfields'=>$headerfields ,'tabledata'=>$tabledata);
       
       
       if(strtolower($format)=="pdf"){
           
        $pdf = PDF::loadView('reports.downloadformats.salesman_report_format',   $data_array)->setPaper('a2')->setOrientation('landscape');
        $pdf->save(storage_path('app/public/download_reports')."/". $random_name);

      }
      else{
        // in case of xlsx or csv run below
           Excel::store(new ExportExcelCsvView( 'reports.downloadformats.salesman_report_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
      }

 
       return  $random_name;



    }


    public function downloadAllSalesmanReport($companyname,$format="xlsx"){
 
        $user_id=Auth::user()->id; 
       $salesman_report_input= Cache::get($user_id."_salesman_report");  
       $download_file_name="salesman_report";

        $zipfolder=time()."-".  $user_id; 

        File::makeDirectory(storage_path('app/public/download_reports/'.$zipfolder));  

        
       $this->reportservice->fieldname=  $salesman_report_input['field_selection'];
       $this->reportservice->fieldvalue=  $salesman_report_input['fieldvalue_selection'];

         
        $all_tables=  $salesman_report_input['all_tables']; 

        $salesman_report_input['searchfields']=array();

        foreach( $all_tables as  $all_table){

            $this->reportservice->tablename=$all_table;

            $tabledata_query=$this->reportservice->getSearchedTableDataFromFieldQuery(array(),array(), array(),array()); 
            $tabledata=  $tabledata_query->get(); 
            $salesman_report_input['tabledata']=    $tabledata;

            $salesman_report_input['fieldvalue_table']= $all_table;
            

            $salesman_report_input['headerfields']= $this->reportservice->getSalesmanDetailHeaderFields();


             $random_file_name=$this->createSalesmanReportForDownload($salesman_report_input, $zipfolder."/".$all_table.".".strtolower($format),$format);
   
        }


        
        $zip = new ZipArchive; 

        if ($zip->open(storage_path('app/public/download_reports/'.$zipfolder.".zip"), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(storage_path('app/public/download_reports/'.$zipfolder) );
        
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
            
            $zip->close();
        }

         
        return response()->download(storage_path('app/public/download_reports/'. $zipfolder.".zip"),  $download_file_name.".zip");


 
    }
}
