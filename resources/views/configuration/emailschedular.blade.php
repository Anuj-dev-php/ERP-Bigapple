@extends('layout.layout')
@section('content')
    <div>
        <span id="showID"></span>
    </div>

  <link rel="stylesheet" href="{{ url('assets/bootstrap-multiselect/css/bootstrap-multiselect.min.css') }}" />
  <style type="text/css">

.multiselect-container {
    /* border:10px solid black!important; */
    /* width:100%!important; */

} 

.multiselect-container.dropdown-menu{width:100%!important; }

.multiselect-native-select .btn-group{width:100%!important;outline:none; border:1px solid rgb(212,206,218);border-radius:1px;}

.multiselect.dropdown-toggle.custom-select{background-color:white!important;  height:30px!important;  width:100%!important; border: none!important;}

    /* .multiselect-container > li > a > label.checkbox
    {
        width: 220px;
    }
    .btn-group > .btn:first-child
    {
        width: 220px;
    } */
</style>
    
    <h2 class="menu-title">Email & Whatsapp Scheduler</h2>
    <div class="pagecontent"  >
        <div class="container-fluid mlr-5">
    
            <!-- id="frm_account_level" -->
                <form  class="form-horizontal" method="post"   action="{{url('/')}}/{{$companyname}}/email-whatsapp-scheduler" >
                    @csrf
                    <input type="hidden" name="id" id="Id" value="" />
                    <div class="row "  >
                    <div class="form-group col-4  mtb-2" >
                        <label  class="lbl_control_inline "> Email Configuration:</label>
                        <div class="inline_control">
                        <select class="form-control  "  id="ddn_email_configuration" name="email_configuration" required >
                            <option value="">Select Email Conf.</option>
                                     @foreach($email_configs as $email_config_key=> $email_config_value)
                                    <option value="{{$email_config_key}}">{{ $email_config_value}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>

                    <div class="form-group col-4  mtb-2">
                        <label  class="lbl_control_inline ">Select Schedule:</label>
                        <div class='inline_control'>
                        <select class="form-control"  name="schedule"  id='ddn_schedule'  required > 
                            <option value="">Select Schedule</option>
                            <option value="Hourly">Hourly</option>
                            <option value="Daily">Daily</option>
                            <option value="Days">Days</option>
                            <option value="Months">Months</option>
                            <option value="Specific">Specific</option>
                               </select>
                        </div>
                    </div>
 

                    <div id="div_specific" class="form-group col-4  mtb-2 d-none">
                        <label class="lbl_control_inline ">Select Date & Time:</label>
                        <input type="text" class="form-control   inline_control "  id="send_datetime"  name="send_datetime" autocomplete="off" >
                    </div>

                    <div id="div_weekdays"  class="form-group col-4  mtb-2 d-none">
                       <label class="lbl_control_inline ">Select Weekdays:</label>
                        <div class="inline_control">
                        <select id="select-weekdays" multiple="multiple" name='weekdays[]' class='form-control'>
                            <option value="1">Mon</option>
                            <option value="2">Tue</option>
                            <option value="3">Wed</option>
                            <option value="4">Thurs</option>
                            <option value="5">Fri</option>
                            <option value="6">Sat</option>
                            <option value="0">Sun</option>
                        </select>
                        </div>
                    </div>


                    

        
                    <div id="div_months"  class="form-group col-4  mtb-2 d-none">
                       <label class="lbl_control_inline ">Select Months:</label>
                        <div class="inline_control">
                        <!-- multiple="multiple" -->
                        <select id="select-months"  multiple="multiple" name="months[]"  class='form-control'>
                            <option value="1">Jan</option>
                            <option value="2">Feb</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">Aug</option>
                            <option value="9">Sept</option>
                            <option value="10">Oct</option>
                            <option value="11">Nov</option>
                            <option value="12">Dec</option>
                        </select>
                        </div>
                    </div>

                    <div  id="div_monthday" class="form-group col-4  mtb-2 d-none">
                       <label class="lbl_control_inline ">Select Month Day:</label>
                        <div class="inline_control">
                        <!-- multiple="multiple" -->
                        <select id="select-monthdays"  class='form-control' name="month_day">
                            @for ($i=1;$i<=31;$i++)
                            <option value="{{$i}}">{{$i}}</option>
                                
                            @endfor
                        </select>
                        </div>
                    </div>

                    
                    <div id="div_time" class="form-group col-4  mtb-2 d-none">
                        <label class="lbl_control_inline ">Select Hours:Min</label>
                        <input type="text" class="form-control   inline_control "  id="send_time"  name="send_time" >
                    </div> 


              
                    <div class="form-group col-12 text-center mtb-2"> 
                          <button type="submit"  id="btn_save_email_schedular" class="btn btn-primary btn-default" >Submit</button>
                          <button type="button"   class="btn btn-primary btn-default"  id="btn_cancel_reload" >Cancel</button>
                    </div>
 
                        </div>
                </form> 
            <div class="row mtb-1"  >
                <div class="col-12 mx-auto" >
          
                <input type="button" value="Delete" class="btn btn-primary" id="btn_delete_emailschedular" />
                <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                        @php
                            $week_day_names=array(1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thurs',5=>'Fri',6=>'Sat');
                            $month_names=array(1=>'Jan',2=>'Feb',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'Aug',9=>'Sept',10=>'Oct',11=>'Nov',12=>'Dec');


                        @endphp
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Select</th>
                            <th scope="col">Email Conf</th>
                            <th scope="col">Schedule</th>
                            <th scope="col"  >Send Months</th>
                            <th scope="col">Send Month Day</th>
                            <th scope="col">Send Week Days</th>
                            <th scope="col">Send Time</th>
                            <th scope="col">Send Date Time</th> 
                            <th scope="col">Delete</th> 
                            <th scope="col">Edit</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($emailschedulars)==0)
                        <tr><td colspan='10' class='text-left'>No data found</td></tr>
                        @endif 
       
                        @foreach($emailschedulars as $key => $item)

                        @php

                        if(!empty($item->send_months)){

                            $send_months_string=$item->send_months;
                        }
                        else{
                            
                        $send_months_string='';
                        }


                        if(empty(  $send_months_string)){
                            $send_months_array=array();
                        }
                        else{
                            $send_months_found_array=explode(',', $send_months_string);
                            $send_months_array=array();

                            foreach($send_months_found_array as $month_id){
                                array_push($send_months_array, $month_names[$month_id]);

                            }

                        }

                        if(!empty($item->send_weekdays)){
                            $send_weekdays_string=$item->send_weekdays;
                        }
                        else{
                            $send_weekdays_string='';
                        }

                   

                        if(empty($send_weekdays_string)){
                            $send_weekdays_array=array();
                        }
                        else{

                            $send_weekdays_found_array=explode(',',$send_weekdays_string);

                            $send_weekdays_array=array(); 

                            foreach($send_weekdays_found_array as $week_day_id){
                                array_push(   $send_weekdays_array,  $week_day_names[$week_day_id]);
                            }


                        }

                        if(!empty($item->send_time)){
                            $show_send_time_string=date('H:i',strtotime($item->send_time));
                        }
                        else{
                            $show_send_time_string=''; 
                        }

                        if(!empty($item->send_datetime)){
                            $show_date_time_string=date('d-m-Y H:i',strtotime($item->send_datetime));
                        }
                        else{
                            $show_date_time_string="";
                        }
                     
                        if(!empty($item->send_month_day)){
                            $send_month_day_string=$item->send_month_day;
                        }
                        else{
                            $send_month_day_string='';
                        }
                            
                            

                        @endphp
                        <tr id="tr_{{$item->id}}">
                            <td><input type="checkbox" name="chkemailschedular"   value="{{$item->id}}" /></td>
                            <td>{{$item->email_configuration_name}}</td>
                            <td>{{$item->schedule}}</td>
                            <td style="max-width:200px;">{{implode(',',  $send_months_array)}}</td>
                            <td>{{  $send_month_day_string}}</td>
                            <td  style="max-width:150px;">{{implode(',',$send_weekdays_array)}}</td>
                            <td>{{  $show_send_time_string}}</td>
                            <td>{{     $show_date_time_string}}</td>
                            <td>
                                <a href="javascript:void(0)" onclick="deleteEmailSchedular(<?php echo $item->id; ?> )">Delete</a> 
                            </td>
                            <td>
                           
                                <a href="javascript:void(0)" onclick="editEmailSchedular(<?php echo $item->id; ?>,<?php echo $item->email_configuration_id; ?>,'{{$item->schedule}}','{{ $show_send_time_string}}','{{ $show_date_time_string}}','{{ $send_weekdays_string}}','{{   $send_months_string}}','{{    $send_month_day_string}}')">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>

            </div> 

</div>

<div   >{{$emailschedulars->links()}}</div>
                </div>

</div>
        </div>
    </div>
@endsection
@section('js')
    {{-- ROLE --}}
 
    <script src="{{ url('assets/bootstrap-multiselect/js/bootstrap-multiselect.min.js') }}"></script>
 
    <script type="text/javascript">

$(function() {

    $('#send_datetime').datetimepicker({
				format: 'd-m-Y H:i:s',
				timepicker: true,
				datepicker: true,
				dayOfWeekStart: 1, 
                minDate:true, 
			});


            
    $('#send_time').datetimepicker({
				format: 'H:i',
                timepicker: true,
				datepicker: false,
                minDate:false,
                minTime:false
			});


});
  
        $("#SelectTransaction").change(function(){
            var SelectTransaction = $("#SelectTransaction").val();

            var  url = "{{url('/')}}/{{$companyname}}/get-table-ids";

            initSelect2Search(`#table_id_select`, url, '', null, {
                'table_name':SelectTransaction, 
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

        $("#btn_delete_emailschedular").click(function(){
              var chkemailschedular=  $("input[name='chkemailschedular']:checked"); 
 
              var checkedids=[];

              chkemailschedular.each(function(){
                checkedids.push($(this).val()); 
                });

                if(checkedids.length==0){     
                        SnackBar({ 
                            message:"Please select at Least 1 Email Schedular",status:'error'
                        });
                         
                    return false;
                }


                var url="/{{$companyname}}/delete-email-schedulars";
                $.post(url,{'tempids':checkedids},function(data,status){

                for(let chkid of checkedids){
                    $("#tr_"+chkid).remove();

                }
                SnackbarMsg(data);

                });




        });


        function deleteEmailSchedular(id){

            var cnf=confirm('Are you sure to delete this ?');

            if(cnf==false){
                return false;
            }

            var checkedids=[id];

            var url="/{{$companyname}}/delete-email-schedulars";
                $.post(url,{'tempids':checkedids},function(data,status){

                for(let chkid of checkedids){
                    $("#tr_"+chkid).remove();

                }
                SnackbarMsg(data);

                });

        }


        function editEmailSchedular(id,email_conf_id,schedule,send_time,send_datetime,send_weekdays_string,send_months_string,send_month_day){


            $("#div_time,#div_monthday,#div_months,#div_weekdays,#div_specific").addClass('d-none');
                
                if(schedule=="Daily" || schedule=="Hourly" ){
                    $("#div_time").removeClass('d-none');
                }
                else if(schedule=="Days"){

                    $("#div_weekdays").removeClass('d-none');
                    $("#div_time").removeClass('d-none');

                }
                else if(schedule=="Months"){
                    $("#div_months").removeClass('d-none');
                    $("#div_monthday").removeClass('d-none');
                    $("#div_time").removeClass('d-none');

                }
                else if(schedule=="Specific"){
                    $("#div_specific").removeClass('d-none');
                }
 
      var weekdays_array=send_weekdays_string.split(",");  
                var months_array=    send_months_string.split(",");  
            
            $("#Id").val(id);
            $("#ddn_email_configuration").val(email_conf_id);
            $("#ddn_schedule").val(schedule);
            $("#send_datetime").val(send_datetime);
            $("#select-weekdays").multiselect('deselectAll', true);
            $("#select-weekdays").multiselect('select',weekdays_array, true);
            $("#select-months").multiselect('deselectAll', true);
            $("#select-months").multiselect('select',months_array, true);
            $("#select-monthdays").multiselect('deselectAll', true);
            $("#select-monthdays").multiselect('select',send_month_day, true);
            $("#send_time").val(send_time);
     
           
        }


        $(document).ready(function() {
        $('#select-weekdays').multiselect({
          includeSelectAllOption: true,
        });

        

        $('#select-monthdays,#select-months').multiselect({
        //   includeSelectAllOption: true,
          maxHeight:250,
          enableCaseInsensitiveFiltering: true
        });


    });


     $("#ddn_schedule").change(function(){
        var schedule=$(this).val();

        $("#div_time,#div_monthday,#div_months,#div_weekdays,#div_specific").addClass('d-none');

        $("#send_datetime,#select-weekdays,#select-months,#select-monthdays,#send_time").prop('required',false);
         
        if(schedule=="Daily" || schedule=="Hourly" ){
            $("#div_time").removeClass('d-none');
            $("#send_time").prop('required',true);
        }
        else if(schedule=="Days"){

            $("#div_weekdays").removeClass('d-none');
            $("#div_time").removeClass('d-none');
            $("#select-weekdays").prop('required',true);
            $("#send_time").prop('required',true);
        }
        else if(schedule=="Months"){
            $("#div_months").removeClass('d-none');
            $("#div_monthday").removeClass('d-none');
            $("#div_time").removeClass('d-none');
            $("#select-months").prop('required',true);
            $("#select-monthdays").prop('required',true);
            $("#send_time").prop('required',true);

        }
        else if(schedule=="Specific"){
            $("#div_specific").removeClass('d-none');
            $("#send_datetime").prop('required',true);
        }

        


     });

    </script>
@endsection