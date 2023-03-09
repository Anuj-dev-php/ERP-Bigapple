@php @endphp @extends('layout.layout') @section('content')
<div> <span id="showID"></span> </div>

<h2 class="menu-title  mb-5 font-size-18">Fa Integration  </h2>
<div class="pagecontent text-center" id="divpagecontent"> 
	<div class="row"  >
		<div class="col-9 mx-auto"> 
			
		<div class="clearfix">
		<a href="/{{ Session::get('company_name') }}/fa-integration/add" class="btn btn-primary btn-md btn_float_right" style="text-decoration:none;">Add</a>
			<input type="button" class="btn btn-primary btn-md  btn_float_right" value="Delete" id="btn_delete_tranaccount"   /> 
			</div>
		</div>
		<div class="row">
			<div class="col-9 mx-auto" style="margin-top:20px;">
				<div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
							<table class="table  table-striped" id="datatable">
								<thead>
									<tr>
										<th>Select </th>
										<th>Template Id </th>
										<th>Description</th>
										<th>Vch Type</th>
										<th>Vch Sub Type</th>
										<th>Account</th>
										<th>Transaction</th>
										<th>Default</th>
										<th>Edit</th>
									</tr>
								</thead>
								<tbody>
									 @foreach($tran_accounts as $tranaccount)
									<tr id="tr_{{ $tranaccount->Id}}">
										<td class="text-center">
											<input type="checkbox" class="tran_accounts" value="{{ $tranaccount->Id}}" />
										</td>
										<td>{{$tranaccount->TemplateId}}</td>
										<td>{{ $tranaccount->Description}} </td>
										<td>{{$tranaccount->VchType}}</td>
										<td>{{$tranaccount->VchSubTypes}}</td>
										<td>{{$tranaccount->Account}}</td>
										<td>{{$tranaccount->Transaction}}</td>
										<td>{{$tranaccount->is_default}} </td>
										<td>
											<button class="tbl_btn"><a class="tbl_link" href="/{{ Session::get('company_name') }}/fa-integration/edit/{{$tranaccount->Id}}">Edit</a></button>
										</td>
									</tr> @endforeach </tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> @endsection @section('js') {{-- ROLE --}}
<script type="text/javascript">
$("#btn_delete_tranaccount").click(function() {
	var selectedtrans = $(".tran_accounts:checked");
	if(selectedtrans.length == 0) {
		SnackBar({
			message: "Please select At Least 1 Tran Account",
			status: 'error'
		});
		return false;
	}
	var tran_accounts = [];
	selectedtrans.each(function() {
		tran_accounts.push($(this).val());
	});
	var cnf = confirm("Are you sure to delete selected Tran Accounts");
	if(cnf == false) return;
	var data = {
		'tran_accounts': tran_accounts
	};
	var url = '/' + '{{$companyname}}' + '/fa-integration/delete';
	$.post(url, data, function(data, status) {
		SnackbarMsg(data);
		for(let tranid of tran_accounts) {
			$("#tr_" + tranid).remove();
		}
	});
});
</script> @endsection