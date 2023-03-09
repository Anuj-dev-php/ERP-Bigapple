@inject('function4filter','App\Http\Controllers\Services\Function4FilterService') 
@inject('functionfilter','App\Http\Controllers\Services\FunctionService')
@php
$function4filter->tablename=$tablename;

$edit_tran_data_search_fields_string=Session::get('edit_tran_data_search_fields');


if(empty($edit_tran_data_search_fields_string)){
	$edit_tran_data_search_fields=array();
}
else{
	$edit_tran_data_search_fields=json_decode($edit_tran_data_search_fields_string,true);
}
 
  
 
 
@endphp

@extends('layout.layout')
<style>
	#tbltransactiondata td,th{text-align:center!important; }
	.table-responsive{max-height:400px!important;}
	.table-responsive td{font-size:0.7rem!important; }
	.table-responsive th{font-size:0.7rem!important;}

	
	.searchdiv_field{width:20%;display:inline-block;margin-right:10px; }
	.searchdiv_condition{width:7%;display:inline-block; margin-right:10px;}
	.searchdiv_value{width:20%;display:inline-block; margin-right:10px;}
	
	 .searchdiv_operator{width:7%;display:inline-block; margin-right:10px;}

	.searchdiv_trash{width:50px;display:inline-block;}


	#printReportPdfModal{ margin-top:50px;}


#printReportPdfModal .modal-dialog{max-width:90%;}


#printframepdf{
	width:100%; 
	/* height:1000px; */
}

#reportPrintControlModal .modal-dialog{max-width:50%;}


#trandataHistoryShowModal th{vertical-align:top;}
	
	</style>
@section('content')
 
<h4 class="menu-title  mb-5 font-size-18 addeditformheading"  >{{$tablename}} <span class="sp_addeditformaction"   >Edit Transaction Table</span></h2>

<!-- modal pop up to select data to fill up in thr form ends-->

<div id="trandataHistoryShowModal" class="modal fade ">
	<div class="modal-dialog  "  style="max-width:95%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">History Data</h4>
				<button type="button" class="close" onclick=" $('#trandataHistoryShowModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
			 
				<div class="card  mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Id</th>
										<th>Doc No</th>
										<th>Doc Date</th>
										<th>User Name</th>
										<th>Operation</th>
										<th>Server Time</th>
										<th>Cust Id</th>
										<th>Location</th>
										<th>Product</th>
										<th>Quantity</th>
										<th>Rate</th>
										<th>Amount</th>
										<th>Gross Amount</th>
										<th>Net Amount</th> 
										</tr>
								</thead>
								<tbody id="tbodytrandatamodalhistory" >
									<tr>
										<td colspan='14' class='text-left'>No Data </td>
									</tr>
								</tbody>
							</table> 
						</div>
 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- modal pop up to show hsitory ends -->
 
<!-- modal pop up to track order starts -->
<div id="trackOrderShowModal" class="modal fade ">
	<div class="modal-dialog  "  style="max-width:60%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Track Order</h4>
				<button type="button" class="close"   onclick=" $('#trackOrderShowModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
			 
				<div class="card  mtb-2">
					<div class="card-body" id='track_order_info'>
						 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- modal pop up to track order ends -->


<!-- Modal Dialog Box To Print Each Record -->


<div id="printReportPdfModal" class="modal fade" >
	<div class="modal-dialog"   >
		<!-- Modal content-->
		<div class="modal-content"  style=" height:100%;"    >
			<div class="modal-header" >
				<h4 class="modal-title">Print Report</h4>
				<button type="button" class="close" onclick=" $('#printReportPdfModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body"   > 
				<div class="container-fluid"   style="max-height:100%;overflow:scroll;"  >
				
				<iframe id="printframepdf"  style='height:100%;'    src="https://reportapi.bigapple.in/reportapi/generatereport?id=1&reportfilename=invoice_pdf.rpt">

				</iframe>

    
	         	</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
 
<!-- Modal Dialog Box To Ask before print -->


<div id="reportPrintControlModal" class="modal fade"  >
	<div class="modal-dialog"   >
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="printreport_heading">Print Report</h4>
				<button type="button" class="close" onclick=" $('#reportPrintControlModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body"> 
				<div class="container">
			

				<form id='frm_print_report' class="form-horizontal" >
					@csrf
					<input type='hidden'  name='reportmode' id='print_reportmode' value='print' />
					<input type='hidden'  name='dataid' id='print_dataid' value='' />
					<input type='hidden' name="tablename" value="{{$tablename}}" />

					<div class="form-group row">
							<label class="lbl_control col-sm-4" >Select Report:</label>
							<div class="control-label col-sm-6"> 
								<select class="form-control"  id="printcontrol_reportname" name="reportname"> 
									@foreach ($crystaltemplates as $crystalkey=>$crystalval )
									<option value="{{$crystalkey}}">{{$crystalval}}</option>
										
									@endforeach
								</select>
							</div>
					</div>

					<div class="form-group row mt-2">
							<label class="lbl_control col-sm-4" >Select Options:</label>
							<div class="control-label col-sm-8"  > 

							<div class="printreport-whatsapp-control">
							<i class="bx bxl-whatsapp"  style="font-size:16px;"></i>
							 
								<label  class="lbl_control_small mt-2" ><input type="checkbox" id="printcontrol_whatsapptocustomer" name='whatsapp_to_customer'  value="1"  >&nbsp;To Customer</label>
							 
								&nbsp;<label    class="lbl_control_small  mt-2"  ><input type="checkbox" id="printcontrol_whatsapptosalesman" name='whatsapp_to_salesman'  value="1"  >&nbsp;To Salesman</label>
			               	</div>
								<div class="clearfix mt-1"></div>
								<div  class="printreport-email-control" >
								<i class="bx bx-mail-send"   style="font-size:16px;"></i>
								<label    class="lbl_control_small  mt-2"  ><input type="checkbox" id="printcontrol_emailtocustomer" name='email_to_customer'  value="1"   >&nbsp;To Customer</label>
								&nbsp;
								<label   class=" lbl_control_small  mt-2"   ><input type="checkbox" id="printcontrol_emailtosalesman" name='email_to_salesman'  value="1" >&nbsp;To Salesman</label>
			                 	</div>

							</div>
					</div>
 
					<div class="form-group row mt-3 printreport-email-control "   >
							<label class="lbl_control col-sm-4" >Enter Email:</label>
							<div class="control-label col-sm-6">  
								<input type='email' name="toemailid" class="form-control" placeholder="Enter Email"  id="printcontrol_enteremail" />
							</div>
					</div>
			 				
					<div class="form-group row  mt-3  printreport-whatsapp-control ">
							<label class="lbl_control col-sm-4" >Select Whatsapp Template:</label>
							<div class="control-label col-sm-6">  
								<select class="form-control" name="whatsapp_template_id" id="towhatsapptemplate_id" >
								 	@foreach ( $whatsapp_templates as $whatsapp_template_key=>$whatsapp_template_value )
										<option value="{{$whatsapp_template_value}}">{{$whatsapp_template_key}}</option>
									@endforeach
								</select>

								<!-- <input type="number"  class="form-control" name="towhatsappno" placeholder="Enter Whatsapp No." id="printcontrol_enterwhatsappno"  />
							   -->
							</div>
					</div> 
					
					<div class="form-group row  mt-3  printreport-whatsapp-control ">
							<label class="lbl_control col-sm-4" >Enter Whatsapp no.:</label>
							<div class="control-label col-sm-6">  

								<input type="number"  class="form-control" name="towhatsappno" placeholder="Enter Whatsapp No." id="printcontrol_enterwhatsappno"  />
							  
							</div>
					</div>

			 
  
					<div class="form-group mt-4">        
					<div class="text-center">
						<button type="button" class="btn btn-primary"  id="btn_print_report">Submit</button>
					</div>
					</div>
  </form>
			 
				 
	         	</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
 

 





<!-- Modal Dialog Box To Change Field Sequence -->

<div id="changeFieldSequenceModal" class="modal fade">
	<div class="modal-dialog" style="max-width:50%;">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Change Field Sequence</h4>
				<button type="button" class="close" onclick=" $('#changeFieldSequenceModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
	 
				<div class="card  mtb-2">
					<div class="card-body">					
			<form method='post' id='frmchangefieldsequence'>
						<div class=" mx-auto table-responsive">						 
							<table class="table">
								<thead>
									<tr  ><th>FieldName</th><th>Sequence</th>
									 
									</tr>
								</thead>
								<tbody  id="tbodytablefieldsequences">
									 
								</tbody>
							</table> 
						</div>
					 
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->


<div class="pagecontent">
	<div class="container-fluid">
		<div class="row  ">
			<form id="frmsearch" class="d-none" method='post' action="{{url('/')}}/{{$companyname}}/edit-transaction-table-data/{{$tablename}}/{{$tableid}}">
				@csrf
			<div class="col-8 text-start"  >
				<!-- <h5 class="text-center ">Search By Fields</h5> -->
			 
				<div id="divaddsearchfields" data-noofsearch="@if(array_key_exists('searchfield',	$edit_tran_data_search_fields)) {{count($edit_tran_data_search_fields['searchfield'])}} @else 1 @endif">
				 
					@if(!array_key_exists('searchfield',	$edit_tran_data_search_fields))
					<div class="searchdiv_field">
						<label class="lbl_control">Field:</label>
						<input type='hidden' id='table_name' value="{{$tablename}}" />
						<div>
							<select class="form-control searchfield" name="searchfield[]" data-index="1">
								<option value="">Select Field</option> @foreach ( $searchheaderfields as $headerfield)
								<option data-function="{{$headerfield->Field_Function}}" value="{{strtolower($headerfield->Field_Name)}}">{{$headerfield->fld_label}}</option> @endforeach </select>
						</div>
					</div>
					<div class="searchdiv_condition ">
						<label class="lbl_control">Condition:</label>
						<div>
							<select class="form-control searchcondition" name="searchcondition[]" data-index="1">
							<option value="<"><</option>
								<option value=">">></option>
							    <option value="=">=</option>
								<option value="!=">!=</option>
								<option value="Like">Like</option>
								<option value="Not Like">Not Like</option> 
								<option value="Contains">Contains</option>
								<option value="Begin With">Begin With</option>
								<option value="Ends With">Ends With</option>
							</select>
						</div>
					</div>
					<div class="searchdiv_value " data-index="1">
						<label class="lbl_control">Value:</label>
						<div>
							<select name="searchval[]" class='form-control  searchval' data-index="1"   required></select>
						</div>
					 
					</div>
					<div class="searchdiv_operator " data-index="1">
						<label class="lbl_control">Operator:</label>
						<div>
							<select name="searchoperator" class='form-control searchoperator' data-index="1" required>
								<option value="And">And</option>
								<option value="Or">Or</option>
							</select>
						</div>
					</div>

					@else

					@php
					$searchindex=1;
					@endphp



					@foreach ($edit_tran_data_search_fields['searchfield'] as $editsearchfield)
					@php
 
						$editsearchcondition=$edit_tran_data_search_fields['searchcondition'][$searchindex-1];
					 
						$editsearchoperator=$edit_tran_data_search_fields['searchoperator'];

						$editsearchfunction= $edit_tran_data_search_fields['searchfunction'][$searchindex-1];
 
					 
					 
					@endphp
					<div class='searchdivfields' data-index='{{$searchindex}}' >


					<div class="searchdiv_field mtb-1">
					  @if(	$searchindex==1)	<label class="lbl_control">Select Field:</label> @endif
						<input type='hidden' id='table_name' value="{{$tablename}}" />
						<div>
							<select class="form-control searchfield" name="searchfield[]" data-index="{{$searchindex}}">
								<option value="">Select Field</option> @foreach ( $editheaderfields as $headerfield)
								<option data-function="{{$headerfield->Field_Function}}"  value="{{strtolower($headerfield->Field_Name)}}">{{$headerfield->fld_label}}</option> @endforeach </select>
						</div>
					</div>
					<div class="searchdiv_condition mtb-1">
					@if(	$searchindex==1)	 <label class="lbl_control">Select Condition:</label> @endif
						<div>
							<select class="form-control searchcondition" name="searchcondition[]" data-index="{{$searchindex}}">
							    <option value="=">=</option>
								<option value="!=">!=</option>
								<option value="<"><</option>
								<option value=">">></option>
								<option value="Like">Like</option>
								<option value="Not Like">Not Like</option> 
								<option value="Contains">Contains</option>
								<option value="Begin With">Begin With</option>
								<option value="Ends With">Ends With</option>
							</select>
						</div>
					</div>
					<div class="searchdiv_value  mtb-1" data-index="{{$searchindex}}">
					@if(	$searchindex==1)	
					<label class="lbl_control">
					@if(in_array($editsearchfunction,array(2,4 ,18,14,16)))
				 
						Select Value:
						@else 
						Enter Value:
						@endif
						</label>
						@endif
						<div>

						@if(in_array($editsearchfunction,array(2,4 ,18,14,16)))
							<select name="searchval[]" class='form-control  searchval' data-index="{{$searchindex}}"   required></select>
						@else
						<input type='text' name="searchval[]" class='form-control searchval'  data-index="{{$searchindex}}"   required />

						@endif
						</div>
					 
					</div>
					<div class="searchdiv_operator  mtb-1" data-index="{{$searchindex}}">
					    @if(	$searchindex==1)		<label class="lbl_control">Select Operator:</label> @endif
						<div>
							<select name="searchoperator" class='form-control searchoperator' data-index="{{$searchindex}}" required>
								<option value="And">And</option>
								<option value="Or">Or</option>
							</select>
						</div>
					</div>

					
					@if($searchindex>1)
					<div class='searchdiv_trash'>
					<a   data-index="{{$searchindex}}" href='javascript:void(0);'  onclick='deleteFieldFilter({{$searchindex}})'>	<i class="fa fa-trash" aria-hidden="true"></i></a>
					</div>
					@endif




					</div>
						@php
						$searchindex++;
						@endphp
					@endforeach

					

					@endif
				</div>
			</div>
			<div class='col-8  text-start mtb-1'>
				
			<input type='button' class='btn btn-sm btn-primary' value='Add Another' onclick="addAnotherSearchField();"   />
			<input type='submit' class='btn btn-sm btn-primary' value='Search' />
				<a href="{{url('/')}}/{{$companyname}}/reset-edit-tran-data-search" class='btn btn-sm btn-primary'>Reset Search</a>

			 </div> 
			<div class='col-8 mx-auto text-center'>
			
			
			</div>

			</form>
			<div class="col-12 "  id="divedittrantabledatas">
				 <input type="hidden"  id="edit_tran_data_table" value="{{$tablename}}" /> 
					<div id="divactionbuttons" class='col-12 text-end' style="height:25px;"    >

					<a href="{{url('/')}}/{{Session::get('company_name')}}/add-transaction-insert-role-fields/{{$tablename}}/{{$tableid}}" class="btn btn-primary   @if(!$showhidebuttons['save'] ) d-none  @endif" style="float:left;margin-left:5px;"  >Add</a>
					<input type='button' id="btn_delete" style="float:left;margin-left:5px;"   class="btn btn-primary    @if(!$showhidebuttons['delete']) d-none  @endif " value='Delete' />
					<input type='button' id="btn_filter" style="float:left;margin-left:5px;"   class="btn btn-primary" value='Filter' />
				 

						<a href="javascript:void(0);" id="link_btn_edit" onclick="openedittrandatasingle();">
					<input type='button' id="btn_edit"  style="float:left;margin-left:5px;"  class="btn btn-primary   notshow @if(!$showhidebuttons['edit']) d-none  @endif" value='Edit'   />
						</a>
						<input type='button' id="btn_history"  onclick="showTranDataHistory()"  style="float:left;margin-left:5px;" class="btn btn-primary   notshow   @if(!$showhidebuttons['history']) d-none  @endif" value='History' />
					
						<input type='button' id='btn_change_sequence'  style='float:left;margin-left:5px;'  class="btn btn-primary"  value="Change Sequence" />
			@if($showhidebuttons['print'])	  <input type='button'  id="btn_print" class="btn btn-primary notshow" value='Print' />  @endif
			<!-- target="_blank"   -->
				<input type='button'  id="btn_email"  class="btn btn-primary    notshow  @if(!$showhidebuttons['view']) d-none  @endif" value='Email' />
					<input type='button' id="btn_whatsapp" class="btn btn-primary    notshow   @if(!$showhidebuttons['view']) d-none  @endif" value='Whatsapp' />
					@if($showhidebuttons['export'])	<a href="{{url('/')}}/{{$companyname}}/edit-transaction-table-data-excel-download/{{$tablename}}"  class="btn btn-primary"  >Export</a>	  @endif
					@if($tablename=='GSI')
					<input type="button"  id='btn_trackorder'  class="btn btn-primary    notshow" value="Track Order"    />
					@endif
				</div>
				 <div class="clearfix">&nbsp;</div>
 
				<div class="card"   >
					<div class="card-body">
						<div class=" mx-auto table-responsive"  style=" max-height:90%!important;">
							<!-- table-striped -->
							<table id="tbltransactiondata"    data-tablename="{{$tablename}}" class="table  table-striped table-bordered  taboncell">
								<thead>
									<tr>
										@php
											$noofcolumns=count($editheaderfields);
										@endphp
										<th>Select</th>
										<th>Id</th>
										@foreach ( $editheaderfields as $headerfield ) @php $width= 100+(int)$headerfield->Display_Width; @endphp
										<th style="min-width:{{$width}}px!important">{{trim($headerfield->fld_label)}}</th> @endforeach
										 
									</tr>
								</thead>
								<tbody id="tbodytransactiondata"  >
								@if(count($transactiondata)>0)	
								@foreach ($transactiondata as $data)
								@php
								 $data=(array)$data; 
								 $data=array_change_key_case($data,CASE_LOWER)

								@endphp
									<tr class="transactiondatarow"  id="{{$data['id']}}">
										<td class='text-center'>
											<input type='checkbox' class="txn_data" value="{{$data['id']}}" />
										</td>
										
									<td class='text-center'   tabindex="1">{{$data['id']}}</td> @foreach ( $editheaderfields as $headerfield ) 
										@php 
										$headerfieldname=strtolower($headerfield->Field_Name);
										if( $headerfield->Field_Function==4){
										$showdata= $function4filter->getFunction4FieldValueUsingId($headerfield->Field_Name,$data[$headerfieldname]);
										}
										else if( $headerfield->Field_Function==31 || $headerfield->Field_Function==27 || $headerfield->Field_Function==6 )
										{
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
										}
										else{
											$showdata=$data[$headerfieldname]; 
										}
										@endphp

										@if ($headerfield->Field_Function==4)
											@php
											$showdata= $function4filter->getFunction4FieldValueUsingId($headerfield->Field_Name,$data[$headerfieldname]);
										
											@endphp
											
										<td   tabindex="1">{{ $showdata }}</td>
										@elseif ($headerfield->Field_Function==31 || $headerfield->Field_Function==27 || $headerfield->Field_Function==6)
											@php
												
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
											@endphp
											
										<td   tabindex="1">{{ $showdata }}</td>
										@elseif($headerfield->Field_Function==5)
										@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td   tabindex="1">
											<a href="{{url('/')}}/{{Session::get('company_name')}}/edit-transaction-table-single-data/{{$tablename}}/{{$tableid}}/{{$data['id']}}"> 
											{{$showdata}}
											</a>
											</td>
											@else
											@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td   tabindex="1">{{	$showdata}}</td>

										@endif
										
										
										@endforeach
									 </tr> 
									 
									 
									 @endforeach 
								@else
 
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			 
				<div class="paginationdiv mtb-2"> {{$transactiondata->links()}} </div>
			</div>
		 
		</div>
	</div>
</div>
</div>
</div>
</div>
</div> @endsection @section('js') {{-- ROLE --}}
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{ asset('js/taboneachcell.js') }}"></script>
<script type="text/javascript">
  
$(function(){

	$("#divaddsearchfields").on("change",".searchfield",function(){

		     var index=$(this).data("index");
 
			var tablename=$("#table_name").val();

			var selectedoption= $(this).find("option:selected");

			var functionnum=selectedoption.data("function");
			functionnum=parseInt(functionnum);

			var fieldname=$(this).val();

			var ddnfunctions=[2,4 ,18 ,14,16];

			var datefunctions=[27,31,6];
  
			// || datefunctions.includes(functionnum)
			if(ddnfunctions.includes(functionnum) ){

				$(`#divaddsearchfields .searchdiv_value[data-index='${index}']`).html(`<label class='lbl_control'>Select Value:</label>	<div  >  
							<select name="searchval[]" class='form-control select2 searchval' data-index='${index}'   required></select>
						</div> `);
			}
			else{
 
				 
				$(`#divaddsearchfields .searchdiv_value[data-index='${index}']`).html(`<label class='lbl_control'>Enter Value:</label>	<div  >  
							 <input  name="searchval[]" type='text' class='form-control  searchval'    data-index='${index}'  required />
						</div> `); 
			}

			if(datefunctions.includes(functionnum)){

				$(`#divaddsearchfields .searchdiv_value[data-index='${index}']`).find("input").datetimepicker({
					format: 'd-m-Y',
					timepicker: false,
					datepicker: true,
					dayOfWeekStart: 1,
					yearStart: 2016,
				});


			}


			if(ddnfunctions.includes(functionnum) ){
				var url;
				if(functionnum==2){

					var selectfound=$(`#divaddsearchfields .searchdiv_value[data-index='${index}']`).find("select");
					 url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues";

					initSelect2Search(`.searchval[data-index='${index}']`, url, '', null, {
							'table_name': tablename,
							'field_name': fieldname
						}); 

				}
				else if(functionnum==4){
				  url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
					initSelect2Search(`.searchval[data-index='${index}']`, url, '', null, {
						'table_name': tablename,
						'field_name': fieldname
					});
				}
				else if(functionnum==5){
				//  url = "{{url('/')}}/{{$companyname}}/get-function5-codes";
				// 	initSelect2Search(`.searchval[data-index='${index}']`, url, '', null, {
				// 		'table_name': tablename,
				// 		'field_name': fieldname
				// 	}); 
				}
				else if(functionnum==18){
					url = '/{{$companyname}}/get-function18-users';
				    initSelect2Search(`.searchval[data-index='${index}']`, url, '');
				}
				else if(functionnum==14){
				      url = "{{url('/')}}/{{$companyname}}/get-Function14-All-currencies";
					initSelect2Search(`.searchval[data-index='${index}']`, url, '');
				}
				else if(functionnum==16){
				  url = "{{url('/')}}/{{$companyname}}/get-function16-uoms";
				
				initSelect2Search(`.searchval[data-index='${index}']`, url, '');
				}

		

			}

 

		});
 
});


function addAnotherSearchField(){

	var selectedoperator=$(".searchoperator[data-index='1']").val();

	if(selectedoperator==""){
		alert("Please select Operator");
		return;

	}

	var serachopeartor_option=$(".searchoperator[data-index='1'] option:selected");

	var newoperatoroption=`<option value='${selectedoperator}'>${serachopeartor_option.text()}</option>`;

	var options=$(".searchfield[data-index='1'] option");

	var fields=[];

	options.each(function(){
	 
			fields.push({'fieldname':$(this).val() ,'fieldlabel':$(this).text(),'fieldfunction':$(this).data('function')});
 
	});

   var noofsearch=	$("#divaddsearchfields").data("noofsearch");
   noofsearch=parseInt(noofsearch)+1;


   $("#divaddsearchfields").append(`
   <div class='searchdivfields' data-index='${noofsearch}' >
   <div  class="searchdiv_field"     > 
						<div  >
							<select class="form-control searchfield" name="searchfield[]"  data-index="${noofsearch}" ></select>
						</div>
					</div>
					<div  class="searchdiv_condition"     >  
						<div  >
							<select class="form-control" name="searchcondition[]"  data-index="1" >
							<option value="<"> < </option>
								<option value=">">></option>	
							<option value="=">=</option>
								<option value="!=">!=</option>	
							<option value="Like">Like</option>
								<option value="Not Like">Not Like</option>
								<option value="Contains">Contains</option>
								<option value="Begin With">Begin With</option>
								<option value="Ends With">Ends With</option>
							</select>
						</div>
					</div>

					 <div   class="searchdiv_value"   data-index="${noofsearch}"  > 
						<div  >  
							<select name="searchval[]" class='form-control select2'  required></select>
						</div> 
					 
					</div>
					
					<div   class="searchdiv_operator"   data-index="${noofsearch}"  >
						<div   >  
							<select name="searchoperator" class='form-control searchoperator'   required> 
							 ${newoperatoroption}
							</select>
						</div> 
						 
					</div>

					<div class='searchdiv_trash'>
					<a   data-index="${noofsearch}" href='javascript:void(0);'  onclick='deleteFieldFilter(${noofsearch})'>	<i class="fa fa-trash" aria-hidden="true"></i></a>
					</div>

					</div>
					
					`);

					$("#divaddsearchfields").data("noofsearch",noofsearch);


					for(let field of fields){
						$(`.searchfield[data-index='${noofsearch}']`).append(`<option  data-function="${field['fieldfunction']}"   value="${field['fieldname']}">${field['fieldlabel']}</option>`);
					}
 

}

 
function deleteFieldFilter(index){

  $(`#divaddsearchfields  .searchdivfields[data-index='${index}']`).remove();

}

 
 $(function(){
 	
		@if(!empty($edit_tran_data_search_fields_string))

		$.get("{{url('/')}}/{{$companyname}}/get-edit-tran-data-search-fields",function(data,status){
 

			var tablename=$("#table_name").val();
			var result=JSON.parse(JSON.stringify(data));
			var index=1;
			for(let editfield of result){
				$(`.searchfield[data-index='${index}']`).val(editfield['searchfield']);
				$(`.searchcondition[data-index='${index}']`).val(editfield['searchcondition']);
				// editfield['searchval'] 
			   var operator=	editfield['searchoperator'];

			   var function_num=editfield['searchfunction']; 
 
			   if(function_num==2){

					url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues";

					initSelect2Search(`.searchval[data-index='${index}']`, url, '', null, {
							'table_name': tablename,
							'field_name': editfield['searchfield']
						}); 
						addSelect2SelectedOptionTriggerChange(`.searchval[data-index='${index}']`,editfield['displayvalue'],editfield['searchval']);


			   }
			   else if(function_num==4){
				url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
					initSelect2Search(`.searchval[data-index='${index}']`, url, '', null, {
						'table_name': tablename,
						'field_name': editfield['searchfield']
					});
					addSelect2SelectedOptionTriggerChange(`.searchval[data-index='${index}']`,editfield['displayvalue'],editfield['searchval']);
 

			   }
			   else if(function_num==5){
				$(`.searchval[data-index='${index}']`).val(editfield['searchval']);
				// url = "{{url('/')}}/{{$companyname}}/get-function5-codes";
				// 	initSelect2Search(`.searchval[data-index='${index}']`, url, '', null, {
				// 		'table_name': tablename,
				// 		'field_name': editfield['searchfield']
				// 	}); 
					
				// 	addSelect2SelectedOptionTriggerChange(`.searchval[data-index='${index}']`,editfield['displayvalue'],editfield['searchval']);

				 }
				 else if(function_num==18){
					 
					url = '/{{$companyname}}/get-function18-users';
				    initSelect2Search(`.searchval[data-index='${index}']`, url, '');
					
					addSelect2SelectedOptionTriggerChange(`.searchval[data-index='${index}']`,editfield['displayvalue'],editfield['searchval']);


					}
					else if(function_num==14){

						url = "{{url('/')}}/{{$companyname}}/get-Function14-All-currencies";
					initSelect2Search(`.searchval[data-index='${index}']`, url, '');
					
					addSelect2SelectedOptionTriggerChange(`.searchval[data-index='${index}']`,editfield['displayvalue'],editfield['searchval']);

					}
					else if(function_num==16){
						
						url = "{{url('/')}}/{{$companyname}}/get-function16-uoms";
				
			         	initSelect2Search(`.searchval[data-index='${index}']`, url, '');
						 
						 addSelect2SelectedOptionTriggerChange(`.searchval[data-index='${index}']`,editfield['displayvalue'],editfield['searchval']);

					}
					else{
 
						$(`.searchval[data-index='${index}']`).val(editfield['searchval']);
 

					}
 

					$(`.searchoperator[data-index='${index}']`).val(editfield['searchoperator']);

				index++;
			}

		});

		@endif
 
 })



 $("#tbodytransactiondata").on("click",'tr',function(){
 
	if($(this).hasClass("selected")) {
		$(this).removeClass("selected");
		
		$(this).find('.txn_data').prop('checked',false);
	
	} else {
	 $(this).addClass("selected");
 
	 $(this).find('.txn_data').prop('checked',true);


	} 

  var noofselected=	$("#tbodytransactiondata .selected").length;
 
  if(noofselected>1 || noofselected==0){
	  
    //   $("#divactionbuttons").addClass("notshow");

	$("#btn_edit").addClass("notshow");
	
	$("#btn_history").addClass("notshow");
	
	$("#btn_export").removeClass("notshow");
	
	$("#btn_email").removeClass("notshow");

	$("#btn_trackorder").addClass("notshow");
	
	$("#btn_whatsapp").removeClass("notshow");
	$("#btn_print").addClass("notshow");
   
	  return;
  }

  $("#btn_print").removeClass("notshow");

	$("#btn_email").removeClass("notshow");
	
	$("#btn_trackorder").removeClass("notshow");
	
	$("#btn_whatsapp").removeClass("notshow");

 
 var dataid=$("#tbodytransactiondata .selected").attr("id");
 
 $("#tbodytransactiondata").data('id',dataid); 
 
 
 $("#btn_edit").removeClass("notshow");
	
	$("#btn_history").removeClass("notshow");
	 
 
 
//  $("#divactionbuttons").removeClass("notshow");



 
 });

 $("#btn_edit").click(function(){
	 
    var dataid=$("#tbodytransactiondata .selected").attr("id");

	var url="{{url('/')}}/{{$companyname}}/edit-transaction-table-single-data/{{$tablename}}/{{$tableid}}/"+dataid;
 
	window.location.href=url;

	$("#link_btn_edit").attr('href',url);

 });



 $("#btn_delete").click(function(){

   var trantable=$("#edit_tran_data_table").val();

   var checked=	$(".txn_data:checked");

   if(checked.length==0){

	alert("Unselected","Please select at least 1 row to delete");

	return false;
   }

   var cnf= confirm("Are you sure to delete Selected Data ?");

   if(cnf==false){
	   return false;
   }
 
 
   var deleteids=[];
   checked.each(function(){

	deleteids.push($(this).val());

   }); 

   $.post("{{url('/')}}/{{$companyname}}/delete-tran-table-data-by-ids",{'trantable':trantable,'deleteids':deleteids}
   ,function(data,status){

	var result=JSON.parse(JSON.stringify(data));

	if(result['status']==false){

		alertUserMsg("Unallowed Delete",result['message']);
		return false;
	}
	else{

		deleteids=result['deleteids'];

		var notdeleted_docno=result['not_deleted_doc_nos'];

		if(deleteids.length>0){

			for(let deleteid of deleteids){
				$(`.transactiondatarow[id='${deleteid}']`).remove();
			 }

				SnackbarMsg(result);

		}


		if(notdeleted_docno.length>0){

			var notdeleted_doc_no_string=notdeleted_docno.join(',');
			
			 SnackbarMsg({'status':'failure','message':notdeleted_doc_no_string+' are not deleted due to GST Invoicing ' });

		}

 

	}
 

   });

 });

 $("#btn_change_sequence").click(function(){

	$("#changeFieldSequenceModal").modal('show');

	// changeFieldSequenceModal
	var tranid='{{$tablename}}';
	loadFieldSequence(tranid);;
  

 });

 
function changeTxnFieldSequence(tranid){
 

 var tablerows = $("#tbodytablefieldsequences tr");
		 var ids = [];
		 tablerows.each(function() {
			 ids.push($(this).attr('id'));
		 });
		 $.post("{{url('/')}}/{{ $companyname }}/update-txn-field-sequence", {
			 'ids': ids
		 }, function(data, status) {

			 var result = JSON.parse(JSON.stringify(data));
			  
			 alertUserMsg("Field Sequence","Field Sequence changed successfully");
			 if (result['status'] == 'success') { 
				 loadFieldSequence(tranid);
			 }
		 });


}


function loadFieldSequence(tranid){

	$.post("{{url('/')}}/{{$companyname}}/get-transaction-table-fields-with-sequence",{'tranid':tranid},function(data,status){

		$("#tbodytablefieldsequences").empty();
var result=JSON.parse(JSON.stringify(data));

for(let dt of result['fields']){

	var sequence=(dt['sequence']==null?'':dt['sequence']);

	$("#tbodytablefieldsequences").append(`<tr id='${dt['id']}'><td>${dt['label']}</td><td>${sequence}</td></tr>`);

	}


		if(result['fields'].length==0){
		$("#tbodytablefieldsequences").append(`<tr><td colspan='2'>No Data</td></tr>`);

		}

		$("#tbodytablefieldsequences").sortable({
				update: function(event, ui) {
				changeTxnFieldSequence(tranid);
				}
			}); 


})

}


$("#btn_filter").click(function(){
	$("#frmsearch").toggleClass("d-none");
});



$("#btn_print").click(function(){
	
  var dataid=	$("#tbodytransactiondata").data('id' );
  $("#print_reportmode").val("print");

  $("#reportPrintControlModal").modal("show");
  $("#printreport_heading").html('Print Report');
  resetPrintReportForm();
  
	$("#print_dataid").val(dataid);
	
 
});


$("#btn_email").click(function(){
	
	var dataid=	$("#tbodytransactiondata").data('id' );
	$("#reportPrintControlModal").modal("show");
	$("#print_reportmode").val("email");
	resetPrintReportForm();
	$("#printreport_heading").html('Email Report');
	$(".printreport-whatsapp-control").addClass('d-none');
	$(".printreport-email-control").removeClass('d-none');

	$("#print_dataid").val(dataid);
	
   
  });

  $("#btn_whatsapp").click(function(){
	
	var dataid=	$("#tbodytransactiondata").data('id' );
	$("#print_reportmode").val("whatsapp");
	$("#reportPrintControlModal").modal("show");
	resetPrintReportForm();
	$("#printreport_heading").html('Whatsapp Report');
	$(".printreport-whatsapp-control").removeClass('d-none');
	$(".printreport-email-control").addClass('d-none');
	
	$("#print_dataid").val(dataid);

	
   
  });
 

	$("#btn_print_report").click(function(){

		
	var dataid=	$("#tbodytransactiondata").data('id' );

	printReportPdf(dataid);
	 

});



function printReportPdf(dataid){

	
var enterwhatsapp_no=$("#printcontrol_enterwhatsappno").val().trim();

var whatsapp_to_cust=$("#printcontrol_whatsapptocustomer").is(":checked");

var whatsapp_to_sales=$("#printcontrol_whatsapptosalesman").is(":checked");

var whatsapp_template_id= $("#towhatsapptemplate_id").val() ;

if(  whatsapp_template_id=="" && (enterwhatsapp_no!='' || whatsapp_to_cust==true || whatsapp_to_sales==true) ){

 alert("Select Whatsapp Template ");

 return false;
}



	$("#reportPrintControlModal").modal("hide");

var reportserverurl='{{$reportserver_url}}';


if(reportserverurl.trim()==''){
	alert("Report Server Url is not added");
	return false;
}

var reportname=$("#printcontrol_reportname").val();

var dbname="{{$companyname}}";

$("#print_dataid").val(dataid);

$.ajax({
			method: 'POST',
			url:"{{url('/')}}/{{Session::get('company_name')}}/edit-tran-data-submit-print-report",
			data: $("#frm_print_report").serialize(),
			success: function(data) {
				resetPrintReportForm(); 

				var result=JSON.parse(JSON.stringify(data)); 

				var reportmode=$("#print_reportmode").val(); 
		 
				if(result['status']=='success' &&  result['message']!=''){
					SnackbarMsg(data);
				}
 
				if(reportmode=='print'){

					var pdfurl=reportserverurl+'?id='+dataid+'&reportfilename='+reportname+"&databasename="+dbname;

					// alert(pdfurl);

					// pdfurl.replace("https","http"); 

					$("#printframepdf").attr('src',pdfurl);
					
					$("#printReportPdfModal").modal("show");
				}
			}
		}); 


}

 function resetPrintReportForm(){
	$("#printcontrol_whatsapptocustomer").prop('checked',false);
	$("#printcontrol_whatsapptosalesman").prop('checked',false);
	$("#printcontrol_emailtocustomer").prop('checked',false);
	$("#printcontrol_emailtosalesman").prop('checked',false);
	$("#printcontrol_enteremail").val("");
	$("#printcontrol_enterwhatsappno").val("");
	$("#printcontrol_enterwhatsappmessage").val("");
	$(".printreport-whatsapp-control").removeClass('d-none');
	$(".printreport-email-control").removeClass('d-none'); 

 }

 @if(!empty($print_dataid))
	 printReportPdf({{$print_dataid}});		
 @endif


 
 function showTranDataHistory(){
	 	var trantable=$("#edit_tran_data_table").val();

		var tranid=	$("#tbodytransactiondata").data('id' );
  
		$("#tbodytrandatamodalhistory").empty();

		$.get("{{url('/')}}/{{$companyname}}/get-edit-tran-data-history-table-and-id/"+trantable+'/'+tranid,function(data,status){

			var result=JSON.parse(JSON.stringify(data));

			var datas=result['data'];

			if(datas.length>0){
				for(let data of datas){
				$("#tbodytrandatamodalhistory").append(`<tr>
				<td>${data['id']}</td><td>${data['docno']}</td><td>${data['docdate']}</td><td>${data['user_name']}</td><td>${data['operation']}</td><td>${data['servertime']}</td><td>${data['cust_name']}</td><td>${data['location_name']}</td><td>${data['product']}</td><td>${data['qty']}</td><td>${data['rate']}</td><td>${data['amount']}</td><td>${data['grossamt']}</td><td>${data['netamt']}</td>
				</tr>`);
              	}

			}
			else{
				$("#tbodytrandatamodalhistory").append(`<tr><td colspan='14'>No Data</td></tr>`);

			}
 

		});


		 $('#trandataHistoryShowModal').modal('show'); 
    }

	function createPopupWin(pageURL, pageTitle,
			popupWinWidth, popupWinHeight) {
			var left = (screen.width - popupWinWidth) / 2;
			var top = (screen.height - popupWinHeight) / 4;

			var myWindow = window.open(pageURL, pageTitle,
				'resizable=yes, width=' + popupWinWidth
				+ ', height=' + popupWinHeight + ', top='
				+ top + ', left=' + left);
		}

    
	$("#btn_trackorder").click(function(){
	
     	var dataid=	$("#tbodytransactiondata").data('id' );

		 $('#trackOrderShowModal').modal('show');

		 $.get("{{url('/')}}/{{$companyname}}/track-order-by-billno/"+dataid,function(data,status){

			var result=JSON.parse(JSON.stringify(data));

			$("#track_order_info").html(result['html']); 

		 });


		//  createPopupWin("{{url('/')}}/{{$companyname}}/track-order-by-billno/"+dataid, 	'Gati / Non Gati', 800, 550);


	});


</script> @endsection