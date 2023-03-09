@extends('layout.layout')
<style>
.tree_checkbox_lbl {
	background-color: rgb(255, 255, 255);
	color: rgb(0, 0, 0);
	cursor: pointer;
}
</style> @section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading"> {{$report_name}}</h4>
@php
	function displayRow($show_foreign_currency1,$account_level1,$account_tree_data1,$child_balances1,$parent_name,$parentid){

		if(array_key_exists($parentid,$account_tree_data1)){
 
  
		$child_acc_ids=$account_tree_data1[$parentid];

		$new_account_level=$account_level1+1;
 
		foreach($child_acc_ids as $child_acc_id){

			if(!array_key_exists($child_acc_id,$child_balances1)){
				continue;
			}

			$single_account_detail=$child_balances1[$child_acc_id];
			@endphp

			<tr><td>{{$single_account_detail['account_name']}}</td><td>{{$new_account_level}}</td><td>{{$parent_name}}</td><td>{{$single_account_detail['total_balances']}}</td>

			@if($show_foreign_currency1==1)
			<td>{{$single_account_detail['fcamt_total_balances']}}</td>
			@endif
		 
		</tr>

			@php

			displayRow($show_foreign_currency1,$new_account_level,$account_tree_data1,$child_balances1,$single_account_detail['account_name'] ,$single_account_detail['account_id']);


		}
				
	} 
	}

 
@endphp

<div class="pagecontent"> 
	<div class="container-fluid">


	@if($open_childaccounts==false)
<form method='post'  action="{{url('/')}}/{{$companyname}}/tree-style-trial-balances-p-and-l"  >
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
						<label class="lbl_control_inline">Start Date :</label>
						<input type='text' class="form-control inline_control " name='start_date' value="@if(!empty($start_date_string)){{$start_date_string}}@else{{date('d-m-Y',strtotime('now'))}}@endif" id='start_date' /> </div>
					<div class="form-group col-6  mtb-2">
						<label class="lbl_control_inline">End Date :</label>
						<input type='text' class="form-control inline_control " name='end_date' value="@if(!empty($end_date_string)){{$end_date_string}}@else{{date('d-m-Y',strtotime('now'))}}@endif" id='end_date' /> </div>
					
						<div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Account Level :</label>
						<input type='number' min="1" max='25' class="form-control inline_control " name='selected_account_level'  @if(isset($selected_account_level)   ) value="{{$selected_account_level}}"  @endif />
					 
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
						<label class="lbl_control_inline">Project :</label>

						<select  class="form-control inline_control"  name="project">
								<option value="">All</option>
								 @foreach ($projects as $project)
								 <option value="{{$project->Id}}">{{$project->ProjectName}}</option>
									 
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


<ul class="nav nav-tabs" id="menutablist" role="tablist">
			<li class="nav-item" role="Vertical" id="tablayout"> <a class="nav-link active" id="vertical-report-tab" data-bs-toggle="tab" href="#vertical-report" role="tab" aria-controls="verticalreport" aria-selected="true" style="font-weight: 500;">Vertical Report</a> </li>
            <li class="nav-item" role="Horizontal" id="tablayout"> <a class="nav-link " id="horizontal-report-tab" data-bs-toggle="tab" href="#horizontal-report" role="tab" aria-controls="horizontalreport" aria-selected="true" style="font-weight: 500;">Horizontal Report</a> </li>

  </ul>

        <div class="tab-content"  style="margin:20px 50px;" >
			<div class="tab-pane fade show active small" id="vertical-report" role="tabpanel" aria-labelledby="vertical-report-tab">
            <table class="table table-bordered"  >
                    <thead><th>Account Name</th>  
                            <th>Account Level</th>
                            <th>Parent Name</th>
                            <th>Amount</th>
							@if($show_foreigncurrency==1)
							<th>Fc Amount</th>
							@endif
                    </thead>
                    <tbody>
						@foreach($balances as $balance ) 
						<tr>
							<td>{{$balance['account_name']}}</td>
							<td>{{$account_level}}</td>
							<td></td>
							<td>{{$balance['total_balances']}}</td>
							@if($show_foreigncurrency==1)
							<td>{{$balance['fcamt_total_balances']}}</td>
							@endif
						</tr>
							@php

							displayRow($show_foreigncurrency,$account_level,$account_tree_data,$child_balances,$balance['account_name'],$balance['account_id']);
								
							@endphp
						@endforeach
                    </tbody>
                    <tfoot>
                       <tr> <td colspan='3'><strong>Total:</strong></th><td><strong>{{round($total_total_balances,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td>{{round($total_fcamt_total_balances,2)}}</td>
					   @endif
					</tr>
                    </tfoot>
                </table>
            </div>

            <div class="tab-pane fade show   small" id="horizontal-report" role="tabpanel" aria-labelledby="horizontal-report-tab">
                 <div class="row">
					 <div class="col-md-12 mx-auto"> 
               <table class="table  table-bordered" >
                    <thead><th >Expenses</th>   
                            <th  >Income</th> 
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <table class="table  table-bordered" >
                                    <thead><td>Particulars</td><td>Account Level</td><td>Parent</td><td>Amount</td>
								@if($show_foreigncurrency==1)
									<td>Fc Amount</td>
								@endif

							

								</thead>
                                    <tbody>
										@php
											$expenses= (array_key_exists(0,$balances)?$balances[0]:array());

										@endphp


										@if(!empty($expenses))
 
										<tr>
                                            <td>{{$expenses['account_name']}}</td> 	<td>{{$account_level}}</td> <td></td><td>{{$expenses['total_balances']}}</td> 
										 
											@if($show_foreigncurrency==1)
											<td>{{$expenses['fcamt_total_balances']}}</td>
											@endif 
                                        </tr> 
										@php
												displayRow($show_foreigncurrency,$account_level,$account_tree_data,$child_balances,$expenses['account_name'],$expenses['account_id']);
											@endphp
										@endif
                                       
                                    </tbody>
                         

                                </table>

                            </td>
                            <td>
                                    <table  class="table  table-bordered">

                                    <thead><td>Particulars</td><td>Account Level</td><td>Parent</td><td>Amount</td>
									@if($show_foreigncurrency==1)
									<td>Fc Amount</td>
									@endif
								</thead>
                                    <tbody>
									@php
											$incomes= (array_key_exists(1,$balances)?$balances[1]:array());

										@endphp

										@if(!empty($incomes))
 
										<tr>
                                            <td>{{$incomes['account_name']}}</td> 	<td>{{$account_level}}</td> <td></td><td>{{$incomes['total_balances']}}</td> 
										  	@if($show_foreigncurrency==1)
											<td>{{$incomes['fcamt_total_balances']}}</td>
											@endif  
                                        </tr> 
										
										@php
												displayRow($show_foreigncurrency,$account_level,$account_tree_data,$child_balances,$incomes['account_name'],$incomes['account_id']);
											@endphp
										@endif
                                    </tbody>

                                  
                                            
                                    </table> 
                            </td>
                        </tr>
                    </tbody>
                    <tfoot> 

					
					<tr> 
						@if(array_key_exists('total_balances',$expenses))
					<td  ><strong>Total Amount Expenses : 	{{$expenses['total_balances']}}
								 @endif


					</strong></td>
					@if(array_key_exists('total_balances',$incomes))
					<td><strong>Total Amount Income: 	 	{{$incomes['total_balances']}} 
									 
									</strong></td>
									@endif
								
								</tr>
						@php


						if(array_key_exists('total_balances',$incomes) &&  array_key_exists('total_balances',$expenses)){

							$net_diff=round( ($incomes['total_balances']-$expenses['total_balances']) ,2);
						}
						else{
							$net_diff=0;
						}


						if($net_diff>0){
							$amt_profit=$net_diff;
							$amt_loss=0;
						}
						else{
							$amt_profit=0;
							$amt_loss=$net_diff*(-1);	
						}

						if(array_key_exists('fcamt_total_balances',$incomes) && array_key_exists('fcamt_total_balances',$expenses) ){

							$net_fc_diff=round(($incomes['fcamt_total_balances']-$expenses['fcamt_total_balances']) ,2);
						}
						else{
							$net_fc_diff=0;
						}


						if(	$net_fc_diff>0){

							$amt_fc_profit=$net_fc_diff;
							$amt_fc_loss=0; 
						}
						else{
							$amt_fc_profit=0;
							$amt_fc_loss=$net_fc_diff*(-1); 

						}
							

						@endphp
						
                       <tr> <td  ><strong>Amount Profit:{{	$amt_profit}}   </strong></td><td><strong>Amount Loss:{{$amt_loss}}</strong></td></tr>
					   			
					<tr> <td  ><strong>Total Fcamount Expenses : @if(array_key_exists('fcamt_total_balances',$expenses)) {{$expenses['fcamt_total_balances']}} @endif</strong></td><td><strong>Total Fcamount Income:@if(array_key_exists('fcamt_total_balances',$incomes)) {{$incomes['fcamt_total_balances']}} @endif</strong></td></tr>
 
                       <tr> <td  ><strong>Fc Amount Profit:{{	$amt_fc_profit}}</strong></td><td><strong>Fc Amount Loss:{{$amt_fc_loss}}</strong></td></tr>
                    </tfoot>
                </table>
				
						</div>
				</div>


            </div>
        </div>


	 
	</div>
</div> @endsection @section('js')
<script src="{{ asset('js/checkboxtree.min.js') }}"></script>
<script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script>
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
	window.location.reload();
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



</script> @endsection