@extends('layout.layout')
@inject('reportservice','App\Http\Controllers\Services\ReportService')
@php
$reportservice->user_id=\Auth::user()->id;
@endphp
<style>
.tree_checkbox_lbl {
	background-color: rgb(255, 255, 255);
	color: rgb(0, 0, 0);
	cursor: pointer;
}


</style> @section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading"> {{$report_name}}</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">


	@if($open_childaccounts==false)
<form method='post' @if($report_name=="Trial Balances")  action="{{url('/')}}/{{$companyname}}/trial-balances"  @else  action="{{url('/')}}/{{$companyname}}/treestyle-trial-balances"  @endif >
  @csrf
		<div class='row'>
			<div class="col-md-4  " style=' height:200px;overflow-y:scroll;'>
			<input type='text'  name='search_account'  style='width:50%;outline:none;'  id='search_txt'  /> &nbsp; <input type='button' class='btn btn-primary' value='Search'  id="btn_search_account" />
			
			<div class='hummingbird-treeview'>
			<ul id="menu_level_tree" class="hummingbird-base" style="padding-left:0px!important;">
         @foreach ($accounts as $account  )
					<li data-id="{{$account['id']}}"> <i class="fa fa-plus" data-level="1" tabIndex="1" data-id="{{$account['id']}}"></i>
					<a class='ga_link'  data-id="{{$account['id']}}"  data-level="1" data-ga="{{trim($account['ga'])}}">
						<label class="tree_checkbox_lbl"> 
							{{$account['account_name']}} (Level 1)</label>
					</a>
					</li> @endforeach 
				</ul>
				</div>
			</div>
			<div class="col-md-8" style=' height:200px;'>
				<div class='row'>
					<div class="form-group col-6 mtb-2 ">
						<label class="lbl_control_inline">From Date :</label>
						<input type='text' class="form-control inline_control " name='start_date' value="@if(!empty($start_date_string)){{$start_date_string}}@else{{date('d-m-Y',strtotime('now'))}}@endif" id='start_date' /> </div>
					<div class="form-group col-6  mtb-2">
						<label class="lbl_control_inline">To Date :</label>
						<input type='text' class="form-control inline_control " name='end_date' value="@if(!empty($end_date_string)){{$end_date_string}}@else{{date('d-m-Y',strtotime('now'))}}@endif" id='end_date' /> </div>
					
						<div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Account Level :</label>
						<!-- <input type='number' min="1" max='25' class="form-control inline_control " name='selected_account_level'  @if(isset($selected_account_level)   ) value="{{$selected_account_level}}"  @endif /> -->
						
						<select class="form-control inline_control " name='selected_account_level' >
							<option value="">All Level</option>
							@for ($i=1;$i<=25;$i++)
							<option value="{{$i}}"   @if(isset($selected_account_level) && $selected_account_level==$i  ) selected   @endif   >{{$i}}</option>
								
							@endfor
						</select>
					
					
					</div>

						 	
						<div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Cost Center :</label>
							<select  class="form-control inline_control"  name="cost_center">
								<option value="">All</option>
								 @foreach ($costcenters as $costcenter)
								 <option value="{{$costcenter->Id}}">{{$costcenter->Name}}</option>
									 
								 @endforeach
							</select>
						   </div>

						   <div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Division :</label>

						<select  class="form-control inline_control"  name="division">
								<option value="">All</option>
								 @foreach ($divisions as $div_key=>$div_value)
								 <option value="{{$div_key}}"  @if(isset($division) && $division==$div_key) selected @endif>{{$div_value}}</option>
									 
								 @endforeach
							</select>
						   </div>
					
			 
			 

					<div class="form-group col-6 mtb-1 " style='text-align:left;'>
					
						<input class="form-check-input" type="checkbox"  name="showzeros" value="1"  @if(isset($showzeros) && $showzeros==1)) checked  @endif>
						<label class="check-1" for="showzeros">Show Zeros</label>

						&nbsp;&nbsp;

						<input class="form-check-input" type="checkbox"  name="showforeigncurrency" value="1"  @if(isset($show_foreigncurrency) && $show_foreigncurrency==1)) checked  @endif>
						<label class="check-1" for="showzeros">Show Foreign Currency</label>




					</div>
					<div class="form-group col-12 mtb-1 " style='text-align:center;'>
						<input type='submit' class='btn btn-primary' value='Submit' />
						<input type='button' class='btn btn-primary' value='Cancel' id='btncancel' /> </div>
				</div>
			</div>
		</div>

</form>
@endif
		<div class='row'>
			

	@if($open_childaccounts==true)
			<h6>Account Name: {{$single_account_name}}</h6>
			@endif
			<div class="col-md-12 mx-auto">
			@if(count($selected_accounts_data)>0 )
			<input type="button" class="btn btn-primary"  value="XLSX" onclick="downloadDocument('xlsx')" />
						
						&nbsp;		&nbsp;  <input type="button" class="btn btn-primary"  value="PDF" onclick="downloadDocument('pdf')" />
 
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary"  value="CSV" onclick="downloadDocument('csv')" />
			@endif			
      <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">

			


							<table class="table  table-striped taboncell"  >
                <thead><th>Account Id</th>  <th>Account Name</th>  
				@if( $open_childaccounts==false)
					
					<th>Parent Name</th> 
					@endif
				<th>Account Type</th>
					 
					
				<th>Opening Debit Balances</th><th>Opening Credit Balances</th><th>Total Debits</th><th>Total Credits</th><th>Closing Debit Balances</th><th>Closing Credit Balances</th>
		        	@if($show_foreigncurrency==1)
					<th>Fcamt Opening Debit Balances</th><th>Fcamt Opening Credit Balances</th><th>Fcamt Total Debits</th><th>Fcamt Total Credits</th><th>Fcamt Total Credit Balances</th><th>Fcamt Total Debit Balances</th>
					@endif
			
			</thead>

                <tbody>
					@if(count($selected_accounts_data)==0)
                  <tr><td colspan='9' class='text-center'>No Data</td></tr>
				  @else

				  @foreach ( $selected_accounts_data as $selected_account_id )
				  @php

				  if($report_name=="Trial Balances"){	$tree_account_detail= $reportservice->getTrialAccountDetail($selected_account_id,$open_childaccounts);

				
				  }
				  else{
					$tree_account_detail= $reportservice->getTreeStyleAccountDetail($selected_account_id,$open_childaccounts);

				  }
				  
				  @endphp


				  <tr>
				  <td  tabindex="1">{{$selected_account_id}}</td>	
				  <td  tabindex="1"> <a class="link_open_child_accounts" href="javascript:void(0);"  tabindex="1" data-accountid="{{$tree_account_detail['account_id']}}">{{  $tree_account_detail['account_name']}} </a></td> 
				  @if( $open_childaccounts==false)
			
						<td  tabindex="1"> @if(!empty($tree_account_detail['parent_name'])) {{  $tree_account_detail['parent_name'] }} @endif </td>
						@endif
				  <td  tabindex="1"> <a class='link_open_general_sub_ledger' href="javascript:void(0);"  data-accounttype="{{$tree_account_detail['account_type']}}"  data-accountid="{{$tree_account_detail['account_id']}}">{{$tree_account_detail['account_type']}}</a></td>
					 
					  
					 
					 <td  tabindex="1">{{$tree_account_detail['opening_debitbalance']}}</td><td  tabindex="1">{{$tree_account_detail['opening_creditbalance']}}</td><td  tabindex="1">{{$tree_account_detail['total_debit']}}</td><td  tabindex="1">{{$tree_account_detail['total_credit']}}</td><td  tabindex="1">{{$tree_account_detail['closing_debit_balance']}}</td><td  tabindex="1">{{$tree_account_detail['closing_credit_balance']}}</td>
						 
						   @if($show_foreigncurrency==1)

							   <td  tabindex="1">{{$tree_account_detail['fcamt_opening_debitbalance']}}</td>
							   <td  tabindex="1">{{$tree_account_detail['fcamt_opening_creditbalance']}}</td>
							   <td  tabindex="1">{{$tree_account_detail['fcamt_total_debit']}}</td>
							   <td  tabindex="1">{{$tree_account_detail['fcamt_total_credit']}}</td>
							   <td  tabindex="1">{{$tree_account_detail['fcamt_closing_debit_balance']}}</td>
							   <td  tabindex="1">{{$tree_account_detail['fcamt_closing_credit_balance']}}</td> 
						   @endif

						   </tr>



					  
				  @endforeach
				    
				  @endif
                </tbody>
				<tfoot>
				<tr>
						<td 	@if( $open_childaccounts==false)    colspan="4" @else    colspan="3"  @endif    >Total:</td>  
							  <td>@if(count($alltotals)>0){{$alltotals[0]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[1]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[2]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[3]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[4]}} @endif</td>  
				  			<td>@if(count($alltotals)>0){{$alltotals[5]}} @endif</td>
						@if($show_foreigncurrency==1)
							  <td>@if(count($alltotals)>0){{$alltotals[6]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[7]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[8]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[9]}} @endif</td>
							  <td>@if(count($alltotals)>0){{$alltotals[10]}} @endif</td>  
							  <td>@if(count($alltotals)>0){{$alltotals[11]}} @endif</td> 
						@endif
					</tr>


					<tr>
						<td  @if( $open_childaccounts==false)  colspan="4" @else  colspan="3"  @endif    >Difference:</td>
					 
						<td colspan="2" tabIndex="1">{{round($total_opening_debitcredit_diff,2)}}</td><td colspan="2"  tabIndex="1">{{round($total_total_debit_credit_diff,2)}}</td><td  tabIndex="1" colspan="2">{{round($total_closing_debit_credit_balance_diff,2)}}</td>

						
						@if($show_foreigncurrency==1)
						<td colspan="2"  tabIndex="1">{{round($fcamt_total_opening_debitcredit_diff,2)}}</td>
						<td colspan="2"  tabIndex="1">{{round($fcamt_total_total_debit_credit_diff,2)}}</td>
						<td colspan="2"  tabIndex="1">{{round($fcamt_total_closing_debit_credit_balance_diff,2)}}</td>

						@endif
					</tr>
 
				</tfoot>

				<!-- firstlevel_accountids_data -->
              </table>
			  
	@if($open_childaccounts==false)
			  <div>
				  {{$selected_accounts_data->links()}}
				</div>
				@endif
            </div>
        </div>
        </div>
        
    
    
    
    
        </div>
		</div>
	</div>
</div> @endsection @section('js')
<script src="{{ asset('js/checkboxtree.min.js') }}"></script>
<script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script>
<script src="{{ asset('js/taboneachcell.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function() {
	$("#menu_level_tree").hummingbird();
	$('#start_date').datetimepicker({
		format: 'd-m-Y',
		timepicker: false,
		datepicker: true,
		dayOfWeekStart: 1,
		yearStart: 2016,
	});
	$('#end_date').datetimepicker({
		format: 'd-m-Y',
		timepicker: false,
		datepicker: true,
		dayOfWeekStart: 1,
		yearStart: 2016,
	});
});



$("#menu_level_tree").on('keypress', '.fa-plus , .fa-minus', function(event) {

	$(this).trigger('click');
});
  


$("#menu_level_tree").on('click', '.fa-plus , .fa-minus', function(event) {
	var id = $(this).data('id');
	if($(`#menu_level_tree li[data-id='${id}']`).data('hasdata') == 'yes') {
		return false;
	}
	var level= $(this).data('level');
	level=level+1;
	// <input type='checkbox'   name='accounts[]'  value='${account['id']}'>&nbsp
	$.ajax({
		url: "{{url('/')}}/{{$companyname}}/get-child-accounts/" + id,
		type: "get",
		async: false,
		success: function(data) {
			var result = JSON.parse(JSON.stringify(data));
			var accounts = result['accounts'];
			var html = '<ul>';
			for(let account of accounts) {

				var ga_val=account['ga'].trim();

				html = html + `<li data-id='${account['id']}'> <i class='fa fa-plus'  tabIndex='1'  data-level='${level}'  data-id='${account['id']}' ></i> <a  data-id='${account['id']}'  data-level='${level}'   class='ga_link' href='javascript:void(0);' data-ga='${ga_val}'><label  class='tree_checkbox_lbl'>&nbsp;${account['account_name']} (Level ${level})</label></a></li>`;
			}
			html = html + '</ul>';
			$(`#menu_level_tree li[data-id='${id}']`).append(html);
			$(`#menu_level_tree li[data-id='${id}']`).data('hasdata', 'yes');
			$("#menu_level_tree").hummingbird();
		},
		error: function() {}
	});
});

 
$("#menu_level_tree").on('click', '.ga_link', function(event) {
		var ga=$(this).data('ga');
		var dataid=$(this).data('id');
		var level=$(this).data('level');

		var reportname="{{$report_name}}";

		if(ga=='G'){

			
				if(reportname=="Trial Balances"){
					
		        	var url="{{url('/')}}/{{$companyname}}/trial-balances-of-g-type/"+dataid+'/'+level;
				}
				else{

					var url="{{url('/')}}/{{$companyname}}/treestyle-trial-balances-of-g-type/"+dataid+'/'+level;
				}


			window.open(url, "_blank"); 
		}
		else{
				 

			var url="{{url('/')}}/{{$companyname}}/treestyle-trial-balances-of-a-type/"+dataid;
			
			$.ajax({ type: "POST",
							async: false,
							url: "{{url('/')}}/{{$companyname}}/set-report-free-style-search-values-in-session",
							data:{  'start_date':$("#start_date").val().trim() ,'end_date':$("#end_date").val().trim() ,'cost_center':$("#ddn_cost_center").val().trim() ,'department':$("#ddn_department").val().trim() },
							success:function(data,status){  
 
		                     	window.open(url, "_blank"); 
							   
							} 
						});


		}
 
});


$("#btncancel").click(function() {

	@if($report_name=="Trial Balances") 
	var url="{{url('/')}}/{{$companyname}}/cancel-cache-report-input-by-name/trial-balances";

	@else
	var url="{{url('/')}}/{{$companyname}}/cancel-cache-report-input-by-name/treestyle-trial-balances";

	@endif


	$.get(url,function(data,status){

var result=JSON.parse(JSON.stringify(data));

if(result['status']=='success'){ 
	
	@if($report_name=="Trial Balances") 

	window.location.href = "{{url('/')}}/{{$companyname}}/trial-balances";
	
	@else

	window.location.href = "{{url('/')}}/{{$companyname}}/treestyle-trial-balances";
	
	@endif
}

});


});


$("#btn_search_account").click(function(){

	var searchtxt=$("#search_txt").val();

	$.get("{{url('/')}}/{{$companyname}}/search-account-in-tree/"+searchtxt,function(data,status){

		var result=JSON.parse(JSON.stringify(data));

		var locations=result['locations'];

		if(result['status']==true && locations.length>0){

			for(let location of locations){
				
		     	SnackbarMsg({'status':'success','message':location});

			} 
		}
		else{
			SnackbarMsg({'status':'failure','message':"No location found by this name"});
		}

	

  
	});

});


function downloadDocument(format){

	
	@if($report_name=="Trial Balances")

	@if($open_childaccounts==false) 
	var url="{{url('/')}}/{{$companyname}}/download-trial-balances/"+format;
	@else
	var url="{{url('/')}}/{{$companyname}}/download-trial-balances-drilldown-report/"+format;
	
	@endif
	

	@else

	
	@if($open_childaccounts==false) 
	var url="{{url('/')}}/{{$companyname}}/download-tree-style-trial-balances/"+format;
	@else
	var url="{{url('/')}}/{{$companyname}}/download-treestyle-drilldown-report/"+format;

	@endif
	
	@endif


  window.open(url);

}

$(".link_open_general_sub_ledger").click(function(){

	var accounttype=$(this).data("accounttype");

    var accountid=$(this).data("accountid");


	@if($open_childaccounts==true) 

			if(accounttype=='G'){
						
						window.open("{{route('company.general_ledger_new',['company_name'=>$companyname])}}", "_blank"); 
					}
					else{

						window.open("{{route('company.subledger_new',['company_name'=>$companyname])}}", "_blank"); 
					}

					return;
	   @else

			
			@if($report_name=="Trial Balances")

			var fromreportname="_trial_balances_input";

			@else
			

			var fromreportname="_tree_style_trial_balances_input";

			@endif



	    @endif
 
	$.post("{{url('/')}}/{{$companyname}}/set-general-subledger-cache-inputs",{'fromreportname': fromreportname,'accounttype':accounttype,'accountid':accountid},function(data,status){

		var result=JSON.parse(JSON.stringify(data));

		if(result['status']=="success"){ 
			if(accounttype=='G'){
				
				window.open("{{route('company.general_ledger_new',['company_name'=>$companyname])}}", "_blank"); 
			}
			else{

				window.open("{{route('company.subledger_new',['company_name'=>$companyname])}}", "_blank"); 
			}
		}
	});

});

$(".link_open_child_accounts").click(function(){
 
	var accountid=$(this).data("accountid"); 
	
	@if($report_name=="Trial Balances") 

	var fromreportname="_tree_style_trial_balances_input";
	var url="{{url('/')}}/{{$companyname}}/trial-balances-open-childaccounts/"+accountid+'/1';

	@else
	var url="{{url('/')}}/{{$companyname}}/treestyle-trial-balances-of-g-type/"+accountid+'/1';
    var fromreportname="_tree_style_trial_balances_input";
	@endif


	$.post("{{url('/')}}/{{$companyname}}/set-treestyle-trial-balance-drilldown-settings",{'from_report':fromreportname},function(data,status){
 
		var result=JSON.parse(JSON.stringify(data)); 
		if(result['status']=="success"){ 
			window.open( url, "_blank");  
		}
	});

});

</script> @endsection