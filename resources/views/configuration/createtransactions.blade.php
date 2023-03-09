@extends('layout.layout')
<style>
	#tblcreatetransactions td,th{text-align:center!important;}
	</style>
@section('content')

<h2 class="menu-title">Create Transactions</h2>
<div class="pagecontent">
	<div class="container-fluid mtb-2">

		<div class="row"> 
			
				<div class="col-5  mx-auto ">
						
						<form  action="/{{$companyname}}/create-transactions" method="post" >
							@csrf
							<label class="lbl_control mlr-1">Search Transaction:</label>
						<input type="text" class="select-configure form-control"  name="searchtext" style="width:200px; display:inline-block;"   value="{{Session::get('createtransaction_search')}}" />
						<input type="submit" class="btn btn-primary" value="Search" />
						</form>
				</div>
        
			<div class="col-11 mx-auto mtb-4"> 
				<div class="clearfix">
				<a href="/{{$companyname}}/add-edit-createtransaction" style="text-decoration:none;" class="btn btn-primary btn-md btn_float_right">Add </a>
				<input type="button" class="btn btn-primary btn-md  btn_float_right" value="Delete" id="btn_delete" />
				
				<a class="btn btn-primary invisible btn_float_right"  id="edittablelink">Edit</a>
				<a class="btn btn-primary invisible btn_float_right"  id="editfieldslink">View Transaction Fields</a>
					</div>
					<div class="card  mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">

					<table  id="tblcreatetransactions" class="table table-striped"  style="width:200%;">
						<thead>
							<tr>
								<th>Select</th>
								<th>Id</th>
								<th style="vertical-align:top;">TxnName</th>
								<th>Edit Fields</th>
								<th> Type</th>
								<th> Parent Txn</th>
								<th> Stock</th>
								<th> Status</th>
								<th> Footer </th>
								<th>Rec/Pay</th>
								<th> Txn Label </th>
								<th> Txn Class</th>
								<th> Chk CrLimit</th>
								<th> Stock Neg Chk </th>
								<th>Allow Zero Qty</th>
								<th> Auto Bill Wise</th>
								<th> Direct Print</th>
								<th>Direct Sms</th>
								<th> Budget Chk</th> 
							</tr>
						</thead>
						<tbody> @php $alltransactions=$transactions->toArray(); @endphp @foreach ( $alltransactions['data'] as $transaction)
							<tr id="tr_{{$transaction['Id']}}">
								<td>
									<input type="checkbox" value="{{$transaction['Id']}}" name="chktransactions" />
								</td>
								<td><a href="/{{$companyname}}/add-edit-createtransaction/{{$transaction['Id']}}">{{$transaction['Id']}}</a></td>
								<td>{{$transaction['Table_Name']}}</td>
								<td><a href="/{{$companyname}}/view-transaction-fields/{{$transaction['Id']}}">Edit Fields</a></td>
								<td>{{$transaction['Tab_Id']}}</td>
								<td>{{$transaction['Parent Table']}}</td>
								<td>{{$transaction['Stock Operation']}}</td>
								<td>{{$transaction['Status']}}</td>
								<td></td>
								<td>{{$transaction['Receivable']}}</td>
								<td>{{$transaction['table_label']}}</td>
								<td> @if(!empty($transaction['txn_class'])) {{$transaction['txn_class']}} @endif </td>
								<td>{{$transaction['cr_chk']}}</td>
								<td> @if(!empty($transaction['ngt_chk'])) {{$transaction['ngt_chk']}} @endif </td>
								<td> @if(!empty($transaction['qty_zero'])) {{$transaction['qty_zero']}} @endif </td>
								<td>@if(!empty($transaction['auto_bill'])) {{$transaction['auto_bill']}} @endif </td>
								<td>@if(!empty($transaction['direct_print'])) {{$transaction['direct_print']}} @endif</td>
								<td> @if(!empty($transaction['direct_sms'])) {{$transaction['direct_sms']}} @endif </td>
								<td> @if(!empty($transaction['bd_chk'])) {{$transaction['bd_chk']}} @endif </td> 
							</tr> @endforeach </tbody>
						<tfoot> </tfoot>
					</table>
					</div>
					</div>
					</div>
					
				<div class="paginationdiv mtb-2"> {{$transactions->links()}} </div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div> @endsection @section('js') {{-- ROLE --}}
<script type="text/javascript">
$("#btn_delete").click(function() {
	var cnf = confirm("Are you sure to delete selected Transactions ?");
	if(cnf == false) {
		return false;
	}
	var chks = $("input[name='chktransactions']:checked");
	var checkedids = [];
	chks.each(function() {
		checkedids.push($(this).val());
	});
	var url = "/{{$companyname}}/delete-create-transactions";
	$.post(url, {
		'ids': checkedids
	}, function(data, status) {
		for(let chkid of checkedids) {
			$("#tr_" + chkid).remove();
		}
		SnackbarMsg(data);
	});
});


$("input[name='chktransactions']").change(function(){

  var chks=	$("input[name='chktransactions']:checked");


  if(chks.length==1){ 
	var tranid=$("input[name='chktransactions']:checked").val();
	$("#editfieldslink").removeClass("invisible");
	$("#edittablelink").removeClass("invisible");
	$("#editfieldslink").attr('href','/{{$companyname}}/view-transaction-fields/'+tranid);
	$("#edittablelink").attr('href','/{{$companyname}}/add-edit-createtransaction/'+tranid);
 

  }
  else{

	$("#editfieldslink").addClass("invisible");
	$("#edittablelink").addClass("invisible");
  }
  

});
</script> @endsection