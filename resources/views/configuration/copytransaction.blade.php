@extends('layout.layout')
<style>
 
	</style>
@section('content')

<h2 class="menu-title">Copy Transaction</h2>
<div class="pagecontent">
	<div class="container mtb-2">
<center>
   <form method="post"  action="/{{$companyname}}/submitcopytransaction" >
      @csrf
      
		<div class="row">  
                <div class="form-group col-7 mx-auto mtb-1 marginpart1">
                        <label class="lbl_control ">Select Company</label> 
                    <select class="form-control select-configure "  name="company" id="ddnCompany"   required >
                       <option value=''>Select Company</option>
                       @foreach (  $companies as   $company )
                       <option value="{{$company->db_name}}">{{$company->comp_name}} (   {{date("Y",strtotime( $company->fs_date))}}-{{date("Y",strtotime( $company->fe_date))}}  ) ({{$company->db_name}})</option>
                       @endforeach
                     
                    </select>
                 </div>
                 <div class="clearfix"></div>
               

                 <div class="form-group  col-7 mx-auto  mtb-1 marginpart1">
                        <label class="lbl_control ">Select Transaction</label> 
                    <select class="form-control select-configure"  name="transaction" id="ddnTransactions"  required ><option>Select Transaction</option></select>
                 </div>
                 <div class="clearfix"></div>
 
                 <div class="form-group col-7 mx-auto  mtb-1 marginpart1">
                        <label class="lbl_control ">New Transaction</label>  
                    <input type="text" name="newtransaction_name" class="form-control select-configure" id="newtransaction_name"  required />
                 </div>
                 <div class="clearfix"></div>
                 <div class="form-group col-7 mx-auto  mtb-1 marginpart1 ">
                        <label class="lbl_control ">New Transaction Label</label>  
                    <input type="text" name="newtransaction_label" class="form-control select-configure"  required />
                 </div>

                 <div class="clearfix"></div>
                 <div class="form-group col-5 mx-auto  mtb-1 text-center">
                         <input type="submit" class="btn btn-primary"  value="Submit" />  &nbsp; &nbsp;

                         <input type="button" class="btn btn-primary"  value="Cancel"  id="btn_cancel_reload" /> 


                 </div> 
       </div>

    </form>
</center>
   </div>
</div> 
@endsection 
@section('js') 
<script type="text/javascript">

   $("#ddnCompany").change(function(){

      var dbname=$(this).val();
      
      $("#ddnTransactions  option:not(:first)").remove();

      $.get('/'+dbname+'/get-company-transactions',function(data,status){

         var tables=JSON.parse(JSON.stringify(data));


         for(let table of tables){
            $("#ddnTransactions").append("<option value='"+table['Id']+"'>"+table['table_label']+"</option>");

         }
 
      });
   });



   
   $("#newtransaction_name").on('keypress', function(e) {
			
         if (e.which == 32){ 
             return false;
         }
     });





</script> @endsection