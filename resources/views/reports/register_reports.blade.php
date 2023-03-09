@extends('layout.layout')
  
@inject('function4filter','App\Http\Controllers\Services\Function4FilterService') 
@inject('functionfilter','App\Http\Controllers\Services\FunctionService')
@php
 
$function4filter->tablename=$tablename;

@endphp

  @section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading">{{$register_name}}</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">

                <form action="{{$currentURL}}" method="post">

                @csrf

                <div class="row mlr-2"   >

            

                <div class="form-group col-4"><label class="lbl_control_inline ">Select Register Table:</label> 
                    <div class='inline_control'>
                     <select class="form-control" name="register_table" required="true" id='ddnRegisterTable' >
                        <option value=''>Select Table</option>
                        @foreach ( $register_tables as  $register_table_id=>$register_table_name )
                            <option value="{{$register_table_id}}" @if( $tablename==$register_table_id) selected @endif>{{$register_table_name}}</option>
                        @endforeach
                     </select>
                        </div>
                    </div> 
                
                </div>


                <div class='mlr-2'  id="divaddsearchfields" data-noofsearch="{{$noofsearchfields}}"  style="margin-top:20px;" >
                
                @for ($i=1;$i<=$noofsearchfields;$i++)
                    
                <div class="row searchfieldrow"  data-index='{{$i}}'>
 
                <div class="form-group col-2 searchdiv_field">
						<label class="lbl_control ">Field:</label> 
						<div  >
							<select class="form-control searchfield" name="searchfield[]"   data-index="{{$i}}">
								<!-- <option value="">Select Field</option>  -->

                                @if(count( $headerfields))

                                @foreach ($headerfields as $field )
                                    <option value="{{$field->Field_Name}}"  data-function="{{$field->Field_Function}}">{{$field->fld_label}}</option>
                                @endforeach

                                @endif



                                   </select>
                            	</div>
					</div>
					<div class="form-group col-1  searchdiv_condition ">
						<label class="lbl_control">Condition:</label>
						<div>
							<select class="form-control searchcondition" name="searchcondition[]" data-index="{{$i}}">
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
					<div class="form-group col-2   searchdiv_value " data-index="{{$i}}">
						<label class="lbl_control">Value:</label>
						<div> 
                        @if(count($searchfunctions)>0 && in_array($searchfunctions[$i-1],array(2,4 ,18,14,16)))
							<select name="searchval[]" class='form-control  searchval' data-index="{{$i}}"   required></select>
						@else
						<input type='text' name="searchval[]" class='form-control searchval'  data-index="{{$i}}"   required />

						@endif 
						</div>
					 
					</div>

					<div class=" form-group col-1   searchdiv_operator " data-index="{{$i}}">
						<label class="lbl_control">Operator:</label>
						<div>
							<select name="searchoperator" class='form-control searchoperator' data-index="{{$i}}" required>
								<option value="And">And</option>
								<option value="Or">Or</option>
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
                    <a href="{{url('/')}}/{{$companyname}}/reset-register-report-data-search" class='btn btn-sm btn-primary'>Reset Search</a>
    
                 </div> 

                </div>

                </form>
            
		<div class='row'  >
 
 
			<div class="col-md-12 mx-auto">
	 
			<input type="button" class="btn btn-primary"  value="XLSX" onclick="downloadDocument('xlsx')" />
						
						&nbsp;		&nbsp;  <input type="button" class="btn btn-primary"  value="PDF" onclick="downloadDocument('pdf')" />
 
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary"  value="CSV" onclick="downloadDocument('csv')" />

						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary"  value="ALL XLSX" onclick="downloadAllDocument('xlsx')" />
						
						&nbsp;		&nbsp;  <input type="button" class="btn btn-primary"  value="ALL PDF" onclick="downloadAllDocument('pdf')" />
 
						&nbsp; 		&nbsp; 	<input type="button" class="btn btn-primary"  value="ALL CSV" onclick="downloadAllDocument('csv')" />


	 		
      <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">

			


							<table class="table  table-striped taboncell"  >
                <thead>
					@if(count($headerfields)>0)
                <th>Id</th>
				@endif
               
               @foreach ( $headerfields as $headerfield )
			           @php 
			   			if($headerfield->fld_label=='Id'){
							continue;
						}
						@endphp
				
					 
				   <th style="min-width:100px!important">{{trim($headerfield->fld_label)}}</th>
                @endforeach


               </thead>

                <tbody>
                @if(count($transactiondata)>0)	
								@foreach ($transactiondata as $data)
								@php
								 $data=(array)$data; 
								 $data=array_change_key_case($data,CASE_LOWER)

								@endphp
									<tr class="transactiondatarow"  id="{{$data['id']}}">
								 
									<td class='text-center'>{{$data['id']}}</td> @foreach ( $headerfields as $headerfield ) 
							
									 @php 
										if($headerfield->fld_label=='Id'){
												continue; 
											}
										
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
											
										<td>{{ $showdata }}</td>
										@elseif ($headerfield->Field_Function==31 || $headerfield->Field_Function==27 || $headerfield->Field_Function==6)
											@php
												
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
											@endphp
											
										<td>{{ $showdata }}</td>
										@elseif($headerfield->Field_Function==5)
										@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td>
											<a href="{{url('/')}}/{{Session::get('company_name')}}/edit-transaction-table-single-data/{{$tablename}}/{{$tableid}}/{{$data['id']}}"> 
											{{$showdata}}
											</a>
											</td>
											@else
											@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td>{{	$showdata}}</td>

										@endif



										
										
										@endforeach
									 </tr> 
									 
									 
									 @endforeach 
								@else

								<tr><td class='text-center' colspan="{{count($headerfields)+1}}">No Data</td></tr>
 
								@endif

 
                </tbody>
				<tfoot>
			 
 
				</tfoot>

				<!-- firstlevel_accountids_data -->
              </table>
			   
			  <div>
                @if(count($transactiondata)>0)
				   {{$transactiondata->links()}}
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

var tablename= $("#ddnRegisterTable").val();

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
         var tablename= $("#ddnRegisterTable").val(); 

         if(tablename==''){
            alert('Please select Register Table');
            return false;
         }


        $.get("{{url('/')}}/{{$companyname}}/add-another-register-report-search-field/"+tablename+"/"+noofsearch,function(data){

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

		var url="{{url('/')}}/{{$companyname}}/download-register-report/"+format;
 
		window.open(url);

	}

	function downloadAllDocument(format){

		var url="{{url('/')}}/{{$companyname}}/download-register-report-all/"+format;

		window.open(url);

	}
	
</script> @endsection