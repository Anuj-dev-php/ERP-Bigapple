<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Controllers\Services\ReportService;
use App\Models\Customer;
use App\Models\ProductMaster;
use Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Services\Function4FilterService;
use App\Models\TableMaster;
use App\Models\StockDet;
use App\Models\Company;
use Illuminate\Support\Facades\Log;
use App\Models\userProducts;
use App\Models\TblRoleStockRateRestriction;
use Session;
use BlueM\Tree as Tree;
use PDF;
use Excel;
use File;
use ZipArchive;
use App\Exports\ExportExcelCsvView; 
use App\Helper\Helper; 
use App\Http\Controllers\Services\EmailTranDataService;
use App\Http\Controllers\Services\WhatsappService;
use App\Models\FieldsMaster;
use App\Models\StockReorder;

class StockRegisterController extends Controller
{
     protected $reportservice;
     protected $function4filterservice;
     protected $emailtrandataservice;
     protected $whatsappservice;

     public function __construct(ReportService $rservice,Function4FilterService $function4filter,EmailTranDataService $emaildataservice,WhatsappService $whatsappservice){
          $this->reportservice=$rservice;
          $this->function4filterservice= $function4filter;
          $this->emailtrandataservice=$emaildataservice;
          $this->whatsappservice= $whatsappservice;

     }

     public function openStockLedger($companyname,Request $request){
 
          $user=Auth::user();
          $user_id=   $user->id; 
 
          $start_date_string="";
          $end_date_string="";
          $product="";
          $location="";
          $all_customers=array(); 
          $valuation_method="";
          $stk_formulas=array(); 

          $show_rate=false;
          $show_specrate=false;
          $purchase_invoice_txn_ids=array(); 
          $sales_invoice_txn_ids=array(); 
        
        $startEndDate = Company::getStartEndDate($companyname);

        
        $start_date_string= formatDateInDmy(  $startEndDate->fs_date);   

        $end_date_string=formatDateInDmy(  $startEndDate->fe_date);  
        
        $stock_detail_amounts=array();

        $user_id=Auth::user()->id;

        $userproduct_ids=UserProducts::where('uid',     $user_id)->pluck('prd_grp')->toArray();
 
 
        if(   count($userproduct_ids) ==0){

          $all_products= ProductMaster::select('Product as title','id','parent')->get()->toArray();
               
          $tree=new Tree(    $all_products);

          $rootproducts=     $tree->getRootNodes();
        }
        else{

          $rootproducts=    ProductMaster::whereIn('id', $userproduct_ids)->select('Product as title','id','parent')->get()->toArray();

        }
   

        $products=ProductMaster::where('parent',0)->pluck('Product','Id')->toArray(); 

        $role_id=Session::get('role_id');

        $role_stockrate_restriction= TblRoleStockRateRestriction::where('role_id', $role_id)->select('rate','spec_rate')->first();

        if(empty( $role_stockrate_restriction)){
          $show_chk_rate=false;
          $show_chk_specrate=false;
        }
        else{
          $show_chk_rate=($role_stockrate_restriction->rate==1?true:false);
          $show_chk_specrate=($role_stockrate_restriction->spec_rate==1?true:false);

        }

        $selected_products=array();
        $products_data=array(); 
 

          if($request->method()=="POST"){

               
               Cache::forget($user_id."_stock_ledger");

              $show_rate=  isset($request->rate)?true:false;

              $show_specrate= isset($request->spec_rate)?true:false;

               $start_date_string=$request->start_date;

               $start_date= formatDateInYmd( $start_date_string) ;

               $end_date_string=$request->end_date;

               $end_date= formatDateInYmd( $end_date_string);

               $product=$request->product;

               $location=$request->location;

               $valuation_method=  $request->valuation_method;

               $selected_products=(isset($request->selected_products)?$request->selected_products:array());

                $this->reportservice->setProductTreeData(); 

                    $this->reportservice->parent_product_ids=  $selected_products;

                    $found_product_ids=$this->reportservice->getAllProductWithChildIds();
            
     
               $this->reportservice->product_ids=  $found_product_ids;
               $this->reportservice->setProductNodes();
               $selected_products=  $this->reportservice->getProductsSequentially();
 
               $this->reportservice->start_date=   $start_date;
               $this->reportservice->end_date=        $end_date;
               $this->reportservice->location_id= $location;

               $this->reportservice->valuation_method= $valuation_method;

               
             
               // $stk_formulas= $this->reportservice->getAllStkFormulaStringWithTables();

               // 'stk_formulas'=>   $stk_formulas
               
                
          $all_customers=Customer::orderby('cust_id','asc')->pluck('cust_id','Id')->toArray(); 

               $stock_ledger_inputs=array('start_date'=>$start_date_string,
               'end_date'=>$end_date_string, 
               'location_id'=>$location,
               'show_rate'=>$show_rate,
               'show_specrate'=>$show_specrate , 
               'valuation_method'=>$valuation_method ,
               'products_data'=>array(),
               'selected_products'=>$selected_products ,
               'all_customers'=>  $all_customers
          );

          // 'opening_stock'=>  $openingstock,'closing_stock'=> $closingstock,'stock_details'=> $stock_details,
          // ,,'sales_invoice_txn_ids'=>$sales_invoice_txn_ids,,'opening_rate'=>$opening_rate,'stock_detail_amounts'=>$stock_detail_amounts);

         $this->reportservice->getAllStkFormulaStringWithTables();
 

               foreach(    $selected_products as     $selected_product){

                    $this->reportservice->product_id= $selected_product;
                    
                    $openingstock=   $this->reportservice->getOpeningProductStock();
                    
                   $opening_start_stock=  $this->reportservice->getStartOpeningProductStock();

                   $opening_rate=  StockDet::where('Prodid', $selected_product)->where('docno','LIKE','op-%')->value('CRate');
  

                   $closingstock=   $this->reportservice->getClosingProductStock();

                   if( $closingstock<0){
                    continue;
                   }
                   
         
                   $stock_details= $this->reportservice->getProductStockDetails(    $opening_start_stock);

                   

                    $txn_ids=array_column(       $stock_details,'Txn_Name'); 

                    $txn_ids=array_unique( $txn_ids); 
     
                    $purchase_invoice_txn_ids= TableMaster::where('txn_class','Purchase Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();
                   
                    $product_name=ProductMaster::where('Id',$selected_product)->value('Product');

                    $sales_invoice_txn_ids= TableMaster::where('txn_class','Sales Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();
               
                    $stock_detail_amounts= $this->reportservice->getProductStockDetailAmounts( $valuation_method,    $opening_start_stock ,  $opening_rate  ,$stock_details, $purchase_invoice_txn_ids,  $sales_invoice_txn_ids);
                                   
                    $stock_ledger_inputs['products_data'][$selected_product]=array(
                                        'opening_stock'=>   $openingstock ,
                                        'opening_start_stock'=> $opening_start_stock,
                                        'opening_rate'=>   $opening_rate ,
                                        'closing_stock'=> $closingstock ,
                                        'stock_details'=>$stock_details ,
                                        'stock_detail_amounts'=>  $stock_detail_amounts ,
                                        'product_name'=>  $product_name ,                 
                                        'purchase_invoice_txn_ids'=>$purchase_invoice_txn_ids  ,
                                        'sales_invoice_txn_ids'=> $sales_invoice_txn_ids 
                              );

               }


               $products_data=   $stock_ledger_inputs['products_data'];
 

               Cache::put($user_id."_stock_ledger",   $stock_ledger_inputs);
 
          }
          else if(!empty( Cache::get($user_id."_stock_ledger"))){
 

               $stock_ledger_inputs=Cache::get($user_id."_stock_ledger");

               $selected_products=  $stock_ledger_inputs['selected_products'];

               $start_date_string=$stock_ledger_inputs['start_date'];

               $end_date_string=$stock_ledger_inputs['end_date'];
 
               $location=$stock_ledger_inputs['location_id']; 

               $products_data=$stock_ledger_inputs['products_data']; 
  
               $show_rate=$stock_ledger_inputs['show_rate']; 

               $show_specrate=$stock_ledger_inputs['show_specrate'];  
          
               $valuation_method=$stock_ledger_inputs['valuation_method']; 
               
               $selected_products=$stock_ledger_inputs['selected_products'];  

               $products_data=$stock_ledger_inputs['products_data'];  

               $all_customers=$stock_ledger_inputs['all_customers'];  

          }

          $selected_products_collection = collect(    $selected_products); 

          $selected_products_collection= $this->reportservice->paginate(['company_name'=>$companyname],'company.stock-ledger',   $selected_products_collection , 5);
    
      
          $this->function4filterservice->setUserAndFromTable($user,"Location");
       
          $filteredids=$this->function4filterservice->getUserDataRestrictionIds();
          $haslocations=(count($filteredids)>0?true:false);

          $locations=   Location::orderby('location','asc')->when($haslocations==true,function($query)use($filteredids){
               $query->whereIn('Id',$filteredids);
          })->pluck('location','Id')->toArray();
 
 
      return view('reports.stock-ledger',compact('companyname','locations' ,'start_date_string','end_date_string','product','location','all_customers' ,'stk_formulas','show_rate','show_specrate','haslocations','purchase_invoice_txn_ids','sales_invoice_txn_ids','valuation_method' ,'stock_detail_amounts','products','show_rate','show_specrate','show_chk_rate','show_chk_specrate','selected_products_collection','products_data','rootproducts','userproduct_ids','selected_products' ));
     }


     public function resetStockLedger($companyname){
          
          $user_id=Auth::user()->id;

          Cache::forget($user_id."_stock_ledger");

          return redirect()->back()->with('message','Stock Ledger reset successfully');

     }


     public function stockLedgerFifo(){

          $opening_qty=0;

          $this->reportservice->start_date="2022-04-01";
          $this->reportservice->end_date="2022-08-01";
          $this->reportservice->product_id=990;
          
          $this->reportservice->getAllStkFormulaStringWithTables();

          $stock_details=   array (
              
               0 => 
               array (
                'docno'=>'',
                'Qty' => 10,
                'Txn_Name' => 380, 
                'CRate' => 10,
                'spec_rate' => 11,
                'balance_qty' => 20,
               ),

               1=> 
               array (
                'docno'=>'',
                'Qty' =>-30,
                'Txn_Name' => 647, 
                'CRate' =>1,
                'spec_rate' => '',
                'balance_qty' =>-10,
               ) 
               ,

               2=> 
               array (
                'docno'=>'',
                'Qty' =>-50,
                'Txn_Name' => 647, 
                'CRate' =>1,
                'spec_rate' => '',
                'balance_qty' => -60,
               ) 
               ,

              3=> 
               array (
                'docno'=>'',
                'Qty' =>58,
                'Txn_Name' =>380, 
                'CRate' =>1,
                'spec_rate' => 12,
                'balance_qty' => -2,
               ) 

              //  ,

              // 4=> 
              //   array (
              //    'docno'=>'',
              //    'Qty' =>-6,
              //    'Txn_Name' =>647, 
              //    'CRate' =>1,
              //    'spec_rate' => '',
              //    'balance_qty' =>8,
              //   ) 
              
             )  
             
              ;

    
          $txn_ids=array_column(       $stock_details,'Txn_Name'); 

          $txn_ids=array_unique( $txn_ids); 

          $purchase_invoice_txn_ids= TableMaster::where('txn_class','Purchase Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();



        $stock_detail_amounts= $this->reportservice->getProductStockDetailAmounts("Lifo", 10,10, $stock_details, $purchase_invoice_txn_ids); 


        dd(  $stock_detail_amounts);

 

     }


     public function getChildProducts($companyname,$parentid){

          $products=ProductMaster::getChildProducts($parentid);

          return response()->json(['products'=> $products]);
     }

     public function downloadStockLedger($companyname,$format="xlsx"){

          $download_file_name="stock_ledger.".strtolower($format);

          $data_array=$this->getDataInputForReport(); 

          // return view("reports.downloadformats.stock_ledger_format",$data_array);

          $download_file_name="stock_ledger_".str_replace("-","", $data_array['start_date_string'])."_".str_replace("-","", $data_array['end_date_string']).".".strtolower($format);

          $random_name=$this->createFileForDownload($format,$data_array); 
 
          return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);



     }


     public function createFileForDownload($format,$data_array){

          $user_id=Auth::user()->id;

          $random_name=time()."-".$user_id.".".strtolower($format);

          if(strtolower($format)=="pdf"){
               $pdf = PDF::loadView('reports.downloadformats.stock_ledger_format',   $data_array)->setPaper('a4')->setOrientation('landscape');
               $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
          }
          else{
               
    
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.stock_ledger_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
    
          } 
          return    $random_name;
     }


     public function sendEmailWhatsappStockLedger($companyname,Request $request){
 
               extract($request->all());
               $data_array=$this->getDataInputForReport(); 
               
               $random_name= $this->createFileForDownload($format,$data_array);

               $filepath='public/download_reports/'.$random_name; 
               $this->reportservice->setSmtpUniversalSettings();

               Helper::connectDatabaseByName('Universal'); 

               if(!empty($emails)){
                    $this->reportservice->subject="Stock Ledger";
                    $this->reportservice->body="";
                    $this->reportservice->filepath='app/'.$filepath;
                    $this->reportservice->showfilename="Stock Ledger.".strtolower($format);
                    $this->reportservice->to_email=$emails; 
                    $this->reportservice->SendReportToMail(); 
                    $message="Email Request submitted successfully";
               }

               if(!empty($whatsapp_no)){
 
                    $pdf_downloaded_url=asset('storage/download_reports/'.$random_name); 

                    $all_whatsapp_numbers=explode(",",$whatsapp_no);

                    foreach($all_whatsapp_numbers as $single_whatsapp){
                         $this->whatsappservice->mob_num= "91".$single_whatsapp;
                         $this->whatsappservice->first_name="Anonymus";
                         $this->whatsappservice->last_name="Anonymus";
                         $this->whatsappservice->gender="male";
                         $result= $this->whatsappservice->getUserIdFromMobNumber(); 
     
                         if($result['status']=="success"){
                             
                             $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                             $this->whatsappservice->whatsapp_template_id="874152";
                             $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                         }

                    } 
                    $message="Whatsapp sended successfully";
               }

 
              Helper::connectDatabaseByName(Session::get('company_name'));


              return response()->json(['status'=>'success','message'=>    $message]);
         
          
     }


     public function getDataInputForReport(){
          
          
          $user_id=Auth::user()->id;

          $stock_ledger_inputs=Cache::get($user_id."_stock_ledger");

          
               $start_date_string=$stock_ledger_inputs['start_date'];
 
               $end_date_string=$stock_ledger_inputs['end_date'];
 
               $location=$stock_ledger_inputs['location_id']; 
               
               $show_rate=$stock_ledger_inputs['show_rate']; 

                $show_specrate=$stock_ledger_inputs['show_specrate'];  

                $valuation_method=$stock_ledger_inputs['valuation_method']; 

               $location_name=Location::where('Id',  $location)->value('location'); 
               $location_name=(empty(  $location_name)?"All": $location_name);

               $selected_products=$stock_ledger_inputs['selected_products'];  

               $products_data=$stock_ledger_inputs['products_data'];  

               $all_customers=$stock_ledger_inputs['all_customers'];  
           
               $data_array=array( 
               'start_date_string'=> $start_date_string,
               'end_date_string'=> $end_date_string,
               'location_name'=>   $location_name,
               'valuation_method'=>  $valuation_method ,
               'show_rate'=>    $show_rate,
               'show_specrate'=>$show_specrate ,
               'selected_products'=>$selected_products,
               'products_data'=> $products_data,
               'all_customers'=>$all_customers 
           
              );


              return  $data_array;


     }


     public function openStockStatement($companyname,Request $request){


          $user=Auth::user();
          $user_id=   $user->id; 
 
          $start_date_string="";
          $end_date_string="";
          $product="";
          $location="";
          $all_customers=array(); 
          $valuation_method="";
          $stk_formulas=array(); 

          $show_rate=false;
          $show_specrate=false;
          $purchase_invoice_txn_ids=array(); 
          $sales_invoice_txn_ids=array(); 
 
        $startEndDate = Company::getStartEndDate($companyname);

        
        $start_date_string= formatDateInDmy(  $startEndDate->fs_date);   

        $end_date_string=formatDateInDmy(  $startEndDate->fe_date);  
        
        $stock_detail_amounts=array();

        $user_id=Auth::user()->id;

        $userproduct_ids=UserProducts::where('uid',     $user_id)->pluck('prd_grp')->toArray();
 
 
        if(   count($userproduct_ids) ==0){

          $all_products= ProductMaster::select('Product as title','id','parent')->get()->toArray();
               
          $tree=new Tree(    $all_products);

          $rootproducts=     $tree->getRootNodes();
        }
        else{

          $rootproducts=    ProductMaster::whereIn('id', $userproduct_ids)->select('Product as title','id','parent')->get()->toArray();

        }
   

        $products=ProductMaster::where('parent',0)->pluck('Product','Id')->toArray(); 

        $role_id=Session::get('role_id');

        $role_stockrate_restriction= TblRoleStockRateRestriction::where('role_id', $role_id)->select('rate','spec_rate','show_amount')->first();

        if(empty( $role_stockrate_restriction)){
          $show_chk_rate=false;
          $show_chk_specrate=false;
          $show_amount=false;
        }
        else{
          $show_chk_rate=($role_stockrate_restriction->rate==1?true:false);
          $show_chk_specrate=($role_stockrate_restriction->spec_rate==1?true:false);
          $show_amount=($role_stockrate_restriction->show_amount==1?true:false);

        }

        $selected_products=array();
        $products_data=array(); 

           $this->function4filterservice->setUserAndFromTable($user,"Location");
       
          $filteredids=$this->function4filterservice->getUserDataRestrictionIds();
          $haslocations=(count($filteredids)>0?true:false);

          $locations=   Location::orderby('location','asc')->when($haslocations==true,function($query)use($filteredids){
               $query->whereIn('Id',$filteredids);
          })->pluck('location','Id')->toArray();


          $selected_locations=array(); 

          if($request->method()=="POST"){

               
               Cache::forget($user_id."_stock_statement");

              $show_rate=  isset($request->rate)?true:false;

              $show_specrate= isset($request->spec_rate)?true:false;

               $start_date_string=$request->start_date;

               $start_date= formatDateInYmd( $start_date_string) ;

               $end_date_string=$request->end_date;

               $end_date= formatDateInYmd( $end_date_string);

               $product=$request->product;

               $location=(isset($request->location)?$request->location:array());
 
               $valuation_method=  $request->valuation_method;
 
               $selected_products=(isset($request->selected_products)?$request->selected_products:array());

                $this->reportservice->setProductTreeData(); 

                    $this->reportservice->parent_product_ids=  $selected_products;

                    $found_product_ids=$this->reportservice->getAllProductWithChildIds();
            
     
               $this->reportservice->product_ids=  $found_product_ids;
               $this->reportservice->setProductNodes();

               $selected_products=  $this->reportservice->getProductsSequentially();
 
               $this->reportservice->start_date=   $start_date;
               $this->reportservice->end_date=        $end_date;


               $this->reportservice->valuation_method= $valuation_method;

               $selected_locations=   $location;
         
                
          $all_customers=Customer::whereNotNull('cust_id')->orderby('cust_id','asc')->pluck('cust_id','Id')->toArray(); 

          $selected_locations =array_reverse($selected_locations );
              
               $stock_statement_inputs=array('start_date'=>$start_date_string,
               'end_date'=>$end_date_string, 
               'location_id'=>$location,
               'show_rate'=>$show_rate,
               'show_specrate'=>$show_specrate , 
               'valuation_method'=>$valuation_method ,
               'products_data'=>array(),
               'selected_products'=>$selected_products ,
               'all_customers'=>  $all_customers , 
                'selected_locations'=>    $selected_locations ,
                'locations'=> $locations ,
                'show_amount'=>    $show_amount
          );

          // 'opening_stock'=>  $openingstock,'closing_stock'=> $closingstock,'stock_details'=> $stock_details,
          // ,,'sales_invoice_txn_ids'=>$sales_invoice_txn_ids,,'opening_rate'=>$opening_rate,'stock_detail_amounts'=>$stock_detail_amounts);

         $this->reportservice->getAllStkFormulaStringWithTables();
 
         $statement_data =array();


               foreach(    $selected_products as     $selected_product){

               
                    // 'opening_stock'=>   $openingstock ,
                    // 'opening_start_stock'=> $opening_start_stock,
                    // 'opening_rate'=>   $opening_rate ,
                    // 'closing_stock'=> $closingstock ,
                    // 'stock_details'=>$stock_details ,
                    // 'stock_detail_amounts'=>  $stock_detail_amounts ,
          

                    // $product_name=ProductMaster::where('Id',$selected_product)->value('Product'); 

                    $product_detail=$this->reportservice->getProductDetailById($selected_product);

                    $total_result= $this->getStockStatementBalanceQtyAndAmount($selected_product,NULL,  $locations);
                       

                    if($total_result['balance']<0){

                         continue;

                    }
                

                    if(in_array('all',$location)){

                        $balance_result= $this->getStockStatementBalanceQtyAndAmount($selected_product,NULL,  $locations);
                       
                         $statement_data['all']=    $balance_result; 


                         foreach(  $locations as $selected_location_id=>$selected_location_name){

                              
                              $balance_result= $this->getStockStatementBalanceQtyAndAmount($selected_product,$selected_location_id,  $locations);

                          
                              $statement_data[$selected_location_id]=  $balance_result;
                         }
 

                    }
                    else{ 

                         foreach(    $selected_locations as $single_location_id){
 
                                        
                                   $balance_result= $this->getStockStatementBalanceQtyAndAmount($selected_product,$single_location_id,  $locations);

                                   $statement_data[$single_location_id]=     $balance_result; 
                         }
 



                         
                    }

       

                    $stock_statement_inputs['products_data'][$selected_product]=array(
                                        'product_name'=>         $product_detail['product_name'] , 
                                        'parent_name'=>  $product_detail['parent_name'] , 
                                        'product_type'=>    $product_detail['product_type'] ,
                                        'statement_data'=>$statement_data    
                              );

               }  

               $products_data= $stock_statement_inputs['products_data']; 
               
    

               Cache::put($user_id."_stock_statement",    $stock_statement_inputs); 
 
          }
          else if(!empty( Cache::get($user_id."_stock_statement"))){
 

               $stock_statement_inputs=Cache::get($user_id."_stock_statement");

               $selected_products=  $stock_statement_inputs['selected_products'];

               $start_date_string=$stock_statement_inputs['start_date'];

               $end_date_string=$stock_statement_inputs['end_date'];
 
               $location=$stock_statement_inputs['location_id']; 

               $products_data=$stock_statement_inputs['products_data']; 
  
               $show_rate=$stock_statement_inputs['show_rate']; 

               $show_specrate=$stock_statement_inputs['show_specrate'];  
          
               $valuation_method=$stock_statement_inputs['valuation_method'];  

               $products_data=$stock_statement_inputs['products_data'];  

               $all_customers=$stock_statement_inputs['all_customers'];  
               $selected_locations=$stock_statement_inputs['selected_locations'];   

               $show_amount=$stock_statement_inputs['show_amount'];  


          }

           
          $selected_products_collection = collect(    $selected_products); 

          $selected_products_collection= $this->reportservice->paginate(['company_name'=>$companyname],'company.stock-statement',   $selected_products_collection , 5);

 

      return view('reports.stock-statement',compact('companyname','locations' ,'start_date_string','end_date_string','product','location','all_customers' ,'stk_formulas','show_rate','show_specrate','haslocations','purchase_invoice_txn_ids','sales_invoice_txn_ids','valuation_method' ,'stock_detail_amounts','products','show_rate','show_specrate','show_chk_rate','show_chk_specrate','selected_products_collection','products_data','rootproducts','userproduct_ids','selected_products','selected_locations','locations','show_amount' ));



     }



     public function resetStockStatement($companyname,Request $request){

          $user_id=Auth::user()->id;

          Cache::forget($user_id."_stock_statement");
 
          return redirect()->back()->with('message','Stock Statement reset successfully');


     }


     public function getStockStatementBalanceQtyAndAmount( $selected_product,$location_id,$locations){

          
          $this->reportservice->product_id= $selected_product;
          $this->reportservice->location_id=$location_id;

          $openingstock=   $this->reportservice->getOpeningProductStock();
               
          $opening_start_stock=  $this->reportservice->getStartOpeningProductStock();

          $opening_rate=  StockDet::where('Prodid', $selected_product)->where('docno','LIKE','op-%')->value('CRate');

 
          $closingstock=   $this->reportservice->getClosingProductStock();
          

          $stock_details= $this->reportservice->getProductStockDetails(    $opening_start_stock);

           
           $txn_ids=array_column(       $stock_details,'Txn_Name'); 

           $txn_ids=array_unique( $txn_ids); 

           $purchase_invoice_txn_ids= TableMaster::where('txn_class','Purchase Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();
          

           $sales_invoice_txn_ids= TableMaster::where('txn_class','Sales Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();
      
           $stock_detail_last_amount= $this->reportservice->getProductLastStockDetailAmount( $this->reportservice->valuation_method ,    $opening_start_stock ,  $opening_rate  ,$stock_details, $purchase_invoice_txn_ids,  $sales_invoice_txn_ids ); 
         
           $found_location_name=(array_key_exists($location_id,$locations)?$locations[$location_id]:"");

          return array( 'location_name'=>  $found_location_name,'balance'=>   $closingstock,'amount'=>   $stock_detail_last_amount);
     }

     public function downloadStockStatement($companyname,$format){



          $user_id=Auth::user()->id;
          if(empty(Cache::get($user_id."_stock_statement"))){

               echo "No Stock Statement cache found";

               exit();

          } 

          $stock_statement_inputs=Cache::get($user_id."_stock_statement");
          // $download_file_name="stock_statement_";
          
          //  return view('reports.downloadformats.stock_statement_format',$stock_statement_inputs);

          // $download_file_name="stock_statement_".str_replace("-","", $stock_statement_inputs['start_date'])."_".str_replace("-","", $stock_statement_inputs['end_date']).".".strtolower($format);

          $download_file_name=makeReportFileName($format,"stock_statement",$stock_statement_inputs['start_date'],$stock_statement_inputs['end_date']);

          $random_name=$this->createStockStatementFileForDownload($format,   $stock_statement_inputs);


          return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);
  
     }

     
     public function createStockStatementFileForDownload($format,$data_array){

          $user_id=Auth::user()->id;

          $random_name=time()."-".$user_id.".".strtolower($format);

          if(strtolower($format)=="pdf"){
               $pdf = PDF::loadView('reports.downloadformats.stock_statement_format',   $data_array)->setPaper('a3')->setOrientation('landscape');
               $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
          }
          else{
               
    
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.stock_statement_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
    
          } 
          return    $random_name;
     }


     public function sendEmailWhatsappStockStatement($companyname,Request $request){


          extract($request->all());
          $user_id=Auth::user()->id;
          $data_array=   Cache::get($user_id."_stock_statement");

 
 
          $random_name= $this->createStockStatementFileForDownload($format,$data_array);

          $filepath='public/download_reports/'.$random_name; 
          $this->reportservice->setSmtpUniversalSettings();

          Helper::connectDatabaseByName('Universal'); 

          if(!empty($emails)){
               $this->reportservice->subject="Stock Statement";
               $this->reportservice->body="";
               $this->reportservice->filepath='app/'.$filepath;
               $this->reportservice->showfilename="Stock Statement.".strtolower($format);
               $this->reportservice->to_email=$emails; 
               $this->reportservice->SendReportToMail(); 
               $message="Email Request submitted successfully";
          }

          if(!empty($whatsapp_no)){

               $pdf_downloaded_url=asset('storage/download_reports/'.$random_name); 

               $all_whatsapp_numbers=explode(",",$whatsapp_no);

               foreach($all_whatsapp_numbers as $single_whatsapp){
                    $this->whatsappservice->mob_num= "91".$single_whatsapp;
                    $this->whatsappservice->first_name="Anonymus";
                    $this->whatsappservice->last_name="Anonymus";
                    $this->whatsappservice->gender="male";
                    $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){
                        
                        $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                        $this->whatsappservice->whatsapp_template_id="874152";
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                    }

               } 
               $message="Whatsapp sended successfully";
          }


         Helper::connectDatabaseByName(Session::get('company_name'));


         return response()->json(['status'=>'success','message'=>    $message]);

     }



     public function openStockMovement($companyname,Request $request){


          $user=Auth::user();
          $user_id=   $user->id; 
 
          $start_date_string="";
          $end_date_string="";
          $product="";
          $location="";
          $all_customers=array(); 
          $valuation_method="";
          $stk_formulas=array(); 

          $show_rate=false;
          $show_specrate=false;
          $purchase_invoice_txn_ids=array(); 
          $sales_invoice_txn_ids=array(); 
 
        $startEndDate = Company::getStartEndDate($companyname);

        
        $start_date_string= formatDateInDmy(  $startEndDate->fs_date);   

        $end_date_string=formatDateInDmy(  $startEndDate->fe_date);  
        
        $stock_detail_amounts=array();

        $user_id=Auth::user()->id;

        $userproduct_ids=UserProducts::where('uid',     $user_id)->pluck('prd_grp')->toArray();
 
 
        if(   count($userproduct_ids) ==0){

          $all_products= ProductMaster::select('Product as title','id','parent')->get()->toArray();
               
          $tree=new Tree(    $all_products);

          $rootproducts=     $tree->getRootNodes();
        }
        else{

          $rootproducts=    ProductMaster::whereIn('id', $userproduct_ids)->select('Product as title','id','parent')->get()->toArray();

        }
   

        $products=ProductMaster::where('parent',0)->pluck('Product','Id')->toArray(); 

        $role_id=Session::get('role_id');

        $role_stockrate_restriction= TblRoleStockRateRestriction::where('role_id', $role_id)->select('rate','spec_rate','show_amount')->first();

        if(empty( $role_stockrate_restriction)){
          $show_chk_rate=false;
          $show_chk_specrate=false;
          $show_amount=false;
        }
        else{
          $show_chk_rate=($role_stockrate_restriction->rate==1?true:false);
          $show_chk_specrate=($role_stockrate_restriction->spec_rate==1?true:false);
          $show_amount=($role_stockrate_restriction->show_amount==1?true:false);

        }

        $selected_products=array();
        $products_data=array(); 

           $this->function4filterservice->setUserAndFromTable($user,"Location");
       
          $filteredids=$this->function4filterservice->getUserDataRestrictionIds();
          $haslocations=(count($filteredids)>0?true:false);

          $locations=   Location::orderby('location','asc')->when($haslocations==true,function($query)use($filteredids){
               $query->whereIn('Id',$filteredids);
          })->pluck('location','Id')->toArray();


          $selected_locations=array(); 

          if($request->method()=="POST"){

               
               Cache::forget($user_id."_stock_movement");

              $show_rate=  isset($request->rate)?true:false;

              $show_specrate= isset($request->spec_rate)?true:false;

               $start_date_string=$request->start_date;

               $start_date= formatDateInYmd( $start_date_string) ;

               $end_date_string=$request->end_date;

               $end_date= formatDateInYmd( $end_date_string);

               $product=$request->product;

               $location=(isset($request->location)?$request->location:array());
 
               $valuation_method=  $request->valuation_method;
 
               $selected_products=(isset($request->selected_products)?$request->selected_products:array());

                $this->reportservice->setProductTreeData(); 

                    $this->reportservice->parent_product_ids=  $selected_products;

                    $found_product_ids=$this->reportservice->getAllProductWithChildIds();
            
     
               $this->reportservice->product_ids=  $found_product_ids;
               $this->reportservice->setProductNodes();
               $selected_products=  $this->reportservice->getProductsSequentially();
 
               $this->reportservice->start_date=   $start_date;
               $this->reportservice->end_date=        $end_date;


               $this->reportservice->valuation_method= $valuation_method;

               $selected_locations=   $location;
         
                
          $all_customers=Customer::whereNotNull('cust_id')->orderby('cust_id','asc')->pluck('cust_id','Id')->toArray(); 

          $selected_locations =array_reverse($selected_locations );
              
               $stock_movement_inputs=array('start_date'=>$start_date_string,
               'end_date'=>$end_date_string, 
               'location_id'=>$location,
               'show_rate'=>$show_rate,
               'show_specrate'=>$show_specrate , 
               'valuation_method'=>$valuation_method ,
               'products_data'=>array(),
               'selected_products'=>$selected_products ,
               'all_customers'=>  $all_customers , 
                'selected_locations'=>    $selected_locations ,
                'locations'=> $locations ,
                'show_amount'=>     $show_amount
          );

          // 'opening_stock'=>  $openingstock,'closing_stock'=> $closingstock,'stock_details'=> $stock_details,
          // ,,'sales_invoice_txn_ids'=>$sales_invoice_txn_ids,,'opening_rate'=>$opening_rate,'stock_detail_amounts'=>$stock_detail_amounts);

         $this->reportservice->getAllStkFormulaStringWithTables();
 
         $movement_data =array();


               foreach(    $selected_products as     $selected_product){
 

                    // $product_name=ProductMaster::where('Id',$selected_product)->value('Product');

                    $product_detail=$this->reportservice->getProductDetailById($selected_product);


                    if(in_array('all',$location)){

                        $balance_result= $this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,NULL );
                       
                         $movement_data['all']=    $balance_result; 


                         foreach(  $locations as $selected_location_id=>$selected_location_name){

                              
                              $balance_result= $this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,$selected_location_id );

                          
                              $movement_data[$selected_location_id]=  $balance_result;
                         }
 

                    }
                    else{ 

                         foreach(    $selected_locations as $single_location_id){
 
                                        
                                   $balance_result= $this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,$single_location_id );

                                   $movement_data[$single_location_id]=     $balance_result; 
                         }
 



                         
                    }

       

                    $stock_movement_inputs['products_data'][$selected_product]=array(
                                        'product_name'=> $product_detail['product_name'] , 
                                        'parent_name'=>  $product_detail['parent_name'] , 
                                        'product_type'=>    $product_detail['product_type'] , 
                                        'movement_data'=>$movement_data    
                              );

               }  

               $products_data=   $stock_movement_inputs['products_data']; 
               
    

               Cache::put($user_id."_stock_movement",    $stock_movement_inputs); 
 
          }
          else if(!empty( Cache::get($user_id."_stock_movement"))){
 

               $stock_movement_inputs=Cache::get($user_id."_stock_movement");

               $selected_products=  $stock_movement_inputs['selected_products'];

               $start_date_string=$stock_movement_inputs['start_date'];

               $end_date_string=$stock_movement_inputs['end_date'];
 
               $location=$stock_movement_inputs['location_id']; 

               $products_data=$stock_movement_inputs['products_data']; 
  
               $show_rate=$stock_movement_inputs['show_rate']; 

               $show_specrate=$stock_movement_inputs['show_specrate'];  
          
               $valuation_method=$stock_movement_inputs['valuation_method'];  

               $products_data=$stock_movement_inputs['products_data'];  

               $all_customers=$stock_movement_inputs['all_customers'];  
               $selected_locations=$stock_movement_inputs['selected_locations'];   
               $show_amount=$stock_movement_inputs['show_amount'];  


          }

           
          $selected_products_collection = collect(    $selected_products); 

          $selected_products_collection= $this->reportservice->paginate(['company_name'=>$companyname],'company.stock-movement',   $selected_products_collection , 10);

 

      return view('reports.stock-movement',compact('companyname','locations' ,'start_date_string','end_date_string','product','location','all_customers' ,'stk_formulas','show_rate','show_specrate','haslocations','purchase_invoice_txn_ids','sales_invoice_txn_ids','valuation_method' ,'stock_detail_amounts','products','show_rate','show_specrate','show_chk_rate','show_chk_specrate','selected_products_collection','products_data','rootproducts','userproduct_ids','selected_products','selected_locations','locations','show_amount' ));



     }



     public function resetStockMovement($companyname,$mode,Request $request){
          $user_id=Auth::user()->id;

          if($mode=="fast"){
               Cache::forget($user_id."_stock_movement_fast");
               $report_name="Stock Movement Fast";
          }
          else{
               Cache::forget($user_id."_stock_movement_slow");   
               $report_name="Stock Movement Slow";
          }
       
 
          return redirect()->back()->with('message',  $report_name.' reset successfully');


     }


     public function getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string,$selected_product,$location_id){

          
          $this->reportservice->product_id= $selected_product;
          $this->reportservice->location_id=$location_id;

          $openingstock=   $this->reportservice->getOpeningProductStock();
               
          $opening_start_stock=  $this->reportservice->getStartOpeningProductStock();

          $opening_rate=  StockDet::where('Prodid', $selected_product)->where('docno','LIKE','op-%')->value('CRate');

          $total_opening_balance=round($openingstock* $opening_rate,2);

 
          $closingstock=   $this->reportservice->getClosingProductStock();
          

          $stock_details= $this->reportservice->getProductStockDetails(    $opening_start_stock);

           
           $txn_ids=array_column(       $stock_details,'Txn_Name'); 

           $txn_ids=array_unique( $txn_ids); 

           $purchase_invoice_txn_ids= TableMaster::where('txn_class','Purchase Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();
          

           $sales_invoice_txn_ids= TableMaster::where('txn_class','Sales Invoice')->whereIn('Id',   $txn_ids)->pluck('Id')->toArray();
      
           $stock_detail_last_amount= $this->reportservice->getProductLastStockDetailAmount( $this->reportservice->valuation_method ,    $opening_start_stock ,  $opening_rate  ,$stock_details, $purchase_invoice_txn_ids,  $sales_invoice_txn_ids ); 
 
           $in_total=0;

           $out_total=0;


           foreach( $stock_details as  $stock_detail){ 

               $stock_date=date("Y-m-d H:i:s",strtotime($stock_detail['docdate']));

               $start_date_stock=date("Y-m-d 00:00:00",strtotime(formatDateInYmd($start_date_string)));

               $end_date_stock=date("Y-m-d 23:59:59",strtotime(formatDateInYmd($end_date_string)));

               if(	 $stock_date<$start_date_stock || 	$stock_date>	$end_date_stock){
               continue;
               }


               if($stock_detail['Qty']>0){
                    $in_total=   $in_total+$stock_detail['Qty'];
               }
               else{

                    $out_total=  $out_total+abs($stock_detail['Qty']);

               }

               

           }



          return array(  'opening_balance'=>  $openingstock,'opening_amount'=>$total_opening_balance ,'in'=>  $in_total,'out'=>   $out_total  ,'closing_balance'=>   $closingstock,'closing_amount'=>   $stock_detail_last_amount);


     }


     public function downloadStockMovement($companyname,$format){

          $user_id=Auth::user()->id;

        $stock_movement_inputs=  Cache::get($user_id."_stock_movement");


        $download_file_name=makeReportFileName($format,"stock_movement",$stock_movement_inputs['start_date'],$stock_movement_inputs['end_date']);;

       $random_name=  $this->createFileStockMovementForDownload($format, $stock_movement_inputs);

 
       return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);



     //    return view('reports.downloadformats.stock_movement_format',  $stock_movement_inputs);



     }


     public function createFileStockMovementForDownload($format,$data_array){

          $user_id=Auth::user()->id;

          $random_name=time()."-".$user_id.".".strtolower($format);

          if(strtolower($format)=="pdf"){
               $pdf = PDF::loadView('reports.downloadformats.stock_movement_format',   $data_array)->setPaper('a2')->setOrientation('landscape');
               $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
          }
          else{
               
    
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.stock_movement_format' ,   $data_array ),  '/download_reports/'.$random_name ,'public');
    
          } 
          return    $random_name;


     }


     public function sendEmailWhatsappStockMovement($companyname,Request $request){

          
          extract($request->all());
          $user_id=Auth::user()->id;
          $data_array=   Cache::get($user_id."_stock_movement");
 
          $random_name= $this->createFileStockMovementForDownload($format,$data_array);

          $filepath='public/download_reports/'.$random_name; 
          $this->reportservice->setSmtpUniversalSettings();

          Helper::connectDatabaseByName('Universal'); 

          if(!empty($emails)){
               $this->reportservice->subject="Stock Movement";
               $this->reportservice->body="";
               $this->reportservice->filepath='app/'.$filepath;
               $this->reportservice->showfilename="Stock Movement.".strtolower($format);
               $this->reportservice->to_email=$emails; 
               $this->reportservice->SendReportToMail(); 
               $message="Email Request submitted successfully";
          }

          if(!empty($whatsapp_no)){

               $pdf_downloaded_url=asset('storage/download_reports/'.$random_name); 

               $all_whatsapp_numbers=explode(",",$whatsapp_no);

               foreach($all_whatsapp_numbers as $single_whatsapp){
                    $this->whatsappservice->mob_num= "91".$single_whatsapp;
                    $this->whatsappservice->first_name="Anonymus";
                    $this->whatsappservice->last_name="Anonymus";
                    $this->whatsappservice->gender="male";
                    $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){
                        
                        $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                        $this->whatsappservice->whatsapp_template_id="874152";
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                    }

               } 
               $message="Whatsapp sended successfully";
          }


         Helper::connectDatabaseByName(Session::get('company_name'));


         return response()->json(['status'=>'success','message'=>    $message]);

     }


     public function checkProductDetail(){

          $this->reportservice->setProductNodes();
          $result= $this->reportservice->getProductDetailById(24);

          dd($result);
          // dd('ok');
     }



     public function fastMovingItems($companyname,Request $request){
 
          $request_url=$request->fullUrl();
 
          $user=Auth::user();
          $user_id=   $user->id; 
 
          $start_date_string="";
          $end_date_string="";
          $product="";
          $location="";
          $all_customers=array(); 
          $valuation_method="";
          $stk_formulas=array(); 
          $qty_period="";
          $show_rate=false;
          $show_specrate=false;
          $purchase_invoice_txn_ids=array(); 
          $sales_invoice_txn_ids=array(); 
          $qty="";
          $qty_period="";
 
        $startEndDate = Company::getStartEndDate($companyname);
 
        $start_date_string= formatDateInDmy(  $startEndDate->fs_date);   

        $end_date_string=formatDateInDmy(  $startEndDate->fe_date);  
        
        $stock_detail_amounts=array();

        $user_id=Auth::user()->id;

        $userproduct_ids=UserProducts::where('uid',     $user_id)->pluck('prd_grp')->toArray();
 
 
        if(   count($userproduct_ids) ==0){

          $all_products= ProductMaster::select('Product as title','id','parent')->get()->toArray();
               
          $tree=new Tree(    $all_products);

          $rootproducts=     $tree->getRootNodes();
        }
        else{

          $rootproducts=    ProductMaster::whereIn('id', $userproduct_ids)->select('Product as title','id','parent')->get()->toArray();

        }
   

        $products=ProductMaster::where('parent',0)->pluck('Product','Id')->toArray(); 

        $role_id=Session::get('role_id');

        $role_stockrate_restriction= TblRoleStockRateRestriction::where('role_id', $role_id)->select('rate','spec_rate','show_amount')->first();

        if(empty( $role_stockrate_restriction)){
          $show_chk_rate=false;
          $show_chk_specrate=false;
          $show_amount=false;
        }
        else{
          $show_chk_rate=($role_stockrate_restriction->rate==1?true:false);
          $show_chk_specrate=($role_stockrate_restriction->spec_rate==1?true:false);
          $show_amount=($role_stockrate_restriction->show_amount==1?true:false);

        }

        $selected_products=array();
        $products_data=array(); 

           $this->function4filterservice->setUserAndFromTable($user,"Location");
       
          $filteredids=$this->function4filterservice->getUserDataRestrictionIds();
          $haslocations=(count($filteredids)>0?true:false);

          $locations=   Location::orderby('location','asc')->when($haslocations==true,function($query)use($filteredids){
               $query->whereIn('Id',$filteredids);
          })->pluck('location','Id')->toArray();


          $selected_locations=array();  

          if(str_contains($request_url,"fast")){
               $mode="fast";
          }
          else{
               $mode="slow";
          }
          
          $months=array(4=>"April",5=>"May",6=>"June",7=>"July",8=>"Aug",9=>"Sept",10=>"Oct",11=>"Nov",12=>"Dec",1=>"Jan",2=>"Feb",3=>"March");

          if($request->method()=="POST"){

 

               if(     $mode=="fast"){
                    Cache::forget($user_id."_stock_movement_fast");
               }
               else{
                    Cache::forget($user_id."_stock_movement_slow");  
               }
           

          //     $show_rate=  isset($request->rate)?true:false;

          //     $show_specrate= isset($request->spec_rate)?true:false;

               // $start_date_string=$request->start_date;

               $start_date= formatDateInYmd( $start_date_string) ;

               // $end_date_string=$request->end_date;

               $end_date= formatDateInYmd( $end_date_string);

               $product=$request->product;
               $qty_period=$request->qty_period;  

               $location=$request->location;
 
               $valuation_method=  $request->valuation_method;
 
               $selected_products=(isset($request->selected_products)?$request->selected_products:array());

               $qty=$request->qty;

                $this->reportservice->setProductTreeData(); 

                    $this->reportservice->parent_product_ids=  $selected_products;

                    $found_product_ids=$this->reportservice->getAllProductWithChildIds();
            
     
               $this->reportservice->product_ids=  $found_product_ids;
               $this->reportservice->setProductNodes();
               $selected_products=  $this->reportservice->getProductsSequentially();
 
               $this->reportservice->start_date=   $start_date;
               $this->reportservice->end_date=        $end_date;


               $this->reportservice->valuation_method= $valuation_method;
 
                
          $all_customers=Customer::whereNotNull('cust_id')->orderby('cust_id','asc')->pluck('cust_id','Id')->toArray(); 
 
              
               $stock_moving_inputs=array(  
                    'start_date'=>$start_date_string,
                    'end_date'=>$end_date_string, 
               'location_id'=>$location, 
               'qty'=>$qty,
               'qty_period'=>$qty_period,
               'valuation_method'=>$valuation_method ,
               'products_data'=>array(),
               'selected_products'=>$selected_products ,
               'all_customers'=>  $all_customers ,  
                'locations'=> $locations,
                'show_amount'=>   $show_amount,
                'months'=>      $months
          );

          // 'opening_stock'=>  $openingstock,'closing_stock'=> $closingstock,'stock_details'=> $stock_details,
          // ,,'sales_invoice_txn_ids'=>$sales_invoice_txn_ids,,'opening_rate'=>$opening_rate,'stock_detail_amounts'=>$stock_detail_amounts);

         $this->reportservice->getAllStkFormulaStringWithTables();
 
         $moving_data =array();


               foreach(    $selected_products as     $selected_product){
 
 
                    $product_detail=$this->reportservice->getProductDetailById($selected_product);


                    if( $location=="all"){

                         $balance_result=$this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,NULL);
                
                    }
                    else{
                         $balance_result=$this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,$location);

                    }

                    $moving_data=array('closing_balance'=> $balance_result['closing_balance'],'closing_amount'=>$balance_result['closing_amount'],'month_data'=>array(),'total_month'=>0,'avg_qty_for_period'=>0);
 
                    foreach($months as $month_key=>$month_name){

                         $month_out_amount=$this->reportservice->getStockFastSlowMovingOutAmount( $selected_product,$month_key);
                         $moving_data['month_data'][$month_key]=$month_out_amount;


                    }


                    $month_values= array_values($moving_data['month_data']);


                   $total_month= array_sum(  $month_values);

                   $moving_data['total_month']=     $total_month; 
                   $avg_qty=$this->calculateAvgForPeriodStockFastSlowMoving(    $qty_period, $total_month,   $start_date_string);

                   if($mode=="fast" &&    $avg_qty< $qty){
                        continue;
                   }
                  else if($mode=="slow" &&    $avg_qty>$qty){
                         continue;
                    }

                   $moving_data['avg_qty_for_period']= $avg_qty;
 
                    $stock_moving_inputs['products_data'][$selected_product]=array(
                                        'product_name'=> $product_detail['product_name'] , 
                                        'parent_name'=>  $product_detail['parent_name'] , 
                                        'product_type'=>    $product_detail['product_type'] , 
                                        'moving_data'=>    $moving_data 
                              );

               }  

               $products_data=   $stock_moving_inputs['products_data'];  
               
               if(     $mode=="fast"){
                    Cache::put($user_id."_stock_movement_fast",  $stock_moving_inputs);
               }
               else{
                    Cache::put($user_id."_stock_movement_slow",  $stock_moving_inputs);  
               }
               
 
          }
          else if(  ( $mode=="fast"  &&  !empty(Cache::get($user_id."_stock_movement_fast"))  )  ||   ( $mode=="slow"  &&  !empty(Cache::get($user_id."_stock_movement_slow"))  )   ){
               $stock_moving_inputs=Cache::get($user_id."_stock_movement_fast");
               $products_data=   $stock_moving_inputs['products_data']; 

               $location= $stock_moving_inputs['location_id']; 
               $qty=$stock_moving_inputs['qty']; 
               $qty_period=$stock_moving_inputs['qty_period'];
               $valuation_method =$stock_moving_inputs['valuation_method'];
               $selected_products=$stock_moving_inputs['selected_products'];
               $all_customers =$stock_moving_inputs['all_customers'];
               $locations =$stock_moving_inputs['locations']; 
          }
         
           
          $selected_products_collection = collect(    $selected_products); 

          if($mode=="fast"){
               $routename="company.stock-fast-moving-items";
          }
          else{
               $routename="company.stock-slow-moving-items"; 
          }

          $selected_products_collection= $this->reportservice->paginate(['company_name'=>$companyname],  $routename,   $selected_products_collection , 10);

 
// dd($products_data);
      return view('reports.stock-fast-slow-moving-items',compact('companyname','locations' ,'start_date_string','end_date_string','product','location','all_customers' ,'stk_formulas','show_rate','show_specrate','haslocations','purchase_invoice_txn_ids','sales_invoice_txn_ids','valuation_method' ,'stock_detail_amounts','products','show_rate','show_specrate','show_chk_rate','show_chk_specrate','selected_products_collection','products_data','rootproducts','userproduct_ids','selected_products','selected_locations','locations','qty','qty_period','months','mode','show_amount' ));



          
     }


     public function calculateAvgForPeriodStockFastSlowMoving($qty_period,$total_month,$start_date_string){


      
          $start_date=date_create(formatDateInYmd($start_date_string));
 
          $today_date=date_create( date("Y-m-d",strtotime('now')));
 
 
          $diff=date_diff( $start_date,  $today_date);
      
         
          if($qty_period=="day"){

             $no_of_days=  $diff->format("%a");

             $total_diff=  $no_of_days;

          }
          else if($qty_period=="week"){

               $no_of_days=  $diff->format("%a");

               $total_diff= round(    $no_of_days/7,2);
                
          }
          else if($qty_period=="month"){

               $no_of_months=  $diff->format("%m");

               $total_diff=     $no_of_months;
                
          }
          else if($qty_period=="qtr"){

               $no_of_days=  $diff->format("%a");
              $total_diff= round( $no_of_days/90,2);
          }
          else if($qty_period=="year"){

               $total_diff=1;

          }

  
          $avg=round($total_month/  $total_diff,2);

          return $avg;


     }


     public function downloadSlowFastMovingItems($companyname,$mode,$format="xlsx"){

          $user_id=Auth::user()->id; 

          if($mode=="fast"){

               $stock_movement_inputs=  Cache::get($user_id."_stock_movement_fast");
               $report_name="stock_fast_moving_items";
          }
          else if($mode=="slow"){

               $stock_movement_inputs=  Cache::get($user_id."_stock_movement_slow");
               $report_name="stock_slow_moving_items";
          }

          $stock_movement_inputs['mode']= $mode;


          // return view('reports.downloadformats.stock_fast_slow_moving_items_format',    $stock_movement_inputs);


           $random_name=$this->createFileMovementFastSlowReportForDownload($format, $mode , $stock_movement_inputs);

           $download_file_name=$report_name."_".str_replace("-","", $stock_movement_inputs['start_date'])."_".str_replace("-","", $stock_movement_inputs['end_date']).".".strtolower($format);

           return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);

       

     }

     public function createFileMovementFastSlowReportForDownload($format,$mode , $stock_movement_inputs){

          
          $user_id=Auth::user()->id;

          $random_name=time()."-".$user_id.".".strtolower($format);

          $stock_movement_inputs['mode']=$mode;

          if(strtolower($format)=="pdf"){
               $pdf = PDF::loadView('reports.downloadformats.stock_fast_slow_moving_items_format',$stock_movement_inputs)->setPaper('a3')->setOrientation('landscape');
               $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
          }
          else{ 
    
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.stock_fast_slow_moving_items_format' ,$stock_movement_inputs ),  '/download_reports/'.$random_name ,'public');
    
          } 

          return    $random_name;

     }


     public function sendEmailWhatsappStockFastSlowMovingItems( $companyname,$report_name,Request $request){
  
          extract($request->all());
          $user_id=Auth::user()->id;
 
          if($report_name=="fast"){
               $data_array=   Cache::get($user_id."_stock_movement_fast");
               $subject="Stock Fast Moving Items";
               $showfilename="Stock_Fast_Moving_Items_".str_replace("-","",   $data_array['start_date'])."-".str_replace("-","",   $data_array['end_date']);
          }
          else{
               $data_array=   Cache::get($user_id."_stock_movement_slow");
               $subject="Stock Slow Moving Items";
               $showfilename="Stock_Slow_Moving_Items_".str_replace("-","",   $data_array['start_date'])."-".str_replace("-","",   $data_array['end_date']);
          }

     
          $random_name= $this->createFileMovementFastSlowReportForDownload($format,$report_name,$data_array);

          $filepath='public/download_reports/'.$random_name; 
          $this->reportservice->setSmtpUniversalSettings();

          Helper::connectDatabaseByName('Universal'); 

          if(!empty($emails)){
               $this->reportservice->subject=  $subject;
               $this->reportservice->body="";
               $this->reportservice->filepath='app/'.$filepath;
               $this->reportservice->showfilename=    $showfilename.".".strtolower($format);
               $this->reportservice->to_email=$emails; 
               $this->reportservice->SendReportToMail(); 
               $message="Email Request submitted successfully";
          }

          if(!empty($whatsapp_no)){

               $pdf_downloaded_url=asset('storage/download_reports/'.$random_name); 

               $all_whatsapp_numbers=explode(",",$whatsapp_no);

               foreach($all_whatsapp_numbers as $single_whatsapp){
                    $this->whatsappservice->mob_num= "91".$single_whatsapp;
                    $this->whatsappservice->first_name="Anonymus";
                    $this->whatsappservice->last_name="Anonymus";
                    $this->whatsappservice->gender="male";
                    $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){
                        
                        $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                        $this->whatsappservice->whatsapp_template_id="874152";
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                    }

               } 
               $message="Whatsapp sended successfully";
          }


         Helper::connectDatabaseByName(Session::get('company_name'));


         return response()->json(['status'=>'success','message'=>    $message]);


     }


     public function reorderReport($companyname,Request $request){ 

          $user=Auth::user();
          $user_id=   $user->id; 
 
          $start_date_string="";
          $end_date_string="";
          $product="";
          $location="";
          $all_customers=array(); 
          $valuation_method="";
          $stk_formulas=array(); 

          $show_rate=false;
          $show_specrate=false;
          $purchase_invoice_txn_ids=array(); 
          $sales_invoice_txn_ids=array(); 
 
        $startEndDate = Company::getStartEndDate($companyname);

        
        $start_date_string= formatDateInDmy(  $startEndDate->fs_date);   

        $end_date_string=formatDateInDmy(  $startEndDate->fe_date);  
        
        $stock_detail_amounts=array();

        $user_id=Auth::user()->id;

        $userproduct_ids=UserProducts::where('uid',     $user_id)->pluck('prd_grp')->toArray();
 
 
        if(   count($userproduct_ids) ==0){

          $all_products= ProductMaster::select('Product as title','id','parent')->get()->toArray();
               
          $tree=new Tree(    $all_products);

          $rootproducts=     $tree->getRootNodes();
        }
        else{

          $rootproducts=    ProductMaster::whereIn('id', $userproduct_ids)->select('Product as title','id','parent')->get()->toArray();

        }
   

        $products=ProductMaster::where('parent',0)->pluck('Product','Id')->toArray(); 

        $role_id=Session::get('role_id');

        $role_stockrate_restriction= TblRoleStockRateRestriction::where('role_id', $role_id)->select('rate','spec_rate','show_amount')->first();

        if(empty( $role_stockrate_restriction)){
          $show_chk_rate=false;
          $show_chk_specrate=false;
          $show_amount=false;
        }
        else{
          $show_chk_rate=($role_stockrate_restriction->rate==1?true:false);
          $show_chk_specrate=($role_stockrate_restriction->spec_rate==1?true:false);
          $show_amount=($role_stockrate_restriction->show_amount==1?true:false);

        }

        $selected_products=array();
        $products_data=array(); 

           $this->function4filterservice->setUserAndFromTable($user,"Location");
       
          $filteredids=$this->function4filterservice->getUserDataRestrictionIds();
          $haslocations=(count($filteredids)>0?true:false);

          $locations=   Location::orderby('location','asc')->when($haslocations==true,function($query)use($filteredids){
               $query->whereIn('Id',$filteredids);
          })->pluck('location','Id')->toArray();


          $selected_locations=array(); 
          $reorder_report_inputs=array();

          $found_products=array();

          if($request->method()=="POST"){

               
               Cache::forget($user_id."_reorder_report");

              $show_rate=  isset($request->rate)?true:false;

              $show_specrate= isset($request->spec_rate)?true:false;

               $start_date_string=$request->start_date;

               $start_date= formatDateInYmd( $start_date_string) ;

               $end_date_string=$request->end_date;

               $end_date= formatDateInYmd( $end_date_string);

               $product=$request->product;

               $location=(isset($request->location)?$request->location:array());
 
               $valuation_method=  $request->valuation_method;
 
               $selected_products=(isset($request->selected_products)?$request->selected_products:array());

                $this->reportservice->setProductTreeData(); 

                    $this->reportservice->parent_product_ids=  $selected_products;

                    $found_product_ids=$this->reportservice->getAllProductWithChildIds();
            
     
               $this->reportservice->product_ids=  $found_product_ids;
               $this->reportservice->setProductNodes();
               $selected_products=  $this->reportservice->getProductsSequentially();
 
               $this->reportservice->start_date=   $start_date;
               $this->reportservice->end_date=        $end_date;


               $this->reportservice->valuation_method= $valuation_method;

               $selected_locations=   $location;
         
                
          $all_customers=Customer::whereNotNull('cust_id')->orderby('cust_id','asc')->pluck('cust_id','Id')->toArray(); 

          $selected_locations =array_reverse($selected_locations );
              
               $reorder_report_inputs=array('start_date'=>$start_date_string,
               'end_date'=>$end_date_string, 
               'location_id'=>$location,
               'show_rate'=>$show_rate,
               'show_specrate'=>$show_specrate , 
               'valuation_method'=>$valuation_method ,
               'products_data'=>array(),
               'selected_products'=>$selected_products ,
               'all_customers'=>  $all_customers , 
                'selected_locations'=>    $selected_locations ,
                'locations'=> $locations ,
                'show_amount'=>     $show_amount ,
                'found_products'=>array()
          );

          // 'opening_stock'=>  $openingstock,'closing_stock'=> $closingstock,'stock_details'=> $stock_details,
          // ,,'sales_invoice_txn_ids'=>$sales_invoice_txn_ids,,'opening_rate'=>$opening_rate,'stock_detail_amounts'=>$stock_detail_amounts);

         $this->reportservice->getAllStkFormulaStringWithTables();
 
         $reorder_data =array();
 

               foreach(    $selected_products as     $selected_product){
 

                    // $product_name=ProductMaster::where('Id',$selected_product)->value('Product');

                    $product_detail=$this->reportservice->getProductDetailById($selected_product);


                    if(in_array('all',$location)){

                        $balance_result= $this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,NULL );
                       
                        $reorder_result= $this->getStockReorderResult($selected_product , "all" ,$balance_result['closing_balance']);

                        $reorder_data['all']= array('closing_balance'=> $balance_result['closing_balance'],'closing_amount'=>$balance_result['closing_amount'],'reorder_qty'=>$reorder_result['reorder_qty'],'diff'=>$reorder_result['diff'])  ; 


                         foreach(  $locations as $selected_location_id=>$selected_location_name){

                              
                              $balance_result= $this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,$selected_location_id );
                              $reorder_result= $this->getStockReorderResult($selected_product , $selected_location_id ,$balance_result['closing_balance']);
 
                              $reorder_data[$selected_location_id]=  array('closing_balance'=> $balance_result['closing_balance'],'closing_amount'=>$balance_result['closing_amount'],'reorder_qty'=>$reorder_result['reorder_qty'],'diff'=>$reorder_result['diff'])  ; ;
                         }
 

                    }
                    else{ 

                         foreach(    $selected_locations as $single_location_id){
 
                                        
                                   $balance_result= $this->getStockMovementBalanceQtyAndAmount($start_date_string,$end_date_string ,$selected_product,$single_location_id );
                                   $reorder_result= $this->getStockReorderResult($selected_product , $single_location_id ,$balance_result['closing_balance']);
 
                                   $reorder_data[$single_location_id]=  array('closing_balance'=> $balance_result['closing_balance'],'closing_amount'=>$balance_result['closing_amount'],'reorder_qty'=>$reorder_result['reorder_qty'],'diff'=>$reorder_result['diff'])  ; 
                         }
  
                         
                    }

                    $diff_negative=false;


                    
                    if(in_array('all',$location)){

                        if($reorder_data['all']['diff']<0) {
                         $diff_negative=true;
                        }
                      

                    }
                    

                    foreach(    $selected_locations as $single_location_id){

                         if($reorder_data[ $single_location_id]['diff']<0) {
                              $diff_negative=true;
                             }

                    }

 
                    if(  $diff_negative==true){

                         $reorder_report_inputs['products_data'][$selected_product]=array(
                              'product_name'=> $product_detail['product_name'] , 
                              'parent_name'=>  $product_detail['parent_name'] , 
                              'product_type'=>    $product_detail['product_type'] , 
                              'reorder_data'=>  $reorder_data   
                    );

                       array_push($found_products,$selected_product); 

                    }

                

               }  

               $products_data=   $reorder_report_inputs['products_data']; 
               $reorder_report_inputs['found_products']=$found_products;
 
               Cache::put($user_id."_reorder_report",    $reorder_report_inputs); 
 
          }
          else if(!empty( Cache::get($user_id."_reorder_report"))){
 

               $reorder_report_inputs=Cache::get($user_id."_reorder_report");

               $selected_products=  $reorder_report_inputs['selected_products'];

               $start_date_string=$reorder_report_inputs['start_date'];

               $end_date_string=$reorder_report_inputs['end_date'];
 
               $location=$reorder_report_inputs['location_id']; 

               $products_data=$reorder_report_inputs['products_data']; 
  
               $show_rate=$reorder_report_inputs['show_rate']; 

               $show_specrate=$reorder_report_inputs['show_specrate'];  
          
               $valuation_method=$reorder_report_inputs['valuation_method'];  

               $products_data=$reorder_report_inputs['products_data'];  

               $all_customers=$reorder_report_inputs['all_customers'];  
               $selected_locations=$reorder_report_inputs['selected_locations'];   
               $show_amount=$reorder_report_inputs['show_amount'];  

               $found_products=$reorder_report_inputs['found_products'];
 

          }
 
          $selected_products_collection = collect(  $found_products); 

          $selected_products_collection= $this->reportservice->paginate(['company_name'=>$companyname],'company.stock-reorder-report',   $selected_products_collection , 10);
 
      return view('reports.reorder-report',compact('companyname','locations' ,'start_date_string','end_date_string','product','location','all_customers' ,'stk_formulas','show_rate','show_specrate','haslocations','purchase_invoice_txn_ids','sales_invoice_txn_ids','valuation_method' ,'stock_detail_amounts','products','show_rate','show_specrate','show_chk_rate','show_chk_specrate','selected_products_collection','products_data','rootproducts','userproduct_ids','selected_products','selected_locations','locations','show_amount' ));



     }


     public function getReorderData($companyname,Request $request){



        $table_data=  TableMaster::where('Table_Name','stockreorder')->get()->toArray();


        Log::info( $table_data);



       $fields_data= FieldsMaster::where('Table_Name','stockreorder')->get()->toArray();


       Log::info(     $fields_data);
 


     }


     public function getStockReorderResult($product_id,$location_id,$closing_balance){


          if($location_id!="all"){

               $reorderqty=  StockReorder::where('product',$product_id)->where('location',$location_id)->value('quantity');

          }
          else{
             $reorderqty=  ProductMaster::where('Id',$product_id)->value('reorderqty');

             if(empty( $reorderqty)){
               $reorderqty=0; 
             }
          }

          $diff=(int)$closing_balance-(int) $reorderqty;
           
          return array('diff'=> $diff,'reorder_qty'=>(int) $reorderqty);

     }


     public function resetReorderReport($companyname){

          $user_id=Auth::user()->id;
          Cache::forget( $user_id."_reorder_report");

          return redirect()->back()->with('message','Reorder Report reset successfully'); 
     }


     public function openingStockRegister($companyname,Request $request){


          $user=Auth::user();
          $user_id=   $user->id;  
          $product="";
          $location="";
          $all_customers=array(); 
          $valuation_method="";
          $stk_formulas=array(); 

          $show_rate=false;
          $show_specrate=false;
          $purchase_invoice_txn_ids=array(); 
          $sales_invoice_txn_ids=array(); 
 
        $startEndDate = Company::getStartEndDate($companyname); 
        $stock_detail_amounts=array();

        $user_id=Auth::user()->id;

        $userproduct_ids=UserProducts::where('uid',     $user_id)->pluck('prd_grp')->toArray();
 
 
        if(   count($userproduct_ids) ==0){

          $all_products= ProductMaster::select('Product as title','id','parent')->get()->toArray();
               
          $tree=new Tree(    $all_products);

          $rootproducts=     $tree->getRootNodes();
        }
        else{

          $rootproducts=    ProductMaster::whereIn('id', $userproduct_ids)->select('Product as title','id','parent')->get()->toArray();

        }
   

        $products=ProductMaster::where('parent',0)->pluck('Product','Id')->toArray(); 

        $role_id=Session::get('role_id');

        $role_stockrate_restriction= TblRoleStockRateRestriction::where('role_id', $role_id)->select('rate','spec_rate','show_amount')->first();

        if(empty( $role_stockrate_restriction)){
          $show_chk_rate=false;
          $show_chk_specrate=false;
          $show_amount=false;
        }
        else{
          $show_chk_rate=($role_stockrate_restriction->rate==1?true:false);
          $show_chk_specrate=($role_stockrate_restriction->spec_rate==1?true:false);
          $show_amount=($role_stockrate_restriction->show_amount==1?true:false);

        }

        $selected_products=array();
        $products_data=array(); 

           $this->function4filterservice->setUserAndFromTable($user,"Location");
       
          $filteredids=$this->function4filterservice->getUserDataRestrictionIds();
          $haslocations=(count($filteredids)>0?true:false);

          $locations=   Location::orderby('location','asc')->when($haslocations==true,function($query)use($filteredids){
               $query->whereIn('Id',$filteredids);
          })->pluck('location','Id')->toArray();


          $selected_locations=array(); 
          $opening_stock_register_inputs=array();
          $selected_stock_ids=array();
          $all_locations=false;
          // Cache::forget($user_id."_opening_stockregister");

          if($request->method()=="POST"){

               
               Cache::forget($user_id."_opening_stockregister");

              $show_rate=  isset($request->rate)?true:false;

              $show_specrate= isset($request->spec_rate)?true:false; 

               $product=$request->product;

               $location=(isset($request->location)?$request->location:array()); 
 
               $selected_products=(isset($request->selected_products)?$request->selected_products:array());

                $this->reportservice->setProductTreeData(); 

                    $this->reportservice->parent_product_ids=  $selected_products;

                    $found_product_ids=$this->reportservice->getAllProductWithChildIds();
            
     
               $this->reportservice->product_ids=  $found_product_ids;
               $this->reportservice->setProductNodes();
               $selected_products=  $this->reportservice->getProductsSequentially();

               $all_locations=(isset($request->all_locations)?true:false);
    
               $selected_locations=   $location; 

               $product_data=array(); 

               foreach(    $selected_products as     $selected_product){
 

                    // $product_name=ProductMaster::where('Id',$selected_product)->value('Product');

                    $product_detail=$this->reportservice->getProductDetailById($selected_product);
 
                          $opening_stock_data=array();


                         foreach(      $selected_locations as       $selected_location){


                              $opening_detail=$this->reportservice->getOpeningStockDetail($selected_location,$selected_product);

                              if(empty($opening_detail)){
                                   continue;
                              } 

                              $product_data[$opening_detail['id']]=  array(
                                   'product_id'=>$selected_product,
                                   'product_name'=> $product_detail['product_name'] , 
                                   'parent_name'=>  $product_detail['parent_name'] , 
                                   'product_type'=>    $product_detail['product_type'] , 
                                    'location'=>$locations[$selected_location],
                                    'docno'=> $opening_detail['docno'],
                                    'docdate'=>$opening_detail['docdate'],
                                    'qty'=> $opening_detail['qty'],
                                    'rate'=>$opening_detail['rate'],
                                    'amount'=>$opening_detail['amount'], 
                                  ); 
 
                              array_push(   $selected_stock_ids,$opening_detail['id']); 
                         }
 


               }

               $opening_stock_register_inputs =array( 
                    'selected_locations'=>    $selected_locations ,
                    'locations'=> $locations ,
                    'show_amount'=>     $show_amount ,
                    'product_data'=>   $product_data,
                    'selected_stock_ids'=>$selected_stock_ids ,
                    'selected_products'=> $selected_products ,
                    'all_locations'=>  $all_locations
              );
               
               $products_data=   $opening_stock_register_inputs['product_data']; 
 
 
               Cache::put($user_id."_opening_stockregister", $opening_stock_register_inputs); 
 
          }
          else if(!empty( Cache::get($user_id."_opening_stockregister"))){
 

               $opening_stock_register_inputs=Cache::get($user_id."_opening_stockregister");
               $selected_stock_ids =  $opening_stock_register_inputs['selected_stock_ids'];
               $products_data=   $opening_stock_register_inputs['product_data'];  
               $selected_products=  $opening_stock_register_inputs['selected_products']; 
               $selected_locations=$opening_stock_register_inputs['selected_locations'];   
               $show_amount=$opening_stock_register_inputs['show_amount'];   
               $all_locations=$opening_stock_register_inputs['all_locations'];  
          }
 
          $selected_stock_ids_collection = collect(  $selected_stock_ids); 

          $selected_stock_ids_collection = $this->reportservice->paginate(['company_name'=>$companyname],'company.stock-open-stockregister',   $selected_stock_ids_collection ,10);
 
      return view('reports.opening-stockregister',compact('companyname','locations' , 'product','location','all_customers' ,'stk_formulas','show_rate','show_specrate','haslocations','purchase_invoice_txn_ids','sales_invoice_txn_ids','valuation_method' ,'stock_detail_amounts','products','show_rate','show_specrate','show_chk_rate','show_chk_specrate','selected_stock_ids_collection','products_data','rootproducts','userproduct_ids','selected_products','selected_locations','locations','show_amount','selected_stock_ids','all_locations' ));




     }


     public function resetOpeningStockRegister($companyname,Request $request){

          $user_id=Auth::user()->id;
          Cache::forget($user_id."_opening_stockregister");

          return redirect()->back()->with('message','Opening Stock Register Reset successfully');

     }


     public function downloadReorderReport($companyname,$format="xlsx"){

          $user_id=Auth::user()->id;
 
          $data_array=  Cache::get($user_id."_reorder_report");

          $random_name=$this->createFileReorderReportForDownload($format,$data_array);

          $download_file_name="stock_reorder_report.".strtolower($format);
          
          return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);

 
     }

     public function createFileReorderReportForDownload($format,$data_array){

             
          $user_id=Auth::user()->id;

          $random_name=time()."-".$user_id.".".strtolower($format);
 

          if(strtolower($format)=="pdf"){
               $pdf = PDF::loadView('reports.downloadformats.reorder_report_format',$data_array)->setPaper('a3')->setOrientation('landscape');
               $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
          }
          else{ 
    
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.reorder_report_format' ,$data_array ),  '/download_reports/'.$random_name ,'public');
    
          } 

          return    $random_name;

          
          // return view('reports.downloadformats.reorder_report_format',    $data_array);

     }


     public function sendEmailWhatsappStockReorderReport($companyname,Request $request){

          extract($request->all());
          $user_id=Auth::user()->id;

          $data_array=  Cache::get($user_id."_reorder_report");
  
     
          $random_name= $this->createFileReorderReportForDownload($format ,$data_array);

          $filepath='public/download_reports/'.$random_name; 
          $this->reportservice->setSmtpUniversalSettings();

          $showfilename="Stock Reorder Report";

          Helper::connectDatabaseByName('Universal'); 

          $subject="Stock Reorder Report";

          if(!empty($emails)){
               $this->reportservice->subject=  $subject;
               $this->reportservice->body="";
               $this->reportservice->filepath='app/'.$filepath;
               $this->reportservice->showfilename=    $showfilename.".".strtolower($format);
               $this->reportservice->to_email=$emails; 
               $this->reportservice->SendReportToMail(); 
               $message="Email Request submitted successfully";
          }

          if(!empty($whatsapp_no)){

               $pdf_downloaded_url=asset('storage/download_reports/'.$random_name); 

               $all_whatsapp_numbers=explode(",",$whatsapp_no);

               foreach($all_whatsapp_numbers as $single_whatsapp){
                    $this->whatsappservice->mob_num= "91".$single_whatsapp;
                    $this->whatsappservice->first_name="Anonymus";
                    $this->whatsappservice->last_name="Anonymus";
                    $this->whatsappservice->gender="male";
                    $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){
                        
                        $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                        $this->whatsappservice->whatsapp_template_id="874152";
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                    }

               } 
               $message="Whatsapp sended successfully";
          }


         Helper::connectDatabaseByName(Session::get('company_name'));


         return response()->json(['status'=>'success','message'=>    $message]);



     }

     public function downloadOpeningStockReport($companyname,$format="xlsx"){
          $user_id=Auth::user()->id;

          $data_array= Cache::get($user_id."_opening_stockregister");


        $random_name= $this->createFileOpeningStockReportForDownload($format,$data_array);
 
         $download_file_name="Stock Opening Report.".strtolower($format);

               
         return response()->download(storage_path('app/public/download_reports/'. $random_name),  $download_file_name);
 

     }

     public function createFileOpeningStockReportForDownload($format,$data_array){


          $user_id=Auth::user()->id;

          $random_name=time()."-".$user_id.".".strtolower($format);
 

          if(strtolower($format)=="pdf"){
               $pdf = PDF::loadView('reports.downloadformats.opening_stockregister_format',$data_array)->setPaper('a3')->setOrientation('landscape');
               $pdf->save(storage_path('app/public/download_reports')."/". $random_name);
          }
          else{ 
    
               Excel::store(new ExportExcelCsvView( 'reports.downloadformats.opening_stockregister_format' ,$data_array ),  '/download_reports/'.$random_name ,'public');
    
          } 

          return    $random_name;

          
          // return view('reports.downloadformats.opening_stockregister_format',$data_array);

     }


     public function sendEmailWhatsappOpeningStockReport($companyname,Request $request){

          extract($request->all());
          $user_id=Auth::user()->id; 

          $data_array= Cache::get($user_id."_opening_stockregister"); 

          $random_name= $this->createFileOpeningStockReportForDownload($format ,$data_array);

          $filepath='public/download_reports/'.$random_name; 
          $this->reportservice->setSmtpUniversalSettings();

          $showfilename="Opening Stock Register";

          Helper::connectDatabaseByName('Universal'); 

          $subject="Opening Stock Register";

          if(!empty($emails)){
               $this->reportservice->subject=  $subject;
               $this->reportservice->body="";
               $this->reportservice->filepath='app/'.$filepath;
               $this->reportservice->showfilename=    $showfilename.".".strtolower($format);
               $this->reportservice->to_email=$emails; 
               $this->reportservice->SendReportToMail(); 
               $message="Email Request submitted successfully";
          }

          if(!empty($whatsapp_no)){

               $pdf_downloaded_url=asset('storage/download_reports/'.$random_name); 

               $all_whatsapp_numbers=explode(",",$whatsapp_no);

               foreach($all_whatsapp_numbers as $single_whatsapp){
                    $this->whatsappservice->mob_num= "91".$single_whatsapp;
                    $this->whatsappservice->first_name="Anonymus";
                    $this->whatsappservice->last_name="Anonymus";
                    $this->whatsappservice->gender="male";
                    $result= $this->whatsappservice->getUserIdFromMobNumber(); 

                    if($result['status']=="success"){
                        
                        $this->whatsappservice->pdf_link=   $pdf_downloaded_url;
                        $this->whatsappservice->whatsapp_template_id="874152";
                        $this->whatsappservice->sendPdfLinkOnWhatsApp();  
                    }

               } 
               $message="Whatsapp sended successfully";
          }


         Helper::connectDatabaseByName(Session::get('company_name'));


         return response()->json(['status'=>'success','message'=>    $message]);

     }
}
