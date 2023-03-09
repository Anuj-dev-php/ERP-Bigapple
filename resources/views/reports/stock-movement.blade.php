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
<h4 class="menu-title  mb-5 font-size-18 addeditformheading">Stock Movement</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">

 
<form method='post' action="{{url('/')}}/{{$companyname}}/stock-movement"   >
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
						<label class="lbl_control_inline">Select Locations :</label>
						<div class='inline_control'  >
					 

                             <ul style="height:70px;overflow:scroll;">
                                    <li style="margin-bottom:10px;"><input type="checkbox" class="select_all_locations"  name="location[]" value="all"  @if(in_array('all', $selected_locations)) checked @endif> All</li>
                                    @foreach ($locations as  $location_key=>$location_name)
                                            <li  style="margin-bottom:10px;"><input type="checkbox" class="select_locations" name="location[]"   @if(in_array($location_key, $selected_locations)) checked @endif value="{{$location_key}}"> {{$location_name}}</li> 
                                    @endforeach
                                
                                </ul>
                                
						
								 </div>
						   </div>
 
					
 
					<div class="form-group col-12 mtb-1 " style='text-align:center;'>
						<input type='submit' class='btn btn-primary' value='Submit' />
					 
						<a class="btn btn-primary" href="{{url('/')}}/{{$companyname}}/reset-stock-movement">Cancel</a>
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
						
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary btnsendreport" data-mode="email" data-generateurl="{{route('company.send-stock-movement' ,['company_name'=>$companyname])}}"     value="Email" />

						
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary btnsendreport" data-mode="whatsapp"   data-generateurl="{{route('company.send-stock-movement' ,['company_name'=>$companyname])}}"     value="Whatsapp"   />
		 		@endif
      <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive"  style="max-height:400px;overflow:scroll;" >
                         <table class="table  table-bordered taboncell" >
                            <thead>
							<th style='min-width:100px;'>Product Id</th>
                                <th style='min-width:250px;'>Product Name</th>
								<th style='min-width:250px;'>Parent Name</th>
								<th style='min-width:100px;'>Product Type</th>

								@foreach ($selected_locations as $selected_location_id  )

								@if ($selected_location_id!="all")

                                
								 <th style="min-width:150px;" > {{$locations[$selected_location_id]}} Op. Bal Qty</th>		
								 @if($show_amount==true)
								 	<th style="min-width:150px;"  >{{$locations[$selected_location_id]}} Op. Amount</th>	
								@endif
								 <th style="min-width:150px;" > {{$locations[$selected_location_id]}} IN</th>	
								 		<th style="min-width:150px;"  >{{$locations[$selected_location_id]}} Out</th>	
                               
								 <th style="min-width:150px;" > {{$locations[$selected_location_id]}} Cl. Bal Qty</th>	
								 @if($show_amount==true)	
								 	<th style="min-width:150px;"  >{{$locations[$selected_location_id]}} Cl. Amount</th>
									@endif	
								@else
							  
                                <th style="min-width:150px;" > Total Op. Bal Qty</th>	
								@if($show_amount==true)		
								<th style="min-width:150px;"  >Total Op. Amount</th>
								@endif	
                                
                                <th style="min-width:150px;" > Total IN</th>			<th style="min-width:150px;"  >Total Out</th>	
                                
                                <th style="min-width:150px;" > Total Cl. Bal Qty</th>		
								@if($show_amount==true)	
								<th  style="min-width:150px;" >Total Cl. Amount</th>	
								@endif
                                
								@endif
							
									
								@endforeach
                           
                            </thead>
							<tbody>

							@foreach ($selected_products_collection as $selected_product)
							@php
						     $product_name=	$products_data[$selected_product]['product_name'];
							 $movement_data=$products_data[$selected_product]['movement_data'];
							 $parent_name=	$products_data[$selected_product]['parent_name'];
							 $product_type=$products_data[$selected_product]['product_type'];
 
							@endphp
							<tr>
							<td style="min-width:100px;" tabIndex="1">{{$selected_product}}</td> 
							<td style="min-width:150px;"  tabIndex="1">{{   $product_name}}</td> 

							<td style="min-width:150px;"  tabIndex="1">{{   $parent_name}}</td> 
							
							<td style="min-width:150px;"  tabIndex="1">{{   $product_type}}</td> 

								@foreach ($selected_locations as $selected_location_id  )
 
								@if ($selected_location_id!="all")

                                           
							     	 <td   tabIndex="1">{{	 $movement_data[$selected_location_id]['opening_balance']}}</td>	
									  @if($show_amount==true)		
									 	<td   tabIndex="1">{{	 $movement_data[$selected_location_id]['opening_amount']}}</td>	
										@endif
                                                                
                                    <td    tabIndex="1">{{ $movement_data[$selected_location_id]['in']}}</td>			<td   tabIndex="1"  >{{ $movement_data[$selected_location_id]['out']}}</td>	
                                    
                                    <td   tabIndex="1" > {{ $movement_data[$selected_location_id]['closing_balance']}}</td>		
									@if($show_amount==true)	
									
									<td   tabIndex="1">{{ $movement_data[$selected_location_id]['closing_amount']}}</td>
									@endif	
                                @else

                                <td   tabIndex="1">{{	 $movement_data['all']['opening_balance']}}</td>	
								@if($show_amount==true)		
									<td   tabIndex="1">{{	 $movement_data['all']['opening_amount']}}</td>
									@endif	
                                                                
                                                                <td   tabIndex="1"  >{{ $movement_data['all']['in']}}</td>	
																		<td   tabIndex="1"  >{{ $movement_data['all']['out']}}</td>	
                                                                
                                                                <td   tabIndex="1" > {{ $movement_data['all']['closing_balance']}}</td>	
																@if($show_amount==true)		
																	<td   tabIndex="1">{{ $movement_data['all']['closing_amount']}}</td>
																	@endif	
                                                      
                                @endif
										
									@endforeach


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
 
var url="{{url('/')}}/{{$companyname}}/download-stock-movement/"+format;
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
 

$(".select_all_locations").change(function(){
	var checked=$(this).prop('checked');
 

	if(checked==true){
		$(".select_locations").prop('checked',true);
	}
	else{
		$(".select_locations").prop('checked',false);	
	}
});

</script> @endsection