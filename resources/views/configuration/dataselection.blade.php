@inject('function4filter','App\Http\Controllers\Services\Function4FilterService') 

@extends('layout.layout')
<style>
</style> @section('content')
<h2 class="menu-title   mb-5 font-size-18">Data Selection</h2>
<div class="pagecontent">
	<div class="container mtb-2">
		<form method="post"  action="{{url('/')}}/{{$companyname}}/submit-data-selection">
			@csrf
	 <div class="row">
		 <input type='hidden' name='data_selection_id' id="data_selection_id" />
				<div class="col-4 mtb-2">
					<label class="lbl_control_inline"> Transaction :</label>
					<div class="inline_control">
						<select class="form-control select2" name="transaction" id="ddnTransaction"  required> @foreach ($tables as $tablename=>$tablelabel )
						<option value=""></option>	
						<option value="{{$tablename}}">{{$tablelabel}}</option> @endforeach </select>
					</div>
				</div>
				<div class=" col-4 mtb-2">
					<label class="lbl_control_inline"> Key Field:</label>
					<select class="form-control inline_control" name="keyfield" id="ddnTransactionKeyFields"   required>
						<option value="">Select Key Field</option>
					</select>
				</div>
				<div class=" col-4 mtb-2">
					<label class="lbl_control_inline"> Default Value:</label>
					<div class='inline_control'> 
						<select class='form-control select2'  name='defaultvalue' id='ddn_defaultvalue' required></select>
					
					</div>
				</div>
				<div class="form-group col-4 mtb-2 mx-auto">
					<input type='submit' value="Submit" class="btn btn-md btn-primary" />
					<input type='button' value="Cancel" class="btn btn-md btn-primary" onclick="formCancel();" />

				 </div>
 
		</div>
		</form>
	</div>
	<div class="row mtb-3">
		<div class="col-8 mx-auto">
			<div class="card">
				<div class="card-body">
					<div class=" mx-auto table-responsive">
						<table data-order='[[ 0, "desc" ]]' id="table_data_selection" class="table table-striped">
							<thead>
								<th>Id</th>
								<th>Table Name</th>
								<th>Key Field</th>
								<th>Username</th>
								<th>Default Value</th>
								<th>Edit</th>
								<th>Delete</th>
							</thead>
							<tbody>
								@if(count($dataselections)>0)
								@foreach (   $dataselections as    $dataselection )
								<tr>
									<td>{{$dataselection->id}}</td>
									<td>{{$dataselection->table_name}}</td>
									<td>{{$dataselection->key_fld}}</td>
									<td>{{$dataselection->username}}</td>
									@php
	
									$function4filter->tablename=$dataselection->table_name;

									$defaultvaluename=$function4filter->getFunction4FieldValueUsingId($dataselection->key_fld,$dataselection->default_value);
 

									@endphp
									<td>{{$defaultvaluename}}</td>
									<td>
									<button class="tbl_btn"><a class="tbl_link" href="javascript:void(0);" onclick="editDataSelection({{$dataselection->id}})">Edit</a></button>
										 </td>

										 <td>
										 <button class="tbl_btn"><a class="tbl_link" href="{{url('/')}}/{{$companyname}}/delete-data-selection-user-key-value/{{$dataselection->id}}" >Delete</a></button>
									</td>


								</tr>
									
								@endforeach
								@else
								<tr><td colspan='7'>No Data</td></tr>

								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div> @endsection @section('js')
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
<script type="text/javascript">
	function loadTransactionFields(tranid,selectedfield=null){

		$("#ddnTransactionKeyFields  option:not(:first)").remove();
		$.post("{{url('/')}}/{{$companyname}}/get-Function4-Fields-From-Table", {
			'tran_id': tranid
		}, function(data, status) {
			var result = JSON.parse(JSON.stringify(data));
			var options = result['fields'];
			for(let option of options) {
				$("#ddnTransactionKeyFields").append(`<option value='${option['Field_Name']}'>${option['fld_label']}</option>`);
			}

			if(selectedfield!=null){
				$("#ddnTransactionKeyFields").val(selectedfield);
			}
		});

	}


	function editDataSelection(id){

		$.get("{{url('/')}}/{{$companyname}}/get-Edit-Data-Selection/"+id,function(data,status){

			var result=JSON.parse( JSON.stringify(data));
 
           $("#data_selection_id").val(result['id']);
		   $("#ddnTransaction").val(result['table_name']);
		   loadTransactionFields(result['table_name'],result['key_fld']);
		   $("#txt_defaultvalue").val(result['default_value']); 
			$("#ddnTransaction").val(result['table_name']); 
			   $("#ddnTransaction").trigger('change');
			   $("#ddnTransactionKeyFields").val(result['key_fld']);
			   loadKeyFieldValues( result['table_name'],result['key_fld'] );
			   $("#ddn_defaultvalue").empty();
			   $("#ddn_defaultvalue").append(`<option value='${result['default_value']}'>`+ result['default_value_displayname']+`</option>`);
			   $("#ddn_defaultvalue").trigger("change"); 
			
		});

	}


	function formCancel(){
		
				$("#data_selection_id").val('');
				$("#ddnTransaction").val(''); 
				$("#ddnTransactionKeyFields  option:not(:first)").remove();
				$("#txt_defaultvalue").val('');  
			   $("#ddnTransaction").trigger('change'); 
	}


	function loadKeyFieldValues(tranid,keyfield ){
		 
		initSelect2SearchTriggerChange(`#ddn_defaultvalue`,"{{url('/')}}/{{$companyname}}/get-Function4-keyfield-All-Values",'Select Default Value',null,{'trantable':tranid,'fieldname':keyfield});

 

	}
$(function() {
	$("#ddnTransaction").on("change", function() {
		var tranid = $(this).val();
		loadTransactionFields(tranid);
	
	});



	$("#ddnTransactionKeyFields").on("change", function() {

		var keyfield=$(this).val();
		var tranid=$("#ddnTransaction").val(); 
		loadKeyFieldValues(tranid ,keyfield);

	});

 
});
</script> @endsection