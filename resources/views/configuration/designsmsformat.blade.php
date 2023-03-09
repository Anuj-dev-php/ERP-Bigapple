@extends('layout.layout')
@section('content')
    <div>
        <span id="showID"></span>
    </div>
    
    <h2 class="menu-title">Whatsapp Configuration</h2>
    <div class="pagecontent"  >
        <div class="container-fluid mlr-5">
    
            <!-- id="frm_account_level" -->
                <form  class="form-horizontal" >
                    <input type="hidden" name="id" id="Id" value="" />
                    <div class="row "  >
                 
                    <div class="form-group col-4">
                        <label class="lbl_control_inline ">Template Name:</label>
                        <input type="text" class="form-control   inline_control " id="SmsTemplate"  name="template_name">
                    </div>

                    <div class="form-group col-4">
                        <label  class="lbl_control_inline ">Select Transaction:</label>
                        <select class="form-control    inline_control" id="SelectTransaction" name="SelectTransaction" id="SelectTransaction">
                                    <option value="">Select</option>
                                    @foreach($tablemaster as $item)
                                    <option value="{{$item->Table_Name}}">{{$item->table_label}}</option>
                                    @endforeach
                                </select>
                    </div>

                    <div class="form-group col-4">
                        <label  class="lbl_control_inline ">Whatsapp Id:</label>
                        <input type="text"  class="form-control    inline_control "  name="whatsapp_id"  id="whatsapp_id_value" required />
                    </div>
                    <div class="form-group col-12 text-center mtb-2"> 
                          <button type="button"  id="btn_save_sms_configuration" class="btn btn-primary btn-default" >Submit</button>
                    </div>
 
                        </div>
                </form> 
            <div class="row mtb-4"  >
                <div class="col-8 mx-auto" >
          
                <input type="button" value="Delete" class="btn btn-primary" id="btn_delete_smsconf" />
                <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Select</th>
                            <th scope="col">Template Name</th>
                            <th scope="col">Transaction Name</th>
                            <th scope="col">Whatsapp Template Id</th>
                            <th scope="col">Delete</th> 
                            <th scope="col">Edit</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($smsheader)==0)
                        <tr><td colspan='6' class='text-left'>No data found</td></tr>
                        @endif 
                        @foreach($smsheader as $key => $item)
                        <tr id="tr_{{$item->tempid}}">
                            <td><input type="checkbox" name="chksmsconfs"   value="{{$item->tempid}}" /></td>
                            <td>{{$item->tempname}}</td>
                            <td>{{$item->txn_name}}</td>
                            <td>{!!$item->msg_txt!!}</td>
                            <td>
                                <a href="javascript:void(0)" onclick="deleteSms(<?php echo $item->tempid; ?>)">Delete</a> 
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick="editSms(<?php echo $item->tempid; ?>,'<?php echo $item->tempname; ?>','<?php echo $item->txn_name; ?>','{!!$item->msg_txt!!}' )">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

            </div>

            </div> 

</div>

</div>
        </div>
    </div>
@endsection
@section('js')
    {{-- ROLE --}}
 
    <script type="text/javascript">
  
        $("#SelectTransaction").change(function(){
            var SelectTransaction = $("#SelectTransaction").val();
            var url = '/{{$companyname}}/design-sms-format-field/' + SelectTransaction;
            $.get(url, function(data, status) {
                $("#SelectFields").html("");
                var SelectFields = "";
                JSON.parse(data).forEach(item => {
                    SelectFields += "<option value='"+item.Field_Name+"'>"+item.fld_label+"</option>"
                });
                $("#SelectFields").html(SelectFields);
            });
        });

        $("#btn_add_sms_configuration").click(function() {
            let SelectFields = $("#SelectFields").val(); 
            let MessageText = $("#MessageText").val();
            $("#MessageText").val("");
            $("#MessageText").val(MessageText+" #"+SelectFields+'#');
        });

        $("#btn_save_sms_configuration").click(function() {
            let token = "{{ csrf_token() }}";
            let Id = $("#Id").val();
            let SmsTemplate = $("#SmsTemplate").val();
            let SelectTransaction = $("#SelectTransaction").val();
            let SelectFields = $("#SelectFields").val();
            let MessageText = $("#whatsapp_id_value").val(); 
            
            $.ajax({
                url: "/{{$companyname}}/design-sms-format-add",
                method: 'POST',
                dataType: "json",
                data: {
                    _token: token,
                    id: Id,
                    SmsTemplate: SmsTemplate,
                    SelectTransaction: SelectTransaction,
                    SelectFields: SelectFields,
                    MessageText: MessageText
                },
                success: function(response) {
                    SnackbarMsg(response);
                    location.reload();
                }
            });
        });

        function deleteSms(id){ 
            $.get("/{{$companyname}}/design-sms-format-delete/"+id, function(response){
                SnackbarMsg(response);
                location.reload();
            });
        }
        // ,msg_txt
        function editSms(id,tempname,txn_name,msg_txt){
            $("#Id").val(id);
            $("#SmsTemplate").val(tempname);
            $("#SelectTransaction").val(txn_name);
            $("#whatsapp_id_value").val(msg_txt);
            $("#btn_cancel_sms_configuration").css("display","inline-block");
        }

        function cancleEditSms(){
            location.reload();
        }

        $("#btn_delete_smsconf").click(function(){
              var chksmsconfs=  $("input[name='chksmsconfs']:checked"); 


              var checkedids=[];

              chksmsconfs.each(function(){
                checkedids.push($(this).val()); 
                });

                if(checkedids.length==0){

                                    
                        SnackBar({ 
                            message:"Please select at Least 1 Sms Format",status:'error'
                        });
                        

                    return false;
                }


                var url="/{{$companyname}}/delete-sms-formats";
                $.post(url,{'tempids':checkedids},function(data,status){

                for(let chkid of checkedids){
                    $("#tr_"+chkid).remove();

                }
                SnackbarMsg(data);

                });




        });
    </script>
@endsection