@extends('layout.layout')

@php
    
function showHeaderField($header_field){

    $header_field=str_replace('_',' ',$header_field);

    
    $header_field=ucfirst(  $header_field);
    
    return $header_field;
}
@endphp
  
 
  @section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading">Pending Documents</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">

                <form action="{{route('company.pending-documents',['company_name'=>$companyname])}}" method="post">

                @csrf
 
                <div class='mlr-2'  id="divaddsearchfields" data-noofsearch="{{$noofsearchfields}}"  style="margin-top:20px;" >
                
                @for ($i=1;$i<=$noofsearchfields;$i++)

				<!-- 'searchfields','searchconditions','searchvals','searchoperator' -->
                    
                <div class="row searchfieldrow"  data-index='{{$i}}'>
 
                <div class="form-group col-2 searchdiv_field">
						<label class="lbl_control ">Field:</label> 
						<div  >
							<select class="form-control searchfield" name="searchfield[]"   data-index="{{$i}}">
								<!-- <option value="">Select Field</option>  -->

                                @if(count( $headerfields))
									@foreach( $headerfields as $field)
                                    <option value="{{$field}}"  @if(array_key_exists(($i-1),$searchfields) &&  $searchfields[$i-1]==$field ) selected @endif  >{{ showHeaderField($field) }}</option>
                                @endforeach

                                @endif



                                   </select>
                            	</div>
					</div>
					<div class="form-group col-1  searchdiv_condition ">
						<label class="lbl_control">Condition:</label>
						<div>
							<select class="form-control searchcondition" name="searchcondition[]" data-index="{{$i}}">
							<option value="<" @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="<" ) selected @endif  ><</option>
								<option value=">"  @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]==">" ) selected @endif   >></option>
							    <option value="="  @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="=" ) selected @endif  >=</option>
								<option value="!="   @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="!=" ) selected @endif  >!=</option>
								<option value="Like"   @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="Like" ) selected @endif  >Like</option>
								<option value="Not Like"   @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="Not Like" ) selected @endif  >Not Like</option> 
								<option value="Contains"   @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="Contains" ) selected @endif  >Contains</option>
								<option value="Begin With"    @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="Begin With" ) selected @endif >Begin With</option>
								<option value="Ends With"   @if(array_key_exists(($i-1),$searchconditions) &&  $searchconditions[$i-1]=="Ends With" ) selected @endif >Ends With</option>
							</select>
						</div>
					</div>
					<div class="form-group col-2   searchdiv_value " data-index="{{$i}}">
						<label class="lbl_control">Value:</label>
						<div> 
                    
						<input type='text' name="searchval[]" class='form-control searchval'   @if(array_key_exists(($i-1),$searchvals)) value="{{$searchvals[$i-1]}}" @endif  data-index="{{$i}}"   required />
 
						</div>
					 
					</div>

					<div class=" form-group col-1   searchdiv_operator " data-index="{{$i}}">
						<label class="lbl_control">Operator:</label>
						<div>
							<select name="searchoperator" class='form-control searchoperator' data-index="{{$i}}" required>
								<option value="And" @if(!empty( $searchoperator) &&  $searchoperator=='And') selected @endif>And</option>
								<option value="Or"  @if(!empty( $searchoperator) &&  $searchoperator=='Or') selected @endif>Or</option>
							</select>
						</div>
					</div>
 
                    <div class=" form-group col-1" data-index="{{$i}}">
						<label class="lbl_control">Delete:</label>
						<div style='vertical-align:middle;'>
                        <a   data-index="{{$i}}" href='javascript:void(0);'   class='delete-link'>	<i class="fa fa-trash" aria-hidden="true"></i></a>
                 
						</div>
					</div> 

                    </div>
                  
                    
                @endfor
 
                   
 
 

                </div>

                <div class="row" style="margin:20px 0px;"  >

                <div class='form-group col-6   mlr-2 searchdiv_field  text-start'>
				
                <input type='button' class='btn btn-sm btn-primary' value='Add Another' onclick="addAnotherSearchField();"   />
                <input type='submit' class='btn btn-sm btn-primary' value='Search' />
                    <a href="{{url('/')}}/{{$companyname}}/reset-pending-documents-data-search" class='btn btn-sm btn-primary'>Reset Search</a>
    
                 </div> 

                </div>

                </form>
            
		<div class='row' style="min-height:300px;" >
 
 
			<div class="col-md-12 mx-auto">
	 
			<input type="button" class="btn btn-primary"  value="XLSX" onclick="downloadDocument('xlsx')" />
						
						&nbsp;		&nbsp;  <input type="button" class="btn btn-primary"  value="PDF" onclick="downloadDocument('pdf')" />
 
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary"  value="CSV" onclick="downloadDocument('csv')" />

					 
	 		
      <div class="card">
					<div class="card-body" >
						<div class=" mx-auto table-responsive">

			


							<table class="table  table-striped taboncell"  >
                <thead>
	 
               
               @foreach ( $headerfields as $headerfield )
			        
				   <th style="min-width:100px!important">{{ showHeaderField($headerfield) }}</th>
                @endforeach


               </thead>

                <tbody>
                @if(count($transactiondata_collection)>0)	
								@foreach ($transactiondata_collection as $data)
						 
									<tr class="transactiondatarow"  >
						 
									<td class='text-center'  >{{$data['id']}}</td> 
									
									<td class='text-center'  >{{ date('d/m/Y',strtotime($data['doc_date'])) }}</td> 
										
									<td class='text-center'  >{{ $data['doc_no']}}</td> 
									
									<td class='text-center'  >{{ $data['location']}}</td> 
 
									<td class='text-center'  >{{ $data['cust_id']}}</td> 
									
									<td class='text-center'  >{{ $data['name']}}</td> 
									
									<td class='text-center'  >{{ $data['product']}}</td>  
									
									<td class='text-center'  >{{ $data['qty']}}</td> 
									
									<td class='text-center'  >{{ $data['rate']}}</td> 
									
									<td class='text-center'  >{{ $data['used_qty'] }}</td> 
									
									<td class='text-center'  >{{ $data["Bal Qty"] }}</td> 
									
									<td class='text-center'  >{{ $data['Ageing Days']  }}</td> 

									 </tr> 
									 
									 
									 @endforeach 
								@else

								<tr><td class='text-center' colspan="{{count($headerfields)}}">No Data</td></tr>
 
								@endif

 
                </tbody>
				<tfoot>
			 
 
				</tfoot>

				<!-- firstlevel_accountids_data -->
              </table>
			   
			  <div>
                @if(count($transactiondata_collection)>0)
				   {{$transactiondata_collection->links()}}
                   @endif
				</div>
			 
            </div>
        </div>
        </div>
        
    
    
    
    
        </div>
		</div>
	</div>
</div> @endsection @section('js')
<script src="{{ asset('js/checkboxtree.min.js') }}"></script>
<script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script>
<script src="{{ asset('js/taboneachcell.js') }}"></script>
<script type='text/javascript'>

    $("#ddnRegisterTable").change(function(){

        var tablename=$(this).val();
 
 
        $.get("{{url('/')}}/{{$companyname}}/get-transaction-table-fields/"+tablename,function(data){
 
            var result=JSON.parse(JSON.stringify(data)); 

            $(".searchfield").empty();

			$("#divaddsearchfields").empty();

			$("#divaddsearchfields").data('noofsearch',0);

            // $(".searchfield  option:not(:first)").remove();

            // $('#ddnFirstField option:not(:first)').remove();

            for(let field of result['fields']){ 

                $(".searchfield").append(`<option data-function='`+field['Field_Function']+`' value='`+field['Field_Name']+`'>`+field['fld_label']+`</option>`);

            }

        });
 
    });

    $(function(){

        $("#divaddsearchfields").on("change",".searchfield",function(){

var index=$(this).data("index");
  
var selectedoption= $(this).val();

if(selectedoption=='doc_date'){
 
		   $(`#divaddsearchfields .searchdiv_value[data-index='${index}']`).find("input").datetimepicker({
       format: 'd-m-Y',
       timepicker: false,
       datepicker: true,
       dayOfWeekStart: 1,
       yearStart: 2016,
   });

}
 
 
 
});


        $.get("{{url('/')}}/{{$companyname}}/get-register-report-search-fields",function(data){

               
			var tablename=$("#ddnRegisterTable").val();;
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

    });



    function addAnotherSearchField(){

        var noofsearch=$("#divaddsearchfields").data('noofsearch');
        noofsearch=noofsearch+1; 
    
        $.get("{{url('/')}}/{{$companyname}}/add-another-pending-document-report-search-field/"+noofsearch,function(data){

            var result=JSON.parse(JSON.stringify(data)); 
            $("#divaddsearchfields").append(result['html']);
            $("#divaddsearchfields").data('noofsearch',noofsearch);

        });
    }


    function deleteFieldFilter(noofsearch){
 
  $(`#divaddsearchfields  .searchdivfields[data-index='${noofsearch}']`).remove();

    }


    $('#divaddsearchfields').on('click','.delete-link',function(){
      
        var index=$(this).data('index');

        $(".searchfieldrow[data-index='"+index+"']").remove();


    })


	function downloadDocument(format){

		var url="{{url('/')}}/{{$companyname}}/download-pending-documents/"+format;
 
		window.open(url);

	}
 
	
</script> @endsection