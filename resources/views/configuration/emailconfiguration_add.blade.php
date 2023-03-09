@extends('layout.layout')
@section('content')

@php
 
if(count($emailconfigs)>0){

    $edittablename=$emailconfigs[0]['table_name'];
    $printtemplateid=$emailconfigs[0]['print_temp'];
    $whatsapptemplateid=$emailconfigs[0]['whatsapp_template_id'];
    $sendmail=$emailconfigs[0]['send_mail'];
    $emailconfname=$emailconfigs[0]['email_configuration_name'];

    $sendsales=$emailconfigs[0]['send_exec'];

    $sendcust=$emailconfigs[0]['send_cust'];

    $is_manual=$emailconfigs[0]['is_manual'];

    $email_subject=$emailconfigs[0]['email_subject'];
 
    $email_body=$emailconfigs[0]['email_body'];


    $emails_given=$emailconfigs[0]['Email'];

    $whatsapp_given=$emailconfigs[0]['whatsapp_no'];

    $conditions_query=$emailconfigs[0]['conditions_query'];


}
 

@endphp
 
<h2  class="menu-title">Email Configuration </h2>
  <div class="pagecontent"  >
      
    <div class="container-fluid mtb-2">
        <form method="post" action="/{{$companyname}}/submitemailconfiguration">
            @csrf
            <input type="hidden" name="id" value="{{$id}}" />
        <div class="row">

        
        <div class="col-2 text-end"><label class="lbl_control">Email Conf. Name:</label></div>
            <div class="col-4 text-start"> 
        <input type='text' name='email_conf_name'  class='form-control '  value="@if(!empty( $emailconfname)){{$emailconfname}}@endif"/>
        </div>



            <div class="col-2 text-end"><label class="lbl_control">Select Transaction:</label></div>
            <!-- select-configure -->
            <div class="col-4 text-start"><select class="form-control " id="ddnTransactions" name="transaction">
            @if(!isset($edittablename))
                <option value="">Select Transaction  </option>
                @endif

       
                @foreach($transactions as $transactionid=>$transactiontable)
                @if(isset($edittablename)) 
                    @if($edittablename==$transactionid)

                    <option value="{{$transactionid}}"   >{{$transactiontable}}</option>

                    @endif

                @else
                <option value="{{$transactionid}}"   >{{$transactiontable}}</option>
               
                @endif
                
                @endforeach
            </select></div>


            <div class="col-2 text-end"><label  class="lbl_control">Print Template:</label></div>
            <div class="col-4 text-start"><select class="form-control select-configure" id="ddnPrintTemplate" name="printtemplate"><option value="">Select Print Header</option>
        @foreach($printtemplates as $printtemplate)
        <option value="{{$printtemplate->Tempid}}"  @if(isset($printtemplateid)  && $printtemplateid==$printtemplate->Tempid) selected="selected"   @endif>{{$printtemplate->TempName}}</option>

        @endforeach
        </select></div>

        
        <div class="col-2 text-end"><label  class="lbl_control">Enter Emails:</label></div>
            <div class="col-4 text-start">
                <input type='text' class="form-control select-configure" name="enter_emails"  value="@if(isset($emails_given)){{ $emails_given}}@endif"/>
        </div>


        
        <div class="col-2 text-end"><label  class="lbl_control">Whatsapp Template:</label></div>
            <div class="col-4 text-start"><select class="form-control select-configure" id="ddnWhatsappTemplate" name="whatsapptemplate">
                <option value="">Select Whatsapp Template</option>
                <!-- @if(isset($whatsapptemplate)  && $whatapptemplateid==$whatsapptemplate->tempid) selected="selected"   @endif -->
                @foreach($whatsapptemplates as $whatsapptemplate)
                
                    <option value="{{$whatsapptemplate->tempid}}"    @if(isset($whatsapptemplateid)  && $whatsapptemplateid==$whatsapptemplate->tempid) selected="selected" @endif >{{$whatsapptemplate->tempname}}</option>
               @endforeach

            </select></div>

            <div class="col-2 text-end"><label  class="lbl_control">Enter Whatsapp No.:</label></div>
            <div class="col-4 text-start">
                <input type='text' class="form-control select-configure" name="enter_whatsapp_no" value="@if(isset($whatsapp_given)){{$whatsapp_given}}@endif"/>
        </div>



        <div class="col-2 text-end"><label class="lbl_control">Enter Email Subject:</label></div>
        <div class="col-4 text-start"> 
            <input type='text' name='email_subject' class='form-control'  required  value="@if(isset($email_subject)){{$email_subject}}@endif" />
        </div>
       

        <div class="col-2 text-end"><label class="lbl_control">Enter Email Body (Html) :</label></div>
        <div class="col-4 text-start">
            <textarea rows="5" cols="10" class="form-control" name="email_body" required>@if(isset($email_body)){{$email_body}}@endif</textarea>
        </div>

        <div class="col-2 text-end"><label class="lbl_control">Enter Conditions Query:</label></div>
        <div class="col-4 text-start">
        <!-- required -->
            <textarea rows="5" cols="10" class="form-control" name="conditions_query" >@if(isset($conditions_query)){{$conditions_query}}@endif</textarea>
        </div>

 
            <div class="col-6 text-start"  style="padding-top:30px;"> 
            
            <label  class="lbl_control mlr-1"><input type="checkbox"  name="manual" value="1"   @if(isset($is_manual) && $is_manual==1) checked @endif/>&nbsp;  Manual</label>
            
                
            <label  class="lbl_control mlr-1"><input type="checkbox"  name="send_email" value="1"   @if(isset($sendmail) && trim($sendmail)=="True") checked @endif/>&nbsp;Send Email</label>
            
            <label  class="lbl_control  mlr-1"><input type="checkbox"  name="send_salesman"  value="1"  @if(isset($sendsales) && trim($sendsales)=="True") checked @endif  />&nbsp;Send Salesman (salesman)</label>

            
            <label  class="lbl_control  mlr-1"><input type="checkbox"  name="send_customer"  value="1"   @if(isset($sendcust) && trim($sendcust)=="True") checked @endif    />&nbsp;Send Customer (cust_id)</label>
        
        </div>


            <div class="row mtb-2">

            <div class="col-md-6  mx-auto  card ">
					<div class="card-body">
                        <input type="button" class="btn btn-primary" id="btn_add_customfields" data-noofcustomfields="0" value="Add Another Custom Field" />
						<div class="table-responsive">
                            <table class="table table-striped" >
                                <thead><th>Whatsapp Custom Field Id</th>  <th>Whatsapp Custom Field Name</th><th>Delete</th></thead>
                                <tbody id="tbody_customfields"> 
                                    @foreach ($whatsapp_custom_fields as $whatsapp_custom_field )

                                    <tr >
                                    <td><select class='form-control' name='whatsapp_custom_field_id[]' required>
                                        <option value=''>Select Custom Field</option>
                                        @foreach ($custom_fields as $custom_field)
                                            <option value="{{$custom_field['id']}}" @if($whatsapp_custom_field['field_id']==$custom_field['id']) selected @endif>{{$custom_field['name']}}</option>
                                        @endforeach
                                    </select></td>
                                    <td><input type='text' class='form-control'  name='whatsapp_custom_field_name[]'  value="{{$whatsapp_custom_field['field_name']}}" required/></td>
                                    <td><a href='javascript:void(0);' class='link_delete_customfield' data-noofcustomfield="{{$noofcustomfield}}"><i class="bi bi-trash"></i></a></td>
                                </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                        </div>

                        <div class="col-12 text-center"  style=' margin-top:20px;'>
                <input type="submit" class="btn btn-primary btn-md" value="Submit" />
            </div>

            </div>
            @if(count($emailconfigs)==0) @endif
        <div class="row" id="divoftransactions"   style="display:none;"  >
            <div class="mx-auto  mtb-3 " style="width:90%;">
            <div class="clearfix">
                <input type="button" class="btn btn-primary btn-sm btn_float_right" value="Add Another" id="btn_add_another" />
                    </div>
                <div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
            <table class="table table-striped" id="datatable"  >
                <thead><tr><th style="width:15%">Field</th><th class="text-center" style="width:10%;">Condition</th><th style="width:20%;" class="text-center">Value</th><th style="width:6%;" class="text-center">Conj</th><th  class="text-center"  style="width:15%">Email</th><th  class="text-center"  style="width:15%">Whatsapp No.</th><th class="text-center" style="width:10%;">Delete</th></tr></thead>
                <tbody id="tbodyemailconfiguration" data-noofrows="{{count($emailconfigs)}}">

                @if(count($emailconfigs)>0)
                @php
                $noofrow=1;

                @endphp

                @foreach($emailconfigs as $emailconfig)
            <tr data-row="{{$noofrow}}">
            <td><select class="form-control fields" name="field[]" data-row="{{$noofrow}}"  >
            <option value=""> Select Field</option>
            @foreach($fields as $field)
            <option value="{{$field['name']}}"  @if($emailconfig['field_name']==$field['name']) selected="selected" @endif>{{$field['label']}}</option>
            @endforeach
            </select></td>
            <td><select class="form-control" name="condition[]" class="conditions">
            <option   value="="  @if($emailconfig['cond']=='=') selected="selected" @endif  >=</option>
            <option value="<"  @if($emailconfig['cond']=='<') selected="selected" @endif  ><</option>
            <option value=">"  @if($emailconfig['cond']=='>') selected="selected" @endif   >></option>
            <option value="<>"  @if($emailconfig['cond']=='<>') selected="selected" @endif   ><></option>
            <option value="starts_with"  @if($emailconfig['cond']=='starts_with') selected="selected" @endif   >Starts With</option>
            <option value="ends_with"    @if($emailconfig['cond']=='ends_with') selected="selected" @endif  >Ends With</option>
            <option value="contains"    @if($emailconfig['cond']=='contains') selected="selected" @endif   >Contains</option> 
            <option value="like"      @if($emailconfig['cond']=='like') selected="selected" @endif   >Like</option>
            <option value="notlike"      @if($emailconfig['cond']=='notlike') selected="selected" @endif  >Not Like</option>
            </select></td>
            <td><input type="text" class="form-control"  name="value[]" class="values"   value="{{$emailconfig['cond_val']}}"  /></td>
            <td><select class="form-control conjs" name="conj[]"     >
            <option value="Null"  @if($emailconfig['conj']=='Null') selected="selected" @endif>Null</option>
            <option value="And"   @if($emailconfig['conj']=='And') selected="selected" @endif >And</option>
            <option value="Or"    @if($emailconfig['conj']=='Or') selected="selected" @endif >Or</option>
            </select></td>
            <td><input type="text" name="email[]" class="emails form-control"  value="{{$emailconfig['Email']}}"   /> </td>
            <td><input type="text" name="whatsapp[]" class="emails form-control"  value="{{$emailconfig['whatsapp_no']}}"   /> </td>
            <td class="text-center"><a class="lnk_delete_row" href="javascript:void(0);"   ><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td>

            </tr>
            @php
            $noofrow++;
            @endphp
            @endforeach

            @endif
 
                </tbody>
            
            </table>
</div>
</div>
</div>
            <div class="col-12 text-center" style="margin-top:30px;">
                <input type="submit" class="btn btn-primary btn-md" value="Submit" />
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
   
    <script type="text/javascript">

        $("#ddnTransactions").change(function(){

            var trantable=$(this).val();

            var url='/'+'{{$companyname}}'+'/get-transaction-print-templates';
            $.post(url,{'tran_table':trantable},function(data,status){

                var result=JSON.parse(JSON.stringify(data));
                $("#ddnPrintTemplate option:not(:first)"). remove();

                for(let printtemplate of result['printtemplates']){

                    $("#ddnPrintTemplate").append("<option value='"+printtemplate['Tempid']+"'>"+printtemplate['TempName']+"</option>");
                } 

                $("#ddnWhatsappTemplate option:not(:first)"). remove();

                for(let whatsapptemplate of result['whatsapptemplates']){
                    $("#ddnWhatsappTemplate").append("<option value='"+whatsapptemplate['tempid']+"'>"+whatsapptemplate['tempname']+"</option>");
                }

                


                });

      

        });

        function addAnotherRow(noofrows){

            var trantable=$("#ddnTransactions").val();

           var url='/'+'{{$companyname}}'+'/get-emailconfiguration-another-row';

           var conj_val=$(".conjs").val();

           if(conj_val=='Null'){

            alert("You cannot add more than 1 row in case of Conj=Null");

            return false;
           }
 
           $.post(url,{'tran_table':trantable ,'no_of_row':noofrows,'conj':conj_val},function(data,status){
               $("#tbodyemailconfiguration").append(data); 

           }); 
           $("#tbodyemailconfiguration").data("noofrows", noofrows);  

      
         
            
        }

        $("#btn_add_another").click(function(){

            var noofrows= $("#tbodyemailconfiguration").data("noofrows");
           noofrows= noofrows+1;
           addAnotherRow(noofrows);

        });


        

        $("#tbodyemailconfiguration").on("click",".lnk_delete_row",function(){

            var noofrows= $("#tbodyemailconfiguration").data("noofrows");
             noofrows= noofrows-1;

            $(this).parent().parent().remove();
            $("#tbodyemailconfiguration").data("noofrows",   noofrows);
        });

        $("#ddnPrintTemplate").change(function(){

            // var trantable= $("#ddnTransactions").val();
            // var printheader=  $("#ddnPrintTemplate").val();

            // if(trantable!='' && printheader!=''){
            //     $("#divoftransactions").css("display","block");

            //     @if(count($emailconfigs)==0)
            //     addAnotherRow(1);
            //     @endif 
  
            // }
            // else{
            //     $("#divoftransactions").css("display","none");
            // }
 
        });



        $("#btn_add_customfields").click(function(){

            var noofcustomfields=$(this).data('noofcustomfields');


            noofcustomfields=noofcustomfields+1;


            $.get("{{url('/')}}/{{$companyname}}/add-another-whatsapp-custom-field/"+noofcustomfields,function(data,status){

                var result= JSON.parse(JSON.stringify(data));

                $("#tbody_customfields").append(result['html']);

            });



        });

        $("#tbody_customfields").on('click','.link_delete_customfield',function(){
       
            $(this).parent().parent().remove();
      

        });
 

 

    </script>
     
 
@endsection
