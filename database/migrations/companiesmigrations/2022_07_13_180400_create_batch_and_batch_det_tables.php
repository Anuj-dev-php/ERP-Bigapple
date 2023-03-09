<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Services\TableFieldService;
use App\Models\Code;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $manufacturetable=TableMaster::where('Table_Name','manufacturingorder')->first();

        $maintabledata=array( 'Table_Name'=>'batch'
        ,'Field_Name'=> $manufacturetable->Field_Name
        ,'Tab_Id'=> $manufacturetable->Tab_Id
        ,'Parent Table'=> $manufacturetable->{'Parent Table'}
        ,'Stock Operation'=>$manufacturetable->{'Stock Operation'}
        ,'LinkId'=>$manufacturetable->LinkId
        ,'Status' =>$manufacturetable->Status
        ,'Receivable'=>$manufacturetable->Receivable
        ,'table_label'=>"Batch"
        ,'txn_class'=>"Purchase Invoice"
        ,'cr_chk'=>$manufacturetable->c_chk
        ,'bd_chk'=>$manufacturetable->bd_chk
        ,'t_type'=>$manufacturetable->t_type
        ,'ngt_chk'=>$manufacturetable->ngt_chk
        ,'qty_zero'=>$manufacturetable->qty_zero
        ,'auto_bill'=>$manufacturetable->auto_bill
        ,'direct_print'=>$manufacturetable->direct_print
        ,'direct_sms'=>$manufacturetable->direct_sms);

        TableMaster::insert($maintabledata);

        
        $manufacture_dettable=TableMaster::where('Table_Name','manufacturingorder_det')->first();

        
        $maintabledata_det=array( 'Table_Name'=>'batch_det'
        ,'Field_Name'=> $manufacture_dettable->Field_Name
        ,'Tab_Id'=> $manufacture_dettable->Tab_Id
        ,'Parent Table'=>"batch"
        ,'Stock Operation'=>$manufacture_dettable->{'Stock Operation'}
        ,'LinkId'=>$manufacture_dettable->LinkId
        ,'Status' =>$manufacture_dettable->Status
        ,'Receivable'=>$manufacture_dettable->Receivable
        ,'table_label'=>"Batch Det"
        ,'txn_class'=>"Purchase Invoice"
        ,'cr_chk'=>$manufacture_dettable->c_chk
        ,'bd_chk'=>$manufacture_dettable->bd_chk
        ,'t_type'=>$manufacture_dettable->t_type
        ,'ngt_chk'=>$manufacture_dettable->ngt_chk
        ,'qty_zero'=>$manufacture_dettable->qty_zero
        ,'auto_bill'=>$manufacture_dettable->auto_bill
        ,'direct_print'=>$manufacture_dettable->direct_print
        ,'direct_sms'=>$manufacture_dettable->direct_sms);

        TableMaster::insert($maintabledata_det);


        FieldsMaster::insert(['Id'=>1
        ,'Field_Id'=>'F1F'
        ,'Table_Name'=>'batch'
        ,'Field_Name'=>'Id'
        ,'Field_Type'=>'integer'
        ,'Field_Size'=>0
        ,'Field_Function'=>12 
        ,'Tab_Id'=>'None' 
        ,'Allow Null'=>'False'
        ,'Is Primary'=>'True' 
        ,'Formula Field'=>''
        ,'Tab Seq'=>0
        ,'Searchable'=>'False'
        ,'Width'=>40
        ,'fld_label'=>'Id'
        ,'fld_unique'=>NULL
        ,'fld_post'=>'False'
        ,'lbl_width'=>150
        ,'min_char'=>NULL    ,'Created_By'=>'admin']);

        
        Schema::create("batch", function($table)
        {
            $table->increments('Id');
        });
 

        

        FieldsMaster::insert(['Id'=>1
        ,'Field_Id'=>'F1F'
        ,'Table_Name'=>'batch_det'
        ,'Field_Name'=>'Id'
        ,'Field_Type'=>'integer'
        ,'Field_Size'=>0
        ,'Field_Function'=>12 
        ,'Tab_Id'=>'None' 
        ,'Allow Null'=>'False'
        ,'Is Primary'=>'True' 
        ,'Formula Field'=>''
        ,'Tab Seq'=>0
        ,'Searchable'=>'False'
        ,'Width'=>40
        ,'fld_label'=>'Id'
        ,'fld_unique'=>NULL
        ,'fld_post'=>'False'
        ,'lbl_width'=>150
        ,'min_char'=>NULL    ,'Created_By'=>'admin']);

        
        Schema::create("batch_det", function($table)
        {
            $table->increments('Id');
        });


        $fields=FieldsMaster::where('Table_Name','manufacturingorder')->where('Field_Name','!=','Id')->get()->toArray();

        $allfields=array();

        $index=0;
        foreach(  $fields as $field){

            $fields[$index]['Table_Name']='batch';
 
            array_push(  $allfields, $fields[$index]);

            $index++;
        }
 
        FieldsMaster::insert( $allfields);

        $fields_det=FieldsMaster::where('Table_Name','manufacturingorder_det')->where('Field_Name','!=','Id')->get()->toArray();

        
        $allfields_det=array();
        $index=0;
        foreach(  $fields_det as $field_det){
            $fields_det[$index]['Table_Name']='batch_det';
            
            array_push(  $allfields_det, $fields_det[$index]);

            $index++;
        }
 

        FieldsMaster::insert( $allfields_det);

        
        $batchfields=FieldsMaster::where('Table_Name','batch')->where('Field_Name','!=','Id')->select('Table_Name','Field_Size' ,'Field_Name','Field_Type','Allow Null')->get()->toArray();

        
        // create fields in that database table as well for both batch and batch fields

        $tablefieldservice=new TableFieldService;
  

        foreach( $batchfields as  $batchfield){ 
 
            if(!Schema::hasColumn('batch', $batchfield['Field_Name'])){

                $tablefieldservice->data=$batchfield;
                $tablefieldservice->AddTransactionTableField();
                
            }
         

        }


        
        
        $batchfields_det=FieldsMaster::where('Table_Name','batch_det')->where('Field_Name','!=','Id')->select('Table_Name','Field_Size' ,'Field_Name','Field_Type','Allow Null')->get()->toArray();
 ;
        
        foreach(   $batchfields_det as    $batchfield_det){

            
            if(!Schema::hasColumn('batch_det', $batchfield_det['Field_Name'])){
            
                    $tablefieldservice->data=$batchfield_det;
                    $tablefieldservice->AddTransactionTableField();
               }

        }

        Code::insert(array('table_name'=>'batch','Field'=>'docno','prefix'=>'BN-','code'=>1,'suffix'=>'-1920'));

        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Code::where('table_name','batch')->delete();

        TableMaster::whereIn('Table_Name',array('batch','batch_det'))->delete();

        FieldsMaster::whereIn('Table_Name',array('batch','batch_det'))->delete();

        Schema::dropIfExists('batch');
        
        Schema::dropIfExists('batch_det');

    }
};
