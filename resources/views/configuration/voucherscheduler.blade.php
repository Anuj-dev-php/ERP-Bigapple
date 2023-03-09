@extends('layout.layout')
@section('content')
    <div>
        <span id="showID"></span>
    </div>
    
    <h2  class="menu-title">Voucher Scheduler</h2>
    <div class="pagecontent"   >
        <div class="container" style="margin-top:10px auto 10px auto;">
            <form id="frm_voucher_scheduler">
                <input type="hidden" name="id" id="Id" value="" />
                <div class="row">
                    <div class="col-6 text-end sp">
                        <label class="lbl_control" >Voucher Number:</label>
                    </div>
                    <div class="col-4 mtb">
                        <select class="form-control select-configure" name="VoucherNumber" id="VoucherNumber"></select>
                    </div>
                    <div class="col-6 text-end sp">
                        <label class="lbl_control">Start Date:</label>
                    </div>
                    <div class="col-4 mtb">
                        <input type="date" name="StartDate" id="StartDate" class="form-control select-configure"/>
                    </div>
                    <div class="col-6 text-end sp">
                        <label class="lbl_control">End Date:</label>
                    </div>
                    <div class="col-4 mtb">
                        <input type="date" class="form-control select-configure" name="EndDate" id="EndDate" />
                    </div>
                    <div class="col-6 text-end sp">
                        <label class="lbl_control">Frequency:</label>
                    </div>
                    <div class="col-4 mtb">
                        <select  class="form-control select-configure" name="Frequency" id="Frequency">
                            <option value="">Select</option>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="fortnightly">Fortnightly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="half yearly">Half Yearly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="col-7 text-end mtb" style="margin-left:60px"> 
                        <button type="button" class="button btn btn-primary" id="btn_save_voucher_scheduler">Save</button>
                        <button type="button" class="button btn btn-primary" id="btn_cancel_voucher_scheduler"   onclick="cancleEditVch()">Cancel</button> 
                    </div>
                </div>
            </form>
 
            <div class="row mtb-2 mx-auto" style="width:70%;">
            <div class="col-12">  
                
            <div class="clearfix">
            <input type="button" value="Delete" class="btn btn-primary btn_float_right btn-md" id="btn_voucher_delete" />
            </div>


        	<div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
            <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col" style="width:10%;">Select</th> 
                            <th scope="col" style="width:20%;">Voucher Number</th>
                            <th scope="col" style="width:15%;">Start Date</th>
                            <th scope="col"  style="width:15%;">End Date</th>
                            <th scope="col"  style="width:15%;">Frequency</th> 
                            <th scope="col" style="width:25%;">Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voucherscheduler as $key => $item)
                        <tr id="tr_{{$item->id}}">
                            <td><input type="checkbox" class="chkmodularscheduler" name="voucherschedulers" value="{{$item->id}}" /></td> 
                            <td>{{$item->VoucherNumber}}</td>
                            <td>{{date("d/m/Y",strtotime($item->StartDate))}}</td>
                            <td>{{date("d/m/Y",strtotime($item->EndDate))}}</td>
                            <td>{{$item->Frequency}}</td>
                            <td>
                                <a href="javascript:void(0)" onclick="deleteVch(<?php echo $item->id; ?>)">Delete</a> | 
                                <a href="javascript:void(0)" onclick="editVch(<?php echo $item->id; ?>)">Edit</a>
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
        $(document).ready(function() {
            // $( "#StartDate" ).datepicker();
            // $( "#EndDate" ).datepicker(); 
            var voucherurl='/{{$companyname}}/search-voucher-no';  
            initSelect2Search("#VoucherNumber",voucherurl,"Select Voucher No.",null); 
     
            $("#btn_save_voucher_scheduler").click(function() {
             
                let VoucherNumber = $("#VoucherNumber").val();
                let StartDatestring = $("#StartDate").val();
                let EndDatestring = $("#EndDate").val();
                let Frequency = $("#Frequency").val();
 

                if(VoucherNumber=="" || VoucherNumber==null){
                    SnackBar({ 
                        message:"Please select Voucher Number",status:'error'
                    });
                    

                    return false;
                }

                if(StartDatestring=='' || EndDatestring=='' ){

                    SnackBar({ 
                        message:"Please select Start Date Or End Date is missing",status:'error'
                    }); 

                    return false;
                }
                var startdate=new Date(StartDatestring);
                var enddate=new Date(EndDatestring);

                if(enddate<startdate)
                {
                    SnackBar({ 
                        message:"Please select End Date greater than Start Date",status:'error'
                    });

                    return false;
                }


                if(Frequency==""){

                    SnackBar({ 
                        message:"Please select Frequency",status:'error'
                    });
                    

                    return false;

                }


                $.ajax({
                    url: "/{{$companyname}}/voucher-scheduler-add",
                    method: 'POST',
                    dataType: "json",
                    data: $("#frm_voucher_scheduler").serialize(),
                    success: function(response) {
                        SnackbarMsg(response);
                        location.reload();
                    }
                });
            });
        });

        function deleteVch(id){

            var cnf=confirm("ARe you sure to delete this ?");

            if(cnf==false){
                return false;
            }

            $.get("/{{$companyname}}/voucher-scheduler-delete/"+id, function(response){
                SnackbarMsg(response);
                $("#tr_"+id).remove();
            });
        }

        function editVch(id){
           var url= "/{{$companyname}}/voucher-scheduler-detail/"+id;

           $.get(url,function(data,status){ 
            var resultarray=JSON.parse(JSON.stringify(data));  

            var voucher=resultarray['voucherscheduler'];
            var voucher_num_id=resultarray['voucher_number_id']; 
        
            $("#VoucherNumber").empty();
            $("#VoucherNumber").append("<option value='"+voucher_num_id+"' selected>"+voucher['VoucherNumber']+"</option>"); 
            var voucherurl='/{{$companyname}}/search-voucher-no';  
            // initSelect2SearchTriggerChange("#VoucherNumber",voucherurl,"Select Voucher No.");
 
            $("#StartDate").val(voucher['StartDate']);
            $("#EndDate").val(voucher['EndDate']);
            $("#Frequency").val(voucher['Frequency']);
            $("#Id").val(voucher['id']); 

           })
      
        }

        function cancleEditVch(){
            location.reload();
        }


    $("#btn_voucher_delete").click(function(){

       var checked= $(".chkmodularscheduler:checked");

       if(checked.length==0){
           return false;
       }

       var cnf=confirm("Are you sure to delete these selected Voucher Scheduler ?");

       if(cnf==false){
           return false;
       }

       var vouchers=[];
       checked.each(function(){
        vouchers.push($(this).val());

       });
       var url="/{{$companyname}}/delete-voucher-scheduler";

       $.post(url,{'vouchers':vouchers},function(data,status){

        for(let vchid of vouchers){
            $("#tr_"+vchid).remove();

        }
           SnackbarMsg(data);


       })
 

    });
    </script>
     
 
@endsection
