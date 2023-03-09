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
<h4 class="menu-title  mb-5 font-size-18 addeditformheading">Opening Stock Register</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">

 
<form method='post' action="{{url('/')}}/{{$companyname}}/opening-stock-register"   >
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
 
					 

					<div class="form-group col-12   mtb-1">
						<label class="lbl_control_inline">Select Locations :</label>
						<div class='inline_control'  >
					 

                             <ul style="height:150px;overflow:scroll;">
                                    <li style="margin-bottom:10px;"><input type="checkbox"   name="all_locations" class="select_all_locations"    value="1"  @if($all_locations==true) checked @endif  > All</li>
                                    @foreach ($locations as  $location_key=>$location_name)
                                            <li  style="margin-bottom:10px;"><input type="checkbox" class="select_locations" name="location[]"   @if(in_array($location_key, $selected_locations)) checked @endif value="{{$location_key}}"> {{$location_name}}</li> 
                                    @endforeach
                                
                                </ul>
                                
						
								 </div>
						   </div>
 
					
 
					<div class="form-group col-12 mtb-1 " style='text-align:center;'>
						<input type='submit' class='btn btn-primary' value='Submit' />
					 
						<a class="btn btn-primary" href="{{url('/')}}/{{$companyname}}/reset-opening-stock-register">Cancel</a>
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
						
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary btnsendreport" data-mode="email" data-generateurl="{{route('company.send-opening-stock-report' ,['company_name'=>$companyname])}}"     value="Email" />

						
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary btnsendreport" data-mode="whatsapp"   data-generateurl="{{route('company.send-opening-stock-report' ,['company_name'=>$companyname])}}"     value="Whatsapp"   />
		 		@endif
      <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive"  style="max-height:400px;overflow:scroll;" >
                         <table class="table  table-bordered taboncell" >
                            <thead>
							<th style='min-width:100px;'>Product Id</th>
                                <th style='min-width:150px;'>Product Name</th>
								<th style='min-width:150px;'>Parent Name</th>
								<th style='min-width:100px;'>Product Type</th>
                                <th style='min-width:100px;'>Location</th>
                                <th style='min-width:100px;'>Doc No</th>
                                <th style='min-width:100px;'>Doc Date</th>
                                <th style='min-width:100px;'>Qty</th>
								@if ($show_amount==true)
								<th style='min-width:100px;'>Rate</th>
                                <th style='min-width:100px;'>Amount</th>
                           
								@endif
                           
                            </thead>
							<tbody>

							@foreach ($selected_stock_ids_collection as $selected_stock_id)
						 

                            <tr>
                                <td style="min-width:100px;" tabIndex="1">{{$products_data[$selected_stock_id]['product_id']}}</td> 
                                <td style="min-width:150px;"  tabIndex="1">{{$products_data[$selected_stock_id]['product_name']}}</td> 

                                <td style="min-width:150px;"  tabIndex="1">{{$products_data[$selected_stock_id]['parent_name']}}</td>  
                                
                                <td style="min-width:150px;"  tabIndex="1">{{  $products_data[$selected_stock_id]['product_type'] }}</td> 
    
                                <td style="min-width:150px;"  tabIndex="1">{{  $products_data[$selected_stock_id]['location']  }}</td> 

                                <td style="min-width:150px;"  tabIndex="1">{{  $products_data[$selected_stock_id]['docno'] }}</td> 

                                <td style="min-width:150px;"  tabIndex="1">{{ $products_data[$selected_stock_id]['docdate']}}</td> 
 
                                
                                <td style="min-width:150px;"  tabIndex="1">{{ $products_data[$selected_stock_id]['qty']}}</td> 
                                	@if ($show_amount==true)
                                <td style="min-width:150px;"  tabIndex="1">{{ $products_data[$selected_stock_id]['rate']}}</td> 
                                
                                <td style="min-width:150px;"  tabIndex="1">{{ $products_data[$selected_stock_id]['amount']}}</td> 
								@endif
 
							</tr>
                                
                            @endforeach 

							</tbody>
						</table>
						
	 
            </div>

        </div>
	
        </div> 
		
		@if(count($selected_stock_ids)>0)
		<div>{{$selected_stock_ids_collection->links()}}</div>
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
 
var url="{{url('/')}}/{{$companyname}}/download-opening-stock-report/"+format;
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