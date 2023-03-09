@extends('layout.layout')
@section('content')
<h2 class="menu-title">Define Workflow</h2>
    <div class="pagecontent">
    
        <div class="container-fluid mtb-2">
            <div class="row">
                <div class="col-10 mx-auto ">

                   
                    <div class="clearfix">
                    <a href="/{{ $companyname }}/add-edit-define-workflow" style="text-decoration:none;"
                        class="btn btn-primary btn-md btn_float_right">Add </a>
                        
                    <input type="button" class="btn btn-primary btn-md btn_float_right " value="Delete" id="btn_delete"   />

                    </div>
                    <div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width:10%;" class="text-center">Select</th>
                                    <th style="width:20%;" class="text-center">Role</th>
                                    <th style="width:20%;" class="text-center">Transaction</th>
                                    <th style="width:10%;" class="text-center">Save Status</th>
                                    <th style="width:10%;" class="text-center">Inbox Status</th>
                                    <th style="width:10%;" class="text-center">Reject Status</th>
                                    <th style="width:10%;" class="text-center">Link</th>
                                    <th style="width:10%;" class="text-center">Inventory</th>
                                    <th class="text-center" style="width:10%;">Accounts</th>
                                    <th class="text-center" style="width:10%;">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($workflowheads) == 0)
                                    <tr>
                                        <td colspan='9' class='text-left'>No Workflow Found</td>
                                    </tr>
                                @else
                                    @foreach ($workflowheads as $workflowhead)
                                        <tr id="tr_{{ $workflowhead->id }}">
                                            <td class="text-center"><input type="checkbox" name="workflowhead"
                                                    value="{{ $workflowhead->id }}" /></td>

                                                    <td class='text-center'>{{   $rolenames[$workflowhead->RoleName]}}</td>
                                            <td class="text-center">{{ $workflowhead->Table_Name }}</td>
                                            <td class="text-center">
                                                @if (array_key_exists($workflowhead->Savestatusid, $status))
                                                    {{ $status[$workflowhead->Savestatusid] }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (array_key_exists($workflowhead->Inboxstatus, $status))
                                                    {{ $status[$workflowhead->Inboxstatus] }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (array_key_exists($workflowhead->Rejectstatus, $status))
                                                    {{ $status[$workflowhead->Rejectstatus] }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ trim($workflowhead->link_up) }}</td>
                                            <td class="text-center">{{ trim($workflowhead->inv_up) }}</td>
                                            <td class="text-center">{{ trim($workflowhead->acc_up) }}</td>
                                            <td class="text-center">
                                            <button class="tbl_btn">    
                                            <a class="tbl_link"
                                                    href="/{{ $companyname }}/add-edit-define-workflow/{{ $workflowhead->id }}">Edit</a></button>
                                            </td>
                                        </tr>
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
    </div>
    </div>

    </div>
    </div>


@endsection
@section('js')
    {{-- ROLE --}}

    <script type="text/javascript">
        $("#btn_delete").click(function() {
            var cnf = confirm("Are you sure to delete selected Workflows ?");
            if (cnf == false) {
                return false;
            }
            var chks = $("input[name='workflowhead']:checked");
            var checkedids = [];
            chks.each(function() {
                checkedids.push($(this).val());
            });
            var url = "/{{ $companyname }}/delete-define-workflows";
            $.post(url, {
                'ids': checkedids
            }, function(data, status) {
                for (let chkid of checkedids) {
                    $("#tr_" + chkid).remove();
                }
                SnackbarMsg(data);
            });
        });
    </script>
@endsection
