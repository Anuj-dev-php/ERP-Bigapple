@extends('layout.layout')
<style>
 
	</style>
@section('content')

<h2 class="menu-title   mb-5 font-size-18">Define Document Number</h2> 
<div class="pagecontent">
	<div class="container mtb-2">
		
	<form class="form-horizontal" method="post" action="/{{$companyname}}/submitdefinedocumentnumber">
            @csrf

			<div class="row">
			<div class="form-group col-4 mtb-2 mx-auto">
					<label class="lbl_control_inline"> Transaction :</label>
					<select class="form-control inline_control select2" name="transaction" id="ddnTransaction">
						<option value="">Select Transaction</option>
						@foreach ($tables as $tablename=>$tablelabel )
						<option value="{{$tablename}}">{{$tablelabel}}</option>
						@endforeach
					</select>
			</div>

		 
 
 
			</div>
		</div>

		<div class="row mtb-3">
                        <div class="col-6 mx-auto"  >
						<div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                                <table data-order='[[ 0, "desc" ]]' id="table_define_doc_numbers" class="table table-striped">
                                    <thead>
                                        <th>Id</th>
										<th>Field Name</th>
                                        <th>Prefix</th>
                                        <th>Number</th>
                                        <th>Suffix</th>
                                    </thead>
                                </table>
                            </div>
							
							</div>

</div>


                        </div>

                    </div>


		</form> 
   </div>
</div> 
@endsection 
@section('js') 

<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
<script type="text/javascript">

	$(function(){ 

        tbldefinedocnumbers = $('#table_define_doc_numbers').DataTable({
            "paging": false,
            "bFilter": false,
            "bInfo": false,
            "ordering":false,
            "ajax": '/{{$companyname}}/get-Transaction-Table-Codes/0',
            "lengthChange": false,
            searching: false,
            "columns": [{
                data: 'id', 

            }, 

			{
				data:'Field'
			}
			,
            {
                data: 'prefix', 
			 
            }
            ,
            {
                data: 'code', 
            }
          ,
          {
                data: 'suffix', 
			 
            } 
          
          ]
        }); 
        
        $('#table_define_doc_numbers').on('draw.dt', function() {
					
					$('#table_define_doc_numbers').Tabledit({
						url: '/{{$companyname}}/update-document-number',
						dataType: 'json', 
						columns: {
							identifier: [0, 'id'],
							editable: [
								[2, 'prefix'] ,
								[3, 'code'] ,
								[4, 'suffix'] 

							]
						},
						buttons: {

							edit: {
								class: "btn btn-default",
								html: '<span ><i class="fas fa-edit"></i></span>',
								action: 'edit'
							},
							 


						},
						onSuccess: function(data, textStatus, jqXHR) {
							

						 if (data.action == 'edit') {

								SnackBar({
									message: "Document Number Updated successfully",
									status: 'success'
								});
							}
						
						}
						,
						onFail:function() { 
						alert("error");
						},

					});
           });
 
		
		   $("#ddnTransaction").change(function(){

			var trantable=$(this).val();

			var docnumberurl = '/{{$companyname}}/get-Transaction-Table-Codes/' +trantable;

			 tbldefinedocnumbers.ajax.url(docnumberurl).load(); 


			});
 
		
	});

 

</script> @endsection