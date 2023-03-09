@extends('layout.layout')
@section('content')
 
<h2  class="menu-title">Field Conditions</h2>
  <div class="pagecontent"  >
    <form method="POST"   action="/{{$companyname}}/save-tran-field-values">
        @csrf
        <div class="row mtb-3">
            <div class="col-4 text-end"><label class="lbl_control">Select Transactions:</label></div><div class="col-4 text-start"><select class="form-control select-configure" name="transaction" id="ddntransactions">
                <option value="">Select Transaction</option>
                @foreach($transactions as $tranid=>$tranname)
                <option value="{{$tranid}}">{{$tranname}}</option>
                @endforeach
            </select></div>


            <div id="divfields" class="col-10 mx-auto mtb-3 text-center invisible "  >
            <input type="button" class="btn btn-primary btn-sm mtb-2" value="Add Another" id="btn_add_another" data-row="1" style="float:right;"  />
           
           
           
            <div class="card mtb-2" style="width:100%;">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
            <table  class="table table-striped" style="width:100%;">
                <thead><th class="text-center" style="width:20%;">Field Name</th><th  style="width:10%;" class="text-center">Condition</th><th  style="width:20%;"  class="text-center">Value</th><th  style="width:20%;" class="text-center">Restrict Field</th><th  style="width:20%;"  class="text-center">Restrict Value</th><th  style="width:10%;" class="text-center">Delete</th></thead>
                <tbody id="tbodytranfieldvalues">
                  
                    <tr><td><select class="form-control fieldnames"  data-row="1" name="field_name[]"  required="true" ><option value="">Select Field</option></select></td>
                    <td><select   class="form-control condition text-center" name="condition[]" data-row="1" required="true" >
                        <option   value="=">=</option>
                        <option value="<"><</option>
                        <option value=">">></option>
                        <option value="<>"><></option>
                        <option value="starts_with">Starts With</option>
                        <option value="ends_with">Ends With</option>
                        <option value="contains">Contains</option>
                        <option value="between">Between</option>
                        <option value="like">Like</option>
                        <option value="notlike">Not Like</option>
                    </select></td>
                    
                    <td><select class="form-control select2 values"  data-row="1"   name="value[]" required="true"></select></td><td><select class="form-control restrictfields" data-row="1" id="restrict_field_1" name="restrict_field[]"   ><option value="">Select Restrict Field</option></select></td><td><select class="form-control restrictvalues select2" id="restrict_value_1" data-row="1" name="restrict_value[]"  ></select></td><td class="text-center"><a class="lnk_delete_row" href="javascript:void(0);" data-row="1"><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td></tr>


                </tbody>
            </table> 
</div>
</div>
</div>

            <input type="submit" class="btn btn-primary btn-md" value="Submit" />

                </div>




            </div> 
        </form>

    
    </div>

 
@endsection
@section('js')
    {{-- ROLE --}}


    <script type="text/javascript">

       $("#ddntransactions").change(function(){
           var transaction=$(this).val();

           if(transaction!=''){
               $("#divfields").removeClass("invisible");
               var url='/'+'{{$companyname}}'+'/get-transaction-fields';

               $.post(url,{'tran_table':transaction},function(data,status){

                var resultarray=JSON.parse(JSON.stringify(data));

                var fields=resultarray['fields'];

                $("#tbodytranfieldvalues").empty();
               if(resultarray['fieldconditionhtml']=='not found'){
                     addAnotherTransactionRow(1,transaction)

               }
               else{

                   $("#tbodytranfieldvalues").html(resultarray['fieldconditionhtml']);
                   var conditionfields=resultarray['conditionfields'];
                   var fieldconditions=resultarray['fieldconditions'];
 
                   var noofrows=conditionfields.length; 
                   $("#btn_add_another").data("row",noofrows); 

                   $(".fieldnames").select2({placeholder:'Select Field' , allowClear:true});
                   $(".restrictfields").select2({placeholder:'Select Restrict Field', allowClear:true}); 
                 
                      var valueurl='/'+'{{$companyname}}'+'/get-transaction-field-values';


                    var index=1;
                   for(let conditionfield of conditionfields){  

                        var fieldvalueid=conditionfield['field_value'];
                        var fieldvaluelabel=fieldconditions[index-1]['field_value_label'];

                        var restvalueid=conditionfield['rest_value'];
                        var restvaluelabel=fieldconditions[index-1]['rest_value_label'];

                        var fieldname=conditionfield['field_name']; 

                        var restfield=conditionfield['rest_field'];  

                        initSelect2Search("#tbodytranfieldvalues  .values[data-row='"+index+"']",valueurl,"Select Field Value",null,{'fieldname':fieldname,'tablename':transaction});
                    
                        $("#tbodytranfieldvalues  .values[data-row='"+index+"']").append(`<option value='${fieldvalueid}' selected>${fieldvaluelabel}</option>`);
                        $("#tbodytranfieldvalues  .values[data-row='"+index+"']").trigger('change');

                        initSelect2Search("#tbodytranfieldvalues  .restrictvalues[data-row='"+index+"']",valueurl,"Select Restrict Value",null,{'fieldname':restfield,'tablename':transaction});

                        if(restvalueid!=null){
                         $("#tbodytranfieldvalues  .restrictvalues[data-row='"+index+"']").append(`<option value='${restvalueid}' selected>${restvaluelabel}</option>`);
                        $("#tbodytranfieldvalues  .restrictvalues[data-row='"+index+"']").trigger('change');

                        }
                      

                        index++;

                   }

               }




 
                // $('.fieldnames option:not(:first)').remove();

                
                // $('.restrictfields option:not(:first)').remove();
                
                // for(let field of fields){
                //     $(".fieldnames[data-row='1']").append("<option value='"+field['name']+"'>"+field['label']+"</option>");
                //     $(".restrictfields[data-row='1']").append("<option value='"+field['name']+"'>"+field['label']+"</option>"); 
                // }
 
                // $(".fieldnames").select2({placeholder:'Select Field' , allowClear:true});
                // $(".restrictfields").select2({placeholder:'Select Restrict Field', allowClear:true});
                // $(".values").select2({placeholder:'Select Values', allowClear:true});
                // $(".restrictvalues").select2({placeholder:'Select Restrict Values', allowClear:true});
                // $(".condition").select2({placeholder:'Select Condition', allowClear:true});
               })
 

           }
           else{
             
            $("#divfields").addClass("invisible");
         

           }


       });

 
       $('#tbodytranfieldvalues').on("change",".fieldnames",function(){  
           var fieldname=$(this).val();

           var row=$(this).data("row"); 

           var url='/'+'{{$companyname}}'+'/get-transaction-field-values';

           var trantable=$("#ddntransactions").val();

           $(".values[data-row='"+row+"']").select2("destroy");
           $(".values[data-row='"+row+"']").empty();
           initSelect2Search("#tbodytranfieldvalues  .values[data-row='"+row+"']",url,"Select Value",null,{'fieldname':fieldname,'tablename':trantable});
  
       });
 
        $("#tbodytranfieldvalues").on("change",".restrictfields",function(){

            var fieldname=$(this).val();

            var row=$(this).data("row"); 

            var url='/'+'{{$companyname}}'+'/get-transaction-field-values';

            var trantable=$("#ddntransactions").val();

            $(".restrictvalues[data-row='"+row+"']").select2("destroy");

            $(".restrictvalues[data-row='"+row+"']").empty();


            initSelect2Search("#tbodytranfieldvalues .restrictvalues[data-row='"+row+"']",url,"Select Restrict Value",null,{'fieldname':fieldname,'tablename':trantable});
   
       });


       function addAnotherTransactionRow(row,transaction){
        var url='/{{$companyname}}/get-field-condition-new-row';
        $.post(url, {"row":row,"transaction":transaction},function(data,status){ 
            $("#tbodytranfieldvalues").append(data); 
            $("#btn_add_another").data("row",row); 
            

            $(".fieldnames[data-row='"+row+"']").select2({placeholder:'Select Field',  allowClear:true});

            $(".condition[data-row='"+row+"']").select2({placeholder:'Select Condittion' , allowClear:true});

            $(".restrictfields[data-row='"+row+"']").select2({placeholder:'Select Restrict Field' , allowClear:true});
 
           $(".values[data-row='"+row+"']").select2({placeholder:'Select Value' , allowClear:true});
           
           $(".restrictvalues[data-row='"+row+"']").select2({placeholder:'Select Restrict Value' , allowClear:true});
           sleep(500); 
        }); 
 

       }


       $("#btn_add_another").click(function(){

        var row=$(this).data("row");
        row=row+1; 
        var transaction=    $("#ddntransactions").val();

        addAnotherTransactionRow(row,transaction); 

       });

       $("#tbodytranfieldvalues").on("click",".lnk_delete_row",function(){
                var row=$(this).data("row");
                 
                $(this).parent().parent().remove();
 
       });

 
    </script>
     
 
@endsection
