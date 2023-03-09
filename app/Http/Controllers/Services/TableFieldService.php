<?php
namespace App\Http\Controllers\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use App\Models\TableMaster;
use App\Models\FieldsMaster;
use App\Models\Code;

class TableFieldService{


    public $tablename;
    public $field;
    public $data=array();
    public $newtablename;
    public $newtablelabel;
    public $tran_id;
    public $tabid;
    public $user;


    public function createNewTable(){


        FieldsMaster::insert(['Id'=>1
        ,'Field_Id'=>'F1F'
        ,'Table_Name'=>$this->tablename
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
        ,'min_char'=>NULL    ,'Created_By'=>trim($this->user->user_id)]);
 
        Schema::create($this->tablename, function($table)
        {
            $table->increments('Id');
        });

        if($this->tabid=="Details"){

            Schema::table($this->tablename, function (Blueprint $table) {
                $table->integer('fk_id');
            });
 
        }
        else if($this->tabid=="Sub Details"){
            
            Schema::table($this->tablename, function (Blueprint $table) {
            $table->integer('fk_id');
            $table->integer('fk_id_id');
        });

        }




 


    }


    public function AddTransactionTableField(){
 
        $data=$this->data;

        $data['Allow Null']=ucfirst(strtolower($data['Allow Null']));
  
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



    public function UpdateTransactionTableField(){

        $data=$this->data;
        $data['Allow Null']=ucfirst(strtolower($data['Allow Null'])) ; 
    
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


    public function copyNewTable(){

        $tablefound=TableMaster::find($this->tran_id); 

        $newtransaction= $tablefound->replicate();

        $newtransaction->Table_Name=    $this->newtablename;

        $newtransaction->table_label=  $this->newtablelabel;

        $newtransaction->{'Parent Table'}='None';

        $newtransaction->save(); 

        $tablefields=FieldsMaster::where('Table_Name', $tablefound->Table_Name)->orderby('Id','asc')->get();

        $index=1;
 
        foreach($tablefields as $tablefield){

            $newtablefield=$tablefield->replicate();
            $newtablefield->Table_Name= $this->newtablename; 
            $newtablefield->Id=$index;
            $newtablefield->save();
            $index++;
 
        }
 

        DB::statement("select * into ".$this->newtablename." from ".$tablefound->Table_Name." where 1=2");

        // check if det table exists

        $tablenamewithdet=   $tablefound->Table_Name.'_det';

        $tablefound1=TableMaster::where('Table_Name',$tablenamewithdet)->first();


        if(!empty($tablefound1)){
 

            $newtransactiondet= $tablefound1->replicate();

            $newtransactiondet->Table_Name= $this->newtablename."_det"; 
              
            $newtransactiondet->table_label=  $this->newtablelabel." Det";

            $newtransactiondet->{'Parent Table'}=$this->newtablename;

            $newtransactiondet->save();  


            $tablefieldsdet=FieldsMaster::where('Table_Name',$tablenamewithdet)->orderby('Id','asc')->get();

            $index=1;
     
            foreach( $tablefieldsdet as $tablefield){
    
                $newtablefield=$tablefield->replicate();
                $newtablefield->Table_Name= $this->newtablename."_det"; 
                $newtablefield->Id=$index;
                $newtablefield->save();
                $index++;
     
            }

           DB::statement("select * into ".$this->newtablename."_det  from ".$tablenamewithdet." where 1=2"); 
        }   


        // add also in code

       $newprefix= strtoupper($this->newtablename);
       
       $newprefix=substr($newprefix,0,3);
       $newprefix=$newprefix."-";
 
        Code::insert(array('table_name'=>$this->newtablename,'Field'=>'docno','prefix'=>  $newprefix,'code'=>1,'suffix'=>'-1920'));


    }

}

?>