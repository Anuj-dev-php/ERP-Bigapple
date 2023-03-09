@extends('layout.layout')
@inject('reportservice','App\Http\Controllers\Services\ReportService')
@php
$reportservice->user_id=\Auth::user()->id;

function showChildProducts($productroot,$productlevel,$parentid="",$selected_products){
	@endphp
	
	<li data-id="{{$productroot->id}}}">   
						<label class="tree_checkbox_lbl"> 
						<input type="checkbox"  value="{{$productroot->id}}" class="menu_level_menus" data-parentid="{{$parentid}}" name="selected_products[]"  @if(in_array($productroot->id,$selected_products)) checked @endif />
							{{$productroot->title}} (Level {{$productlevel}})</label>

							@php
							 $childs=$productroot->getChildren();

							 if($childs>0){
								@endphp
								<ul class='product_list'>
								@php
								$productlevel=$productlevel+1;

								foreach($childs as $childnode){
									showChildProducts($childnode,$productlevel,$productroot->id,$selected_products);
								 

								}
								@endphp
						        </ul>
								@php

							 }
								
							@endphp 
				
					</li>
					@php

}

@endphp
<style>
.tree_checkbox_lbl {
	background-color: rgb(255, 255, 255);
	color: rgb(0, 0, 0);
	cursor: pointer;
}

.product_list{
	padding-left:1rem;
	display:block!important;
	margin-top:4px;
	margin-bottom:4px;
}
 


</style> 
@section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading">Stock Ledger</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">

 
<form method='post' action="{{url('/')}}/{{$companyname}}/stock-ledger"   >
  @csrf
		<div class='row'>
		<!-- -->
		<div class="col-md-4"   style=' height:200px;overflow-y:scroll;'  >
		<div class='hummingbird-treeview'>
		<ul id="menu_level_tree" class="hummingbird-base" style="padding-left:0px!important;">
         @foreach ($rootproducts as $rootproduct )
				@if(count($userproduct_ids)>0) 
				<li data-id="{{$rootproduct['id']}}">   
						<label class="tree_checkbox_lbl"> 
						<input type="checkbox"  value="{{$rootproduct['id']}}" class="menu_level_menus"  @if(in_array($rootproduct['id'],$selected_products)) checked @endif data-parentid="" name="selected_products[]" />
							{{$rootproduct['title']}}  </label>
                          </li>
				 
				@else
				    @php

					showChildProducts($rootproduct,1,"",$selected_products);
						
					@endphp
				@endif
			
				 @endforeach 
				</ul>

				</div>
				

			</div>
		 
			<div class="col-md-8"   >
				<div class='row'    >

				<!-- <div class="form-group col-5   mtb-1">
						<label class="lbl_control_inline"> Product :</label>
						 <div class='inline_control'>
                        <select class="form-control  " name='product'  id="ddn_product" required>
							 </select>
							</div> 
					</div> -->

						 	
				
 

					<div class="form-group col-6   mtb-1 "   >
						<label class="lbl_control_inline">From  Date :</label>
						<div class="inline_control">
						<input type='text' class="form-control  " name='start_date' @if(!empty($start_date_string))  value="{{$start_date_string}}"   @endif id='start_date' autocomplete="off" required/>
						</div>
					
					</div>
					<div class="form-group col-6    mtb-1">
						<label class="lbl_control_inline">To  Date :</label>
						<div class="inline_control">
						<input type='text' class="form-control  " name='end_date' @if(!empty($end_date_string))  value="{{$end_date_string}}"   @endif  id='end_date'  autocomplete="off" required/>
					
						</div>
					
					</div>

					
					<div class="form-group col-6  mtb-1">
						<label class="lbl_control_inline">Valuation Method :</label>
						<div class="inline_control">
					 			<select name="valuation_method" class='form-control'>
									<option value="Purchase Invoice"  @if(  $valuation_method=="Purchase Invoice") selected @endif>Last Purchase Price</option>
									<option value="Sales Invoice"   @if(  $valuation_method=="Sales Invoice") selected @endif>Last Sale Price</option>
									<option value="Avg Cost Method" @if(  $valuation_method=="Avg Cost Method") selected @endif>Avg Cost Method</option>
									<option value="Fifo" @if(  $valuation_method=="Fifo") selected @endif>Fifo</option>
									<option value="Lifo" @if(  $valuation_method=="Lifo") selected @endif>Lifo</option>
								</select>

						</div>
					
					</div>
					

					<div class="form-group col-6   mtb-1">
						<label class="lbl_control_inline">Location :</label>
						<div class='inline_control'>
							<select  class="form-control "  name="location"  >
								@if($haslocations==false)
								<option value="">All</option>
								@endif
								@foreach ($locations as $location_key=>$location_val)
								<option value="{{$location_key}}" @if(!empty($location) && $location==$location_key) selected @endif>{{$location_val}}</option>
									
								@endforeach
								 </select>
								 </div>
						   </div>

					<div class="form-group col-6  mtb-1" 	@if($show_chk_rate==false && $show_chk_specrate==false) style='display:none;' @endif >

						<label class="lbl_control_inline">Show :</label>
						<div class="inline_control">
							
					@if($show_chk_rate==true)
						<input class="form-check-input" type="checkbox"  name="rate" value="1"  @if($show_chk_rate==true && $show_rate==true) checked  @endif   >
						<label class="check-1"  >Rate</label>

						&nbsp;&nbsp;
						@endif

						
					@if($show_chk_specrate==true)
						<input class="form-check-input" type="checkbox"  name="spec_rate" value="1"   @if($show_chk_specrate==true && $show_specrate==true) checked  @endif   >
						<label class="check-1"  >Spec Rate</label>

						&nbsp;&nbsp;
						@endif

						</div>
					
					</div>

				

					
 
					<div class="form-group col-12 mtb-1 " style='text-align:center;'>
						<input type='submit' class='btn btn-primary' value='Submit' />
					 
						<a class="btn btn-primary" href="{{url('/')}}/{{$companyname}}/reset-stock-ledger">Cancel</a>
						</div>
				</div>
			</div>
		</div>

</form> 

@include('reports.send_report_modal')
		<div class='row'>
	 
			<div class="col-md-12 mx-auto">

			@if(count($selected_products)>0)
		 
			<input type="button" class="btn btn-primary"  value="XLSX" onclick="downloadDocument('xlsx')" />
						
						&nbsp;		&nbsp;  <input type="button" class="btn btn-primary"  value="PDF" onclick="downloadDocument('pdf')" />
 
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary"  value="CSV" onclick="downloadDocument('csv')" />
						
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary btnsendreport" data-mode="email" data-generateurl="{{route('company.send-stock-ledger' ,['company_name'=>$companyname])}}"     value="Email" />

						
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary btnsendreport" data-mode="whatsapp"   data-generateurl="{{route('company.send-stock-ledger' ,['company_name'=>$companyname])}}"     value="Whatsapp"   />
		 		@endif
      <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive"  style="max-height:400px;overflow:scroll;" >
                         <table class="table  table-striped taboncell" >
							<thead> 
								<th>Docno</th><th>Doc Date</th><th>Product Name</th><th>Particulars</th><th>Quantity</th><th>Balance</th>@if($show_rate==true)<th>Rate</th>@endif @if($show_specrate==true)<th>Spec rate</th> @endif
								@if($show_chk_rate==true ||  $show_chk_specrate==true)
								<th>Amount</th>
								@endif

							</thead>
							@php
								$no_of_columns=7;
								if($show_rate==true){
									$no_of_columns++;

								}
								if($show_specrate==true){
									$no_of_columns++;
								}

							@endphp

							<tbody >
								@foreach ($selected_products_collection as $product_id )

								@php
									if(!array_key_exists($product_id,$products_data)){
										continue;
									}
								@endphp

								<tr><td   tabIndex="1"><strong>Product Name:</strong></td><td   tabIndex="1">{{$products_data[ $product_id]['product_name']}}</td><td colspan='{{	$no_of_columns-1}}'> </td></tr>
							
								<tr><td></td> <td></td> <td>{{$products_data[ $product_id]['product_name']}}</td> <td  > </td><td style="text-align:right"><strong>Opening Balance:</strong></td>
								<td>{{$products_data[ $product_id]['opening_stock']}}</td> 
								@if ($show_rate==true) 
								<td  >{{$products_data[ $product_id]['opening_rate']}}</td>
								@endif
								@if ($show_specrate==true) 
								<td></td>
								@endif
								
								@if($show_chk_rate==true ||  $show_chk_specrate==true)
								<td>{{round($products_data[ $product_id]['opening_rate']*$products_data[ $product_id]['opening_stock'] ,2)}}</td></tr>
								@endif
								@php
								$row_index=0;
								$stock_detail_amounts=$products_data[ $product_id]['stock_detail_amounts']; 
								$stock_details=$products_data[ $product_id]['stock_details'] ;
								@endphp

							

								@foreach (	$stock_details as  $stock_detail)
										@php
										

										$stock_date=date("Y-m-d H:i:s",strtotime($stock_detail['docdate']));

										$start_date_stock=date("Y-m-d 00:00:00",strtotime(formatDateInYmd($start_date_string)));

										$end_date_stock=date("Y-m-d 23:59:59",strtotime(formatDateInYmd($end_date_string)));

										if(	$stock_date<$start_date_stock || 	$stock_date>	$end_date_stock){
										continue;
										}
										
										@endphp
										<tr>
										<td   tabIndex="1">{{$stock_detail['docno']}}</td>
										<td   tabIndex="1">{{date('d/m/Y',strtotime($stock_detail['docdate'])) }}</td>
										<td    tabIndex="1">{{$products_data[ $product_id]['product_name']}}</td>
										<td   tabIndex="1"> 
											@if(array_key_exists($stock_detail['partyid'],$all_customers))
											{{$all_customers[$stock_detail['partyid']]}}
											@endif
										</td>

										@php 
											$current_balance= $stock_detail['balance_qty'];

										$calc_spec=$stock_detail['spec_rate'];



										$final_amount=$stock_detail_amounts[$stock_detail['id']];




										$qty=$stock_detail['Qty'];



										@endphp

										<td   tabIndex="1">{{	 $qty}}</td>
										<td   tabIndex="1"> {{$current_balance}}</td>
										@if($show_rate==true)
										<td   tabIndex="1">{{$stock_detail['CRate']}}</td>
										@endif

										@if($show_specrate==true)
										<td   tabIndex="1">{{	 $calc_spec}} </td>
										@endif

										@if($show_chk_rate==true ||  $show_chk_specrate==true)
										<td   tabIndex="1">{{$final_amount}} 
											@endif
										</td>



										</tr>
										@php
										$row_index++;
										@endphp

										@endforeach
										<tr><td></td> <td></td>  <td>{{$products_data[ $product_id]['product_name']}}</td>  <td></td>  <td    tabIndex="1" style='text-align:right;'><strong>Closing Balance:</strong></td> <td   tabIndex="1">{{$products_data[ $product_id]['closing_stock']}}</td>
										<td></td>
										@if($show_rate==true)
										<td></td>
										@endif
										@if($show_specrate==true)
										<td></td>
										@endif
									</tr>

								@endforeach
														</tbody>
						</table>
						
	 
            </div>

        </div>
	
        </div> 
		
		@if(count($selected_products)>0)
		<div>{{$selected_products_collection->links()}}</div>
		@endif
    	
		<div class="mtb-2"> 
			</div>
        </div>
		</div>
	</div>
</div> @endsection @section('js')
<script src="{{ asset('js/checkboxtree.min.js') }}"></script>
<script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script> 
<script src="{{ asset('js/taboneachcell.js') }}"></script>
<script src="{{ asset('js/send_report.js') }}"></script>
<script type='text/javascript'>
$(document).ready(function() {

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


	var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
		 

			initSelect2Search("#ddn_product", url, '', null, {
				'table_name': 'GSI_det',
				'field_name': 'product'
			});


			@if (!empty($product))

			addSelect2SelectedOption("#ddn_product","{{$product_name}}",{{$product}});
				
			@endif

			

 
 
});

   

function downloadDocument(format){
 
var url="{{url('/')}}/{{$companyname}}/download-stock-ledger/"+format;
  window.open(url);

}




$("#menu_level_tree").on('click', '.fa-plus , .fa-minus', function(event) {
	var id = $(this).data('id');
	if($(`#menu_level_tree li[data-id='${id}']`).data('hasdata') == 'yes') {
		return false;
	}
	var level= $(this).data('level');
	level=level+1;
	// <input type='checkbox'   name='accounts[]'  value='${account['id']}'>&nbsp
	$.ajax({
		url: "{{url('/')}}/{{$companyname}}/get-child-products/" + id,
		type: "get",
		async: false,
		success: function(data) { 
			var result = JSON.parse(JSON.stringify(data));
			var products = result['products'];
			var html = '<ul>';
			for(let product of products) {
 
				html = html + `<li data-id='${product['product_id']}'> <i class='fa fa-plus'  tabIndex='1'  data-level='${level}'  data-id='${product['product_id']}' ></i> <a  data-id='${product['product_id']}'  data-level='${level}'   class='ga_link' href='javascript:void(0);' ><label  class='tree_checkbox_lbl'>
				<input type="checkbox"  value="${product['product_id']}" class="menu_level_menus" data-parentid="${id}" name="selected_products[]" />
				&nbsp;${product['product_name']} (Level ${level})</label></a></li>`;
			}
			html = html + '</ul>';
			$(`#menu_level_tree li[data-id='${id}']`).append(html);
			$(`#menu_level_tree li[data-id='${id}']`).data('hasdata', 'yes');
			$("#menu_level_tree").hummingbird();
		},
		error: function() {}
	});
});


$("#menu_level_tree").on('change', '.menu_level_menus', function() {
 
 var ischecked=$(this).is(':checked');

 var chkid=$(this).val();

 if(ischecked==true){

	 $(`#menu_level_tree .menu_level_menus[data-parentid='${chkid}']`).prop('checked',true);

 }
 else{


	 $(`#menu_level_tree .menu_level_menus[data-parentid='${chkid}']`).prop('checked',false);
 } 


 var parentid=$(this).data('parentid');

 var noofsiblings= $(`#menu_level_tree .menu_level_menus[data-parentid='${parentid}']`).length;

 var noofcheckedsiblings=$(`#menu_level_tree .menu_level_menus[data-parentid='${parentid}']:checked`).length;;


 if(noofsiblings==noofcheckedsiblings){
	 $(`#menu_level_tree .menu_level_menus[value='${parentid}']`).prop('checked',true);
 }
 else{
	 $(`#menu_level_tree .menu_level_menus[value='${parentid}']`).prop('checked',false);
 }


});;
 
 

</script> @endsection