@extends('layout.layout') @section('content')
<style>
		.field_full_width{width:100%;display:block;}
		 .field_half_width{width:45%;display:inline-block; }
	</style>

	
<!-- Formula Generator Modal -->
<div id="formulageneratorModal" class="modal fade" role="dialog"  >
  <div class="modal-dialog"  style="padding-top:100px;max-width:60%;margin:auto;">

    <!-- Modal content-->
    <div class="modal-content"   >
      <div class="modal-header">
	  <h4 class="modal-title">Generate Formula</h4> 
        
      </div>
      <div class="modal-body">
        <div class="row">
			<div class="col-4" style="height:300px;overflow-y:auto;">
 <dl  id="generator_table"> <dt  id="generator_table_head"></dt>  </dl>


<dl  id="generator_table_det" >  <dt  id="generator_table_det_head"> </dt>  </dl>
		
		</div>
			<div class="col-8" style="height:300px;"> 
				<textarea class="form-control"  rows="11"  id="txt_generator_string"  placeholder="Select Fields and Generate Formula Here"></textarea>
		    </div>

			<div class="col-12 text-center" >
				<input type="button"  class="btn btn-primary"  value="Add Value"  id="btn_add_generated_string" />

			</div>


		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-md"  id="btn_generate_formula_close">Close</button>
      </div>
    </div>

  </div>
</div>

<!--  -->

<h2 class="menu-title">@if(!empty($tranfield)) Edit @else Add @endif Transaction Field</h2>
<div class="pagecontent">
	<div class="container mtb-2">
		
    <a href="{{url('/')}}/{{$companyname}}/view-transaction-fields/{{$tranid}}"  class="btn btn-primary" style="float:right;" >Back</a>

    <h5 class="mtb-3">Transaction Table Name:&nbsp;&nbsp;{{$tablename}} </h5>
		<input type="hidden" id="transaction_tablename" value="{{$tablename}}"  />

		<form method="post" action="{{url('/')}}/{{$companyname}}/submittranfield"  onsubmit="return CheckDataType();"> @csrf @if(!empty($tranfield))
			<input type="hidden" name="tranfieldid" value="{{$tranfield->Field_Name}}" /> @endif
			<input type="hidden"  name="tranid"  value="{{$tranid}}" />
			<div class="row">
				<div class="form-group col-4">
					<label class="lbl_control">Field Name</label>
					<input type="text" class="form-control" id="field_name_txt" name="Field_Name" placeholder="Enter Field Name"  @if(!empty($tranfield)) value="{{$tranfield->Field_Name}}" readonly="readonly" @endif   required="true" /> </div>
				<div class="form-group col-4">
					<label class="lbl_control">Field Label</label>
					<input type="text" class="form-control" name="fld_label" placeholder="Enter Field Label"  @if(!empty($tranfield)) value="{{$tranfield->fld_label}}"  @endif  required="true" /> </div>
		
				<div class="form-group col-4">
					<label class="lbl_control">Data Type</label>
					<select class="form-control"  name="Field_Type"  id="ddnDataType" required="true"   @if(!empty($tranfield)) disabled  @endif>
						<option value="">Select Data Type</option>
						<option value="varchar"  @if(!empty($tranfield) && $tranfield->Field_Type=='varchar') selected  @endif>varchar</option>
						<option value="decimal"  @if(!empty($tranfield) && $tranfield->Field_Type=='decimal') selected  @endif >decimal</option>
						<option value="integer"   @if(!empty($tranfield) && $tranfield->Field_Type=='integer') selected  @endif >integer</option>
						<option value="datetime"   @if(!empty($tranfield) && $tranfield->Field_Type=='datetime') selected  @endif >datetime</option>
						<option value="nchar"   @if(!empty($tranfield) && $tranfield->Field_Type=='nchar') selected  @endif >nchar</option>
				 	</select>
				</div>
				<div class="form-group col-2 mtb-2">
					<label class="lbl_control">Field Size</label>
					<div>
					
				 	<input type="number" class="form-control   @if(!empty($tranfield) && $tranfield->Field_Type=='decimal') field_half_width  @endif"   name="Field_Size"  id="field_size_1"  required="true"   @if(!empty($tranfield)  && ( $tranfield->Field_Type=='integer'  || $tranfield->Field_Type=='datetime'   ) ) disabled @endif @if(!empty($tranfield)  &&   !( $tranfield->Field_Type=='integer'  || $tranfield->Field_Type=='datetime'   ) ) value="{{$tranfield->Field_Size}}"  @endif   @if(!empty($tranfield))  min="{{$tranfield->Field_Size}}" @else min="1"  @endif   />
 
					 <input type="number" class="form-control  @if(!empty($tranfield) && $tranfield->Field_Type=='decimal') field_half_width visible @else invisible @endif"   name="no_dec"  id="field_size_2"   min="1"   @if(!empty($tranfield)  && !empty($tranfield->no_dec)) value="{{trim($tranfield->no_dec)}}"  @endif    />
					</div>

				</div>

				<div class="form-group col-2 mtb-2">
					<label class="lbl_control">Display Width</label>
				 	<input type="number" class="form-control"   name="Width"   @if(!empty($tranfield)) value="{{$tranfield->Width}}"  @endif  required="true"/>
				</div>

				
				<div class="form-group col-2 mtb-2">
					<label class="lbl_control">Align</label>
				 	 <select class="form-control" name="Align">
						  <option value="Left"   @if(!empty($tranfield) && $tranfield->Align=='Left') selected="selected"  @endif>Left</option>
						  <option value="Center"   @if(!empty($tranfield) && $tranfield->Align=='Center') selected="selected"  @endif>Center</option>
						  <option value="Right"   @if(!empty($tranfield) && $tranfield->Align=='Right') selected="selected"  @endif>Right</option>
					</select>
				</div>


				
				
				<div class="form-group col-2 mtb-2">
					<label class="lbl_control">Add Type</label>
				 	 <select class="form-control" name="add_type">
						  <option value="Plus" @if(!empty($tranfield) && $tranfield->add_type=='Plus') selected="selected"  @endif>Plus</option>
						  <option value="Minus"  @if(!empty($tranfield) && $tranfield->add_type=='Minus') selected="selected"  @endif >Minus</option> 
					</select>
				</div>
				<div class="clearfix"></div>

				<div class="form-group col-3 mtb-1">
					<label class="lbl_control">Function</label>
				 	 <select class="form-control" id="ddnFieldFunctions" name="Field_Function"   @if(!empty($tranfield)) disabled  @endif>
						  @foreach ( $functions    as $functionid=>$functionval)
						  <option value="{{$functionid}}"   @if(!empty($tranfield) && $tranfield->Field_Function==$functionid)  selected="selected" @elseif( empty($tranfield) && $functionid==1) selected="selected" @endif>{{$functionval}}</option> 
						  @endforeach 

					</select>
				</div>

				
				<div id="divformulagenerator" class="form-group col-4 mtb-1    @if(!empty($tranfield) &&  $tranfield->Field_Function==11) d-block   @else d-none @endif"  style="padding-top:30px;">
					 
				<button type="button" class="btn btn-primary"  id="btn_formula_generator" >Formula Generator</button>


				</div>


				

<div class="clearfix" >&nbsp;</div>
				
				<!-- <div class="form-group col-3 mtb-1">
					<label class="lbl_control">Map Fields</label>
					<select class="form-control" name="map_fields" ></select>
                </div> -->

				<div id="divkeyfield" class="row divfunction    @if(empty($keyfields['from_table']))   d-none  @endif mtb-1">

						<div class="form-group col-3">
							<label class="lbl_control">From Table</label>
							<select class="form-control function-control" name="keyfield_fromtable" >
								<option value="">Select Table</option>
								@foreach ($trantables as $tranid=>$tranname)
								<option value="{{$tranid}}"  @if(!empty($keyfields['from_table'])  &&  $keyfields['from_table']==$tranid) selected   @endif>{{$tranname}}</option> 
								@endforeach
							
							</select>
						</div> 

						<div class="form-group col-3">
							<label class="lbl_control">Select Field</label>
							<select class="form-control  function-control" name="keyfield_selectfield" >
								@if(!empty($keyfields['table_fields']))

								@foreach($keyfields['table_fields'] as $field)
									<option value="{{$field->Field_Name}}"     @if( $keyfields['scr_field']==$field->Field_Name )  selected   @endif>{{$field->fld_label}}</option>
								@endforeach
								@endif
							</select>
						</div> 

						<div class="form-group col-3">
							<label class="lbl_control">Display Field</label>
							<select class="form-control  function-control" name="keyfield_displayfield" >
							@if(!empty($keyfields['table_fields']))

							@foreach($keyfields['table_fields'] as $field)
								<option value="{{$field->Field_Name}}"  @if( $keyfields['display_field']==$field->Field_Name )  selected   @endif>{{$field->fld_label}}</option>
							@endforeach
							@endif
							</select>
						</div> 

				</div>

				
				<div id="divautogenerate" class="row divfunction  @if(empty($autogenerate['prefix'])) d-none  @endif mtb-1">

				         <div class="form-group col-2">
							<label class="lbl_control">Prefix</label> 
							<input type="text" class="form-control function-control" name="autogenerate_prefix"  @if(!empty($autogenerate['prefix'])) value="{{$autogenerate['prefix']}}" @endif  />
						</div> 
						<div class="form-group col-2">
							<label class="lbl_control">Code</label> 
							<input type="number" class="form-control function-control" name="autogenerate_code"   @if(!empty($autogenerate['prefix'])) value="{{$autogenerate['code']}}" @endif  />
						</div> 

						<div class="form-group col-2">
							<label class="lbl_control">Suffix</label> 
							<input type="text" class="form-control function-control" name="autogenerate_suffix"    @if(!empty($autogenerate['prefix'])) value="{{$autogenerate['suffix']}}" @endif  />
						</div> 

				</div>

				<div id="divautopopulate"   class="row divfunction  @if(empty($autopopulate['from_table'])) d-none @endif mtb-1" >
					<input type="hidden"  name="autopopulate_fromtable" @if(!empty($autopopulate['from_table'])) value="{{$autopopulate['from_table']}}" @endif    />
				<div class="form-group col-3">
							<label class="lbl_control">Key Field</label>
							<select class="form-control  function-control" name="autopopulate_keyfield" >
								<option value=''>Select Key Field</option>
								@foreach ($autopopulatekeyfields as $keyid=>$keyname)
								<option value="{{$keyid}}"  @if($autopopulate['key_field']==$keyid) selected  @endif>{{$keyname}}</option>
								@endforeach
						
							</select>
						</div> 
						<div class="form-group col-3">
							<label class="lbl_control">Mapping Field</label>
							<select class="form-control  function-control" name="autopopulate_mapfield" >
							@if(!empty($autopopulate['from_table']))
							  @foreach($autopopulate['from_table_fields'] as $mapfieldid=>$mapfieldval)
							  <option value="{{$mapfieldid}}"   @if(trim($autopopulate['mapping_field'])==$mapfieldid)  selected @endif>{{$mapfieldval}}</option>
							  @endforeach

							@endif
							</select>
						</div>  

				</div>



				
				<div id="divautopopulatefromheader"   class="row divfunction  @if(empty($autopopulatefromheader['from_table'])) d-none @endif mtb-1" >
					<input type="hidden"  name="autopopulatefromheader_fromtable"    @if(!empty($autopopulatefromheader['from_table'])) value="{{$autopopulatefromheader['from_table']}}"  @endif  />
				 <div class="form-group col-3">
							<label class="lbl_control">Key Field</label>
							<select class="form-control  function-control" name="autopopulatefromheader_keyfield" >
								<option value=''>Select Key Field</option>
								@foreach ($autopopulatefromheaderkeyfields as $keyid=>$keyname)
								<option value="{{$keyid}}"  @if($autopopulatefromheader['scr_field']==$keyid) selected  @endif>{{$keyname}}</option>
								@endforeach
						
							</select>
						</div> 
						<div class="form-group col-3">
							<label class="lbl_control">Mapping Field</label>
							<select class="form-control  function-control" name="autopopulatefromheader_mapfield" >
							@if(!empty($autopopulatefromheader['from_table']))
							  @foreach($autopopulatefromheader['from_table_fields'] as $mapfieldid=>$mapfieldval)
							  <option value="{{$mapfieldid}}"   @if(trim($autopopulatefromheader['mapping_field'])==$mapfieldid)  selected @endif>{{$mapfieldval}}</option>
							  @endforeach

							@endif
							</select>
						</div>  

				</div>

 


				

				<div class="form-group col-6 mtb-1">
					<label class="lbl_control">Value</label> 

					<textarea class="form-control" cols="10" rows="2" name="Field_Value"  id="value_txt">@if(!empty($tranfield) ){{$tranfield->Field_Value}}@endif</textarea>
				  </div>

				<div class="form-group col-3 mtb-1" style="padding-top:30px;">
					<label class="checkbox-inline lbl_control"><input type="checkbox" name="get_tot" value="yes" @if(!empty($tranfield) && trim($tranfield->get_tot)=='True' )  checked="checked"  @endif />&nbsp;Get Totals</label> 
				</div>
				<div class="clearfix">&nbsp;</div>

				<div class="form-group col-2 ">
					<label class="lbl_control">Tab Id</label>  
					<select class="form-control" name="Tab_Id">
					@if(!empty($tranfield) && $tranfield->Tab_Id=='None') 
					<option @if(!empty($tranfield) && $tranfield->Tab_Id=='None') selected="selected"  @endif value="None">None</option>
				 	@else
					 <option @if(!empty($tranfield) && $tranfield->Tab_Id=='Header') selected="selected"  @endif value="Header">Header</option>
						<option @if(!empty($tranfield) && $tranfield->Tab_Id=='Pricing') selected="selected"  @endif value="Pricing">Pricing</option>
					
					@endif
					</select>
                </div>

				<div class="form-group col-2 ">
					<label class="lbl_control">Tab Seq</label> 
					@php 
					if(!empty($tranfield)){
						$tabsequence=$tranfield->{'Tab Seq'} ;
					
					}
				
					@endphp

					<input type="number" class="form-control" name="Tab Seq"  @if(!empty($tranfield))  value="{{$tabsequence}}"  @endif/>
                </div>

				<div class="form-group col-2">
					<label class="lbl_control">Fld Order</label> 
					<input type="number" class="form-control"  />
                </div>
 
				
				<div class="form-group col-2">
					<label class="lbl_control">Label Width</label> 
					<input type="number" class="form-control" name="lbl_width"   @if(!empty($tranfield))  value="{{$tranfield->lbl_width}}"  @endif />
                </div>

				<div class="form-group col-2">
					<label class="lbl_control">Is Primary</label>  
					<select name="Is Primary" class="form-control">
						<option  @if(!empty($tranfield) && $tranfield->{'Is Primary'}=='False' ) selected="selected" @endif value="False">False</option>
						<option value="True"   @if(!empty($tranfield) && $tranfield->{'Is Primary'}=='True' ) selected="selected" @endif>True</option></select>
                </div>

				<div class="clearfix"></div>

				<div class="form-group col-2">
					<label class="lbl_control">Allow Null</label> 
				 
 
					 <select class="form-control"  name="Allow Null"  @if(!empty($tranfield)  && trim($tranfield->{'Allow Null'})=='True' ) disabled @endif>
						 <option value="True"  @if(!empty($tranfield) && trim($tranfield->{'Allow Null'})=='True') selected="selected"  @endif>True</option>
						 <option value="False"  @if(!empty($tranfield) && trim($tranfield->{'Allow Null'})=='False') selected="selected"  @endif >False</option>
					</select>
                </div>

				<div class="form-group col-2">
					<label class="lbl_control">Searchable</label> 
					 <select class="form-control"  name="Searchable">
						 <option value="True"  @if(!empty($tranfield) && trim($tranfield->Searchable)=='True') selected="selected"  @endif >True</option>
						 <option value="False" @if(!empty($tranfield) && trim($tranfield->Searchable)=='False') selected="selected"  @endif >False</option>
					</select>
                </div>

				<div class="form-group col-2">
					<label class="lbl_control">Unique</label> 
					 <select class="form-control" name="fld_unique">
					 <option value="False"   @if(!empty($tranfield) && trim($tranfield->fld_unique)=='False') selected="selected"  @endif  >False</option>
					  <option value="True"   @if(!empty($tranfield) && trim($tranfield->fld_unique)=='True') selected="selected"  @endif  >True</option>
				</select>
                </div>


				<div class="form-group col-2">
					<label class="lbl_control">Post Back</label> 
					 <select class="form-control" name="fld_post">
					 <option value="False"   @if(!empty($tranfield) && trim($tranfield->fld_post)=='False') selected="selected"  @endif   >False</option>
				     <option value="True"   @if(!empty($tranfield) && trim($tranfield->fld_post)=='True') selected="selected"  @endif  >True</option>
					</select>
                </div>


				<div class="form-group col-2">
					<label class="lbl_control">Read Only</label> 
					 <select class="form-control" name="rd_only" >
					 <option value="False"    @if(!empty($tranfield) && trim($tranfield->rd_only)=='False') selected="selected"  @endif >False</option>
					 <option value="True"   @if(!empty($tranfield) && trim($tranfield->rd_only)=='True') selected="selected"  @endif  >True</option>
				</select>
                </div>

				<div class="form-group col-2">
					<label class="lbl_control">Multiline</label> 
					 <select class="form-control" name="mul_line">
					 <option value="False"   @if(!empty($tranfield) && trim($tranfield->mul_line)=='False') selected="selected"  @endif  >False</option>
				     <option value="True"    @if(!empty($tranfield) && trim($tranfield->mul_line)=='True') selected="selected"  @endif   >True</option>
					</select>
                </div>


				<div class="form-group col-2">
					<label class="checkbox-inline lbl_control"><input type="checkbox"  />&nbsp;Add / Deduct</label> 
				 
                </div>
 
				 <div class="form-group col-12 mtb-4 text-center">
					<input type="submit" name="btn_submit" value="Submit" class="btn btn-primary onsubmit" /> &nbsp;&nbsp;
					<input type="button" name="btn_cancel" value="Cancel" class="btn btn-primary"  id="btn_cancel_reload" /> </div>
			</div>
		</form>
	</div>
</div> @endsection @section('js') {{-- ROLE --}}
<script type="text/javascript">

	$("#ddnDataType").change(function(){
		var datatype=$(this).val();

		$("#field_size_1").attr("disabled",false);

		if(datatype=='varchar' || datatype=='nchar' ){

			$("#field_size_1").prop("required",true);
			$("#field_size_2").prop("required",false);

		}
		else if(datatype=='decimal'){
		
			$("#field_size_1").prop("required",true);
			$("#field_size_2").prop("required",true);
		}
		else if(datatype=='integer' || datatype=='datetime'  ){ 
			$("#field_size_1").attr("disabled",true);
			$("#field_size_1").prop("required",false);
			$("#field_size_2").prop("required",false);
		}

		if(datatype=='decimal'){

			$("#field_size_1").addClass("field_half_width");
			
			$("#field_size_2").addClass("field_half_width");
			$("#field_size_2").removeClass("invisible");

		}
		else{
			
			$("#field_size_1").removeClass("field_half_width");

			$("#field_size_1").addClass("field_full_width");
			
			$("#field_size_2").removeClass("field_half_width");
			$("#field_size_2").addClass("invisible");

		}

		$("#field_size_1").val("");
		
		$("#field_size_2").val("");


	});




	$(function(){

		$("#field_name_txt").on('keypress', function(e) {
			
            if (e.which == 32){ 
                return false;
            }
        });


		$("#generator_table,#generator_table_det").on("click",".link_add_field",function(){

			var tablename=$(this).data("tablename");

			var fieldname=$(this).data("fieldname");

			var textstring= $("#txt_generator_string").val();

			textstring=textstring+fieldname+'_IS_'+tablename;

			$("#txt_generator_string").val(textstring); 

		})


	 

 


	})


	$("#ddnFieldFunctions").change(function(){
		var fieldfunction=parseInt($(this).val());

		$(".divfunction").addClass("d-none"); 
		$("#divformulagenerator").addClass("d-none");

		$(".function-control").prop("required",false);

		if(fieldfunction==4 || fieldfunction==33 ||  fieldfunction==35){

			$("#divkeyfield").removeClass("d-none");
			$("#divkeyfield select").prop("required",true);

		}
		else if(fieldfunction==5){

			$("#divautogenerate").removeClass("d-none");
			$("#divautogenerate input").prop("required",true);

		}
		else if(fieldfunction==11){
			$("#divformulagenerator").removeClass("d-none");
		}
		else if(fieldfunction==3 || fieldfunction==30){
			$("#divautopopulate").removeClass("d-none");  
			$("#divautopopulate select").prop("required",true);
		}
		else if(fieldfunction==24){
			$("#divautopopulatefromheader").removeClass("d-none");  
			$("#divautopopulatefromheader select").prop("required",true);
		}

		var mandatory=[2,19,30]; 

		if(mandatory.includes(fieldfunction)){
			$("#value_txt").prop('required',true);
		}
		else{
			$("#value_txt").prop('required',false);
		}


	});
 

 function CheckDataType(){

	var selecteddatatype=$("#ddnDataType").val();

	var datetimefunction=[6,27,31];

	var fieldfunction=parseInt($("#ddnFieldFunctions").val());

	 

	if(datetimefunction.includes(fieldfunction)){

		if(selecteddatatype!=="datetime"){
 
		    alert("Please select Datetime Data Type , Data Type does not match with Function Selected ");

			var cnf=confirm("Are you sure to submit ?");
 
            return cnf;

		} 

	}
	else{
		return true;
	}
  
 
 }



 $("select[name='keyfield_fromtable']").change(function(){

	 var tablename=$(this).val(); 

	 var url='{{url('/')}}/{{$companyname}}/design-sms-format-field/'+tablename;

	 $.get(url,function(data,status){

		var fields=JSON.parse( data); 

		$("select[name='keyfield_selectfield']").empty();
		$("select[name='keyfield_displayfield']").empty();

		for(let field of fields){
 

			$("select[name='keyfield_selectfield']").append("<option value='"+field.Field_Name+"'>"+field.fld_label+"</option>");
			$("select[name='keyfield_displayfield']").append("<option value='"+field.Field_Name+"'>"+field.fld_label+"</option>");

		} 

	 });
 


	//  alert(tablename);

 });


 $("#btn_formula_generator").click(function(){
	 $("#formulageneratorModal").modal("show");

	 var tablename=$("#transaction_tablename").val();
      var tabledet=tablename+'_det';
	 $.get('{{url('/')}}/{{$companyname}}/design-sms-format-field/'+tablename,function(data,status){

		var fields=JSON.parse(data);

		$("#generator_table dd").remove();

		if(fields.length>0){
			$("#generator_table_head").html(tablename);

			for(let field of fields){

				$("#generator_table").append("<dd class='table_field_def'><a href='javascript:void(0);' class='link_add_field' data-tablename='"+tablename+"' data-fieldname='"+field['Field_Name']+"'>"+field['fld_label']+"</a></dd>");

			}
		} 

	 });


	 $.get('/{{$companyname}}/design-sms-format-field/'+tabledet,function(data,status){

var fields=JSON.parse(data);

$("#generator_table_det dd").remove();

if(fields.length>0){
	$("#generator_table_det_head").html(tabledet);

	for(let field of fields){

		$("#generator_table_det").append("<dd class='table_field_def'><a href='javascript:void(0);' class='link_add_field' data-tablename='"+tabledet+"' data-fieldname='"+field['Field_Name']+"'>"+field['fld_label']+"</a></dd>");

	}
} 

});
 

 });

 $("#btn_generate_formula_close").click(function(){
	 $("#formulageneratorModal").modal("hide");
 });


 $("#btn_add_generated_string").click(function(){
	 var text=$("#txt_generator_string").val(); 
	 $("#value_txt").val(text);
     $("#formulageneratorModal").modal("hide");
 });


 $("select[name='autopopulate_keyfield']").change(function(){

	
	 var keyfield=$(this).val();
	var tablename=$("#transaction_tablename").val();  

	$.post('/{{$companyname}}/get-autopopulate-mapping-fields',{'tablename':tablename,'keyfield':keyfield,'fromheader':0},function(data,status){

		var resultarray=JSON.parse(JSON.stringify(data));
		$("input[name='autopopulate_fromtable']").val(resultarray['fromtable']);

		var mappingfields= 	resultarray['mappingfields'];

		$("select[name='autopopulate_mapfield']").empty();

		for(let mapfield in mappingfields){
			$("select[name='autopopulate_mapfield']").append("<option value='"+mapfield+"'>"+mappingfields[mapfield]+"</option>");
		}
 
 

	})

 });



 
 $("select[name='autopopulatefromheader_keyfield']").change(function(){

	
var keyfield=$(this).val();
var tablename=$("#transaction_tablename").val();


alert(tablename+' '+keyfield);


$.post('/{{$companyname}}/get-autopopulate-mapping-fields',{'tablename':tablename,'keyfield':keyfield,'fromheader':1},function(data,status){

   var resultarray=JSON.parse(JSON.stringify(data));
   $("input[name='autopopulatefromheader_fromtable']").val(resultarray['fromtable']);

   var mappingfields= 	resultarray['mappingfields'];

   $("select[name='autopopulatefromheader_mapfield']").empty();

   for(let mapfield in mappingfields){

	   $("select[name='autopopulatefromheader_mapfield']").append("<option value='"+mapfield+"'>"+mappingfields[mapfield]+"</option>");
   }



})

});



</script> @endsection