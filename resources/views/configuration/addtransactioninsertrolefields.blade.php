@inject('controller','App\Http\Controllers\Configuration\TransactionActionRolesController') @extends('layout.layout') @section('content')
@inject('edittrandataservice','App\Http\Controllers\Services\EditTranDataService')
<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.min.js" integrity="sha512-dnyteqeKASHjUgi20CTeO5cfd1JwMTNV2ZS+tx5rlPCdWgnd6UKYNLM2EarSU9E6J3lMtMhUkcA6g8f3cAjoQQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js" integrity="sha512-pWCMlLqWPfRQz669NdwWZL243IK+6w+Vkt6pjiyR4TmVMy8isXg8vAvZW0UMZGIJJyoXChig3vMGL+2Xzr0INw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.min.js" integrity="sha512-Ty04j+bj8CRJsrPevkfVd05iBcD7Bx1mcLaDG4lBzDSd6aq2xmIHlCYQ31Ejr+JYBPQDjuiwS/NYDKYg5N7XKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css"> -->
<!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"> -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
/* #tbldetails th,
td {
	min-width: 200px;
} */
 
/* #tblfooter th,
td {
	min-width: 200px;
} */ 

.divfunction19 {
	overflow-x: auto;
	white-space: nowrap;
}

.divfunction19 .checkbox-inline {
	width: 100px;
}

.trandiv {
	padding: 0px;
	margin-top: 5px;
	margin-bottom: 5px;
	width:254px;
}

.tranlabel {
	display: inline-block;
	width: 35%;
	vertical-align: middle;
	word-wrap: break-word;
	text-align: left;
	height: 30px;
	padding: 0px;
}

.trancontrol {
	display: inline-block;
	width: 55%;
	vertical-align: middle;
	height: 30px;
	font-size: 12px;
}

.select2 {
	width: 100%!important;
	font-size: 12px;
}

.form-control {
	font-size: 12px;line-height:18px;
}

#trandataHistoryShowModal th{vertical-align:top;}
.addsubdetailtd{vertical-align:middle;}
.addsubdetailtd a{margin-left:-10px;}

.copymodaldialog{max-width:50%;}

.ui-widget.ui-widget-content {
z-index:100000;
}
 
 .function8{
	display:none;
	width:40px;
	height:30px;
	margin:10px;
 }
</style>

<h4 class="menu-title  mb-5 font-size-18 addeditformheading"    >{{$tablefound->table_label}} 
 
<span class="sp_addeditformaction"   >
@if(isset($dataid))
@php 
	$lasttran_message=$controller::getLastTransactionMessageWithStatus( $dataid,$tablefound->Table_Name);
@endphp
	 <strong  class='para_lasttraninfo' style="margin-left:-500px"> {{$lasttran_message}}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</strong>
@endif	
@if($mode=='add') Add @else Edit @endif</span></h4>
 
<!-- <h4 class="menu-title  mb-5 font-size-18 addeditformheading"    >{{$tablefound->table_label}} 
 
 <span class="sp_addeditformaction"   >@if($mode=='add') Add @else Edit @endif</span></h4>

  -->
 
<!-- Modal Dialog Box To Show Receivables -->

<div id="showReceivableModal" class="modal fade">
	<div class="modal-dialog  subdetailentermodaldialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Receivable Details</h4>
				<button type="button" class="close" onclick=" $('#showReceivableModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body"> 
				<div class="container ">

			<form class='form-horizontal'>
		
			<div class="form-group mtb-1">
							 
							 <label class="control-label col-sm-2 font-weight-bold" style='font-weight:bold;'  >Account Name: </label>
							 
							 <label class="control-label col-sm-6"   id='sp_accountname' ></label>
						  

					 </div>

					 <div class="form-group ">
						  
						  <label class="control-label col-sm-2 " style='font-weight:bold;' >Account Balance:</label>
						  
						  <label class="control-label col-sm-6"  id='sp_accountbalance' >0.00</label>


						 </div>

						 <div class="form-group  mtb-1">
						  
						  <label class="control-label col-sm-2" style='font-weight:bold;' >On Account:</label>
 
							<input type="number"  class="col-sm-2  receivablepayableamounts" style="width:100px;" placeholder="0.00" id='receivablepayable_onaccountentry'  value="0.00">
						 
						 
						 </div>






				<div class="card  mtb-2">
					<div class="card-body">		
				 	<div class=" mx-auto table-responsive">						
						 	<table class="table">
								<thead>
									<tr  >
										<th>Docno</th><th>DocDate</th><th>Amount</th><th>Balance</th><th>Amount To Be Adjusted &nbsp;
									<input type='button' class='btn btn-primary btn-sm' value='LIFO'  onclick="amountAdjustment('lifo')" />
									<input type='button' class='btn btn-primary btn-sm' value='FIFO' onclick="amountAdjustment('fifo')"/>
									</th>
									 
									</tr>
								</thead>
								<tbody  id="tbodyreceivabledetails">
									 
								</tbody>
							</table> 
						</div>
					</div>
				</div>

				
				<div class="form-group text-center mtb-1">
						<input type='button' class='btn btn-primary' value='Submit' id='btnsubmitform' onclick="submitReceivablePayableDetails();"/>

					

				</div>

		</form>
		</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
 

<!-- Modal Dialog Box To Show Receivables Detail wise -->

<div id="showReceivableModalDetailwise" class="modal fade"   data-row="0">
	<div class="modal-dialog  subdetailentermodaldialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Receivable Details</h4>
				<button type="button" class="close" onclick=" $('#showReceivableModalDetailwise').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body"> 
				<div class="container ">

			<form class='form-horizontal'>
		
			<div class="form-group mtb-1">
							 
							 <label class="control-label col-sm-2 font-weight-bold" style='font-weight:bold;'  >Account Name: </label>
							 
							 <label class="control-label col-sm-6"   id='sp_accountname_detailwise' ></label>
						  

					 </div>

					 <div class="form-group ">
						  
						  <label class="control-label col-sm-2 " style='font-weight:bold;' >Account Balance:</label>
						  
						  <label class="control-label col-sm-6"  id='sp_accountbalance_detailwise' >0.00</label>


						 </div>

						 <div class="form-group  mtb-1">
						  
						  <label class="control-label col-sm-2" style='font-weight:bold;' >On Account:</label>
 
							<input type="number"  class="col-sm-2  receivablepayableamounts_detailwise" style="width:100px;" placeholder="0.00" id='receivablepayable_onaccountentry_detailwise'  value="0.00">
						 
						 
						 </div>






				<div class="card  mtb-2">
					<div class="card-body">		
				 	<div class=" mx-auto table-responsive">						
						 	<table class="table">
								<thead>
									<tr  >
										<th>Docno</th><th>DocDate</th><th>Amount</th><th>Balance</th><th>Amount To Be Adjusted &nbsp;
									<input type='button' class='btn btn-primary btn-sm' value='LIFO'  onclick="amountAdjustmentDetailwise('lifo')" />
									<input type='button' class='btn btn-primary btn-sm' value='FIFO' onclick="amountAdjustmentDetailwise('fifo')"/>
									</th>
									 
									</tr>
								</thead>
								<tbody  id="tbodyreceivabledetails_detailwise">
									 
								</tbody>
							</table> 
						</div>
					</div>
				</div>

				
				<div class="form-group text-center mtb-1">
						<input type='button' class='btn btn-primary' value='Save' id='btnsave_randp_detailwise'   onclick="saveShowRandPDetailwise();"  />

					

				</div>

		</form>
		</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->




 
<!-- Modal Dialog Box To Enter Sub Details -->

<div id="subdetailEnterModal" class="modal fade">
	<div class="modal-dialog  subdetailentermodaldialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Enter Sub Details</h4>
				<button type="button" class="close" onclick=" $('#subdetailEnterModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
			<input type="hidden" id="hf_function11_sub_det_header_fields"  />
		
				<div class="card  mtb-2">
					<div class="card-body">					
			<form method='post' id='frmsubdetails'>
						<div class=" mx-auto table-responsive">						
							<input type='hidden' id='hf_subdetail_row_no' name="subdetail_row_no"  value="" />
							<table class="table">
								<thead>
									<tr id="theadsubdetails">
									 
									</tr>
								</thead>
								<tbody  id="tbodysubdetails">
									 
								</tbody>
							</table> 
						</div>
						<div class='text-center mtb-2'>
							<input type='button' class='btn btn-md btn-primary' value='Save' onclick="saveDetailSubDetails();"  />
						
						</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<!-- Modal Dialog Box for Call Data -->
<div id="callDataFromModal" class="modal fade  callmodalpopup">
	<div class="modal-dialog callmodaldialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Call Data</h4>
				<button type="button" class="close" onclick=" $('#callDataFromModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class='col-5 mx-auto'>
						<div class='tranlabel'>
							<label class="lbl_control ">Select Table :</label>
						</div>
						<div class='trancontrol'>
							<select class="form-control" id="ddnCallData_for_load_data"> @foreach ($linksetupbase_txns as $linksetupbase_txn)
								<option data-keyfld="{{$linksetupbase_txn->key_fld}}" value="{{$linksetupbase_txn->base_txn}}">{{$linksetupbase_txn->base_txn}}</option> @endforeach </select>
						</div>
					</div>
				</div>
				<div class="card  mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Docdate</th>
										<th>Docno</th>
										<th>Location</th>
										<th  >Party</th>
										<th id="call_lineacc_heading" class='d-none'>Line Acc</th>
										<th id="call_batch_heading" class='d-none'>Batch No.</th>
										<th>Product</th>
										<th>Qty</th>
										<th>Rate</th>
										<th>Amount</th>
									</tr>
								</thead>
								<tbody id="tbodycalldata">
									<tr>
										<td colspan='8' class='text-left'>No Data </td>
									</tr>
								</tbody>
							</table> 
						</div>
						<div class='text-center mtb-2'>
							<input type='button' class='btn btn-md btn-primary' value='Fill Selected Data' onclick="FillSelectedCallDataToForm()" /> </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- modal pop up to show history -->



<!-- modal pop up to select data to fill up in thr form starts-->
 
<div id="copyDataFromModal" class="modal fade">
	<div class="modal-dialog copymodaldialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Copy Data</h4>
				<button type="button" class="close" onclick=" $('#copyDataFromModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row"> 
						<div class='col-5 mx-auto'>
						<label class="lbl_control ">Select Transaction Table :</label>
						<select class='form-control' id="copydata_ddn_select_transaction_table" >
								@foreach(  $alltableswithoutdet as $tblkey=>$tblkeyval)
								<option value="{{$tblkey}}">{{$tblkeyval}}</option>
								@endforeach							
							</select>
						</div>
					 
  
				</div>
 

				<div class="row"> 
						<div class='col-5  mx-auto'>
						<label class="lbl_control ">Select Id :</label>
						 <input type='text' id='copydata_ddnselectid' class='form-control' />
						</div>
					 
				</div>	
				<div class="row"> 
			     	<h5 class='text-center'>OR</h5>
						<div class='col-5 mx-auto'>
							<label class="lbl_control ">Select Doc No. :</label>
							<input type='text' id='copydata_ddndocno' class='form-control' />
							
						</div>
				</div>
				 
				<div class='text-center mtb-2'>
					<input type='button' class='btn btn-md btn-primary' value='Copy Selected Data' onclick="CopySelecetedDataToForm()" /> 
				</div>
				
			</div>
		</div>
	</div>
</div>

<!-- modal pop up to enter reject reason starts -->


<div id="RejectReasonFromModal" class="modal fade">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Reject Reason</h4>
				<button type="button" class="close" onclick=" $('#RejectReasonFromModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body">
				
			<div class='text-center '>
				<textarea class="form-control"  id="txt_rejectreason" cols="5" rows="8"></textarea>
			</div>
 
				<div class='text-center mtb-2'>
					<input type='button' class='btn btn-md btn-primary' value='Submit' onclick="SubmitRejectReason()" /> 
				</div>
				
			</div>
		</div>
	</div>
</div>


<!-- modal pop up to enter reject reason ends -->

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
<div class="pagecontent" style="max-width:100%;overflow:hidden!important;">

<!-- @if(isset($dataid))
@php 
	$lasttran_message=$controller::getLastTransactionMessageWithStatus( $dataid,$tablefound->Table_Name);
@endphp
	 <p  class='para_lasttraninfo'> {{$lasttran_message}}</p>
@endif
 -->

	<div class="container-fluid"> 
		<!-- <input type='text' id="copydata_ddnselectid"  /> -->
	<!-- <a href="javascript:void(0);" onclick="Swal.fire('Welcome');">click here only</a> -->
  @include('configuration.loader')
  <!-- onsubmit="return ValidateForm();" -->
		<form method="post" id="transactionAddDataForm" class='transactionAddDataForm' action="{{url('/')}}/{{$companyname}}/submit-Add-Transaction-Insert-Role-Fields" enctype="multipart/form-data" > @csrf
		<input type='hidden' name="formmode" id="formmode" value="{{$mode}}" />
		<input type="hidden" name="transaction_table" id="transaction_table" value="<?php  echo    $tablefound->Table_Name; ?>" />
			<input type="hidden" name="transaction_table_id" id="transaction_table_id" value="<?php  echo    $tablefound->Id; ?>" />
			<input type="hidden" name="transaction_table_txnclass" id="transaction_table_txnclass" value="<?php  echo    $tablefound->txn_class; ?>" />
			<input type='hidden' name='receivablepayable_onaccount'  id="receivablepayable_onaccount" value="0"/>
			<input type='hidden' id='hf_subdetail_rows_data' name="subdetail_rows_data"  value="" />	
			<input type='hidden' name='receivablepayable_amountadjustments'  id="receivablepayable_amountadjustments" value="{}"/>
			<input type='hidden' id="receivablepayable_allow" value="@if($allowpayablereceivable==true) 1 @else 0 @endif" />
			<input type="hidden" id="stockavailability_validation"  value="@if($checkstockavailability['check_validation']==true) 1 @else 0 @endif"  data-warnstop="@if($checkstockavailability['warn_stop']==true) 1 @else 0 @endif"  />
			<input type="hidden"  id="email_whatsapp_mode" value=""  name="email_whatsapp_mode"  />
			<input type='hidden' id='print_report_mode'  value=""  name="print_report_mode" />
 			<input type='hidden' name='receivablepayable_amountadjustments_detailwise'  id="receivablepayable_amountadjustments_detailwise" value="{}"/>
			<input type='hidden'  name='detail_rows_indexes'   id="detail_rows_indexes"  value="[1]"  />
			@if(isset($dataid))
			<input type='hidden' id='editdetailonreference'  value="{{$editdetailonreference}}" />
			<input type="hidden" name="data_id" id="data_id" value="{{$dataid}}"  />
			@endif 
			<input type='hidden' name='transaction_subdetails_saved' id="transaction_subdetails_saved" value="" />

			
			<input type='hidden'  id="creditlimit_details" data-creditlimit="@if($tblpdacc_result['crelimit']==true) 1 @else 0 @endif"   data-warnstop="@if($tblpdacc_result['warn_stop']==true) 1 @else 0 @endif" />
		
			
 			<div @if(count($headerfields)==0) style="display:none;" @endif>
				<!-- <label class="lbl_control_big  mlr-2">Header Fields:</label> -->

				  	<div class="card" @if( (count($detailfields)==0 && count($footerfields)==0)==0 ) style="height:150px;overflow-y:auto;" @endif>
						<div class="card-body row plr-1">

						@foreach($headerfields as $headerfield)
					<div class="col-3 trandiv @if($controller::CheckFieldDisplay($headerfield->Field_Name,$showhidefields)) d-none @endif">
						<div class='tranlabel'>
							<label class="lbl_control ">{{$headerfield->fld_label}} :</label>
						</div>
						<div class='trancontrol'> @if($headerfield->Field_Function==1 || $headerfield->Field_Function==12)
							<input type="text" class="form-control" name="data[{{$headerfield->Field_Name}}]"  data-fieldname="{{strtolower($headerfield->Field_Name)}}" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==11 && $headerfield->Tab_Id=='Pricing')
							<input type="text" class="form-control function11" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" data-formulatype='pricing' data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==11 && $headerfield->Tab_Id!='Pricing')
							<input type="text" class="form-control function11" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" data-formulatype='nonpricing' data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==2)
							<select class="form-control  function2" data-isdet="0" name="data[{{$headerfield->Field_Name}}]" data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select> 
							@elseif ($headerfield->Field_Function==4)
							<select class="form-control function4" data-isdet="0" name="data[{{$headerfield->Field_Name}}]" @if(trim($headerfield->Field_Name)=='cust_id') id='ddnCustId' @endif data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select>
		
							@elseif ($headerfield->Field_Function==5)
							<select class="form-control function5" data-isdet="0" name="data[{{$headerfield->Field_Name}}]" data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select> @elseif ($headerfield->Field_Function==6)
							<input type="text" class="form-control function6" name="data[{{$headerfield->Field_Name}}]" data-isdet="0"   data-fieldname="{{strtolower($headerfield->Field_Name)}}" placeholder="Select Date Calendar" value="{{date('d-m-Y',strtotime('now'))}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) data-readonly="1" @else data-readonly="0" @endif />
							<!-- {{date('Y-m-d',strtotime('now'))}} -->@elseif ($headerfield->Field_Function==8)
							<input type="file" class="form-control" data-isdet="0" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields)) readonly @endif data-fieldname="{{strtolower($headerfield->Field_Name)}}" /> 
							<a class="btn btn-primary function8"  data-isdet="0" download  data-fieldname="{{strtolower($headerfield->Field_Name)}}"   href=""><i class="bi bi-download"></i></a>
							@elseif ($headerfield->Field_Function==19)
							<div class="divfunction19" data-isdet="0" data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) data-readonly='readonly' @endif > </div> @elseif ($headerfield->Field_Function==27)
							<input type='text' class="form-control" data-isdet="0" name="data[{{$headerfield->Field_Name}}]" data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif value="
							<?php echo date('Y-m-d H:i:s',strtotime('now'));  ?>" /> @elseif ($headerfield->Field_Function==31)
								<input type="datetime-local" class='form-control' name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif data-isdet="0" data-fieldname="{{strtolower($headerfield->Field_Name)}}" value="{{date('Y-m-d H:i:s',strtotime('now'))}}"/> @elseif ($headerfield->Field_Function==40)
								<input type="file" class="form-control" accept="image/*" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif data-isdet="0" data-fieldname="{{strtolower($headerfield->Field_Name)}}"/> @elseif ($headerfield->Field_Function==18)
								<select class="form-control function18" data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select> @elseif ($headerfield->Field_Function==22)
								<input type='text' data-id="{{$headerfield->Id}}" class='form-control function22 trancontrol' name="data[{{$headerfield->Field_Name}}]" data-isdet="0" data-fieldname="{{strtolower($headerfield->Field_Name)}}" readonly='true' /> @elseif ($headerfield->Field_Function==20)
								<select class='form-control function20' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select> @elseif ($headerfield->Field_Function==3)
								<select class="form-control function3" data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif data-isdet="0"  ></select> @elseif ($headerfield->Field_Function==14)
								<select class='form-control function14' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif data-isdet="0" required ></select> @elseif ($headerfield->Field_Function==15)
								<input type='text' class='form-control function15' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif data-datefield="{{$headerfield->Field_Value}}" data-isdet="0" required ></select> @elseif ($headerfield->Field_Function==16)
								<select class="form-control function16" data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select> @elseif ($headerfield->Field_Function==24)
								<select class="form-control function24" data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif data-isdet="0" required ></select> @elseif ($headerfield->Field_Function==34)
								<textarea class='form-control function34' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif data-isdet="0" ></textarea> @elseif ($headerfield->Field_Function==21 && empty($headerfield->Field_Value) )
								<input type='text' class='form-control function21' data-hasfieldvalue='0' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==21 && !empty($headerfield->Field_Value) )
								<input type='text' class='form-control function21' data-hasfieldvalue='1' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==45 && empty($headerfield->Field_Value) )
								<input type='text' class='form-control function45' data-hasfieldvalue='0' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==45 && !empty($headerfield->Field_Value) )
								<input type='text' class='form-control function45' data-hasfieldvalue='1' data-fieldname="{{strtolower($headerfield->Field_Name)}}" name="data[{{$headerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) readonly @endif /> @elseif ($headerfield->Field_Function==30)
								<select class='form-control function30' data-fieldname="{{strtolower($headerfield->Field_Name)}}" data-isdet="0" name="data[{{$headerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select>
								@elseif ($headerfield->Field_Function==17)
							<select class="form-control function17" data-isdet="0" name="data[{{$headerfield->Field_Name}}]"  data-fieldname="{{strtolower($headerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($headerfield->Field_Name,$showhidefields,$headerfield->rd_only)) disabled @endif required ></select>
		
								@endif </div>
					</div> @endforeach

					@if(count($tran_accounts)>0)
					<div class="col-3 trandiv">
						<div class='tranlabel'>
							<label class="lbl_control ">Tran Account:</label>
						</div>
						<div class='trancontrol'>
							<select name="tran_account" id="tran_account" class="form-control" required>
								<option value=''>Select Tran Account</option>
								@foreach (  $tran_accounts as   $tran_account)
								<option value="{{$tran_account->Id}}"  
								@if( isset( $tranaccount_tempid) && $tran_account->Id== $tranaccount_tempid)
								selected
								@elseif(trim($tran_account->is_default)=="True")
								  selected
								 @endif 
								>{{$tran_account->TemplateId}}</option>
									
								@endforeach

							</select>

						</div>
					</div>
					@endif
						</div>
						</div>

				

				
				 

			</div>
			<input type='hidden' id='hf_function30_det_fields' />
			<input type='hidden' id='hf_function30_fields' />
			<input type='hidden' id='hf_function11_det_header_fields' />
			<input type='hidden' id='hf_function11_pricing_fields' />
			<div @if(count($detailfields)==0) style='display:none;' @endif>
				<!-- <label class="lbl_control_big  mlr-2" style="margin-top:50px;">Detail Fields:</label> -->
				<div class="row  mlr-2 mtb-2">
					<!-- style="display: block;height:250px;overflow-y:auto;overflow-x: auto; " -->
					<div class="col-12 plr-0 "> @php $noofdetailfields=count($detailfields); @endphp
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table class="table table-striped" id="tbldetails">
										<thead>
											<tr>
												<th class="text-center" style='min-width:10px;'>#</th>
												 @foreach($detailfields as $detailfield) @php $detailindex=0; @endphp
												<th class="text-center   @if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif">{{$detailfield->fld_label}}</th>

												@if(trim($detailfield->Field_Value)=="qty")
												<th  class="text-center"></th>
												@endif
												@endforeach

												@if($show_randp==true)
												<th  class="text-center">R / P</th>
												@endif
												<th class='text-center'>Delete</th>
											</tr>
										</thead>
										<tbody data-noofrows='1' id='tbodydetailfields'>
											<tr id='tr_1'>
												<td style='min-width:10px'>
													<label class='lbl_control text-center' style='font-weight:bold;'>1</label>
												</td> @foreach($detailfields as $detailfield) @php $detailindex++; $fieldwidth=$detailfield->Width; if(empty($fieldwidth)){ $fieldwidthpx='200';} else{  $fieldwidthpx=$fieldwidth;  } @endphp @if($detailfield->Field_Function==1 || $detailfield->Field_Function==12 )
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type="text" name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control   @if(!empty($detailfield->get_tot) && trim($detailfield->get_tot)=='True'  ) gettotal  @endif  	@if(trim($detailfield->Field_Value)=='qty') qtyentry @endif" @if(trim($detailfield->Field_Name)!="descr") required @endif data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif /> </td>
													 @elseif ( $detailfield->Field_Function=='11')
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type="text" name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control function11  " data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-row='1' @if($detailfield->Field_Name!='descr') value='0' @endif name="field" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required /> </td> @elseif ($detailfield->Field_Function==2)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control   function2  " data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required > </select>
												</td> @elseif ($detailfield->Field_Function==4)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control function4  " data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif ($detailfield->Field_Function==5)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control function5  " data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required > </select>
												</td> @elseif ($detailfield->Field_Function==6)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type="text" name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control function6 " data-isdet="1" data-row='1' placeholder="Select Date Calendar" data-fieldname="{{strtolower($detailfield->Field_Name)}}" value="{{date('Y-m-d',strtotime('now'))}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) data-readonly="1" @else data-readonly="0" @endif required/> </td> @elseif ($detailfield->Field_Function==8)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type="file" name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control  " data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required/> </td> @elseif ($detailfield->Field_Function==19)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<div class="divfunction19  " data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-isdet="1" data-row='1' @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif > </div>
												</td> @elseif ($detailfield->Field_Function==27)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control  " data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" value="<?php echo date('Y-m-d h:i:s',strtotime('now'));  ?>" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required/> </td> @elseif ($detailfield->Field_Function==31)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type="datetime-local" name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control  ' data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" value="{{date('Y-m-d H:i:s',strtotime('now'))}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required /> </td> @elseif($detailfield->Field_Function==40)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type="file" name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control  " data-isdet="1" data-row='1' accept="image/*" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required/> </td> @elseif($detailfield->Field_Function==18)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control function18  " data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($detailfield->Field_Function==22)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-row='1' data-isdet="1" class='form-control function22  ' readonly='true' @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required /> </td> @elseif($detailfield->Field_Function==20)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function20  ' data-isdet="1" data-row='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($detailfield->Field_Function==3)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class="form-control function3  " data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($detailfield->Field_Function==14)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function14  ' data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($detailfield->Field_Function==15)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function15  ' data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-datefield="{{$detailfield->Field_Value}}" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required /></td> @elseif($detailfield->Field_Function==16)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function16  ' data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($detailfield->Field_Function==24)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function24  ' data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($detailfield->Field_Function==34)
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<textarea name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function34  ' data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required ></textarea>
												</td> @elseif($detailfield->Field_Function==21 && empty($detailfield->Field_Value))
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function21  ' data-row='1' data-isdet="1" data-hasfieldvalue='0' data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-isdet="1" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required /> </td> @elseif($detailfield->Field_Function==21 && !empty($detailfield->Field_Value))
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function21  ' data-row='1' data-isdet="1" data-hasfieldvalue='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-isdet="1" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required/> </td> @elseif($detailfield->Field_Function==45 && empty($detailfield->Field_Value))
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function45  ' data-row='1' data-isdet="1" data-hasfieldvalue='0' data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-isdet="1" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required /> </td> @elseif($detailfield->Field_Function==45 && !empty($detailfield->Field_Value))
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<input type='text' name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control function45  ' data-row='1' data-isdet="1" data-hasfieldvalue='1' data-fieldname="{{strtolower($detailfield->Field_Name)}}" data-isdet="1" @if($detailfield->Field_Name!='descr') value='0' @endif @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required/> </td> @elseif($detailfield->Field_Function==30 )
												<td class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px'>
													<select name="data_det[0][{{$detailfield->Field_Name}}]" class='form-control  function30  ' data-row='1' data-isdet="1" data-fieldname="{{strtolower($detailfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) disabled @endif required ></select>
												</td> @endif  
												@if(trim($detailfield->Field_Value)=="qty")
												
												<td  class="addsubdetailtd"><a href="javascript:void(0);" data-row='1'  class="addeditsubdetaillink" data-detailrow="1"><i class="fa fa-plus"></i></a>
												</td>

												@endif 
												@endforeach

												
												@if($show_randp==true)
												<td class='text-center'> <a class="lnk_show_randp"  data-row="1"  href="javascript:void(0);"  ><i class="fa fa-plus" aria-hidden="true"></i></a>

												@endif
												<td class='text-center'> <a class="lnk_delete_detail_row lastdetailelement" data-row="1" href="javascript:void(0);"  ><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a> </td>
											</tr>
										</tbody>
										<tfoot> @foreach($detailfields as $detailfield) @if(trim($detailfield->get_tot)=="True")
											<th class='text-center'>
												<label class="lbl_grand_control detgrandtotal" data-totalof="{{$detailfield->Field_Name}}">0.00</label>
											</th> @else
											<th></th> @endif @endforeach </tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div @if(count($footerfields)==0) style='display:none;' @endif>
				<!-- <label class="lbl_control_big  mlr-2"  >Footer Fields:</label> -->
				<div class="row  mlr-2">
					<div class="col-12  plr-0" style="display: block;height:150px;overflow-y:auto;overflow-x: auto; ">
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table class="table table-striped" id="tblfooter">
										<thead>
											<tr> @foreach($footerfields as $footerfield) 
												<th class="text-center   @if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='min-width:{{$fieldwidthpx}}px;'> {{$footerfield->fld_label}}</th> @endforeach </tr>
										</thead>
										<tbody>
											<tr> @foreach($footerfields as $footerfield) 
												@php 
												$fieldwidth=$footerfield->Width; 
												if(empty($fieldwidth)){ $fieldwidthpx=200; } 
												else
												{ $fieldwidthpx=$fieldwidth; }
												 @endphp 
												 @if($footerfield->Field_Function==1 || $footerfield->Field_Function==12)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif"  style='width:{{$fieldwidth}}px'>
													<input type="text" class="form-control" data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" data-isdet="0" name="data[{{$footerfield->Field_Name}}]"
													 @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required/> </td> @elseif ( $footerfield->Field_Function=='11')
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type="text" class="form-control function11" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif data-fieldname="{{strtolower($footerfield->Field_Name)}}" required/> </td> @elseif ($footerfield->Field_Function==2)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class="form-control function2" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif data-fieldname="{{strtolower($footerfield->Field_Name)}}" required >
														<option>Function 2</option>
													</select>
												</td> @elseif ($footerfield->Field_Function==4)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class="form-control function4" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required ></select>
												</td> @elseif ($footerfield->Field_Function==5)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class="form-control function5" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required ></select>
												</td> @elseif ($footerfield->Field_Function==6)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type="text" class="form-control function6" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" placeholder="Select Date Calendar" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) data-readonly="1" @else data-readonly="0" @endif data-fieldname="{{strtolower($footerfield->Field_Name)}}" value="{{date('d-m-Y',strtotime('now'))}}" required/> </td> @elseif ($footerfield->Field_Function==15)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type="text" class="form-control" data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required/> </td> @elseif ($footerfield->Field_Function==18)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class="form-control" data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required >
														<option>Select From Ajax suggestion box</option>
													</select>
												</td> @elseif ($footerfield->Field_Function==8)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<!-- <select class="form-control">
										<option>Select From Ajax suggestion box</option>
									</select> -->
													<input type="file" class="form-control" data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required/> </td> @elseif ($footerfield->Field_Function==19)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<div class="divfunction19" data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) data-readonly='readonly' @endif> </div>
												</td> @elseif ($footerfield->Field_Function==27)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' class="form-control" data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif value="
													<?php echo date('Y-m-d H:i:s',strtotime('now'));  ?>" required /> </td> @elseif ($footerfield->Field_Function==31)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type="datetime-local" class='form-control' name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif value="{{date('Y-m-d H:i:s',strtotime('now'))}}" required/> </td> @elseif ($footerfield->Field_Function==40)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type="file" class="form-control" accept="image/*" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif data-isdet="0" required /> </td> @elseif ($footerfield->Field_Function==18)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class="form-control function18" data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required ></select>
												</td> @elseif($footerfield->Field_Function==22)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' data-fieldname="{{strtolower($footerfield->Field_Name)}}" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" class='form-control function22' readonly='true' required/> </td> @elseif ($footerfield->Field_Function==20)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class='form-control function20' data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" name="data[{{$footerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required ></select>
												</td> @elseif ($footerfield->Field_Function==3)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class='form-control function3' data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required ></select>
												</td> @elseif ($footerfield->Field_Function==14)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class='form-control function14' data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required></select>
												</td> @elseif ($footerfield->Field_Function==15)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' class='form-control function15' data-datefield="{{$footerfield->Field_Value}}" name="data[{{$footerfield->Field_Name}}]" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif data-fieldname="{{strtolower($footerfield->Field_Name)}}" required /> </td> @elseif ($footerfield->Field_Function==16)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class='form-control function16' data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" name="data[{{$footerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif required ></select>
												</td> @elseif ($footerfield->Field_Function==24)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<select class='form-control function24' name="data[{{$footerfield->Field_Name}}]" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) disabled @endif data-isdet="0" data-fieldname="{{strtolower($footerfield->Field_Name)}}" required ></select>
												</td> @elseif ($footerfield->Field_Function==34)
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<textarea class='form-control function34' data-isdet="0" name="data[{{$footerfield->Field_Name}}]" data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required ></textarea>
												</td> @elseif ($footerfield->Field_Function==21 && empty($footerfield->Field_Value) )
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' class='form-control function21' name="data[{{$footerfield->Field_Name}}]" data-hasfieldvalue='0' data-fieldname="{{strtolower($footerfield->Field_Name)}}" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif data-isdet="0" required /> </td> @elseif ($footerfield->Field_Function==21 && !empty($footerfield->Field_Value) )
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' class='form-control function21' name="data[{{$footerfield->Field_Name}}]" data-hasfieldvalue='1' data-fieldname="{{strtolower($footerfield->Field_Name)}}" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required/> </td> @elseif ($footerfield->Field_Function==45 && empty($footerfield->Field_Value) )
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' class='form-control function45' name="data[{{$footerfield->Field_Name}}]" data-hasfieldvalue='0' data-fieldname="{{strtolower($footerfield->Field_Name)}}" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required/> </td> @elseif ($footerfield->Field_Function==45 && !empty($footerfield->Field_Value) )
												<td class="@if($controller::CheckFieldDisplay($footerfield->Field_Name,$showhidefields)) d-none @endif" style='width:{{$fieldwidthpx}}px'>
													<input type='text' class='form-control function45' name="data[{{$footerfield->Field_Name}}]" data-hasfieldvalue='1' data-fieldname="{{strtolower($footerfield->Field_Name)}}" data-isdet="0" @if($controller::CheckFieldReadOnly($footerfield->Field_Name,$showhidefields,$footerfield->rd_only)) readonly @endif required/> </td> @endif @endforeach </tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='row mtb-2'>
				<div class='col-12 text-center'> @if(count($linksetupbase_txns)>0)
					<input type='button' class='btn btn-primary' value='Pull Data' onclick="showCallDataModal();" /> @endif
					<input type='button' id="btnsave" class="btn btn-primary  @if(!$showhidebuttons['save']) d-none  @endif" value='Save' />
					<input type='submit' class="btn btn-primary  @if(!$showhidebuttons['approve_and_save']) d-none  @endif" value='Approve & Save' />
					<input type='button' class="btn btn-primary  @if(!$showhidebuttons['reject']) d-none  @endif"  onclick="OpenRejectReason();" value='Reject' />
					<input type='button' class="btn btn-primary   @if(!$showhidebuttons['edit']) d-none  @endif" value='Edit'   />
					<input type='button'  id="btndelete" class="btn btn-primary     @if(!$showhidebuttons['delete']) d-none  @endif" value='Delete' />
					<input type='button' class='btn btn-primary prevnextrecord'  value='Previous' />
					@if(isset($dataid))
					<input type='button' class='btn btn-primary  prevnextrecord' value='Next' />
					@endif
					
					<input type='button' onclick="showCopyDataModal();" class="btn btn-primary @if(!$showhidebuttons['copy']) d-none  @endif" value='Copy' />
					
					<input type='button'  onclick="showTranDataHistory()" class="btn btn-primary  @if(!$showhidebuttons['history']) d-none  @endif" value='History' />
					<input type='button' class="btn btn-primary @if(!$showhidebuttons['view']) d-none  @endif" value='Save & Print'  onclick="SaveAndPrint();" /> 
					<input type='button' class="btn btn-primary    @if(!$showhidebuttons['view']) d-none  @endif" value='Save & Email'  onclick="SaveAndEmail();" />
					<input type='button' class="btn btn-primary    @if(!$showhidebuttons['view']) d-none  @endif" value='Save & Whatsapp'  onclick='SaveAndWhatsapp();' /> </div>


			</div>
	</div>
	</form>
</div>
</div> @endsection @section('js')
<!--     
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
     -->
<script type="text/javascript">
$(function() {
    showLoader(3); 
	var tablename = $("#transaction_table").val();
	var nooffunction2 = $(".function2[data-isdet='0']").length;
	if(nooffunction2 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues-checkoptions/" + tablename;
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(".function2[data-isdet='0']").each(function() {
				var fieldname = $(this).data('fieldname');
				var url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues";
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(".function2[data-fieldname='" + fieldname + "'][data-isdet='0']", '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
				} else {
					initSelect2Search(".function2[data-fieldname='" + fieldname + "'][data-isdet='0']", url, '', null, {
						'table_name': tablename,
						'field_name': fieldname
					});
				}
			});
		});
	}
	var nooffunction4 = $(".function4[data-isdet='0']").length;
	if(nooffunction4 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows-checkoptions/" + tablename;
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(".function4[data-isdet='0']").each(function() {
				var fieldname = $(this).data('fieldname'); 
 
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']", '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
				} else {
					var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
					initSelect2Search(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']", url, '', null, {
						'table_name': tablename,
						'field_name': fieldname
					});

					if( $("#formmode").val()=="add" && optiondetail[fieldname]['default_id']!==undefined){
						
					addSelect2SelectedOption(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']",optiondetail[fieldname]['default_text'],optiondetail[fieldname]['default_id']);

					$(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']").trigger("change");

					}



				}
			});
		});
	}
	var nooffunction5 = $(".function5[data-isdet='0']").length;
	if(nooffunction5 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function5-codes-checkoptions/" + tablename;
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(".function5[data-isdet='0']").each(function() {
				var fieldname = $(this).data('fieldname');
				if(optiondetail[fieldname]['noofoptions'] == 1) {
				initSelect2WithOnlyOneOption(".function5[data-fieldname='" + fieldname + "']", '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
		  
				} else {
					var url = "{{url('/')}}/{{$companyname}}/get-function5-codes";
					initSelect2Search(".function5[data-fieldname='" + fieldname + "']", url, '', null, {
						'table_name': tablename,
						'field_name': fieldname
					});
				}
			});
		});
	}
	var nooffunction21_without = $(".function21[data-hasfieldvalue='0'][data-isdet='0']").length;
	if(nooffunction21_without > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function21-Without-Fieldvalues/" + tablename;
		$.get(url, function(data, status) {
			var fields = JSON.parse(JSON.stringify(data));
			for(let field of fields) {
				$(`.function21[data-fieldname='${field['field_name']}'][data-isdet='0']`).val(field['field_value']);
			}
		});
	}
	$(".divfunction19[data-isdet='0']").each(function() {
		var thiselement = $(this);
		var fieldname = $(this).data('fieldname');
		var url = "{{url('/')}}/{{$companyname}}/get-function19-fieldvalues";
		$.post(url, {
			'table_name': tablename,
			'field_name': fieldname
		}, function(data, status) {
			var html = '';
			var result = JSON.parse(JSON.stringify(data));
			var textfieldname = result['fieldname'];
			var items = result['fieldvalues'];
			for(let item of items) {
				html = html + `<label class="checkbox-inline"><input type="checkbox" name='data[${textfieldname}][]' value="${item['id']}">&nbsp;${item['text']}</label>`;
			}
			thiselement.html(html);
		});
	});
	$(".function18[data-isdet='0']").each(function() {
		var fieldname = $(this).data('fieldname');
		var url = "{{url('/')}}/{{$companyname}}/get-function18-users-checkoptions";
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			if(resultarray['noofusers'] == 1) {
				initSelect2WithOnlyOneOption(".function18[data-fieldname='" + fieldname + "'][data-isdet='0']", '', resultarray['single_id'], resultarray['single_text']);
			} else {
				url = '/{{$companyname}}/get-function18-users';
				initSelect2Search(".function18[data-fieldname='" + fieldname + "'][data-isdet='0']", url, '');
			}
		})
	});
	$(`.function20[data-isdet='0']`).each(function() {
		var id = $(this).data('id');
		var url = "{{url('/')}}/{{$companyname}}/get-function20-user";
		var tablename = $("#transaction_table").val();
		$.get(url, function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			$(`.function20[data-isdet='0']`).append(`<option value="${result['id']}">${result['user_id']}</option>`);
		});
	});
	$("#ddnCustId").change(function() {
		var custid = $(this).val();
		$(".function22").val("");
		var url = "{{url('/')}}/{{$companyname}}/get-customer-balance/" + custid;
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			$(".function22").val(resultarray['balance']);
			$(".function22").prop('readonly', true);
		});
	});
	$(".function4[data-isdet='0']").change(function() {
		var fieldname = $(this).data('fieldname');
		var fieldval = $(this).val();
		if(fieldval == null) {
			return;
		}
		var url = "{{url('/')}}/{{$companyname}}/get-function3-fieldvalues-checkoptions";
		$.post(url, {
			'table_name': tablename,
			'scr_field': fieldname,
			'field_val': fieldval
		}, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				if(result['noofoptions'] == 1) {
					initSelect2WithOnlyOneOptionWithAddOption(".function3[data-fieldname='" + result['field_name'] + "'][data-isdet='0']", '', result['field_value'], result['field_value']);
				} else {
					url = "{{url('/')}}/{{$companyname}}/get-function3-fieldvalues";
					initSelect2Search(".function3[data-fieldname='" + result['field_name'] + "'][data-isdet='0']", url, '', null, {
						'field_name': result['field_name'],
						'table_name': tablename
					});
				}
				//  $(".function3[data-fieldname='"+result['field_name']+"'][data-isdet='0']").val(result['field_value']);  
			}
		});
		var url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues-checkoptions";
		$.post(url, {
			'table_name': tablename + '_det',
			'scr_field': fieldname,
			'field_val': fieldval
		}, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				if(result['noofoptions'] == 1) {
					initSelect2WithOnlyOneOptionWithAddOption(".function24[data-fieldname='" + result['field_name'] + "'][data-isdet='1']", '', result['field_value'], result['field_value']);
				} else {
					url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues";
					initSelect2Search(".function24[data-fieldname='" + result['field_name'] + "'][data-isdet='1']", url, '', null, {
						'field_name': result['field_name'],
						'table_name': tablename
					});
				}
			}
		});
	});
	var nooffunction14 = $(".function14[data-isdet='0']").length;
	if(nooffunction14 > 0) {
		$.get("{{url('/')}}/{{$companyname}}/get-Function14-Currency", function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result['noofcurrency'] == 1) {
				$(".function14").each(function() {
					var fieldname = $(this).data('fieldname');
					initSelect2WithOnlyOneOption(`.function14[data-fieldname='${fieldname}']`, 'Select Currency', result['single_id'], result['single_text']);
				});
			} else {
				$(".function14").each(function() {
					var fieldname = $(this).data('fieldname');
					var url = "{{url('/')}}/{{$companyname}}/get-Function14-All-currencies";
					initSelect2Search(`.function14[data-fieldname='${fieldname}']`, url, '');
				});
			}
		});
	}
	var nooffunction15 = $(`.function15[data-isdet='0']`).length;
	if(nooffunction15 > 0) {
		$(`.function15[data-isdet='0']`).each(function() {
			var exrateelement = $(this);
			var datefield = $(this).data('datefield');
			$(`.function14[data-isdet='0']`).change(function() {
				var currencyval = $(`.function14[data-isdet='0']`).val();
				var datechange = $(`input[data-isdet='0'][data-fieldname='${datefield}']`).val();
				$.post("{{url('/')}}/{{$companyname}}/get-Function15-Exchange-Rate", {
					'dategiven': datechange,
					'currency': currencyval
				}, function(data, status) {
					var result = JSON.parse(JSON.stringify(data));
					exrateelement.val(result['exrate']);
				})
			});
		});
	}
	var nooffunction16 = $(`.function16[data-isdet='0']`).length;
	if(nooffunction16 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function16-uoms";
		$.post(url, function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result.length == 1) {
				initSelect2WithOnlyOneOption(`.function16[data-isdet='0']`, '', result[0]['id'], result[0]['text']);
			} else {
				initSelect2Search(`.function16[data-isdet='0']`, url, '');
			}
		});
	}
	var noooffunction21_withfield = $(".function21[data-hasfieldvalue='1'][data-isdet='0']").length;
	if(noooffunction21_withfield > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function21-With-Fieldvalues/" + tablename;
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let fieldresult of resultarray) {
				var fieldisdet = fieldresult['fieldisdet'];
				var forfield = fieldresult['forfield'];
				var fromfields = fieldresult['fromfields'];
				for(let result of fromfields) {
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`).data('function21fieldvalues', JSON.stringify(fromfields));
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`).data('function21forfield', forfield);
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`).on('change', function() {
						var function4fieldsvaluestring = $(this).data('function21fieldvalues');
						var function21forfield = $(this).data('function21forfield');
						var newfieldarray = JSON.parse(function4fieldsvaluestring);
						var allvaluesgiven = true;
						var index = 0;
						for(let fieldofarray of newfieldarray) {
							var nameoffield = fieldofarray['field_name'];
							var val = $(`.function4[data-fieldname='${fieldofarray['field_name']}'][data-isdet='${fieldofarray['is_det']}']`).val();
							if(val == null || val == '') {
								alert('Please give value at Field ' + nameoffield);
								allvaluesgiven = false;
								break;
							}
							newfieldarray[index]['value'] = val;
							index++;
						}
						if(allvaluesgiven == true) {
							$.post("{{url('/')}}/{{$companyname}}/getFunction21-SingleFieldValue-For-WithoutDet", {
								'tablename': tablename,
								'fieldname': function21forfield,
								'fromfields': newfieldarray
							}, function(data, status) {
								var result = JSON.parse(JSON.stringify(data));
								$(`.function21[data-fieldname='${function21forfield}'][data-isdet='0']`).val(result['fieldvalue']);
							});
						}
					});
				}
			}
		});
	}
	var noooffunction45_withfield = $(".function45[data-hasfieldvalue='1'][data-isdet='0']").length;
	if(noooffunction45_withfield > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function45-With-Fieldvalues/" + tablename;
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let fieldresult of resultarray) {
				var fieldisdet = fieldresult['fieldisdet'];
				var forfield = fieldresult['forfield'];
				var fromfields = fieldresult['fromfields'];
				for(let result of fromfields) {
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`).data('function45fieldvalues', JSON.stringify(fromfields));
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`).data('function45forfield', forfield);
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`).on('change', function() {
						var function4fieldsvaluestring = $(this).data('function45fieldvalues');
						var function45forfield = $(this).data('function45forfield');
						var newfieldarray = JSON.parse(function4fieldsvaluestring);
						var allvaluesgiven = true;
						var index = 0;
						for(let fieldofarray of newfieldarray) {
							var nameoffield = fieldofarray['field_name'];
							var val = $(`.function4[data-fieldname='${fieldofarray['field_name']}'][data-isdet='${fieldofarray['is_det']}']`).val();
							if(val == null || val == '') {
								alert('Please give value at Field ' + nameoffield);
								allvaluesgiven = false;
								break;
							}
							newfieldarray[index]['value'] = val;
							index++;
						}
						if(allvaluesgiven == true) {
							$.post("{{url('/')}}/{{$companyname}}/getFunction45-SingleFieldValue-For-WithoutDet", {
								'tablename': tablename,
								'fieldname': function45forfield,
								'fromfields': newfieldarray
							}, function(data, status) {
								var result = JSON.parse(JSON.stringify(data));
								$(`.function45[data-fieldname='${function45forfield}'][data-isdet='0']`).val(result['fieldvalue']);
							});
						}
					});
				}
			}
		});
	}
	// $(".lnk_delete_detail_row").click(function() {
	// 	$(this).parent().parent().remove();
	// 	var noofrows = $("#tbodydetailfields").data('noofrows');
	//      noofrows = noofrows - 1;
	// 	 $("#tbodydetailfields").data('noofrows', noofrows);
	// 	calculateAllFunction11FieldFormulaPricing();
	// });
	$("#tbldetails").on('click', ".lnk_delete_detail_row", function() {
		var clickrow=$(this);
		var rownum=$(this).data('row');

		var isedit=$("#data_id").length;

		var hasdoc=$(`[data-fieldname='docno'][data-isdet='0']`).length;

		var transactiontable=$("#transaction_table").val();

		if(isedit==1 && hasdoc==1){

			var docno=$(`[data-fieldname='docno'][data-isdet='0']`).val();

			var id=$(`[data-fieldname='id'][data-isdet='1'][data-row='${rownum}']`).val();

			$.post("{{url('/')}}/{{$companyname}}/check-detail-row-before-delete-is-referenced",{ 'docno':docno ,'detail_id':id,'tran_table':transactiontable},
			function(data,status){

				var result=JSON.parse(JSON.stringify(data));
 
				if(result['status']==true){
				 
					clickrow.parent().parent().remove();

					deleteShowRAndPData(rownum);
					addRemoveDetailIndex(rownum,'remove');
				}
				else{
					SnackbarMsg(result);
				} 
			}
			);

		}
		else{
			$(this).parent().parent().remove();
			
			deleteShowRAndPData(rownum);
			addRemoveDetailIndex(rownum,'remove');

		     var noofrows = $("#tbodydetailfields").data('noofrows');
		// noofrows = noofrows - 1;
		// $("#tbodydetailfields").data('noofrows', noofrows);
	

		}

		calculateAllFunction11FieldFormulaPricing();


	});
	$("#tbldetails").on('keydown', '.lastdetailelement', function(e) {
		var keyCode = e.keyCode || e.which;
		if(keyCode == 9 || keyCode == 13) {
			addNewDetailTableRow();
		}
	});
	var nooffunction11 = $(".function11[data-isdet='0']").length;
	if(nooffunction11 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function11-Field-Formulas/" + tablename;
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				var fieldname = result['field_name'];
				var formulafields = result['formula_fields'];
				$(`.function11[data-fieldname='${fieldname}'][data-isdet='0']`).data('function11formulafields', JSON.stringify(result));
			}
		});
	}
	var nooffunction30 = $(`.function30[data-isdet='0']`).length;
	if(nooffunction30 > 0) {
		$.get("{{url('/')}}/{{$companyname}}/get-Function30-Fields-From-Table/" + tablename, function(data, status) {
			$("#hf_function30_fields").val(JSON.stringify(data));
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				var comparisons = result['comparisons'];
				for(let comparison of comparisons) {
					var forfieldstring = $(`[data-fieldname='${comparison['compareto']}'][data-isdet='0']`).data('function30forfields');
					if(forfieldstring == undefined) {
						forfields = [];
					} else {
						forfields = JSON.parse(forfieldstring);
					}
					forfields.push({
						'field_name': result['field_name'],
						'is_det': result['is_det']
					});
					$(`[data-fieldname='${comparison['compareto']}'][data-isdet='${comparison['comparetodet']}']`).data('function30forfields', JSON.stringify(forfields));
					$(`[data-fieldname='${comparison['compareto']}'][data-isdet='${comparison['comparetodet']}']`).change(function() {
						var compareforfields = JSON.parse($(this).data('function30forfields'));
						if(compareforfields.length > 0) {
							CalculateFunction30Values(compareforfields, $(this).data('row'), 'header');
						}
					});
				}
			}
		});
	}
	var function6 = $(".function6");
	function6.each(function() {
		var readonly = $(this).data('readonly');
		if(readonly == 0) {
			$(this).datetimepicker({
				format: 'd-m-Y',
				timepicker: false,
				datepicker: true,
				dayOfWeekStart: 1,
				yearStart: 2016,
			});
		}
	});
	// check in field condition if any fields present then make on change function on it
	var url = "{{url('/')}}/{{$companyname}}/get-Function4-Fieldconditions/" + tablename;
	$.get(url, function(data, status) {
		var fieldconditionfields = JSON.parse(JSON.stringify(data));
		for(let conditionfield of fieldconditionfields) {
			$(`[data-fieldname='${conditionfield}'][data-isdet='0']`).change(function() {
				var val = $(this).val();
				url = "{{url('/')}}/{{$companyname}}/get-Function4-Fieldcondition-Restricted-Field-Value";
				$.post(url, {
					'tablename': tablename,
					'fieldname': $(this).data('fieldname'),
					'val': val
				}, function(data, status) {
					var resultarray = JSON.parse(JSON.stringify(data));
					for(let result of resultarray) {
						$(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0']`).empty();
						$(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0']`).select2('destroy');
						if(result['rest_field_value'].trim() != '') {
							initSelect2WithOnlyOneOption(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0']`, '', result['rest_field_value'], result['rest_field_display']);
						} else {
							reInitializeFunction4(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0']`);
						}
					}
				});
			});
		}
	});

	var nooffunction17=$(".function17[data-isdet='0']").length; 
	if(nooffunction17 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function17-Batch-Numbers";
	 
		initSelect2Search(".function17[data-fieldname='batch_no'][data-isdet='0']", url, '', null, {
			'table_name': tablename, 
		}); 
	}

	$("#tbodysubdetails").on("click",".lnk_delete_subdetail_row",function(){

			var row=$(this).data('row');

			$("#tbodysubdetails #tr_"+row).remove();

			});


			$("[data-fieldname='gstno'][data-isdet='0']").change(function(){
				FillPanNumberFromGstNumber();
			});


	loadDetElements(1);
	initTabOnSelect2();
	loadGrandTotalCalculation();
	$("#ddnCallData_for_load_data").change(function() {
	
		var selectedtable = $(this).val();
		$("#tbodycalldata").empty();
		var keyfield = $(this).find(":selected").data('keyfld');
		if(keyfield.trim() == '') {
			return;
		}
	 
		loadCallDataTable(selectedtable,keyfield);

	});


	// check if form is in edit mode if yes then load data for it 
	if($("#data_id").length==0)
	return;


	var dataid=$("#data_id").val();
	resetDetailTable();
	$.post("{{url('/')}}/{{$companyname}}/get-transaction-table-data-by-id",{'tablename':tablename,'data_id':dataid},function(data,status){

		var result=JSON.parse(JSON.stringify(data));
		loadSelectedData(result);

		// $(".function5[data-isdet='0']").attr("disabled",'disabled');
		
		// $(".function5[data-isdet='1']").attr("disabled",'disabled');


	});

	 
 
});

function loadDetElements(rownum) {
	var nooffunction2_det = $(`.function2[data-isdet='1'][data-row='${rownum}']`).length;
	var tablename = $("#transaction_table").val();
	if(nooffunction2_det > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues-checkoptions/" + tablename + '_det';
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(`.function2[data-isdet='1'][data-row='${rownum}']`).each(function() {
				var fieldname = $(this).data('fieldname');
				var url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues";
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(`.function2[data-fieldname='${fieldname}'][data-isdet='1'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
				} else {
					initSelect2Search(`.function2[data-fieldname='${fieldname}'][data-isdet='1'][data-row='${rownum}']`, url, '', null, {
						'table_name': tablename + '_det',
						'field_name': fieldname
					});
				}
			});
		});
	}
	var nooffunction4_det = $(`.function4[data-isdet='1'][data-row='${rownum}']`).length;
	if(nooffunction4_det > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows-checkoptions/" + tablename + '_det';
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(`.function4[data-isdet='1'][data-row='${rownum}']`).each(function() {
				var fieldname = $(this).data('fieldname');
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(`.function4[data-fieldname='${fieldname}'][data-isdet='1'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
				} else {
					var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
					initSelect2Search(`.function4[data-fieldname='${fieldname}'][data-isdet='1'][data-row='${rownum}']`, url, '', null, {
						'table_name': tablename + '_det',
						'field_name': fieldname
					});


					if($("#formmode").val()=="add" &&  optiondetail[fieldname]['default_id']!==undefined){
						
							addSelect2SelectedOption(".function4[data-fieldname='" + fieldname + "'][data-isdet='1']",optiondetail[fieldname]['default_text'],optiondetail[fieldname]['default_id']);
		
							$(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']").trigger("change");
	
						}


				}
			});
		});
	}
	var nooffunction5_det = $(`.function5[data-isdet='1'][data-row='${rownum}']`).length;
	if(nooffunction5_det > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function5-codes-checkoptions/" + tablename + '_det';
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(`.function5[data-isdet='1'][data-row='${rownum}']`).each(function() {
				var fieldname = $(this).data('fieldname');
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(`.function5[data-fieldname='" + fieldname + "'][data-isdet='1'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
				} else {
					var url = "{{url('/')}}/{{$companyname}}/get-function5-codes";
					initSelect2Search(`.function5[data-fieldname='" + fieldname + "'][data-isdet='1'][data-row='${rownum}']`, url, '', null, {
						'table_name': tablename + '_det',
						'field_name': fieldname
					});
				}
			});
		});
	}
	$(`.function4[data-isdet='1'][data-row='${rownum}']`).change(function() {
		var fieldname = $(this).data('fieldname');
		var fieldval = $(this).val();
		var url = "{{url('/')}}/{{$companyname}}/get-function3-fieldvalues-checkoptions";
		$.post(url, {
			'table_name': tablename + '_det',
			'scr_field': fieldname,
			'field_val': fieldval
		}, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				if(result['noofoptions'] == 1) {
					initSelect2WithOnlyOneOptionWithAddOption(`.function3[data-fieldname='${result['field_name']}'][data-isdet='1'][data-row='${rownum}']`, '', result['field_value'], result['field_value']);
				} else {
					url = "{{url('/')}}/{{$companyname}}/get-function3-fieldvalues";
					initSelect2Search(`.function3[data-fieldname='${result['field_name']}'][data-isdet='1'][data-row='${rownum}']`, url, '', null, {
						'field_name': result['field_name'],
						'table_name': tablename + '_det'
					});
				}
				//  $(".function3[data-fieldname='"+result['field_name']+"'][data-isdet='0']").val(result['field_value']);  
			}
		});
		url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues-checkoptions";
		$.post(url, {
			'table_name': tablename + "_det",
			'scr_field': fieldname,
			'field_val': fieldval
		}, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				if(result['noofoptions'] == 1) {
					initSelect2WithOnlyOneOptionWithAddOption(`.function24[data-fieldname='"+result['field_name']+"'][data-isdet='1'][data-row='${rownum}']`, '', result['field_value'], result['field_value']);
				} else {
					url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues";
					initSelect2Search(`.function24[data-fieldname='"+result['field_name']+"'][data-isdet='1'][data-row='${rownum}']`, url, '', null, {
						'field_name': result['field_name'],
						'table_name': tablename + '_det'
					});
				}
			}
		});
	});
	var nooffunction14 = $(`.function14[data-isdet='1'][data-row='${rownum}']`).length;
	if(nooffunction14 > 0) {
		$.get("{{url('/')}}/{{$companyname}}/get-Function14-Currency", function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result['noofcurrency'] == 1) {
				$(`.function14[data-isdet='1'][data-row='${rownum}']`).each(function() {
					var fieldname = $(this).data('fieldname');
					initSelect2WithOnlyOneOption(`.function14[data-fieldname='${fieldname}'][data-row='${rownum}']`, 'Select Currency', result['single_id'], result['single_text']);
				});
			} else {
				$(`.function14[data-isdet='1'][data-row='${rownum}']`).each(function() {
					var fieldname = $(this).data('fieldname');
					var url = "{{url('/')}}/{{$companyname}}/get-Function14-All-currencies";
					initSelect2Search(`.function14[data-fieldname='${fieldname}'][data-isdet='1'][data-row='${rownum}']`, url, '');
				});
			}
		});
	}
	var nooffunction21withdet_without = $(`.function21[data-hasfieldvalue='0'][data-isdet='1'][data-row='${rownum}']`).length;
	if(nooffunction21withdet_without > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function21-Without-Fieldvalues/" + tablename + '_det';
		$.get(url, function(data, status) {
			var fields = JSON.parse(JSON.stringify(data));
			for(let field of fields) {
				$(`.function21[data-fieldname='${field['field_name']}'][data-isdet='1'][data-row='${rownum}']`).val(field['field_value']);
			}
		});
	}
	var nooffunction16 = $(`.function16[data-isdet='1'][data-row='${rownum}']`).length;
	if(nooffunction16 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function16-uoms";
		$.post(url, function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result.length == 1) {
				initSelect2WithOnlyOneOption(`.function16[data-isdet='1'][data-row='${rownum}']`, '', result[0]['id'], result[0]['text']);
			} else {
				initSelect2Search(`.function16[data-isdet='1'][data-row='${rownum}']`, url, '');
			}
		});
	}
	var noooffunction21det_withfield = $(`.function21[data-hasfieldvalue='1'][data-isdet='1'][data-row='${rownum}']`).length;
	if(noooffunction21det_withfield > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function21-With-Fieldvalues/" + tablename + '_det';
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let fieldresult of resultarray) {
				var fieldisdet = fieldresult['fieldisdet'];
				var forfield = fieldresult['forfield'];
				var fromfields = fieldresult['fromfields'];
				for(let result of fromfields) {
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`).data('function21fieldvalues', JSON.stringify(fromfields));
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`).data('function21forfield', forfield);
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`).on('change', function() {
						var function4fieldsvaluestring = $(this).data('function21fieldvalues');
						var function21forfield = $(this).data('function21forfield');
						var newfieldarray = JSON.parse(function4fieldsvaluestring);
						var allvaluesgiven = true;
						var index = 0;
						for(let fieldofarray of newfieldarray) {
							var nameoffield = fieldofarray['field_name'];
							var val = $(`.function4[data-fieldname='${fieldofarray['field_name']}'][data-isdet='${fieldofarray['is_det']}'][data-row='${rownum}']`).val();
							if(val == null || val == '') {
								alert('Please give value at Field ' + nameoffield);
								allvaluesgiven = false;
								break;
							}
							newfieldarray[index]['value'] = val;
							index++;
						}
						if(allvaluesgiven == true) {
							$.post("{{url('/')}}/{{$companyname}}/getFunction21SingleFieldValue-For-Det", {
								'tablename': tablename,
								'fieldname': function21forfield,
								'fromfields': newfieldarray
							}, function(data, status) {
								var result = JSON.parse(JSON.stringify(data));
								$(`.function21[data-fieldname='${function21forfield}'][data-isdet='1'][data-row='${rownum}']`).val(result['fieldvalue']);
							});
						}
					});
				}
			}
		});
	}
	var noooffunction45det_withfield = $(`.function45[data-hasfieldvalue='1'][data-isdet='1'][data-row='${rownum}']`).length;
	if(noooffunction45det_withfield > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function45-With-Fieldvalues/" + tablename + '_det';
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let fieldresult of resultarray) {
				var fieldisdet = fieldresult['fieldisdet'];
				var forfield = fieldresult['forfield'];
				var fromfields = fieldresult['fromfields'];
				for(let result of fromfields) {
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`).data('function45fieldvalues', JSON.stringify(fromfields));
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`).data('function45forfield', forfield);
					$(`.function4[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`).on('change', function() {
						var function4fieldsvaluestring = $(this).data('function45fieldvalues');
						var function45forfield = $(this).data('function45forfield');
						var newfieldarray = JSON.parse(function4fieldsvaluestring);
						var allvaluesgiven = true;
						var index = 0;
						for(let fieldofarray of newfieldarray) {
							var nameoffield = fieldofarray['field_name'];
							var val = $(`.function4[data-fieldname='${fieldofarray['field_name']}'][data-isdet='${fieldofarray['is_det']}'][data-row='${rownum}']`).val();
							if(val == null || val == '') {
								alert('Please give value at Field ' + nameoffield);
								allvaluesgiven = false;
								break;
							}
							newfieldarray[index]['value'] = val;
							index++;
						}
						if(allvaluesgiven == true) {
							$.post("{{url('/')}}/{{$companyname}}/getFunction45SingleFieldValue-For-Det", {
								'tablename': tablename,
								'fieldname': function45forfield,
								'fromfields': newfieldarray
							}, function(data, status) {
								var result = JSON.parse(JSON.stringify(data));
								$(`.function45[data-fieldname='${function45forfield}'][data-isdet='1'][data-row='${rownum}']`).val(result['fieldvalue']);
							});
						}
					});
				}
			}
		});
	}
	$(`.divfunction19[data-isdet='1'][data-row='${rownum}']`).each(function() {
		var thiselement = $(this);
		var fieldname = $(this).data('fieldname');
		var url = "{{url('/')}}/{{$companyname}}/get-function19-fieldvalues";
		$.post(url, {
			'table_name': tablename + '_det',
			'field_name': fieldname
		}, function(data, status) {
			var html = '';
			var result = JSON.parse(JSON.stringify(data));
			var items = result['fieldvalues'];
			var txtfieldname = result['fieldname'];
			for(let item of items) {
				html = html + `<label class="checkbox-inline"><input type="checkbox" name='data_det[${rownum}][${txtfieldname}][]' value="${item['id']}">&nbsp;${item['text']}</label>`;
			}
			thiselement.html(html);
		});
	});
	$(`.function18[data-isdet='1'][data-row='${rownum}']`).each(function() {
		var fieldname = $(this).data('fieldname');
		var url = "{{url('/')}}/{{$companyname}}/get-function18-users-checkoptions";
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			if(resultarray['noofusers'] == 1) {
				initSelect2WithOnlyOneOption(`.function18[data-fieldname='" + fieldname + "'][data-isdet='1'][data-row='${rownum}']`, '', resultarray['single_id'], resultarray['single_text']);
			} else {
				url = "{{url('/')}}/{{$companyname}}/get-function18-users";
				initSelect2Search(`.function18[data-fieldname='" + fieldname + "'][data-isdet='1'][data-row='${rownum}']`, url, '');
			}
		})
	});
	// check for function11 det dependent element if yes then then initialize and bind it 
	$.get("{{url('/')}}/{{$companyname}}/get-Function11-Det-Dependent-Formula-Fields/" + tablename, function(data, status) {
		var resultarray = JSON.parse(JSON.stringify(data));
		$("#hf_function11_pricing_fields").val(JSON.stringify(data));
		for(let result of resultarray) {
			var formulafields = result['formula_fields'];
			var fieldname = result['field_name'];
			var tabid = result['tab_id'];
			for(let formulafield of formulafields) {
				if(formulafield['is_det']==1){
					var forfields_string = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='${formulafield['is_det']}'][data-row='${rownum}']`).data('forfields');
			
				}
				else{
					var forfields_string = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='${formulafield['is_det']}']`).data('forfields');
			
				}
				var forfields;
				if(forfields_string == undefined) {
					forfields = [];
				} else {
					
					forfields = JSON.parse(forfields_string);
				}

				forfields.push({
					'forfieldname': fieldname,
					'forisdet': 0,
					'fortabid': tabid,
					'fromfieldname': formulafield['fromfield'],
					'fromfieldisdet': formulafield['is_det']
				});

				 
				if(formulafield['is_det']==1){
					
			    	var fromtarget=`[data-fieldname='${formulafield['fromfield']}'][data-isdet='${formulafield['is_det']}'][data-row='${rownum}']`;
 
				}
				else{

					var fromtarget=`[data-fieldname='${formulafield['fromfield']}'][data-isdet='${formulafield['is_det']}']`;
				
				}

					
				$(fromtarget).data('forfields', JSON.stringify(forfields));
			

				$("#tbldetails").on('keydown tap', fromtarget, function(e) {

					var keyCode = e.keyCode || e.which; 
 
					if(keyCode != 9 && keyCode != 13 ) {
						return;
					}
					calculateAllFunction11FieldFormulaPricing();
					// var getforfields=JSON.parse( $(this).data('forfields'));
					// for(let getforfield of getforfields){
					//     // if(getforfield['fortabid']=='Pricing'){
					//     //     // calculateFunction11FieldFormulaPricing(`[data-fieldname='${getforfield['forfieldname']}'][data-isdet='${getforfield['forisdet']}']`, `${getforfield['fromfieldname']}`,$(this).data('row'), getforfield['fromfieldisdet'],$(this).val(),'Pricing');
					//     // }
					// }
				});
			}
		}
	});
	// get all det related fields and give for fields formula to each
	$.get("{{url('/')}}/{{$companyname}}/get-Function11-Field-Formulas-Only-Header/" + tablename + '_det', function(data, status) {
 

		var resultarray = JSON.parse(JSON.stringify(data));
		$("#hf_function11_det_header_fields").val(JSON.stringify(data));
		for(let result of resultarray) {
			var formulafields = result['formula_fields'];
			var fieldname = result['field_name'];
			var tabid = result['tab_id'];
			$(`[data-fieldname='${fieldname}'][data-isdet='1'][data-row='${rownum}']`).data('function11formulafields', JSON.stringify(result));
			for(let formulafield of formulafields) {
				var forfields_string = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='1'][data-row='${rownum}']`).data('forfields');
				var forfields;
				if(forfields_string == undefined) {
					forfields = [];
				} else {
					forfields = JSON.parse(forfields_string);
				}
				forfields.push({
					'forfieldname': fieldname,
					'forisdet': 1,
					'fortabid': tabid,
					'fromfieldname': formulafield['fromfield'],
					'fromfieldisdet': formulafield['is_det']
				});
				$(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='1'][data-row='${rownum}']`).data('forfields', JSON.stringify(forfields));
				$("#tbldetails").on('keydown', `[data-fieldname='${formulafield['fromfield']}'][data-isdet='1'][data-row='${rownum}']`, function(e) {
					var keyCode = e.keyCode || e.which;
					if(keyCode != 9 && keyCode !=13 ) {
						return;
					}
					var getforfields = JSON.parse($(this).data('forfields'));
					for(let getforfield of getforfields) {
						if(getforfield['fortabid'] == 'Header') {
							// calculateFunction11FieldFormulaHeader(`[data-fieldname='${getforfield['forfieldname']}'][data-isdet='${getforfield['forisdet']}'][data-row='${rownum}']`, `${getforfield['fromfieldname']}`,$(this).data('row'), getforfield['fromfieldisdet'],$(this).val(),'Header');
							calculateFunction11FormulaHeaderFields($(this).data('row'), 1);
						}
					}
				});
			}
		}
	});


	// final get all header fields which do not have det table as well



	// check function4 without det tables 
	var nooffunction4_withoutdet = $(".function4[data-isdet='0']").length;
	if(nooffunction4_withoutdet > 0) {
		var function4es = $(".function4[data-isdet='0']");
		var data = [];
		function4es.each(function() {
			var val = $(this).val();
			var fieldname = $(this).data('fieldname');
			if(val !== null && val != '') {
				data.push({
					'fieldname': fieldname,
					'fieldgivenvalue': val
				});
			}
		});
		if(data.length > 0) {
			$.post("{{url('/')}}/{{$companyname}}/get-Function24-Det-Fields-to-load", {
				'data': data,
				'tablename': tablename + '_det'
			}, function(response, status) {
				var resultarray = JSON.parse(JSON.stringify(response));
				for(let result of resultarray) {
					if(result['noofoptions'] == 1) {
						initSelect2WithOnlyOneOptionWithAddOption(`.function24[data-fieldname='${result['field_name']}'][data-isdet='1'][data-row='${rownum}']`, '', result['field_value'], result['field_value']);
					} else {
						url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues";
						initSelect2Search(`.function24[data-fieldname='${result['field_name']}'][data-isdet='1'][data-row='${rownum}']`, url, '', null, {
							'field_name': result['field_name'],
							'table_name': tablename
						});
					}
				}
			});
		}
	}
	var nooffunction30 = $(`.function30[data-isdet='1'][data-row='${rownum}']`).length;
	if(nooffunction30 > 0) {
		$.get("{{url('/')}}/{{$companyname}}/get-Function30-Fields-From-Table/" + tablename + '_det', function(data, status) {
			$("#hf_function30_det_fields").val(JSON.stringify(data));
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				var comparisons = result['comparisons'];
				for(let comparison of comparisons) {
					if(comparison['comparetodet'] == 1) {
						var forfieldstring = $(`[data-fieldname='${comparison['compareto']}'][data-isdet='1'][data-row='${rownum}']`).data('function30forfields');
					} else {
						var forfieldstring = $(`[data-fieldname='${comparison['compareto']}'][data-isdet='0']`).data('function30forfields');
					}
					if(forfieldstring == undefined) {
						forfields = [];
					} else {
						forfields = JSON.parse(forfieldstring);
					}
					forfields.push({
						'field_name': result['field_name'],
						'is_det': result['is_det']
					});
					$(`[data-fieldname='${comparison['compareto']}'][data-isdet='${comparison['comparetodet']}']`).data('function30forfields', JSON.stringify(forfields));
					$(`[data-fieldname='${comparison['compareto']}'][data-isdet='${comparison['comparetodet']}']`).change(function() {
						var compareforfields = JSON.parse($(this).data('function30forfields'));
						if(compareforfields.length > 0) {
							CalculateFunction30Values(compareforfields, $(this).data('row'), 'details');
						}
					});
				}
			}
		});
	}
	// check in field condition if any fields present then make on change function on it
	var url = "{{url('/')}}/{{$companyname}}/get-Function4-Fieldconditions/" + tablename + '_det';
	$.get(url, function(data, status) {
		var fieldconditionfields = JSON.parse(JSON.stringify(data));
		for(let conditionfield of fieldconditionfields) {
			$(`[data-fieldname='${conditionfield}'][data-isdet='1'][data-row='${rownum}']`).change(function() {
				var val = $(this).val();
				url = "{{url('/')}}/{{$companyname}}/get-Function4-Fieldcondition-Restricted-Field-Value";
				$.post(url, {
					'tablename': tablename + '_det',
					'fieldname': $(this).data('fieldname'),
					'val': val
				}, function(data, status) {
					var resultarray = JSON.parse(JSON.stringify(data));
					for(let result of resultarray) {
						$(`[data-fieldname='${result['rest_field_name']}'][data-isdet='1'][data-row='${rownum}']`).empty();
						$(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0'][data-row='${rownum}']`).select2('destroy');
						if(result['rest_field_value'].trim() != '') {
							initSelect2WithOnlyOneOption(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0'][data-row='${rownum}']`, '', result['rest_field_value'], result['rest_field_display']);
						} else {
							reInitializeFunction4_det(`[data-fieldname='${result['rest_field_name']}'][data-isdet='0'][data-row='${rownum}']`);
						}
					}
				});
			});
		}
	});

	// check if table is journal if yes then check if credit amount becomes greater than zero then 

	if(tablename=="journal"){

		$(`[data-fieldname='creditamount'][data-isdet='1'][data-row='${rownum}']`).keydown(function(){

			var creditamount=$(this).val();

			if(creditamount>0){
				$(`[data-fieldname='debitamount'][data-isdet='1'][data-row='${rownum}']`).val(0)

			}
		 
		});

		$(`[data-fieldname='debitamount'][data-isdet='1'][data-row='${rownum}']`).keydown(function(){

				var debitamount=$(this).val();

				if(debitamount>0){
					$(`[data-fieldname='creditamount'][data-isdet='1'][data-row='${rownum}']`).val(0)

				}

				});
 
	}


 
	initTabOnSelect2();
}




$("#tbldetails").on('click', ".lnk_show_randp", function() {
		var clickrow=$(this);

		var rownum=$(this).data('row');
		
	      var docno=$(`[data-fieldname='docno'][data-isdet='0']`).val();

		var accountid=$(`[data-fieldname='line_acc'][data-row='${rownum}']`).val();
 
		if(accountid==null || accountid==""){
			alert("Please select Account to Open Its R & P");

			return false;
		}

		var debitamount=$(`[data-fieldname='debitamount'][data-row='${rownum}']`).val();

		if(debitamount==undefined || debitamount==""){
			debitamount=0;
		}

	 
         var creditamount=$(`[data-fieldname='creditamount'][data-row='${rownum}']`).val();

		 if(creditamount==undefined || creditamount==""){
			creditamount=0;
		 }

		 if( debitamount==0 && creditamount==0  ){
			 alert("Please enter Debit Amount or Credit Amount greater than zero");
			 return false;
		 }


		$("#showReceivableModalDetailwise").modal("show");
		$("#showReceivableModalDetailwise").data("row",rownum);
		$("#showReceivableModalDetailwise").data("accid",accountid); 

		$.get("{{url('/')}}/{{$companyname}}/get-tran-account-receivable-details/"+accountid,function(data,status){
 

			var result=JSON.parse(JSON.stringify(data));


			var accname=result['account_name'];
			
			$("#sp_accountname_detailwise").html(accname);

			var accbal=result['account_balance'];

			$("#sp_accountbalance_detailwise").html(accbal);

				var receivables=result['receivables'];

				var balances=result['balances'];

				var balancestring=JSON.stringify(balances);

				$("#tbodyreceivabledetails_detailwise").data('balances',balancestring);
				 

				$("#tbodyreceivabledetails_detailwise").empty();

				var detailindex=1;


				$("#tbodyreceivabledetails_detailwise").data("noofreceivables",receivables.length);

				for(let receivable of receivables ){
					
					var orgamount=parseFloat(receivable['orgamount']).toFixed(2);;
					var bal=parseFloat(receivable['balance']).toFixed(2);;

					if(docno!=receivable['docno']){
						$("#tbodyreceivabledetails_detailwise").append(`<tr><td>${receivable['docno']}</td><td>${receivable['docdate']}</td><td>${orgamount}</td><td>${bal}</td><td><input type='number' class='amtadjusted_detailwise receivablepayableamounts_detailwise'  data-detailindex='${detailindex}' data-docno='${receivable['docno']}' style='width:100px;' placeholder='0.00' /></td></tr>`);
						detailindex++;
					}
						
				
				}	


				setTimeout(loadEditShowRandP(rownum),1000);


		});

 




 


});




function calculateFunction11FieldFormulaPricing(element, fromfield, rownum, isdet, entervalue, tabid) {
	var jsonstring = $(element).data('function11formulafields');
	if(jsonstring == undefined) {
		return;
	}
	var isdetfunctionll = $(element).data('isdet');
	var resultarray = JSON.parse(jsonstring);
	var noofdetailfields = $("#tbodydetailfields").data('noofrows');
	var formulafields = resultarray['formula_fields'];
	var valuesarray = new Array(noofdetailfields).fill('');
	var index = 0;
	for(let formulafield of formulafields) {
		if(formulafield['fromfield'] == fromfield) {
			for(i = 0; i < noofdetailfields; i++) {
				var enteredvalue = $(`[data-fieldname='${fromfield}'][data-isdet='1'][data-row='${(i+1)}']`).val().trim();
				if(enteredvalue == '') {
					valuesarray[i] = 0;
				} else {
					valuesarray[i] = enteredvalue;
				}
			}
			resultarray['formula_fields'][index]['values'] = valuesarray;
			$(element).data('function11formulafields', JSON.stringify(resultarray));
		}
		index++;
	}
	var tablename = $("#transaction_table").val();
	if(isdetfunctionll == 1) {
		tablename = tablename + '_det';
	}
	$.post("{{url('/')}}/{{$companyname}}/calculate-Function11-Pricing-Field-Value", {
			'data': resultarray,
			'tablename': tablename
		}, function(resultdata, status) {
			var result = JSON.parse(JSON.stringify(resultdata));
			$(element).val(result['field_value']);
		})
		//    return false;
}

function calculateFunction11FieldFormulaHeader(element, fromfield, rownum, isdet, entervalue, tabid) {
	var jsonstring = $(element).data('function11formulafields');
	if(jsonstring == undefined) {
		return;
	}
	var isdetfunctionll = $(element).data('isdet');
	var resultarray = JSON.parse(jsonstring);
	var tablename = $("#transaction_table").val();
	var formulafields = resultarray['formula_fields'];
	if(isdetfunctionll == 1) {
		tablename = tablename + '_det';
	}
	var index = 0;
	for(let formulafield of formulafields) {
		var valuesarray = [];
		enteredvalue = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='1'][data-row='${rownum}']`).val().trim();
		if(enteredvalue == '') {
			valuesarray.push(0);
		} else {
			valuesarray.push(enteredvalue);
		}
		resultarray['formula_fields'][index]['values'] = valuesarray;
		$(element).data('function11formulafields', JSON.stringify(resultarray));
		index++;
	}
	$.post("{{url('/')}}/{{$companyname}}/calculate-Function11-Pricing-Field-Value", {
			'data': resultarray,
			'tablename': tablename
		}, function(resultdata, status) {
			var result = JSON.parse(JSON.stringify(resultdata));
			$(element).val(result['field_value']);
		})
		//    return false;
}

function CalculateFunction30Values(forfields, rownum = undefined, tabid) {
	if(tabid == 'details') {
		var function30fields = JSON.parse($("#hf_function30_det_fields").val());
	} else {
		var function30fields = JSON.parse($("#hf_function30_fields").val());
	}
	var isdetenvolved = false;
	for(let forfield of forfields) {
		for(let function30field of function30fields) {
			if(forfield['field_name'] == function30field['field_name'] && forfield['is_det'] == function30field['is_det']) {
				var senddata = function30field;
				var comparisons = function30field['comparisons'];
				var index = 0;
				for(let comparison of comparisons) {
					if(comparison['comparetodet'] == 1) {
						isdetenvolved = true;
						var givenvalue = $(`[data-fieldname='${comparison['compareto']}'][data-isdet='1'][data-row='${rownum}']`).val();
					} else {
						var givenvalue = $(`[data-fieldname='${comparison['compareto']}'][data-isdet='0']`).val();
					}
					if(givenvalue == '') {
						senddata['comparisons'][index]['value'] = '';
					} else {
						senddata['comparisons'][index]['value'] = givenvalue;
					}
					index++;
				}
				$.post("{{url('/')}}/{{$companyname}}/calculate-Function30-Field-Value", {
					'data': senddata
				}, function(response, status) {
					var result = JSON.parse(JSON.stringify(response));
					if(result['field_name'] != undefined) {
						if(isdetenvolved == true) {
							initSelect2WithOnlyOneOption(`.function30[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}'][data-row='${rownum}']`, '', result['field_display'], result['field_value']);
						} else {
							initSelect2WithOnlyOneOption(`function30[data-fieldname='${result['field_name']}'][data-isdet='${result['is_det']}']`, '', result['field_display'], result['field_value']);
						}
					}
				});
			}
		}
	}
}

function calculateFunction11FormulaHeaderFields(rownum, isdet) {
	var allfields = $("#hf_function11_det_header_fields").val();
 
	var tablename = $("#transaction_table").val();
	var fieldsarray = JSON.parse(allfields);
	var data_array = [];
	if(fieldsarray.length > 0) {
		for(let function11field of fieldsarray) {
			var result = function11field;
			var formulafields = function11field['formula_fields'];
			var index = 0;
			for(let formulafield of formulafields) {
				var valuesarray = [];
				enteredvalue = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='1'][data-row='${rownum}']`).val();
				if(enteredvalue == '' || enteredvalue == null) {
					valuesarray.push(0);
				} else {
					valuesarray.push(enteredvalue.trim());
				}
				result['formula_fields'][index]['values'] = valuesarray;
				index++;
			}
			data_array.push(result);
		}
		$.post("{{url('/')}}/{{$companyname}}/calculate-All-Function11-Pricing-Field-Value", {
			'data': data_array,
			'tablename': tablename + '_det'
		}, function(resultdata, status) {
			var calculated = JSON.parse(JSON.stringify(resultdata));
			for(let calc of calculated) {
				$(`[data-fieldname='${calc['field_name']}'][data-isdet='${isdet}'][data-row='${rownum}']`).val(calc['field_value']);
			}
		})
	}
}

function calculateAllFunction11FieldFormulaPricing() {
	var function11pricing = $('#hf_function11_pricing_fields').val();
	var tablename = $("#transaction_table").val();
	var pricingarray = JSON.parse(function11pricing);
	var noofdetailfields = $("#tbodydetailfields tr").length;
	var data = [];
	var outerindex = 0;
	for(let pricing of pricingarray) {
		var formulafields = pricing['formula_fields'];
		var index = 0;
		for(let formulafield of formulafields) { 

			if(formulafield['is_det']==1){
				
					var valuesarray = new Array(noofdetailfields).fill('');
					for(i = 0; i < noofdetailfields; i++) {
						var enteredvalue = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='1'][data-row='${(i+1)}']`).val();
						if(enteredvalue == '' || enteredvalue == null) {
							valuesarray[i] = 0;
						} else {
							valuesarray[i] = enteredvalue.trim();
						}
					}
					pricingarray[outerindex]['formula_fields'][index]['values'] = valuesarray;

			}
			else{
				var enteredvalue = $(`[data-fieldname='${formulafield['fromfield']}'][data-isdet='0']`).val();

				pricingarray[outerindex]['formula_fields'][index]['values'] =[enteredvalue];

			}

			index++;
		}
		outerindex++;
	}
	if(outerindex == 0) return;
	$.post("{{url('/')}}/{{$companyname}}/calculate-All-Function11-Pricing-Field-Value", {
		'data': pricingarray,
		'tablename': tablename
	}, function(resultdata, status) {
		var calculated = JSON.parse(JSON.stringify(resultdata));
		for(let calc of calculated) {
			 
			$(`[data-fieldname='${calc['field_name']}'][data-isdet='0']`).val(calc['field_value']);
		}
	});
}

function reInitializeFunction4(elementstring) {
	var element = $(elementstring);
	var fieldname = element.data('fieldname');
	var tablename = $("#transaction_table").val();
	var url = "{{url('/').'/'.$companyname.'/get-function4-tablerows-checkoptions/'}}" + tablename;
	$.get(url, function(data, status) {
		var optiondetail = JSON.parse(JSON.stringify(data));
		if(optiondetail[fieldname]['noofoptions'] == 1) {
			initSelect2WithOnlyOneOption(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']", '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text']);
		} else {
			var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
			initSelect2Search(".function4[data-fieldname='" + fieldname + "'][data-isdet='0']", url, '', null, {
				'table_name': tablename,
				'field_name': fieldname
			});
		}
	});
}

function reInitializeFunction4_det(elementstring,parent='body') {
	var element = $(elementstring);
	var fieldname = element.data('fieldname');
	var tablename = $("#transaction_table").val();
	var rownum = element.data('row');
	var url = "{{url('/').'/'.$companyname.'/get-function4-tablerows-checkoptions/'}}" + tablename + "_det";
	$.get(url, function(data, status) {
		var optiondetail = JSON.parse(JSON.stringify(data));
		if(optiondetail[fieldname]['noofoptions'] == 1) {
			initSelect2WithOnlyOneOption(`.function4[data-fieldname='" + fieldname + "'][data-isdet='0'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text'],parent);
		} else {
			var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
			initSelect2Search(`.function4[data-fieldname='" + fieldname + "'][data-isdet='0'][data-row='${rownum}']`, url, '', null, {
				'table_name': tablename + "_det",
				'field_name': fieldname
			},parent);
		}
	});
}

function ValidateForm() {
	var validation = false;
	var disabledelements = $("#transactionAddDataForm").find(':disabled');
	disabledelements.each(function() {
		$(this).removeAttr('disabled');
	});
	var datastring = $("#transactionAddDataForm").serialize();
	$.ajax({
		type: "POST",
		async: false,
		url: "{{url('/')}}/{{$companyname}}/validate-Submit-Transaction-TableData",
		data: datastring,
		dataType: "json",
		success: function(data) {
			var result = JSON.parse(JSON.stringify(data));
			if(result['status'] == "success") {
				validation = true;
			} else {
				SnackbarMsg(data);
				validation = false;
			}
		},
		error: function(xhr, status, error) {}
	});
	if(validation == false) {
		disabledelements.each(function() {
			$(this).attr('disabled', 'disabled');
		});
	}
	return validation;
}

function loadGrandTotalCalculation() {
	$("#tbodydetailfields").on("change", ".gettotal", function() {
		var fieldname = $(this).data('fieldname');
		var grandtotal = 0;
		var allfields = $(`[data-fieldname='${fieldname}'][data-isdet='1']`);
		allfields.each(function() {
			var foundvalue = $(this).val();
			if(isNaN(foundvalue) == false) {
				grandtotal = grandtotal + parseInt(foundvalue);
			}
		});
		grandtotal = parseFloat(grandtotal).toFixed(2);
		$(`.detgrandtotal[data-totalof='${fieldname}']`).html(grandtotal);
	})
}
$("#tbodycalldata").on("click", "tr", function() {

	if($(this).hasClass("selected")) {
		$(this).removeClass("selected");
	
	} else {
		$(this).addClass("selected");
	}
});

function FillSelectedCallDataToForm() {
 

	var calldataarray=[];
	var tablename = $("#transaction_table").val(); 
	var calldatarows=$("#tbodycalldata tr");
	var selectedindexes=[];

	calldatarows.each(function(index){ 
		if($(this).hasClass('selected')){ 
			calldataarray.push($(this).data('id'));
			selectedindexes.push(index);
		}

	});
 
	if(calldataarray.length==0){
		alert("Select At Least 1 record for Fill up");
		return false;
	}
 
	var i=0;
	var linkfifo=$("#tbodycalldata").data("linkfifo");
 

	if(linkfifo==1 && selectedindexes.length>1){

		while (i < (selectedindexes.length-1)) { 
			var d= selectedindexes[i + 1] - selectedindexes[i]; 

			if(Math.abs(d)!=1){

				alert("You are not selecting consecutive rows , need to follow link fifo");

				return false;
			}
			i++; 
		 }


	}
 

	resetDetailTable();
    showLoader(16);
	var calldatatableselection = $("#ddnCallData_for_load_data").val();
	var hasselecetdmultiple = false;
	$.ajax({
		type: "POST",
		async: false,
		url: "{{url('/')}}/{{$companyname}}/check-Transaction-Call-Data-For-Multiple-Main-Id",
		data: {
			'calldataarray': calldataarray
		},
		dataType: "json",
		success: function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result['status'] == "success") {
				hasselecetdmultiple = true;
			}
		}
	});
	if(hasselecetdmultiple == true) {
		var cnf = confirm("Multiple Document nos selected. Do you want to continue?");
		if(cnf == false) {
			return false;
		}
	}
	$.post("{{url('/')}}/{{$companyname}}/get-Transaction-Call-Data-For-Selected", {
		'calldataarray': calldataarray
	}, function(data, status) {


		var result = JSON.parse(JSON.stringify(data)); 
		loadSelectedData(result,true);
		
	     $(`[data-fieldname='base_doc_dt'][data-isdet='0']`).val(result['docdate']);
		
		$(`[data-fieldname='base_doc_no'][data-isdet='0']`).val(result['docno']);


	});
    // $("#ddnCallData_for_load_data").val("");
    $("#tbodycalldata").empty();
    $("#tbodycalldata").append(`<tr><td colspan='8'>No Data</td></tr>`);

	$("#callDataFromModal").modal("hide");
}

function setFormFieldValue(element, fielddisplay, fieldvalue) {
	if(fielddisplay == '') { 
		$(element).val(fieldvalue);
	} else {
       	addSelect2SelectedOption(element, fielddisplay, fieldvalue);
	  
	}
}

function addNewDetailTableRow() {
	var noofrows = $("#tbodydetailfields").data('noofrows');
	noofrows = noofrows + 1;
	var url = "{{url('/')}}/{{$companyname}}/add-new-detail-field-row";
    var tableid={{$tablefound->Id}};
	
	// showLoader(8);
	$.ajax({
		type: "POST",
		async: false,
		url: url,
		data: {
			'tranid': tableid ,
			'rownum': noofrows
		},
		dataType: "json",
		success: function(data) {
			$(".lastdetailelement").removeClass('lastdetailelement');
			$("#tbodydetailfields").append(data);
			$("#tbodydetailfields").data('noofrows', noofrows);
			addRemoveDetailIndex(noofrows,'add');
			loadDetElements(noofrows);
			initTabOnSelect2();
			$("#tbodydetailfields #tr_" + noofrows + ' .firstdetailelement').focus();
		},
		error: function(xhr, status, error) {}
	});
}


function resetDetailTable(){
	$("#tbodydetailfields").data("noofrows",0);
	$("#tbodydetailfields").empty();
}

function showCallDataModal(){
	//   resetDetailTable();

	var selectedtable = $("#ddnCallData_for_load_data").val();
	var keyfield = $("#ddnCallData_for_load_data").find(":selected").data('keyfld'); 
	var response=loadCallDataTable(selectedtable,keyfield);
	if(response==false){
		return false;
	}
    $('#callDataFromModal').modal('show');
}


function loadCallDataTable(selectedtable,keyfield){
	 
	$("#tbodycalldata").empty();
	$("#call_batch_heading").addClass("d-none");
	$("#call_lineacc_heading").addClass("d-none");
	var tablename = $("#transaction_table").val(); 
		var keyfieldval = '';
		if(keyfield == 'Party') {
			var custid = $("[data-fieldname='cust_id']").val();
			if(custid == null || custid.trim() == '') {
				alert("Please select Customer and then Call Data");
				return false;
			}
			keyfieldval = custid;
		} else if(keyfield == 'product') {
			var product = $("[data-fieldname='product']").val();
			if(product == null || product.trim() == '') {
				alert("Please select Product 1 and then Call Data");
				return false;
			}
			keyfieldval = product;
		} else if(keyfield == 'batch_no') {
			var batchno = $("[data-fieldname='batch_no']").val();
			if(batchno == null || batchno.trim() == '') {
				alert("Please select Batch No. and then Call Data");
				return false;
			}
			keyfieldval = batchno;
		}
		else if(keyfield == 'line_acc') {

		var lineacc=$("[data-fieldname='line_acc']").val();

		if(lineacc==null || lineacc.trim()==''){
			alert("Please select Line Acc Field");
			return false;
		}

		keyfieldval =lineacc;

	}
	else if(keyfield == 'location') {

		var location=$("[data-fieldname='location']").val();

		if(location==null  || location.trim()==''){
			alert("Please select Location Field");
			return false;
		}

		keyfieldval =location;
	}
 
	var noofcolumns=8;

	if(keyfield == 'line_acc'){
		noofcolumns=noofcolumns+1;

		$("#call_lineacc_heading").removeClass("d-none");
	}
	else{
		$("#call_lineacc_heading").addClass("d-none");
	}

	if(keyfield == 'batch_no'){

		$("#call_batch_heading").removeClass("d-none");
	}
	else{
		
		$("#call_batch_heading").addClass("d-none");

	}

		showLoader(2);

		$.post("{{url('/')}}/{{$companyname}}/get-AddEditTransaction-Get-Call-Data", {
			'link_txn': tablename,
			'keyfield': keyfield,
			'keyfieldval': keyfieldval,
			'txn_id': selectedtable
		}, function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			var calldatatabledata= result['data']; 
			var linksetup=result['linksetup'];

			if(linksetup['linkfifo'].trim()=='True'){

				$("#tbodycalldata").data("linkfifo",1);
			}
			else{

				$("#tbodycalldata").data("linkfifo",0);
			}

 
			if(calldatatabledata.length == 0) {
				$("#tbodycalldata").append(`<tr><td colspan='${noofcolumns}'>No Data Found</td></tr>`);
			} else {
				for(let calldata of calldatatabledata) {

					var showqty=calldata['qty']-calldata['used_qty'];
					var newrow=`<tr data-id='${calldata['Id']}'><td>${calldata['format_doc_date']}</td><td>${calldata['doc_no']}</td><td>${calldata['location_name']}</td><td>${calldata['party_name']}</td>`;

					if(keyfield=='line_acc'){
						newrow=newrow+`<td>${calldata['line_account_name']}</td>`;
					}
					else if(keyfield=='batch_no'){
						newrow=newrow+`<td>${calldata['batch_number']}</td>`;
					}

					newrow=newrow+`<td>${calldata['product_name']}</td><td>${showqty}</td><td>${calldata['rate']}</td><td>${calldata['amount']}</td></tr>`;

  
					$("#tbodycalldata").append(newrow);
				}
			}
		});
}


function loadSelectedData(result,ispull=false){

	var data = result['data'];
		var detdata = result['datadet'];
	  var detdata_refs=result['datadet_refs'];  
	 
		var docdate=result['docdate'];
		var docno=result['docno'];
		var subdetdata= result['datasubdet'];
		var detailwise_receivables=result['detailwise_receivables'];

 
		if(Object.keys(subdetdata).length>0){ 
			$("#hf_subdetail_rows_data").val(JSON.stringify(result['datasubdet']));
		}

		if(Object.keys(detailwise_receivables).length>0){

			$("#receivablepayable_amountadjustments_detailwise").val(JSON.stringify(detailwise_receivables))

		}
		  
		if(ispull==false){
			
			setFormFieldValue(`[data-fieldname='docno'][data-isdet='0']`, docno,docno);
		
		}
		 
		for(let dt of data) {
			if(dt['field_function'] == 8){
				var fieldvaluestring = dt['field_value'];
				$(`.function8[data-fieldname='${dt['field_name']}'][data-isdet='0']`).css('display','block');
				$(`.function8[data-fieldname='${dt['field_name']}'][data-isdet='0']`).attr('href',fieldvaluestring);
			}
			else if(dt['field_function'] == 19) {
				var fieldvaluestring = dt['field_value'];
				var fieldvaluearray = fieldvaluestring.split(",");
				for(let fieldvalue of fieldvaluearray) {
					$(`[data-fieldname='${dt['field_name']}'][value='${fieldvalue}']`).prop('checked', true);
				}
			} else if(dt['field_value']!=null  && dt['field_value'].trim()!=''   ){ 

				if(ispull==true && (dt['field_name']!='docno' && dt['field_name']!='docdate' ) ){
					 
					setFormFieldValue(`[data-fieldname='${dt['field_name']}'][data-isdet='0']`, dt['field_display'], dt['field_value']);
		
				}
				else if(ispull==false){

					setFormFieldValue(`[data-fieldname='${dt['field_name']}'][data-isdet='0']`, dt['field_display'], dt['field_value']);
		 
				}
			}
		}
		var dtindex = 0;
		var rownum = $("#tbodydetailfields").data('noofrows');;
		rownum = rownum + 1;
		var nooofdetails = detdata.length;
		for(let j = 0; j < nooofdetails; j++) {
			addNewDetailTableRow();
		}
 
		for(let dt_det of detdata) {  
			
			setTimeout(function() {
				var nooffields = dt_det.length;
				for(let i = 0; i < nooffields; i++) {
			
					var foundfieldvalue=dt_det[i]['field_value']; 
 
					if(dt_det[i]['field_function'] == 19) {
						var fieldvaluestring =  foundfieldvalue;
						var fieldvaluearray = fieldvaluestring.split(",");
						for(let fieldvalue of fieldvaluearray) {
							$(`[data-fieldname='${dt_det[i]['field_name']}'][data-row='${rownum}'][value='${fieldvalue}'][data-isdet='1']`).prop('checked', true);
						}
					} else if(dt_det[i]['field_value']!=null   ) {
						// && dt_det[i]['field_value'].trim()!=''

						setFormFieldValue(`[data-fieldname='${dt_det[i]['field_name']}'][data-row='${rownum}'][data-isdet='1']`, dt_det[i]['field_display'], dt_det[i]['field_value']);
					
 
							// if(dt_det[i]['field_name']=='line_acc'){

							// 	var lineaccurl="{{url('/')}}/{{$companyname}}/search-sub-accounts";
 
							// 	initSelect2SearchTriggerChange(`[data-fieldname='product'][data-row='${rownum}'][data-isdet='1']`,lineaccurl,'Select Line Acc',null);
							//     setFormFieldValue(`[data-fieldname='product'][data-row='${rownum}'][data-isdet='1']`, dt_det[i]['field_display'], dt_det[i]['field_value']);
					
							// }
							// else{
  
							
							// }

				}
				}


				if( detdata_refs[dtindex]!==undefined){
				  $(`[data-fieldname='refdetailid'][data-isdet='1'][data-row='${rownum}']`).val(detdata_refs[dtindex]);
				}
		 
				dtindex++;
				rownum++;

			}, 5000); 
		}


}


$(".prevnextrecord").click(function(){
		var action=$(this).val();

		var trantable=$("#transaction_table").val();
		var dataid=$("#data_id").val();
		var trantableid=$("#transaction_table_id").val();

		if(dataid==undefined){
			dataid='';
		}
 
		$.post("{{url('/')}}/{{$companyname}}/get-prev-next-transaction-table-record",{'tran_table':trantable,'action':action,'currentid':dataid},
		function(data,status){
		 

			var result=JSON.parse(JSON.stringify(data));

			if(result['status']=='success'){ 
				var url="{{url('/')}}/{{$companyname}}/edit-transaction-table-single-data/"+trantable+'/'+trantableid+'/'+result['prevnextid'];

				window.location.href=url;

			}
			else{
				SnackbarMsg(result);
			}
		}
		);
 
	});


	function showTranDataHistory(){
		// tbodytrandatamodalhistory
		var docno=$("[data-fieldname='docno'][data-isdet='0']").val();

		$("#tbodytrandatamodalhistory").empty();

		$.get("{{url('/')}}/{{$companyname}}/get-edit-tran-data-history/"+docno,function(data,status){

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

	$("#tbodydetailfields").on("click",".addeditsubdetaillink",function(){

		var row=$(this).data('row');
 
		var qtyentry=$(`.qtyentry[data-row='${row}']`).val();
		 
		if(isNaN(qtyentry)){
			qtyentry=1;
		}


		if(qtyentry==0){
			alert("Please enter at least 1 quantity");
			return false;
		}
 
		$("#hf_subdetail_row_no").val(row);

		$("#tbodysubdetails").data("noofrows",qtyentry); 
		
		var trantable=$("#transaction_table").val();
		trantable=trantable+'_det';

		var data_id='';

		if($("#data_id").val()!=undefined){
			data_id=$("#data_id").val();
		}
 
		showLoader(5);

		$.ajax({
		type: "POST",
		async: false,
		url: "{{url('/')}}/{{$companyname}}/get-transaction-sub-detail-rows",
		data: {'table_name':trantable,'no_of_rows':qtyentry,'data_id':data_id},
		dataType: "json",
		success: function(data) { 

			var result=JSON.parse(JSON.stringify(data));

			var fieldlabels=result['fieldlabels'];
			var fieldnames=result['fieldnames'];
			var subdetailtablename=result['subdetailtablename'];

			$("#theadsubdetails").data('subdetailtablename',subdetailtablename);

			$("#theadsubdetails").html("<th>Sno.</th>");

			for(let fieldlabel of fieldlabels){
				$("#theadsubdetails").append(`<th>${fieldlabel}</th>`);

			}

			var fieldnamestring=JSON.stringify(fieldnames);

			$("#theadsubdetails").data("headcolumns",fieldnamestring);
 
			var result=JSON.parse(JSON.stringify(data));
			$("#tbodysubdetails").html(result['html']);  
			$('#subdetailEnterModal').modal('show');
		

			for(let i=1;i<=qtyentry;i++){
				loadSubDetElements(i,row);
			}
			showLoader(5);
			setTimeout(function(){
				loadEditSubDetails(row);
			},5000);
		}

	});
 
	});


 
function loadSubDetElements(rownum,parentnum) {
	var nooffunction2_det = $(`.function2[data-issubdet='1'][data-row='${rownum}']`).length;
	var tablename = $("#theadsubdetails").data('subdetailtablename');
	if(nooffunction2_det > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues-checkoptions/" + tablename ;
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(`.function2[data-issubdet='1'][data-row='${rownum}']`).each(function() {
				var fieldname = $(this).data('subfieldname');
				var url = "{{url('/')}}/{{$companyname}}/get-function2-fieldvalues";
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(`.function2[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text'],'#subdetailEnterModal');
				} else {
					initSelect2Search(`.function2[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${rownum}']`, url, '', null, {
						'table_name': tablename  ,
						'field_name': fieldname
					},'#subdetailEnterModal');
				}
			});
		});
	}
	var nooffunction4_det = $(`.function4[data-issubdet='1'][data-row='${rownum}']`).length;
	if(nooffunction4_det > 0) {
		// + tablename + '_det'
		var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows-checkoptions/"+tablename ;
		$.get(url, function(data, status) {  
			
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(`.function4[data-issubdet='1'][data-row='${rownum}']`).each(function() {
				var fieldname = $(this).data('subfieldname');
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(`.function4[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text'],'#subdetailEnterModal');
				} else {
					var url = "{{url('/')}}/{{$companyname}}/get-function4-tablerows";
					initSelect2Search(`.function4[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${rownum}']`, url, '', null, {
						'table_name': tablename,
						'field_name': fieldname
					},'#subdetailEnterModal');


					if($("#formmode").val()=="add" &&  optiondetail[fieldname]['default_id']!==undefined){
						
							addSelect2SelectedOption(".function4[data-subfieldname='" + fieldname + "'][data-issubdet='1']",optiondetail[fieldname]['default_text'],optiondetail[fieldname]['default_id']);
		
							$(".function4[data-subfieldname='" + fieldname + "'][data-issubdet='0']").trigger("change");
	
						}


				}
			});
		});
	}
	var nooffunction5_det = $(`.function5[data-issubdet='1'][data-row='${rownum}']`).length;
	if(nooffunction5_det > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function5-codes-checkoptions/" + tablename  ;
		$.get(url, function(data, status) {
			var optiondetail = JSON.parse(JSON.stringify(data));
			$(`.function5[data-issubdet='1'][data-row='${rownum}']`).each(function() {
				var fieldname = $(this).data('subfieldname');
				if(optiondetail[fieldname]['noofoptions'] == 1) {
					initSelect2WithOnlyOneOption(`.function5[data-subfieldname='" + fieldname + "'][data-issubdet='1'][data-row='${rownum}']`, '', optiondetail[fieldname]['single_id'], optiondetail[fieldname]['single_text'],'#subdetailEnterModal');
				} else {
					var url = "{{url('/')}}/{{$companyname}}/get-function5-codes";
					initSelect2Search(`.function5[data-subfieldname='" + fieldname + "'][data-issubdet='1'][data-row='${rownum}']`, url, '', null, {
						'table_name':  tablename,
						'field_name': fieldname
					},'#subdetailEnterModal');
				}
			});
		});
	}
	$(`.function4[data-issubdet='1'][data-row='${rownum}']`).change(function() {
		var fieldname = $(this).data('subfieldname');
		var fieldval = $(this).val();

	
		var url = "{{url('/')}}/{{$companyname}}/get-function3-fieldvalues-checkoptions";
		$.post(url, {
			'table_name': tablename  ,
			'scr_field': fieldname,
			'field_val': fieldval
		}, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				if(result['noofoptions'] == 1) {
					initSelect2WithOnlyOneOptionWithAddOption(`.function3[data-subfieldname='${result['field_name']}'][data-issubdet='1'][data-row='${rownum}']`, '', result['field_value'], result['field_value'],'#subdetailEnterModal');
				} else {
					url = "{{url('/')}}/{{$companyname}}/get-function3-fieldvalues";
					initSelect2Search(`.function3[data-subfieldname='${result['field_name']}'][data-issubdet='1'][data-row='${rownum}']`, url, '', null, {
						'field_name': result['field_name'],
						'table_name':tablename
					},'#subdetailEnterModal');
				}
				//  $(".function3[data-subfieldname='"+result['field_name']+"'][data-issubdet='0']").val(result['field_value']);  
			}
		});
		url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues-checkoptions";
		$.post(url, {
			'table_name': tablename  ,
			'scr_field': fieldname,
			'field_val': fieldval
		}, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				if(result['noofoptions'] == 1) {
					initSelect2WithOnlyOneOptionWithAddOption(`.function24[data-subfieldname='${result['field_name']}'][data-issubdet='1'][data-row='${rownum}']`, '', result['field_value'], result['field_value'],'#subdetailEnterModal');
				} else {
					url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues";
					initSelect2Search(`.function24[data-subfieldname='${result['field_name']}'][data-issubdet='1'][data-row='${rownum}']`, url, '', null, {
						'field_name': result['field_name'],
						'table_name':tablename 
					},'#subdetailEnterModal');
				}
			}
		});
	});
	var nooffunction14 = $(`.function14[data-issubdet='1'][data-row='${rownum}']`).length;
	if(nooffunction14 > 0) {
		$.get("{{url('/')}}/{{$companyname}}/get-Function14-Currency", function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result['noofcurrency'] == 1) {
				$(`.function14[data-issubdet='1'][data-row='${rownum}']`).each(function() {
					var fieldname = $(this).data('subfieldname');
					initSelect2WithOnlyOneOption(`.function14[data-subfieldname='${fieldname}'][data-row='${rownum}']`, 'Select Currency', result['single_id'], result['single_text'],'#subdetailEnterModal');
				});
			} else {
				$(`.function14[data-issubdet='1'][data-row='${rownum}']`).each(function() {
					var fieldname = $(this).data('subfieldname');
					var url = "{{url('/')}}/{{$companyname}}/get-Function14-All-currencies";
					initSelect2Search(`.function14[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${rownum}']`, url, '','#subdetailEnterModal');
				});
			}
		});
	}
	var nooffunction21withdet_without = $(`.function21[data-hasfieldvalue='0'][data-issubdet='1'][data-row='${rownum}']`).length;
	if(nooffunction21withdet_without > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function21-Without-Fieldvalues/" + tablename  ;
		$.get(url, function(data, status) {
			var fields = JSON.parse(JSON.stringify(data));
			for(let field of fields) {
				$(`.function21[data-subfieldname='${field['field_name']}'][data-issubdet='1'][data-row='${rownum}']`).val(field['field_value']);
			}
		});
	}
	var nooffunction16 = $(`.function16[data-issubdet='1'][data-row='${rownum}']`).length;
	if(nooffunction16 > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-function16-uoms";
		$.post(url, function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			if(result.length == 1) {
				initSelect2WithOnlyOneOption(`.function16[data-issubdet='1'][data-row='${rownum}']`, '', result[0]['id'], result[0]['text'],'#subdetailEnterModal');
			} else {
				initSelect2Search(`.function16[data-issubdet='1'][data-row='${rownum}']`, url, '','#subdetailEnterModal');
			}
		});
	}
	var noooffunction21det_withfield = $(`.function21[data-hasfieldvalue='1'][data-issubdet='1'][data-row='${rownum}']`).length;
	if(noooffunction21det_withfield > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function21-With-Fieldvalues/" + tablename ;
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let fieldresult of resultarray) {
				var fieldissubdet = fieldresult['fieldissubdet'];
				var forfield = fieldresult['forfield'];
				var fromfields = fieldresult['fromfields'];
				for(let result of fromfields) {
					$(`.function4[data-subfieldname='${result['field_name']}'][data-issubdet='${result['is_det']}'][data-row='${rownum}']`).data('function21fieldvalues', JSON.stringify(fromfields));
					$(`.function4[data-subfieldname='${result['field_name']}'][data-issubdet='${result['is_det']}'][data-row='${rownum}']`).data('function21forfield', forfield);
					$(`.function4[data-subfieldname='${result['field_name']}'][data-issubdet='${result['is_det']}'][data-row='${rownum}']`).on('change', function() {
						var function4fieldsvaluestring = $(this).data('function21fieldvalues');
						var function21forfield = $(this).data('function21forfield');
						var newfieldarray = JSON.parse(function4fieldsvaluestring);
						var allvaluesgiven = true;
						var index = 0;
						for(let fieldofarray of newfieldarray) {
							var nameoffield = fieldofarray['field_name'];
							var val = $(`.function4[data-subfieldname='${fieldofarray['field_name']}'][data-issubdet='${fieldofarray['is_det']}'][data-row='${rownum}']`).val();
							if(val == null || val == '') {
								alert('Please give value at Field ' + nameoffield);
								allvaluesgiven = false;
								break;
							}
							newfieldarray[index]['value'] = val;
							index++;
						}
						if(allvaluesgiven == true) {
							$.post("{{url('/')}}/{{$companyname}}/getFunction21SingleFieldValue-For-Det", {
								'tablename': tablename,
								'fieldname': function21forfield,
								'fromfields': newfieldarray
							}, function(data, status) {
								var result = JSON.parse(JSON.stringify(data));
								$(`.function21[data-subfieldname='${function21forfield}'][data-issubdet='1'][data-row='${rownum}']`).val(result['fieldvalue']);
							});
						}
					});
				}
			}
		});
	}
	var noooffunction45det_withfield = $(`.function45[data-hasfieldvalue='1'][data-issubdet='1'][data-row='${rownum}']`).length;
	if(noooffunction45det_withfield > 0) {
		var url = "{{url('/')}}/{{$companyname}}/get-Function45-With-Fieldvalues/" + tablename  ;
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let fieldresult of resultarray) {
				var fieldissubdet = fieldresult['fieldissubdet'];
				var forfield = fieldresult['forfield'];
				var fromfields = fieldresult['fromfields'];
				for(let result of fromfields) {
					$(`.function4[data-subfieldname='${result['field_name']}'][data-issubdet='${result['is_det']}'][data-row='${rownum}']`).data('function45fieldvalues', JSON.stringify(fromfields));
					$(`.function4[data-subfieldname='${result['field_name']}'][data-issubdet='${result['is_det']}'][data-row='${rownum}']`).data('function45forfield', forfield);
					$(`.function4[data-subfieldname='${result['field_name']}'][data-issubdet='${result['is_det']}'][data-row='${rownum}']`).on('change', function() {
						var function4fieldsvaluestring = $(this).data('function45fieldvalues');
						var function45forfield = $(this).data('function45forfield');
						var newfieldarray = JSON.parse(function4fieldsvaluestring);
						var allvaluesgiven = true;
						var index = 0;
						for(let fieldofarray of newfieldarray) {
							var nameoffield = fieldofarray['field_name'];
							var val = $(`.function4[data-subfieldname='${fieldofarray['field_name']}'][data-issubdet='${fieldofarray['is_det']}'][data-row='${rownum}']`).val();
							if(val == null || val == '') {
								alert('Please give value at Field ' + nameoffield);
								allvaluesgiven = false;
								break;
							}
							newfieldarray[index]['value'] = val;
							index++;
						}
						if(allvaluesgiven == true) {
							var trantable=$("#transaction_table").val();

							$.post("{{url('/')}}/{{$companyname}}/getFunction45SingleFieldValue-For-Det", {
								'tablename':trantable ,
								'fieldname': function45forfield,
								'fromfields': newfieldarray
							}, function(data, status) {
								var result = JSON.parse(JSON.stringify(data));
								$(`.function45[data-subfieldname='${function45forfield}'][data-issubdet='1'][data-row='${rownum}']`).val(result['fieldvalue']);
							});
						}
					});
				}
			}
		});
	}
	$(`.divfunction19[data-issubdet='1'][data-row='${rownum}']`).each(function() {
		var thiselement = $(this);
		var fieldname = $(this).data('subfieldname');
		var url = "{{url('/')}}/{{$companyname}}/get-function19-fieldvalues";
		$.post(url, {
			'table_name': tablename + '_det',
			'field_name': fieldname
		}, function(data, status) {
			var html = '';
			var result = JSON.parse(JSON.stringify(data));
			var items = result['fieldvalues'];
			var txtfieldname = result['fieldname'];
			for(let item of items) {
				html = html + `<label class="checkbox-inline"><input type="checkbox" name='data_sub_det[${rownum}][${txtfieldname}][]' value="${item['id']}">&nbsp;${item['text']}</label>`;
			}
			thiselement.html(html);
		});
	});
	$(`.function18[data-issubdet='1'][data-row='${rownum}']`).each(function() {
		var fieldname = $(this).data('subfieldname');
		var url = "{{url('/')}}/{{$companyname}}/get-function18-users-checkoptions";
		$.get(url, function(data, status) {
			var resultarray = JSON.parse(JSON.stringify(data));
			if(resultarray['noofusers'] == 1) {
				initSelect2WithOnlyOneOption(`.function18[data-subfieldname='" + fieldname + "'][data-issubdet='1'][data-row='${rownum}']`, '', resultarray['single_id'], resultarray['single_text'],'#subdetailEnterModal');
			} else {
				url = "{{url('/')}}/{{$companyname}}/get-function18-users";
				initSelect2Search(`.function18[data-subfieldname='" + fieldname + "'][data-issubdet='1'][data-row='${rownum}']`, url, '','#subdetailEnterModal');
			}
		})
	});
	// check for function11 det dependent element if yes then then initialize and bind it 
	// $.get("{{url('/')}}/{{$companyname}}/get-Function11-Det-Dependent-Formula-Fields/" + tablename, function(data, status) {
	// 	var resultarray = JSON.parse(JSON.stringify(data));
	// 	$("#hf_function11_pricing_fields").val(JSON.stringify(data));
	// 	for(let result of resultarray) {
	// 		var formulafields = result['formula_fields'];
	// 		var fieldname = result['field_name'];
	// 		var tabid = result['tab_id'];
	// 		for(let formulafield of formulafields) {
	// 			var forfields_string = $(`[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`).data('forfields');
	// 			var forfields;
	// 			if(forfields_string == undefined) {
	// 				forfields = [];
	// 			} else {
	// 				continue;
	// 				forfields = JSON.parse(forfields_string);
	// 			}
	// 			forfields.push({
	// 				'forfieldname': fieldname,
	// 				'forissubdet': 0,
	// 				'fortabid': tabid,
	// 				'fromfieldname': formulafield['fromfield'],
	// 				'fromfieldissubdet': formulafield['is_det']
	// 			});
	// 			$(`[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`).data('forfields', JSON.stringify(forfields));
	// 			$("#tbldetails").on('keydown', `[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`, function(e) {
	// 				var keyCode = e.keyCode || e.which;
	// 				if(keyCode != 9) {
	// 					return;
	// 				}
	// 				// calculateAllFunction11FieldFormulaPricing();
	// 				// var getforfields=JSON.parse( $(this).data('forfields'));
	// 				// for(let getforfield of getforfields){
	// 				//     // if(getforfield['fortabid']=='Pricing'){
	// 				//     //     // calculateFunction11FieldFormulaPricing(`[data-subfieldname='${getforfield['forfieldname']}'][data-issubdet='${getforfield['forissubdet']}']`, `${getforfield['fromfieldname']}`,$(this).data('row'), getforfield['fromfieldissubdet'],$(this).val(),'Pricing');
	// 				//     // }
	// 				// }
	// 			});
	// 		}
	// 	}
	// });
	// get all det related fields and give for fields formula to each
	$.get("{{url('/')}}/{{$companyname}}/get-Function11-Field-Formulas-Only-Header/" + tablename + '_det', function(data, status) {
		var resultarray = JSON.parse(JSON.stringify(data));
		$("#hf_function11_sub_det_header_fields").val(JSON.stringify(data));
		for(let result of resultarray) {
			var formulafields = result['formula_fields'];
			var fieldname = result['field_name'];
			var tabid = result['tab_id'];
			$(`[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${rownum}']`).data('function11formulafields', JSON.stringify(result));
			for(let formulafield of formulafields) {
				var forfields_string = $(`[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`).data('forfields');
				var forfields;
				if(forfields_string == undefined) {
					forfields = [];
				} else {
					forfields = JSON.parse(forfields_string);
				}
				forfields.push({
					 'forfieldname': fieldname,
					'forisdet': 1,
					'fortabid': tabid,
					'fromfieldname': formulafield['fromfield'],
					'fromfieldisdet': formulafield['is_det']
				});

 
				$(`[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`).data('forfields', JSON.stringify(forfields));
				$("#tbodysubdetails").on('keydown', `[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`, function(e) {
					var keyCode = e.keyCode || e.which;
					if(keyCode != 9 && keyCode != 13) {
						return;
					}
					var getforfields = JSON.parse($(this).data('forfields'));
					for(let getforfield of getforfields) {
						if(getforfield['fortabid'] == 'Header') { 
						   calculateFunction11SubFormulaHeaderFields($(this).data('row'), 1);
						}
					}
				});
			}
		}
	});
	// check function4 without det tables 
	var nooffunction4_withoutdet = $(".function4[data-isdet='1'][data-row="+parentnum+"]").length;
	if(nooffunction4_withoutdet > 0) {
		var function4es = $(".function4[data-isdet='1'][data-row="+parentnum+"]");
		var data = [];
		function4es.each(function() {
			var val = $(this).val();
			var fieldname = $(this).data('fieldname');
			if(val !== null && val != '') {
 
				data.push({
					'fieldname': fieldname,
					'fieldgivenvalue': val
				});
			}
		});
		if(data.length > 0) {
 
			var subdettablename=$("#theadsubdetails").data('subdetailtablename');

			$.post("{{url('/')}}/{{$companyname}}/get-Function24-Det-Fields-to-load", {
				'data': data,
				'tablename': subdettablename
			}, function(response, status) {
				var resultarray = JSON.parse(JSON.stringify(response));
				for(let result of resultarray) { 
					if(result['noofoptions'] == 1) {
						initSelect2WithOnlyOneOptionWithAddOption(`.function24[data-subfieldname='${result['field_name']}'][data-issubdet='1'][data-row='${rownum}']`, '', result['field_value'], result['field_value'],'#subdetailEnterModal');
					} else {
						url = "{{url('/')}}/{{$companyname}}/get-function24-fieldvalues";
						initSelect2Search(`.function24[data-subfieldname='${result['field_name']}'][data-issubdet='1'][data-row='${rownum}']`, url, '', null, {
							'field_name': result['field_name'],
							'table_name': subdettablename
						},'#subdetailEnterModal');
					}
				}
			});
		}
	}
	var nooffunction30 = $(`.function30[data-issubdet='1'][data-row='${rownum}']`).length;
	if(nooffunction30 > 0) {
		$.get("{{url('/')}}/{{$companyname}}/get-Function30-Fields-From-Table/" + tablename + '_det', function(data, status) {
			$("#hf_function30_det_fields").val(JSON.stringify(data));
			var resultarray = JSON.parse(JSON.stringify(data));
			for(let result of resultarray) {
				var comparisons = result['comparisons'];
				for(let comparison of comparisons) {
					if(comparison['comparetodet'] == 1) {
						var forfieldstring = $(`[data-subfieldname='${comparison['compareto']}'][data-issubdet='1'][data-row='${rownum}']`).data('function30forfields');
					} else {
						var forfieldstring = $(`[data-subfieldname='${comparison['compareto']}'][data-issubdet='0']`).data('function30forfields');
					}
					if(forfieldstring == undefined) {
						forfields = [];
					} else {
						forfields = JSON.parse(forfieldstring);
					}
					forfields.push({
						'field_name': result['field_name'],
						'is_det': result['is_det']
					});
					$(`[data-subfieldname='${comparison['compareto']}'][data-issubdet='${comparison['comparetodet']}']`).data('function30forfields', JSON.stringify(forfields));
					$(`[data-subfieldname='${comparison['compareto']}'][data-issubdet='${comparison['comparetodet']}']`).change(function() {
						var compareforfields = JSON.parse($(this).data('function30forfields'));
						if(compareforfields.length > 0) {
							CalculateFunction30Values(compareforfields, $(this).data('row'), 'details');
						}
					});
				}
			}
		});
	}
	// check in field condition if any fields present then make on change function on it
	var url = "{{url('/')}}/{{$companyname}}/get-Function4-Fieldconditions/" + tablename + '_det';
	$.get(url, function(data, status) {
		var fieldconditionfields = JSON.parse(JSON.stringify(data));
		for(let conditionfield of fieldconditionfields) {
			$(`[data-subfieldname='${conditionfield}'][data-issubdet='1'][data-row='${rownum}']`).change(function() {
				var val = $(this).val();
				url = "{{url('/')}}/{{$companyname}}/get-Function4-Fieldcondition-Restricted-Field-Value";
				$.post(url, {
					'tablename': tablename + '_det',
					'fieldname': $(this).data('subfieldname'),
					'val': val
				}, function(data, status) {
					var resultarray = JSON.parse(JSON.stringify(data));
					for(let result of resultarray) {
						$(`[data-subfieldname='${result['rest_field_name']}'][data-issubdet='1'][data-row='${rownum}']`).empty();
						$(`[data-subfieldname='${result['rest_field_name']}'][data-issubdet='0'][data-row='${rownum}']`).select2('destroy');
						if(result['rest_field_value'].trim() != '') {
							initSelect2WithOnlyOneOption(`[data-subfieldname='${result['rest_field_name']}'][data-issubdet='0'][data-row='${rownum}']`, '', result['rest_field_value'], result['rest_field_display'],'#subdetailEnterModal');
						} else {
							reInitializeFunction4_det(`[data-subfieldname='${result['rest_field_name']}'][data-issubdet='0'][data-row='${rownum}']`,'#subdetailEnterModal');
						}
					}
				});
			});
		}
	});
 

	initTabOnSelect2();
}
 



function calculateFunction11SubFormulaHeaderFields(rownum, isdet) {
	var allfields = $("#hf_function11_sub_det_header_fields").val();
 
	var tablename = $("#transaction_table").val();
	var fieldsarray = JSON.parse(allfields);
	var data_array = [];
	if(fieldsarray.length > 0) {
		for(let function11field of fieldsarray) {
			var result = function11field;
			var formulafields = function11field['formula_fields'];
			var index = 0;
			for(let formulafield of formulafields) {
				var valuesarray = [];
 

				enteredvalue = $(`[data-subfieldname='${formulafield['fromfield']}'][data-issubdet='1'][data-row='${rownum}']`).val();
				if(enteredvalue == '' || enteredvalue == null) {
					valuesarray.push(0);
				} else {
					valuesarray.push(enteredvalue.trim());
				}
				result['formula_fields'][index]['values'] = valuesarray;
				index++;
			}
			data_array.push(result);
		}
 
		$.post("{{url('/')}}/{{$companyname}}/calculate-All-Function11-Pricing-Field-Value", {
			'data': data_array,
			'tablename': tablename + '_det'
		}, function(resultdata, status) {
			var calculated = JSON.parse(JSON.stringify(resultdata));
			for(let calc of calculated) {
				$(`[data-subfieldname='${calc['field_name']}'][data-issubdet='${isdet}'][data-row='${rownum}']`).val(calc['field_value']);
			}
		})
	}
}

function saveDetailSubDetails(){

	var row=$("#hf_subdetail_row_no").val();

	var subdetailrows=$("#hf_subdetail_rows_data").val();
 
	var noofrows=$("#tbodysubdetails").data('noofrows');

	if(subdetailrows==''){

		var subdetaildata={};
	}
	else{
		
		var subdetaildata=JSON.parse(subdetailrows);

	}
 
	var fieldnamestring=$("#theadsubdetails").data('headcolumns');
 
	var fieldnames=JSON.parse(fieldnamestring);
 
	var datafoundarray=[];

	for(i=1;i<=noofrows;i++){

		var singledata={};

		if($(`[data-issubdet='1'][data-row='${i}']`).length==0)
		  continue;

		for(let fieldname of fieldnames){
 
           var foundvalue=$(`[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${i}']`).val();
		   
		    var isselect2= $(`[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${i}']`).data('select2');
         
			var displayvalue='';

			if(isselect2!=undefined){
				displayvalue=$(`[data-subfieldname='${fieldname}'][data-issubdet='1'][data-row='${i}']  :selected`).text();
			}

			singledata[fieldname]={'fielddisplay':displayvalue,'fieldvalue':foundvalue};

		 }
 
		 datafoundarray.push(singledata);
 
	}
 
	  subdetaildata['subdetailrow_'+row]=datafoundarray;  
 
	  $(`#hf_subdetail_rows_data`).val(JSON.stringify(subdetaildata));
	  alert("Sub Detail Data saved temporarily");
	  $('#subdetailEnterModal').modal('hide');
 
}


function loadEditSubDetails(row){
 
 
	var subdetailrowdata=  $(`#hf_subdetail_rows_data`).val();
 
	if(subdetailrowdata==''){
		return ;
	}  
  
	var subdetaildataarray=JSON.parse(subdetailrowdata);
 
	
	if(subdetaildataarray['subdetailrow_'+row]==undefined){
		return;
	}
 
	var noofrows=$("#tbodysubdetails").data('noofrows');
	

  	var detfieldstring=	$("#theadsubdetails").data("headcolumns");
  
	var fieldnames=JSON.parse(detfieldstring);
 
	for(let i=1;i<=noofrows;i++){

		var editdatasaved=	subdetaildataarray['subdetailrow_'+row][i-1];
  

		for(let fielddname of fieldnames){ 

			var editfieldvalue=editdatasaved[fielddname]['fieldvalue'];  
			var editfielddisplay=editdatasaved[fielddname]['fielddisplay'];  
 
 
			if( editfieldvalue!=null  && editfieldvalue.trim()!=''  ){   

				var hasfunction24=	$(`[data-subfieldname='${fielddname}'][data-issubdet='1'][data-row='${i}']`).hasClass('function24');
				if(hasfunction24==false){
					setFormFieldValue(`[data-subfieldname='${fielddname}'][data-issubdet='1'][data-row='${i}']`,editfielddisplay,editfieldvalue);
			
				} 
		}

		}

	}
 
}

$("#btnsave").click(function(){

		var form = document.querySelector('.transactionAddDataForm');
	     var formisvalid=form.reportValidity();
 
		if(formisvalid==false)
		return false;

		var validateform1result=ValidateForm();

		if(validateform1result==false){

			return false;
		} 

		var noofcust=$(`[data-fieldname='cust_id'][data-isdet='0']`).length;

		var allow=parseInt($("#receivablepayable_allow").val().trim());
		
		var creditlimit=	$("#creditlimit_details").data("creditlimit").trim();

		var custid=$(`[data-fieldname='cust_id'][data-isdet='0']`).val();

		var netamount=$(`[data-fieldname='net_amount'][data-isdet='0']`).val();
 
		var creditlimitexceeded=false;
  
		if(noofcust>0 && creditlimit=='1' &&  Number.isInteger(custid) ){

			var warnstop=	parseInt($("#creditlimit_details").data("warnstop").trim());
			var creditlimit,ledgerbalance,shownetamount;
			$.ajax({
					type: "POST",
					async: false,
					url: "{{url('/')}}/{{$companyname}}/check-transaction-credit-limit-exceeded",
					data:{'cust_id':custid,'net_amount':netamount},
					success:function(data,status){ 
						var result=JSON.parse(JSON.stringify(data));

					        creditlimitexceeded=result['balance_exceeded'];
							creditlimit=result['credit_limit'];
							ledgerbalance=result['ledger_balance'];
							shownetamount=result['net_amount'];
					 
					}

			});

			var limitexceededok=true;

			if(warnstop==1 && creditlimitexceeded==true){

				alertUserMsg("Credit Limit","Ledger Balance is "+ledgerbalance+", Current Doc Net Amount ix "+shownetamount+" and Credit Limit is "+creditlimit+". ");

			}
			else if(creditlimitexceeded==true){
				
	 
				var successfunc=function(){
						CheckCreditDays();
					};

				var failfunc=function(){
					return false;
				} 
			   confirmUserMsg("Credit Limit Exceeded","Ledger Balance is "+ledgerbalance+", Current Doc Net Amount ix "+shownetamount+" and Credit Limit is "+creditlimit+". Do you want to continue?",successfunc,failfunc,'Yes','No');
 
			}
			else{
				CheckCreditDays();
			}
 
		}
		else{
			// FinalAskReceivablePayable();
			CheckStockAvailability();
		}

 
 
});


function showDocumentAccountDetails(custid){
 
	var docno=$(`[data-fieldname='docno'][data-isdet='0']`).val();


	$.post("{{url('/')}}/{{$companyname}}/get-tran-customer-account-receivable-details",{ 'docno':docno,'custid':custid} ,function(data,status){
		// +custid

		$("#showReceivableModal").modal('show');
		$("#tbodyreceivabledetails").empty();

		var result=JSON.parse(JSON.stringify(data));

		var accname=result['account_name'];
 
		$("#sp_accountname").html(accname);

		var accbal=result['account_balance'];

		$("#sp_accountbalance").html(accbal);

		var receivables=result['receivables'];

		var balances=result['balances'];

		var balancestring=JSON.stringify(balances);

		$("#tbodyreceivabledetails").data('balances',balancestring);

		for(let receivable of receivables ){
			
			var orgamount=parseFloat(receivable['orgamount']).toFixed(2);;
			var bal=parseFloat(receivable['balance']).toFixed(2);;


			if(docno!=receivable['docno']){
				$("#tbodyreceivabledetails").append(`<tr><td>${receivable['docno']}</td><td>${receivable['docdate']}</td><td>${orgamount}</td><td>${bal}</td><td><input type='number' class='amtadjusted receivablepayableamounts' data-docno='${receivable['docno']}' style='width:100px;' placeholder='0.00' /></td></tr>`);

			}
		
		
		}	 
	});

	var dataid=$("#data_id").val();

	if(data_id==undefined)
	return ;


	var docno=$("[data-fieldname='docno'][data-isdet='0']").val();

	$.get("{{url('/')}}/{{$companyname}}/get-edit-tran-data-receivables/"+docno,function(data,status){
 
		var result=JSON.parse(JSON.stringify(data));

		var onaccount=result['onaccount'];

		$("#receivablepayable_onaccountentry").val(onaccount);

		var receivables=result['receivables'];

		for(let receivable of receivables){
			
			$(`#tbodyreceivabledetails  .receivablepayableamounts[data-docno='${receivable['docno']}']`).val(receivable['amount']);


		}



	});
 
}


function submitReceivablePayableDetails(){

	var recpay_onaccount=$("#receivablepayable_onaccountentry").val();
	 recpay_onaccount=parseFloat(recpay_onaccount).toFixed(2);;
	 $("#receivablepayable_onaccount").val(recpay_onaccount);
		var amountentries=	$("#showReceivableModal .amtadjusted");
 
		var amountadjust=[];
		var total=0;
		var amtentry;
		var onaccountentry= $("#receivablepayable_onaccountentry").val();
		
		var balances=JSON.parse($("#tbodyreceivabledetails").data('balances'));

		var invalidentries=[];
		var bindex=0;
		
		total=parseFloat(total)+parseFloat(onaccountentry);

		amountentries.each(function(){
			if($(this).val()==''){
				amtentry=0;
			}
			else{

				amtentry=parseFloat($(this).val()).toFixed(2); 
			}

			var docno=$(this).data("docno");
			
			if(parseFloat(balances[bindex])<parseFloat(amtentry)){
				invalidentries.push((bindex+1));
			}
 
			if(amtentry>0){
				amountadjust.push({ 'docno':docno,'amtentry':amtentry});

                 total=parseFloat(total)+parseFloat(amtentry);
			}


			bindex++;
		});


		if(invalidentries.length>0){
			alert("Please enter amount which is lower than or equal to the balance amount in Line No. "+invalidentries.join(','));
			return false;
		}
 

		total=parseFloat(total).toFixed(2);

		var netamount=$("[data-fieldname='net_amount'][data-isdet='0']").val();
		netamount=parseFloat(netamount).toFixed(2);
 
		if(parseFloat(total)>parseFloat(netamount)){
			alert("Please enter amounts within limit of Net Amount="+netamount);
			return false;
		}

		var amountadjuststring=JSON.stringify(amountadjust);

 
		$("#receivablepayable_amountadjustments").val(amountadjuststring);
 
		$('#transactionAddDataForm').submit();
}



function amountAdjustment(action){

	var custid=$("[data-fieldname='cust_id'][data-isdet='0']").val();

	var netamount=$("[data-fieldname='net_amount'][data-isdet='0']").val();

	var onaccount=$("#receivablepayable_onaccountentry").val();

	$.post("{{url('/')}}/{{$companyname}}/get-transaction-receivablepayable-amount-adjustments",{'cust_id':custid,'net_amount':netamount,'action':action,'on_account':onaccount},
	function(data,status){
 
		var result=JSON.parse(JSON.stringify(data));

		var amtadjustentries=result['adjustments'];

		var amtadjustments=$("#tbodyreceivabledetails .amtadjusted");

		var index=0;

		amtadjustments.each(function(){
			$(this).val(parseFloat(amtadjustentries[index]).toFixed(2));
			index++;
		});
		 

	}
	);

}

function FinalAskReceivablePayable(){

var noofcust=$(`[data-fieldname='cust_id'][data-isdet='0']`).length;

var allow=parseInt($("#receivablepayable_allow").val().trim());

var custid=$(`[data-fieldname='cust_id'][data-isdet='0']`).val();

if(noofcust>0 && allow==1){
		
		showDocumentAccountDetails(custid);
		return false;
	}
	else{
		$("#transactionAddDataForm").submit();
		return true;
	} 
}


function CheckCreditDays(){ 
	var warnstop=	parseInt($("#creditlimit_details").data("warnstop").trim());
	var netamount=$("[data-fieldname='net_amount'][data-isdet='0']").val();
	var custid=$("[data-fieldname='cust_id'][data-isdet='0']").val();
	var trantable=$("#transaction_table").val();
	var docdatestring=$("[data-fieldname='docdate'][data-isdet='0']").val();

	var docdatearray=docdatestring.split('-');

	var docdatecreated=docdatearray[2]+'-'+docdatearray[1]+'-'+docdatearray[0];
 
	var   average_receivable_days,allowed_days,dayslimitexceeded=false; 
	$.ajax({
					type: "POST",
					async: false,
					url: "{{url('/')}}/{{$companyname}}/check-transaction-credit-days-exceeded",
					data:{'cust_id':custid,'net_amount':netamount,'tran_table':trantable,'doc_date':docdatecreated},
					success:function(data,status){ 
						var result=JSON.parse(JSON.stringify(data));
						dayslimitexceeded=result['days_exceeded'];
						average_receivable_days=result['average_receivable_days'];
						allowed_days=result['allowed_days'];
					}

			});  
			
			if(dayslimitexceeded==true){

				if(warnstop==1){

					alertUserMsg("Credit Days Limit","You have exceeded the credit days limit. Current Average Receivable is "+average_receivable_days+" days and allowed is "+allowed_days+" days.");
					return false;
				}
				else{

					var successfunc=function(){CheckStockAvailability() }

					var failurefunc=function(){
						return false;
					}

			       confirmUserMsg("Credit Days Limit","You have exceeded the credit days limit. Current Average Receivable is "+average_receivable_days+" days and allowed is "+allowed_days+" days. Do you still want to continue?",successfunc,failurefunc);

				}

			}
			 
			CheckStockAvailability();

}


function CheckStockAvailability(){ 
 
	var warnstop=	parseInt($("#stockavailability_validation").data("warnstop").trim());
	var stockavailabilityvalidation=$("#stockavailability_validation").val().trim();
	 
	stockavailabilityvalidation=parseInt(stockavailabilityvalidation);
 

	var products=$(`[data-fieldname='product'][data-isdet='1']`);

	var noofproducts=products.length;
  
  
	if( stockavailabilityvalidation==1 &&  noofproducts>0){

		var productsqty=[];
		
		var index=0;
		var row,productid,quantity;
	 
		var locationval=$(`[data-fieldname='location'][data-isdet='0']`).val();

		if(locationval==undefined){
			locationval='';
		}

		var tran_table=$("#transaction_table").val();
 
		products.each(function(){
			row=$(this).data('row');
			productid=$(this).val();
			quantity=$(`[data-fieldname='quantity'][data-row='${row}']`).val();
			productsqty.push({'product_id':productid,'quantity':quantity });
			index++;
		}); 
		var htmlmsg,noofinvalidproducts=0;

		$.ajax({
					type: "POST",
					async: false,
					url: "{{url('/')}}/{{$companyname}}/check-products-stock-availability",
					data:{ 'tran_table':tran_table ,'location':locationval ,'products':JSON.stringify(productsqty)},
					success:function(data,status){ 
					 
					     	var data=JSON.parse(JSON.stringify(data)); 
							var unavailableproducts=data['unavailableproducts'];

							 noofinvalidproducts=unavailableproducts.length;

							 htmlmsg="";

							for(let prd of unavailableproducts){
 
								htmlmsg=htmlmsg+`<p>Current Stock Quantity of ${prd['product_name']} is ${prd['stock_qty']} qty where as the document is for ${prd['asked_qty']} qty.</p>`;

							}

							// htmlmsg=htmlmsg+'</ul>';
 
					}

			});  

			if(noofinvalidproducts>0){
			     	if( warnstop==1 ){
					alertUserMsg('Stock Unavailable',htmlmsg);
					return false;
					}
					else{
						var successfunc=function(){ CheckDetailItemsReferenced(); };
						var failurefunc=function(){ return false;};
						confirmUserMsg('Stock Unavailable',htmlmsg,successfunc,failurefunc,"Yes","No");
					}
			}
			else{
				CheckDetailItemsReferenced();
			}
		
	
			 
		}
		else{
			CheckDetailItemsReferenced();
		}
	
}


$("#btndelete").click(function(){

var trantable=$("#transaction_table").val();
var tranid=$("#transaction_table_id").val();

	if($("#data_id").length==0)
	return;

	var dataid=$("#data_id").val();

	var cnf= confirm("Are you sure to delete this  ?");

		if(cnf==false){
			return false;
		}
	
			var deleteids=[];
			
			deleteids.push(dataid);
	 
			$.post("{{url('/')}}/{{$companyname}}/delete-tran-table-data-by-ids",{'trantable':trantable,'deleteids':deleteids}
   ,function(data,status){

	var result=JSON.parse(JSON.stringify(data));

	if(result['status']==false){

		alertUserMsg("Unallowed Delete",result['message']);
		return false;
	}
	else{
 
		SnackbarMsg(result); 

		setTimeout(function(){
			window.location.href="{{url('/')}}/{{$companyname}}/add-transaction-insert-role-fields/"+trantable+'/'+tranid;
		},2000);

	}
   });

});



function CheckDetailItemsReferenced(){

	var trantable=$("#transaction_table").val();
	var docno=$("[data-fieldname='docno'][data-isdet='0']").val();
 
	var isedit=$("#data_id").length;
 
	
	var warnstop=$("#editdetailonreference").val();

	if(isedit==undefined || warnstop=="disable" ){
		FinalAskReceivablePayable();
		return;
	}
 
	var isreferenced=false;

	var rownums=[];

	$("[data-fieldname='id'][data-isdet='1']").each(function(){
		rownums.push($(this).data('row'));

	});


	var detailrows=[];
 
 
	for(let rownum of rownums){

		detailrows.push({
			'id':	$(`[data-fieldname='id'][data-isdet='1'][data-row='${rownum}']`).val(),
			'product':$(`[data-fieldname='product'][data-isdet='1'][data-row='${rownum}']`).val(),
			'quantity':$(`[data-fieldname='quantity'][data-isdet='1'][data-row='${rownum}']`).val(), 
		});
 
	}

 
	
	$.ajax({ type: "POST",
					async: false,
					url: "{{url('/')}}/{{$companyname}}/check-tran-details-are-referenced-check-delete",
					data:{ 'tran_table':trantable ,'docno':docno,'detailrows':detailrows},
					success:function(data,status){  
						var result=JSON.parse(JSON.stringify(data));
						isreferenced=result['status'];   
					} 
				});
 

	if(isreferenced==true && warnstop=="stop"){
		alertUserMsg( "Referenced","Document used in Pull Data. Cannot edit.");
		return false;
	}		
	else if(isreferenced==true && warnstop=="warn"){

		var successfunc=function(){
			FinalAskReceivablePayable();
		}
		var failurefunc=function(){ return false;};
		confirmUserMsg("Referenced","Document used in Pull Data.  Do you still Want to Continue?",successfunc,failurefunc,"Yes","No");

	} 
	else{
	    FinalAskReceivablePayable();
	}

}




function showCopyDataModal(){
	$("#copyDataFromModal").modal("show");
}
 

 $(function(){
 
	$("#copydata_ddndocno").autocomplete({
      source: function( request, response ) {
        $.ajax( {
		  type:"POST",
          url: "{{url('/')}}/{{$companyname}}/get-copy-data-ids-or-docnumbers-from-transactiontable",
		  dataType: "json",
          data: {
            term: request.term,
			trantable:$("#copydata_ddn_select_transaction_table").val() ,
			type:'docno'
          },
          success: function( data ) {  
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) { 
		$("#copydata_ddndocno").val(ui.item.label);   
		$("#copydata_ddnselectid").val("");
      }
    } );

 
 $("#copydata_ddnselectid").autocomplete({
      source: function( request, response ) {
        $.ajax( {
		  type:"POST",
          url: "{{url('/')}}/{{$companyname}}/get-copy-data-ids-or-docnumbers-from-transactiontable",
		  dataType: "json",
          data: {
            term: request.term,
			trantable:$("#copydata_ddn_select_transaction_table").val() ,
			type:'id'
          },
          success: function( data ) {  
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) { 

		$("#copydata_ddnselectid").val(ui.item.value);   
		$("#copydata_ddndocno").val("");
      }
    } );

 });


 function CopySelecetedDataToForm(){

	var trantable=$("#copydata_ddn_select_transaction_table").val() ;
 
	var copyddnselectid=$("#copydata_ddnselectid").val();
 
	var copyddndocno=$("#copydata_ddndocno").val();
	
	var dataid;

	if(copyddndocno=="" && copyddnselectid.trim()!='' && isNaN(copyddnselectid)){

		alert("Invalid Copy Data Id given");

		return false;
	}
	else{
		dataid=copyddnselectid;
	}
 
   if(copyddndocno!=""){

		$.ajax({ type: "POST",
					async: false,
					url: "{{url('/')}}/{{$companyname}}/get-transaction-table-data-id-by-docno",
					data:{ 'tran_table':trantable ,'docno':copyddndocno },
					success:function(data,status){  
						var result=JSON.parse(JSON.stringify(data));
						dataid=result['data_id'];   
					} 
				});

	}
	$("#copyDataFromModal").modal("hide");
	resetDetailTable();
	$.post("{{url('/')}}/{{$companyname}}/get-transaction-table-data-by-id",{'tablename':trantable,'data_id':dataid},function(data,status){

		var result=JSON.parse(JSON.stringify(data));
		loadSelectedData(result,true);
 
	}); 
 }


 function OpenRejectReason(){
	 var dataid=$("#data_id").val();
	 var trantable=$("#transaction_table").val();

	 var rejectreason='';

	 $.ajax({ type: "POST",
					async: false,
					url: "{{url('/')}}/{{$companyname}}/get-transaction-table-reject-reason-by-data-id",
					data:{ 'tran_table':trantable ,'data_id':dataid },
					success:function(data,status){ 
						var result=JSON.parse(JSON.stringify(data)); 
				       rejectreason=result['reject_reason']; 
					} 
				});
				$("#txt_rejectreason").val(rejectreason);
				$("#RejectReasonFromModal").modal("show");
 
 }


 function SubmitRejectReason(){

	var rejectreason=$("#txt_rejectreason").val();
	var dataid=$("#data_id").val();
	 var trantable=$("#transaction_table").val(); 

	 var trantableid=$("#transaction_table_id").val();

	 $.post("{{url('/')}}/{{$companyname}}/submit-transaction-table-reject-reason-using-data-id",{'tran_table':trantable,'data_id':dataid,'reject_reason':rejectreason},
	 function(data,status){
       SnackbarMsg(data);
	   $("#RejectReasonFromModal").modal("hide");

	   window.location.href="{{url('/')}}/{{$companyname}}/add-transaction-insert-role-fields/"+trantable+'/'+trantableid;

	 }
	 );


 }


 function FillPanNumberFromGstNumber(){

    var trantable=$("#transaction_table").val();

	if(trantable=="Customers"){

		var gstno=$("[data-fieldname='gstno'][data-isdet='0']").val();

		gstno=gstno.trim();

		if(gstno.length==15){

			var panno=gstno.substring(2,11); 
			$("[data-fieldname='panno'][data-isdet='0']").val(panno);

		} 
	}


 }


 
function amountAdjustmentDetailwise(action){

	var accid= $("#showReceivableModalDetailwise").data("accid");

	var rownum=	$("#showReceivableModalDetailwise").data("row");

	var onaccount=$("#receivablepayable_onaccountentry_detailwise").val();

	var debitamount=$(`[data-fieldname='debitamount'][data-row='${rownum}']`).val();

	var creditamount=$(`[data-fieldname='creditamount'][data-row='${rownum}']`).val();

	var netamount;

	if(debitamount>creditamount){
        netamount=debitamount;
	}
	else{
	    netamount=	creditamount;
	}


	$.post("{{url('/')}}/{{$companyname}}/get-account-receivablepayable-amount-adjustments",{'acc_id':accid,'net_amount':netamount,'action':action,'on_account':onaccount} ,
	function(data,status){
			var result=JSON.parse(JSON.stringify(data));

			var amtadjustentries=result['adjustments'];

			var amtadjustments=$("#tbodyreceivabledetails_detailwise .amtadjusted_detailwise");

			var index=0;

			amtadjustments.each(function(){
				$(this).val(parseFloat(amtadjustentries[index]).toFixed(2));
				index++;
			});


	}
	);



// var custid=$("[data-fieldname='cust_id'][data-isdet='0']").val();

// var netamount=$("[data-fieldname='net_amount'][data-isdet='0']").val();

// var onaccount=$("#receivablepayable_onaccountentry").val();

// $.post("{{url('/')}}/{{$companyname}}/get-transaction-receivablepayable-amount-adjustments",{'cust_id':custid,'net_amount':netamount,'action':action,'on_account':onaccount},
// function(data,status){

// 	var result=JSON.parse(JSON.stringify(data));

// 	var amtadjustentries=result['adjustments'];

// 	var amtadjustments=$("#tbodyreceivabledetails .amtadjusted");

// 	var index=0;

// 	amtadjustments.each(function(){
// 		$(this).val(parseFloat(amtadjustentries[index]).toFixed(2));
// 		index++;
// 	});
	 

// }
// );

}


function saveShowRandPDetailwise(){

	var noofreceivables=$("#tbodyreceivabledetails_detailwise").data("noofreceivables");

	var onaccount=$("#receivablepayable_onaccountentry_detailwise").val();

      var balances_string=$("#tbodyreceivabledetails_detailwise").data('balances');

	  var balances=JSON.parse(balances_string);

	var accid=$("#showReceivableModalDetailwise").data("accid");
	
	var show_randp_data_string=$("#receivablepayable_amountadjustments_detailwise").val();

	var row=$("#showReceivableModalDetailwise").data("row" ); 

	 var debitamount=$(`[data-fieldname='debitamount'][data-row='${row}']`).val();


	 if(debitamount==undefined){
		debitamount=0; 
	 }

	 
	 var creditamount=$(`[data-fieldname='creditamount'][data-row='${row}']`).val();

	 if(creditamount==undefined){
		creditamount=0; 
	 }

	 var netamount;

	 if(debitamount>creditamount){
		netamount=debitamount;
	 }
	 else{
		netamount=creditamount; 
	 }
 
	var show_randp_data=JSON.parse(show_randp_data_string);
  

	var receivables=[];
 

	var receivables_detailwise=$(".amtadjusted_detailwise");

	var receivablesindex=0;

	var invalidentries=[];

	var total=parseFloat(onaccount).toFixed(2);;

	receivables_detailwise.each(function(){

		var docno=$(this).data('docno');

         var amtentry=$(this).val();

		 if(amtentry.trim()!="" && amtentry!=0 ){

			receivables.push({'docno':docno,'amtentry':amtentry});
			total=parseFloat(total)+parseFloat(amtentry);
		 }


		 if(parseFloat(amtentry)>parseFloat(balances[receivablesindex])){
			invalidentries.push( (receivablesindex+1));
		 }

		 receivablesindex++;
 
	});

	 
	if(invalidentries.length>0){

		alert("Please enter amount which is lower than or equal to the balance amount in Line No. "+invalidentries.join(','));

		return false;
	}


	total=parseFloat(total).toFixed(2);


	if(parseFloat(netamount)<total){ 
		alert("Please enter amounts within limit of Net Amount="+netamount);
		 return false; 
	}

	 var accname=$("#sp_accountname_detailwise").html();

	 var accbalance=$("#sp_accountbalance_detailwise").html();

	show_randp_data['show_randp_'+row]={ 'acc_name':accname , 'acc_balance':accbalance,'net_amount':parseFloat(netamount).toFixed(2) ,'onaccount':onaccount,'acc_id':accid,'receivables': receivables};

	$("#receivablepayable_amountadjustments_detailwise").val(JSON.stringify(show_randp_data));
 
	$("#showReceivableModalDetailwise").modal('hide'); 
 
}

function deleteShowRAndPData(rownum){

	var show_ranp_string=$("#receivablepayable_amountadjustments_detailwise").val();
 

	var show_randp=JSON.parse(show_ranp_string);
 

	if(show_randp.hasOwnProperty('show_randp_'+rownum)){ 
		delete show_randp['show_randp_'+rownum];
		
	}

	$("#receivablepayable_amountadjustments_detailwise").val(JSON.stringify(show_randp));
 
 
}

function addRemoveDetailIndex(index,action){
 

	var rowindexes_string=$("#detail_rows_indexes").val();

	var rowindexes=JSON.parse(  rowindexes_string);
 
	if(action=="add"){

		rowindexes.push(index);

	}
	else{

		var foundindex=rowindexes.indexOf(index);

		rowindexes.splice(foundindex,1);

	}  

	$("#detail_rows_indexes").val(JSON.stringify(rowindexes));

}


function loadEditShowRandP(rownum){

	var showrandp_string=$("#receivablepayable_amountadjustments_detailwise").val();

	var show_randp=JSON.parse(showrandp_string);

	if(show_randp.hasOwnProperty('show_randp_'+rownum)){
	 
		var randp_show= show_randp['show_randp_'+rownum];
 
		var onaccount=randp_show['onaccount'];
		 
		var receivables= randp_show['receivables'];

  
		var accbalance=randp_show['acc_balance'];

		$("#sp_accountname_detailwise").html(randp_show['acc_name']);
		$("#sp_accountbalance_detailwise").html(accbalance);

		$("#receivablepayable_onaccountentry_detailwise").val(onaccount);

		for(let receivable of receivables){

			var docno=receivable['docno'];
			var amtentry=receivable['amtentry'];

			$(`.amtadjusted_detailwise[data-docno='${docno}']`).val(amtentry);
 
		} 


	}
 

}


function SaveAndWhatsapp(){
	$("#email_whatsapp_mode").val("whatsapp");
	$("#btnsave").click();
}
function SaveAndEmail(){
	$("#email_whatsapp_mode").val("email");
	$("#btnsave").click();
}
function SaveAndPrint(){
	$("#print_report_mode").val("email");
	$("#btnsave").click();
}
</script>

@if(Session::has('gst_error'))
<script> 
	SnackbarMsg({'status':'failure','message':"{!! Session::get('gst_error') !!}"});
	</script>
	@php
	Session::forget('gst_error');	
	@endphp

@endif


@endsection