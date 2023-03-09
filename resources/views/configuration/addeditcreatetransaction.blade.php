@extends('layout.layout') @section('content')
<h2 class="menu-title">@if(!empty($transaction)) Edit @else Add @endif Create Transaction</h2>
<div class="pagecontent">

	<div class="container mtb-2">
		<form method="post" action="{{url('/')}}/{{$companyname}}/submitcreatetransaction"> @csrf @if(!empty($transaction))
			<input type="hidden" name="tranid" value="{{$transaction->Id}}" /> @endif
			<div class="row">
				<div class="form-group col-4">
					<label class="lbl_control">Txn Name</label>
					<input type="text" class="select-configure form-control" name="table_name" placeholder="Enter Table Name"  id="table_name_txt"  @if(!empty($transaction)) readonly value="{{$transaction->Table_Name}}"  @endif   required="true" /> </div>
				<div class="form-group col-4">
					<label class="lbl_control">Txn Label</label>
					<input type="text" class="select-configure form-control" name="table_label" placeholder="Enter Table Label"  @if(!empty($transaction))  readonly  value="{{$transaction->table_label}}"  @endif  required="true" /> </div>
		
				<div class="form-group col-4">
					<label class="lbl_control">Txn Class</label>
					<select class="select-configure form-control"  name="txn_class">
						<option value="">Select Txn Class</option>
						<option value="Purchase Indent"    @if(!empty($transaction)  &&  $transaction->txn_class=='Purchase Indent') selected="selected"  @endif>Purchase Indent</option>
						<option value="Purchase Enquiry"      @if(!empty($transaction)  &&  $transaction->txn_class=="Purchase Enquiry") selected="selected"  @endif>Purchase Enquiry</option>
						<option value="Purchase Quotation"      @if(!empty($transaction)  &&  $transaction->txn_class=="Purchase Enquiry") selected="selected"  @endif >Purchase Quotation</option>
						<option value="Purchase Order"     @if(!empty($transaction)  &&  $transaction->txn_class=="Purchase Order") selected="selected"  @endif>Purchase Order</option>
						<option value="Material Receipt Note"      @if(!empty($transaction)  &&  $transaction->txn_class=="Purchase Receipt Note") selected="selected"  @endif>Material Receipt Note </option>
						<option value="Purchase Invoice"  @if(!empty($transaction)  &&  $transaction->txn_class=="Purchase Invoice") selected="selected"  @endif>Purchase Invoice</option>
						<option value="Purchase Returns"   @if(!empty($transaction)  &&  $transaction->txn_class=="Purchase Returns") selected="selected"  @endif>Purchase Returns</option>
						<option value="Sales Enquiry"   @if(!empty($transaction)  &&  $transaction->txn_class=="Sales Enquiry") selected="selected"  @endif >Sales Enquiry</option>
						<option value="Sales Quotation"    @if(!empty($transaction)  &&  $transaction->txn_class=="Sales Quotation") selected="selected"  @endif >Sales Quotation</option>
						<option value="Sales Order"   @if(!empty($transaction)  &&  $transaction->txn_class=="Sales Order") selected="selected"  @endif>Sales Order</option>
						<option value="Delivery Note"   @if(!empty($transaction)  &&  $transaction->txn_class=="Delivery Note") selected="selected"  @endif>Delivery Note</option>
						<option value="Sales Invoice"   @if(!empty($transaction)  &&  $transaction->txn_class=="Sales Invoice") selected="selected"  @endif>Sales Invoice</option>
						<option value="Sales Returns"   @if(!empty($transaction)  &&  $transaction->txn_class=="Sales Returns") selected="selected"  @endif>Sales Returns</option>
						<option value="Stock Transfer Note"   @if(!empty($transaction)  &&  $transaction->txn_class=="Stock Transfer Note") selected="selected"  @endif>Stock Transfer Note</option>
						<option value="Excess In Stock"   @if(!empty($transaction)  &&  $transaction->txn_class=="Excess In Stock") selected="selected"  @endif>Excess In Stock</option>
						<option value="Shortage In Stock"   @if(!empty($transaction)  &&  $transaction->txn_class=="Shortage In Stock") selected="selected"  @endif>Shortage In Stock</option>
						<option value="Issue To Production"   @if(!empty($transaction)  &&  $transaction->txn_class=="Issue To Production") selected="selected"  @endif>Issue To Production</option>
						<option value="Receipt From Production"   @if(!empty($transaction)  &&  $transaction->txn_class=="Receipt From Production") selected="selected"  @endif>Receipt From Production</option>
						<option value="Masters"   @if(!empty($transaction)  &&  $transaction->txn_class=="Masters") selected="selected"  @endif>Masters</option>
					</select>
				</div>
				<div class="form-group col-4 mtb-2">
					<label class="lbl_control">Stock</label>
					<select class="select-configure form-control" name="stock_operation">
						<option value="None"   @if(!empty($transaction)  &&  $transaction->{'Stock Operation'}=='None') selected="selected"  @endif>None</option>
						<option value="Add"  @if(!empty($transaction)  &&  $transaction->{'Stock Operation'}=='Add') selected="selected"  @endif>Add</option>
						<option value="Remove"  @if(!empty($transaction)  &&  $transaction->{'Stock Operation'}=='Remove') selected="selected"  @endif>Remove</option>
					</select>
				</div>

				<div class="form-group col-4 mtb-2">
					<label class="lbl_control">Select Txn Type</label>
					<select class="select-configure form-control"  id="ddnTab" name="tab_id">
						<option value="">Select Type</option>
						<option value="Header"  @if(!empty($transaction)  &&  $transaction->Tab_Id=="Header") selected="selected"  @endif>Header</option>
						<option value="Details"   @if(!empty($transaction)  &&  $transaction->Tab_Id=="Details") selected="selected"  @endif  >Details</option>
						<option value="Sub Details"   @if(!empty($transaction)  &&  $transaction->Tab_Id=="Sub Details") selected="selected"  @endif  >Sub Details</option>
					
					</select>
				</div>

				<div id="divparenttxn" class="form-group col-4 mtb-2  @if(!empty($transaction)  &&  $transaction->Tab_Id=='Header') invisible @elseif(!empty($transaction)  &&  ($transaction->Tab_Id=='Details' || $transaction->Tab_Id=='Sub Details'   )) visible @else invisible @endif">
					<label class="lbl_control">Select Parent Txn  </label>
					<select class="form-control" name="parent_txn">
						<option value="">Select Parent Txn</option>
						@foreach ($parent_transactions as $tranid=>$tranname)
							<option value="{{$tranid}}"     @if(!empty($transaction)  &&  trim($transaction->{'Parent Table'})==$tranid) selected  @endif >{{$tranname}}</option>				
						@endforeach
					</select>

					</div>

				<div class="form-group col-7 mtb-2 " style="margin-left:-10px" > 
					<!-- <label class="radio-inline lbl_control mlr-1"> -->
						<table cellpadding="10" style="margin-top:-20px">
							<tr>
								<td style='Poppins, sans-serif;font-weight:600;width:200px'>
						<input type="radio" name="receivable" value="R"   @if(!empty($transaction)  &&  trim($transaction->Receivable)=='R') checked  @endif  />&nbsp;&nbsp;  Receivables
								</td>
						<!-- </label>
					<label class="radio-inline lbl_control mlr-1"> -->
						<td style='Poppins, sans-serif;font-weight:600;width:200px'>
						<input type="radio" name="receivable" value="P"    @if(!empty($transaction)  &&  trim($transaction->Receivable)=='P') checked  @endif  />&nbsp;&nbsp; Payables</td>
						<td style='Poppins, sans-serif;font-weight:600;width:200px'>
					<!-- </label>
					<label class="radio-inline  lbl_control mlr-1"> -->
						<input type="radio" name="receivable" value="N"    @if(!empty($transaction)  &&  trim($transaction->Receivable)=='N') checked  @endif  /> &nbsp;&nbsp;None
</td></tr></table>
					<!-- </label> -->
				</div>
				<div class="form-group col-10 mtb-2" style="margin-top:-20px" >
					<!-- <label class="checkbox-inline lbl_control"> -->
						<table cellpadding="10" style="margin-left:-10px">
							<tr>
								<td style='Poppins, sans-serif;font-weight:600;width:200px'>
						<input type="checkbox" value="1" name="cr_chk"    @if(!empty($transaction)  &&  trim($transaction->cr_chk)=='True') checked  @endif   >&nbsp;&nbsp;Check Credit Limit </td>
					<!-- </label>
					<label class="checkbox-inline  lbl_control mlr-2"> -->
						<td style='Poppins, sans-serif;font-weight:600;width:200px'>
						<input type="checkbox"   name="bd_chk" value="1"   @if(!empty($transaction)  &&  trim($transaction->bd_chk)=='True') checked  @endif   >&nbsp;&nbsp;Check Budgets 
					<!-- </label>
					<label class="checkbox-inline  lbl_control mlr-2"> -->
</td>
<td style='Poppins, sans-serif;font-weight:600;width:200px'>
						<!-- <input type="checkbox"    name="a_deduct" value="1" @if(!empty($transaction)  &&  trim($transaction->ADeduct)==1) checked  @endif  >&nbsp;Add/Deduct </label>
					<label class="checkbox-inline  lbl_control mlr-2"> -->
						
						<input type="checkbox"   name="ngt_chk" value="1" @if(!empty($transaction)  &&  trim($transaction->ngt_chk)=='True') checked  @endif  >&nbsp;&nbsp;Stock Negative Check 
					<!-- </label> -->
</td></td></table>
				</div>
				<div class="form-group col-10 mtb-2" style="margin-top:-20px" >

					<!-- <label class="checkbox-inline lbl_control"> -->
						<table cellpadding="10" style="margin-left:-10px">
							<tr>
								<td style='Poppins, sans-serif;font-weight:600;width:200px'>
						<input type="checkbox" value="1" name="qty_zero" @if(!empty($transaction)  &&  trim($transaction->qty_zero)=='True') checked  @endif >&nbsp;&nbsp;Allow Zero Qty 
</td>
<td style='Poppins, sans-serif;font-weight:600;width:200px'>
					<!-- </label>
					<label class="checkbox-inline  lbl_control mlr-2"> -->
						<input type="checkbox"  value="1" name="auto_bill"  @if(!empty($transaction)  &&  trim($transaction->auto_bill)=='True') checked  @endif>&nbsp;&nbsp;Auto Bill Wise 
</td>
<td style='Poppins, sans-serif;font-weight:600;width:200px'>
					<!-- </label>
					<label class="checkbox-inline  lbl_control mlr-2"> -->
						<input type="checkbox"  value="1" name="direct_print"   @if(!empty($transaction)  &&  trim($transaction->direct_print)=='True') checked  @endif  >&nbsp;&nbsp;Direct Print 
</td>
							<td style='Poppins, sans-serif;font-weight:600;width:200px'>
					<!-- </label>
					<label class="checkbox-inline  lbl_control mlr-2"> -->
						<input type="checkbox"   value="1" name="direct_sms"   @if(!empty($transaction)  &&  trim($transaction->direct_sms)=='True') checked  @endif   >&nbsp;&nbsp;Direct SMS 
</td>
</tr></table>
					<!-- </label> -->
				</div>
				<div class="form-group col-12 mtb-4 text-center">
					<input type="submit" name="btn_submit" value="Submit" class="btn btn-primary" /> &nbsp;&nbsp;
					<input type="button" name="btn_cancel" value="Cancel" class="btn btn-primary"  id="btn_cancel_reload" /> </div>
			</div>
		</form>
	</div>
</div> @endsection @section('js') {{-- ROLE --}}
<script type="text/javascript">


	$("#ddnTab").change(function(){

		var tabval=$(this).val(); 
		if(tabval=="Details" || tabval=="Sub Details" ){
			$("#divparenttxn").removeClass("invisible");

		}
		else{
			$("#divparenttxn").addClass("invisible");
		}


	});


	$(function(){

$("#table_name_txt").on('keypress', function(e) {
	
	if (e.which == 32){ 
		return false;
	}
});
})


</script> @endsection