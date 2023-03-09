@extends('layout.layout') @section('content')

<h2 class="menu-title">Add Edit Define Workflow</h2>
<div class="pagecontent" >
    <a href="{{url('/')}}/{{$companyname}}/define-work-flow"  class="btn btn-primary btn_back_link"  >Back</a>
	<div class="container-fluid mtb-2">
       
		<form class="form-horizontal" method="post" action="{{url('/')}}/{{$companyname}}/submitdefineworkflow">
            @csrf
			<div class="row">
				<div class="form-group col-4 mtb-2">
					<label class="lbl_control_inline">Role :</label>
					<select class="form-control inline_control select-configure" name="role"  id="ddnselectrole"  @if(!empty($workflowheadid)) disabled  @endif>
						<option value="">Select Role</option>
                        @foreach ( $roles as $role )
                        <option   value="{{$role->id}}"  @if(!empty($workflowheadid) && $workflowhead->RoleName==$role->id)  selected   @endif    >{{$role->role_name}}</option>
                            
                        @endforeach
					</select>
				</div>
                @if(!empty($workflowheadid) )
 
                   <input type="hidden"  name="workflowheadid" value="{{$workflowheadid}}" />

                @endif

				<div class="form-group col-4  mtb-2 ">
					<label class="lbl_control_inline">Transaction :</label>
					<select class="form-control inline_control select-configure" name="transaction" id="ddnTransactions"  @if(!empty($workflowheadid)) disabled  @endif>
                    @if(empty($workflowheadid)) 
                    <option value="">Select Transaction</option>
                    @else  
                    <option value="{{$transactiontable}}" data-tablename="{{$transactiontable}}" >{{$transactiontablelabel}}</option>
                    @endif
                     
					</select>
				</div>
				<div class="form-group col-4  mtb-2">
					<!-- <label class="lbl_control_inline"> -->
                        <table cellpadding="5px">
                            <tr>
                                <td style="font-weight: 600;font-size:12px">
						<input type="checkbox" class="chk" name="update_links" value="1"   @if(!empty($workflowheadid) &&  trim($workflowhead->link_up)=='True') checked  @endif />Update Links</td>
                        <!-- &nbsp&nbsp</label> -->
					<!-- <label class="lbl_control_inline"> -->
                        <td style="font-weight: 600;font-size:12px">
						<input type="checkbox" class="chk" name="update_accounts" value="1"    @if(!empty($workflowheadid) &&  trim($workflowhead->acc_up)=='True') checked  @endif  />Update Accounts</td>
                    <!-- </label>
					<label class="lbl_control_inline"> -->
                        <td style="font-weight: 600;font-size:12px">
						<input type="checkbox" class="chk" name="update_inventory" value="1"  @if(!empty($workflowheadid) &&  trim($workflowhead->inv_up)=='True') checked  @endif />Update Inventory</td></tr></table>
                    <!-- </label> -->
				</div>
				<div class="form-group col-4 mtb-2 addeditdefineflow">
					<label class="lbl_control_inline">Save Status :</label>
					<select class="form-control inline_control select-configure" name="savestatus" required>
						<option value="">Select Save Status</option>
                        @foreach ($statuses as $status )
                        <option value="{{$status->id}}"   @if(!empty($workflowheadid) &&  $workflowhead->Savestatusid==$status->id)  selected  @endif>{{$status->StatusName}}</option>
                        @endforeach
					</select>
				</div>
				<div class="form-group col-4  mtb-2 addeditdefineflow">
					<label class="lbl_control_inline">Inbox Status :</label>
					<select class="form-control inline_control select-configure" name="inboxstatus"  required>
						<option value="">Select Inbox Status</option>
                        @foreach ($statuses as $status )
                        <option value="{{$status->id}}"    @if(!empty($workflowheadid) &&  $workflowhead->Inboxstatus==$status->id)  selected  @endif>{{$status->StatusName}}</option>
                        @endforeach
					</select>
				</div>
				<div class="form-group col-4  mtb-2 addeditdefineflow">
					<label class="lbl_control_inline">Reject Status :</label>
					<select class="form-control inline_control select-configure " name="rejectstatus"  required>
						<option value="">Select Reject Status</option>
                        @foreach ($statuses as $status )
                        <option value="{{$status->id}}"    @if(!empty($workflowheadid) &&  $workflowhead->Rejectstatus==$status->id)  selected  @endif>{{$status->StatusName}}</option>
                        @endforeach
					</select>
				</div>
				<div class="form-group col-11  mtb-1  mx-auto" style="min-height:200px;"  >
					<ul class="nav nav-tabs" id="workflowtablist" role="tablist">
						<li class="nav-item" role="save">
							<button class="nav-link active" id="save-tab" data-bs-toggle="tab" data-bs-target="#save-workflow-tab" type="button" role="tab" aria-controls="saveworkflowtab" aria-selected="true">Save</button>
						</li>
						<li class="nav-item" role="inbox">
							<button class="nav-link" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox-workflow-tab" type="button" role="tab" aria-controls="inboxworkflowtab" aria-selected="true">Inbox</button>
						</li>
					 
					</ul>
					<div class="tab-content mtb-2">
						<div class="tab-pane fade show active small" id="save-workflow-tab" role="tabpanel" aria-labelledby="save-workflow-tab">

                     
                                <div style="width:90%;margin:auto;">
                                <div class="clearfix">
                                <input type="button" value="Add New Save "  class="btn btn-primary btn-sm btn_float_right"   onclick="getNewWorkflowSaveFieldRow();"/>
                                </div>
                                <div class="card  mtb-2 ">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                            <table  class="table table-striped" >
                                <thead><th style="width:5%;" class="text-center">Sno.</th> <th  class="text-center" style="width:20%;">Field</th> <th  class="text-center" style="width:15%;">Condition</th>  <th  class="text-center" style="width:15%;">Value</th> <th  class="text-center" style="width:15%;">Status </th> <th  class="text-center" style="width:15%;">Conjunction</th> <th   class="text-center" style="width:15%;">Delete</th></thead>

                                <tbody id="tbodyworkflowsave"  @if(!empty($workflowdetails)  &&  count($workflowdetails['save'])>0 )   data-rows="{{count($workflowdetails['save'])}}" @else  data-rows="0" @endif>

                                @if(!empty($workflowdetails)  &&  count($workflowdetails['save'])>0)

                                @php
                                    $index=1;
                                @endphp

                                    @foreach ( $workflowdetails['save'] as  $savewf )
                                       @include('configuration.workflowsavetr',['rownum'=>$index,'savewf'=>$savewf,'savestatuses'=>$savestatuses])
 
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach

                                @endif

                                </tbody>
                            </table>
                            
                        </div>
                        
                        </div>
                        
                        </div>
                            
                        </div>
                       
                        </div>
						<div class="tab-pane fade small" id="inbox-workflow-tab" role="tabpanel" aria-labelledby="inbox-workflow-tab"> 
                   

                        <div  style="width:90%;margin:auto;">
                        <div class="clearfix">
                        <input type="button" value="Add New Inbox"  class="btn btn-primary btn-sm btn_float_right" onclick="getNewWorkflowInboxFieldRow();"/>

                        </div>
                        <div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                        <table  class="table table-striped">
                                <thead><th style="width:5%;" class="text-center">Sno.</th> <th  class="text-center" style="width:20%;">Field</th> <th  class="text-center" style="width:15%;">Condition</th>  <th  class="text-center" style="width:15%;">Value</th> <th  class="text-center" style="width:15%;">Status </th> <th  class="text-center" style="width:15%;">Conjunction</th> <th   class="text-center" style="width:15%;">Delete</th></thead>

                                <tbody id="tbodyworkflowinbox"     @if(!empty($workflowdetails)  &&  count($workflowdetails['inbox'])>0 )   data-rows="{{count($workflowdetails['inbox'])}}" @else  data-rows="0" @endif >
                                   @if(!empty($workflowdetails)  &&  count($workflowdetails['inbox'])>0)
                                        @php
                                         $index=1;
                                        @endphp

                                        @foreach ( $workflowdetails['inbox'] as  $inboxwf )
                                        
                                          @include('configuration.workflowinboxtr',['rownum'=>$index,'inboxwf'=>$inboxwf,'inboxstatuses'=>$inboxstatuses])


                                        @php
                                            $index++;
                                        @endphp

                                        @endforeach



                                   @endif

                                </tbody>
                            </table>
                            
                       </div>
					 
                       
                       </div>
					 
                       
                       </div>
					 
                            
                       </div>
					 

                       </div>
					 
					</div>
                </div>
					<div class="form-group col-12 mtb-4 text-center">
						<input type="submit" name="btn_submit" value="Submit" class="btn btn-primary" /> &nbsp;&nbsp;
						<input type="button" name="btn_cancel" value="Cancel" class="btn btn-primary" id="btn_cancel_reload" /> </div>
				</div>
		</form>
		</div>
	</div> @endsection @section('js') {{-- ROLE --}}
	<script type="text/javascript">

        $("#ddnselectrole").change(function(){

            var roleid=$(this).val();

            $("#ddnTransactions option:not(:first)"). remove();
        
            var url="{{url('/')}}"+'/{{$companyname}}/role-trans/'+roleid;

            $.get( url,function(data,status){

                var resultarray=JSON.parse(JSON.stringify(data));

                var tables=resultarray['tables']; 

                for(let table of tables){

                    $("#ddnTransactions").append("<option  data-tablename='"+table['Table_Name']+"' value='"+table['Id']+"'>"+table['table_label']+"</option>");

                }

            });
        });

        $("#ddnTransactions").change(function(){

            $("#tbodyworkflowsave").empty();

            $("#tbodyworkflowsave").data("rows",0);

            $("#tbodyworkflowinbox").empty();

            $("#tbodyworkflowinbox").data("rows",0);

            
            var tablename=  $("#ddnTransactions").find(":selected").data('tablename'); 

                    if(tablename==undefined)
                    {

                        SnackBar({ 
                                message:"Please select Transaction",status:'error'
                            });


                        return false;
                    }

            
     
            getNewWorkflowSaveFieldRow(); 

            getTranFieldsAndBindSave(1);
            // getNewWorkflowInboxFieldRow();
            // getTranFieldsAndBindInbox(1);
        
        });


        function getTranFieldsAndBindSave(rownum){

            var roleid=$("#ddnselectrole").val();

             var tablename=  $("#ddnTransactions").find(":selected").data('tablename'); 

             var selected=  $("#tbodyworkflowsave .savefield[data-row='"+rownum+"']").data("selected");
 
             $("#tbodyworkflowsave .savefield[data-row='"+rownum+"'] option:not(:first)"). remove(); 
           var url="{{URL::to('/'.$companyname)}}/trans-fields/"+roleid+'/'+tablename;
 
             $.get( url,function(data,status){
                 var resultarray=JSON.parse(JSON.stringify(data));
                 
                 for(let savefield of resultarray['transactionfields']){
                    $("#tbodyworkflowsave .savefield[data-row='"+rownum+"']").append("<option  value='"+savefield['Field_Name']+"' "+(selected==savefield['Field_Name']?'selected':'')+"  >"+savefield['fld_label']+"</option>");

                 }

                 for(let savefield1 of resultarray['transactionfields_det']){
                    $("#tbodyworkflowsave .savefield[data-row='"+rownum+"']").append("<option value='"+savefield1['Field_Name']+"'   "+(selected==savefield1['Field_Name']?'selected':'')+" >"+savefield1['fld_label']+"</option>");

                 }

             
             });
 

            //  savefield 
            
        }



        function getNewWorkflowSaveFieldRow(){

            var tablename=  $("#ddnTransactions").find(":selected").data('tablename'); 

                    if(tablename==undefined)
                    {

                        SnackBar({ 
                                message:"Please select Transaction",status:'error'
                            });


                        return false;
                    }



           var rownum= $("#tbodyworkflowsave").data("rows");
           rownum=rownum+1;

           var url="{{url('/')}}/{{$companyname}}/get-new-workflow-save-row/"+rownum;

          var status= $("#tbodyworkflowsave .savefieldstatus[data-index='1']").val();
          var conjunction=$("#tbodyworkflowsave .saveconjunction[data-index='1']").val();
                    
          if(status==''){
              alert("Please select Save Status 1 ");
              return false;
          }
         
           $.post(url,{'selectstatus':status,'selectconjunction':conjunction},function(data,status){
                var resultarray=JSON.parse(JSON.stringify(data)); 
                $("#tbodyworkflowsave").append(resultarray['savehtml']); 
                $("#tbodyworkflowsave").data("rows",rownum);
                getTranFieldsAndBindSave(rownum)

            }); 
    

        }

           
        $("#tbodyworkflowsave").on("click",".lnk_delete_save",function(){
            $(this).parent().parent().remove();
            var rows=$("#tbodyworkflowsave").data('rows');
            rows=rows-1;
            $("#tbodyworkflowsave").data('rows', rows);

        })

        function getNewWorkflowInboxFieldRow(){

            var tablename=  $("#ddnTransactions").find(":selected").data('tablename'); 

                    if(tablename==undefined)
                    {

                        SnackBar({ 
                                message:"Please select Transaction",status:'error'
                            });


                        return false;
                    }


            var rownum= $("#tbodyworkflowinbox").data("rows");
           rownum=rownum+1;

           var url="{{url('/')}}/{{$companyname}}/get-new-workflow-inbox-row/"+rownum;
         
           $.get(url,function(data,status){
                var resultarray=JSON.parse(JSON.stringify(data)); 
                $("#tbodyworkflowinbox").append(resultarray['inboxhtml']); 
                $("#tbodyworkflowinbox").data("rows",rownum);
                getTranFieldsAndBindInbox(rownum)

            }); 

        }


        
        function getTranFieldsAndBindInbox(rownum){

            var roleid=$("#ddnselectrole").val();

            var tablename=  $("#ddnTransactions").find(":selected").data('tablename');  
            
            var selected=  $("#tbodyworkflowinbox .inboxfield[data-row='"+rownum+"']").data("selected");
             

            $("#tbodyworkflowinbox .inboxfield[data-row='"+rownum+"'] option:not(:first)"). remove(); 
           
            var url="{{url('/')}}/{{$companyname}}/trans-fields/"+roleid+'/'+tablename;

            $.get( url,function(data,status){
                var resultarray=JSON.parse(JSON.stringify(data));
            
                for(let inboxfield of resultarray['transactionfields']){
                    $("#tbodyworkflowinbox .inboxfield[data-row='"+rownum+"']").append("<option value='"+inboxfield['Field_Name']+"'   "+(selected==inboxfield['Field_Name']?'selected':'')+"   >"+inboxfield['fld_label']+"</option>");

                }
            

                for(let inboxfield1 of resultarray['transactionfields_det']){
                    $("#tbodyworkflowinbox .inboxfield[data-row='"+rownum+"']").append("<option value='"+inboxfield1['Field_Name']+"'    "+(selected==inboxfield1['Field_Name']?'selected':'')+"  >"+inboxfield1['fld_label']+"</option>");

                }

                //  $("#tbodyworkflowsave .savefield[data-row='"+rownum+"']").select2({placeholder:'Select Field'});

                });

                //  savefield 

                }


          
        $("#tbodyworkflowinbox").on("click",".lnk_delete_inbox",function(){
            $(this).parent().parent().remove();
            var rows=$("#tbodyworkflowinbox").data('rows');
            rows=rows-1;
            $("#tbodyworkflowinbox").data('rows', rows);

        });

        // on edit run this

        @if (!empty($workflowheadid))
        $(function(){
 
            @php
                if(!empty($workflowdetails)  &&  count($workflowdetails['save'])>0){
                    $noofsave=count($workflowdetails['save']);
                }
                else{
                    $noofsave=0;
                }

                if(!empty($workflowdetails)  &&  count($workflowdetails['inbox'])>0){
                    $noofinbox=count($workflowdetails['inbox']);
                }
                else{
                    $noofinbox=0;
                }
               
               
            @endphp

            var noofsave={{$noofsave}};

            var noofinbox={{$noofinbox}}; 

            for(i=1;i<=noofsave;i++){
                
            getTranFieldsAndBindSave(i);

            }

            for(j=1;j<=noofinbox;j++){

               getTranFieldsAndBindInbox(j);

            }
 

        });
    
@endif

	</script> @endsection 