@extends('layout.layout')  

@inject('function4filter','App\Http\Controllers\Services\Function4FilterService') 
@inject('functionfilter','App\Http\Controllers\Services\FunctionService')

@php

if(!empty($fieldvalue_table)){

    $function4filter->tablename=$fieldvalue_table;
}


if(!empty($detail_tablename)){

    $function4filter->detail_tablename=$detail_tablename;

}
 

 
function showHeaderField($header_field){

    $header_field=str_replace('_',' ',$header_field);

    
    $header_field=ucfirst(  $header_field);
    
    return $header_field;
}
@endphp

  @section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading">History Report</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">

                <form action="{{url('/')}}/{{$companyname}}/history-report" method="post">
                @csrf 
                <div class="row mlr-2"   > 
                <div class="form-group col-4"><label class="lbl_control_inline ">Select Fields:</label> 
                    <div class='inline_control'>
                     <select class="form-control select2" name="fields_selection"  id="ddn_fields_selection" required="true"   >
                        <option value=''></option>

						@foreach ($fieldnames as $fieldname )
						<option value="{{$fieldname}}"  @if($field_selection==$fieldname) selected @endif>{{$fieldname }}</option>
							
						@endforeach
                     </select>
                        </div>
                    </div> 

					
					<div class="form-group col-4"><label class="lbl_control_inline ">Select Field Value:</label> 
                    <div class='inline_control'>
                     <select class="form-control" name="fieldvalue_selection"  id="fieldvalue_selection"   > </select>
                        </div>
                    </div> 

					
					<div class="form-group col-4"><label class="lbl_control_inline ">Select Table :</label> 
                    <div class='inline_control'>
                     <select class="form-control" name="fieldvalue_tables" id="fieldvalue_tables"   > </select>
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
             

                @if(count( $table_fields))
                    @foreach( $table_fields as $field_key=>$field_val)
                    <option value="{{$field_key}}"  @if(array_key_exists(($i-1),$searchfields) &&  $searchfields[$i-1]==$field_key ) selected @endif  >{{$field_val}}</option>
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
                    <a href="{{url('/')}}/{{$companyname}}/reset-salesman-report-data-search" class='btn btn-sm btn-primary'>Reset Search</a>
    
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
			   			if($headerfield['fld_label']=='Id'){
							continue;
						}
						@endphp
				
					 
				   <th style="min-width:100px!important">{{trim($headerfield['fld_label'])}}</th>
                @endforeach
 

               </thead>

                <tbody>

                @php
                $det_fields=array('rate','quantity','disc','product'); 
                @endphp

                @if(count($tabledata)>0)	
								@foreach ($tabledata as $data)
								@php
								 $data=(array)$data; 
								 $data=array_change_key_case($data,CASE_LOWER)


								@endphp
									<tr class="transactiondatarow"  id="{{$data['id']}}">
								 
									<td class='text-center'>{{$data['id']}}</td> @foreach ( $headerfields as $headerfield ) 
							
									 @php 
										if($headerfield['fld_label']=='Id'){
												continue; 
											}
										
										$headerfieldname=strtolower($headerfield['Field_Name']);

                                     
                                       

										if( $headerfield['Field_Function']==4){

                                               
                                        if(in_array($headerfield['Field_Name'],$det_fields)){
                                            $showdata= $function4filter->getFunction4FieldValueUsingId($headerfield['Field_Name'],$data[$headerfieldname],true);
									
                                        }
                                        else{
                                            $showdata= $function4filter->getFunction4FieldValueUsingId($headerfield['Field_Name'],$data[$headerfieldname]);
									
                                        }
										}
										else if( $headerfield['Field_Function']==31 || $headerfield['Field_Function']==27 || $headerfield['Field_Function']==6 )
										{
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
										}
										else{
											$showdata=$data[$headerfieldname]; 
										}
										@endphp

										@if ($headerfield['Field_Function']==4)

                                      

											@php

                                            if(in_array($headerfield['Field_Name'],$det_fields)){
                                       
										     	$showdata= $function4filter->getFunction4FieldValueUsingId($headerfield['Field_Name'],$data[$headerfieldname],true);
										
                                                }
                                                else{

                                                    $showdata= $function4filter->getFunction4FieldValueUsingId($headerfield['Field_Name'],$data[$headerfieldname]);
										
                                                }

											@endphp
											
										<td>{{ $showdata }}</td>
										@elseif ($headerfield['Field_Function']==31 || $headerfield['Field_Function']==27 || $headerfield['Field_Function']==6)
											@php
												
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
											@endphp
											
										<td>{{ $showdata }}</td>
										@elseif($headerfield['Field_Function']==5)
										@php
											$showdata=$data[$headerfieldname]; 
											@endphp
 
											<td>
 
											{{$showdata}} 
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

              @if(count($tabledata)>0)

                {{ $tabledata->links()}}

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

$("#fieldvalue_tables").change(function(){

        var tablename=$(this).val();
 
 
        $.get("{{url('/')}}/{{$companyname}}/get-transaction-table-fields/"+tablename,function(data){
 
            var result=JSON.parse(JSON.stringify(data)); 

            $(".searchfield").empty();

			// $("#divaddsearchfields").empty();

			// $("#divaddsearchfields").data('noofsearch',0);

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

var tablename= $("#fieldvalue_tables").val(); 

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

               
			var tablename=$("#fieldvalue_tables").val();;
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
         var tablename=  $("#fieldvalue_tables").val();  

         if(tablename=='' || tablename==null  ){
            alert('Please select Table');
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

		var url="{{url('/')}}/{{$companyname}}/download-salesman-report/"+format;
 
		window.open(url);

	}

	function downloadAllDocument(format){

		var url="{{url('/')}}/{{$companyname}}/download-salesman-report-all/"+format;

		window.open(url);

	}


    $("#ddn_fields_selection").on("change",function(){
 
        var selectedfield=$(this).val();

        var url="/{{$companyname}}/get-field-values-from-field-names-all-tables";

        
        //  $("#divaddsearchfields").empty();

        // $("#divaddsearchfields").data('noofsearch',0);

        initSelect2SearchTriggerChange("#fieldvalue_selection",url,'Select Field Value',null,{
            'field_name':selectedfield
        });

        

    });


    $("#fieldvalue_selection").on('change',function(){

        var fieldname=  $("#ddn_fields_selection").val();

        var fieldval=$(this).val(); 

        if(fieldval==null){
            return;
        }

        
        //  $("#divaddsearchfields").empty();

        // $("#divaddsearchfields").data('noofsearch',0);


        $("#fieldvalue_tables").empty();

        $.post("{{url('/')}}/{{$companyname}}/get-tables-from-selected-field-value-for-report",{'field_name':fieldname,'field_value':fieldval},function(data,status){

            var tables=JSON.parse(JSON.stringify(data)); 

            var selectedtable="{{$fieldvalue_table}}";

            for(let table of tables){
                var seltxt='';
                if(selectedtable==table){
                    var seltxt="selected='selected'";
                }

                $("#fieldvalue_tables").append(`<option value='`+table+`' `+seltxt+` >`+table+`</option>`);

            }

      

        });



    })


    $(function(){

            @if(!empty($field_selection))

            addSelect2SelectedOptionTriggerChange("#fieldvalue_selection","{{$fieldvalue_selection_text}}","{{$fieldvalue_selection}}");


            @endif

    });
 
</script> @endsection