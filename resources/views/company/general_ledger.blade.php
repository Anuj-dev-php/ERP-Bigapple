@extends('layout.layout')
<style>
    #custombutton {
        width: 100%;
    }

    #subledgerdata_length {
        margin: 10px;
    }

    #subledgerdata_filter {
        margin: 10px;
        width: 37%;
    }

    .btn.btn-primary {
        left: -22px;
        margin-left: 5px;
    }

    .select2-container {
        text-align: left !important;
    }

    .dt-buttons {
        float: right;
        margin: 10px;
    }

    .jstree-themeicon {
        display: none !important;
    }

    .jstree-wholerow-ul {
        max-width: 100% !important;
    }


    /**
    date picker 
     */

    #selectVchFromDate, #selectVchToDate {
        position: relative;
        /* width: 150px; */
        height: 30px;
        color: white;
        line-height:1.8
    }

    #selectVchFromDate:before, #selectVchToDate:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    #selectVchFromDate::-webkit-datetime-edit,
    #selectVchFromDate::-webkit-inner-spin-button,
    #selectVchFromDate::-webkit-clear-button,
    #selectVchToDate::-webkit-datetime-edit,
    #selectVchToDate::-webkit-inner-spin-button,
    #selectVchToDate::-webkit-clear-button {
        display: none;
    }

    #selectVchFromDate::-webkit-calendar-picker-indicator, #selectVchToDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>

@section('content')
<h2 class="menu-title ">Table - General Ledger</h2>
{{-- <div class="container-fluid mtb-1"> --}}
<div class="pagecontent">
    <div class="card-body">
        <form id="accountform" name="accountform" method="post" action="{{route('company.submitgeneralledger' ,['company_name'=>$companyname])}}">
            <div id="jsfields"></div>
            @csrf
            <!-- <div class="col-12"> -->
            <div class="col-12">
                <div class='row'>
                    <div class="col-4 float-start">
                        <!-- <label class="lbl_control"> Account Name :</label> -->
                      
                        <div class='hummingbird-treeview'>
			<ul id="menu_level_tree" class="hummingbird-base" style="padding-left:0px!important;">
         @foreach ($accounts as $account  )
					<li data-id="{{$account['id']}}"> <i class="fa fa-plus" data-level="1" tabIndex="1" data-id="{{$account['id']}}"></i>
					<a class='ga_link'  data-id="{{$account['id']}}"  data-level="1" data-ga="{{trim($account['ga'])}}">
						<label class="tree_checkbox_lbl"> 
                            <input type="checkbox"  value="{{$account['id']}}" class="menu_level_menus" data-parentid="" name="selected_accounts[]" />
							{{$account['account_name']}} (Level 1)</label>
					</a>
					</li> @endforeach 
				</ul>
				</div>

                        
                    </div>

                    <div class="col-8">
                        <div class="row">
                            <div class="col-2">
                                <label class="lbl_control">Start Date:</label>
                                <input type='date' name="selectVchFromDate" required id='selectVchFromDate' class='form-control' value="{{date('Y-m-d',strtotime($companyDates->fs_date))}}" data-date="" data-date-format="DD/MM/YYYY" />
                            </div>

                            <div class="col-2">
                                <label class="lbl_control">End Date:</label>
                                <input type='date' id='selectVchToDate' required name="selectVchToDate" class='form-control' value="{{date('Y-m-d',strtotime($companyDates->fe_date))}}" data-date="" data-date-format="DD/MM/YYYY" />
                            </div>
                            <div class="col-2">
                                {{-- <label class="lbl_control">Cost Center:</label>
                                <input type="text" id="costCenter" name="costCenter" class="form-control" /> --}}
                                <label class="lbl_control">Cost Center :</label>
                                <select class='form-control' name="costId" id='costId'>
                                    <option value=''>Select Cost Center</option>
                                    @foreach ($costdata as $cost)
                                    <option  @if(!empty($costcenter)  && $costcenter==$cost->Id) selected  @endif value="{{ $cost->Id }}">{{ $cost->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="lbl_control">Department:</label>
                                <select class='form-control' name="deptId" id='deptId'>
                                    <option value=''>Select Department</option>
                                    @foreach ($deptdata as $dept)
                                    <option value="{{ $dept->Id }}"  @if(!empty($department) && $department==$dept->Id ) selected  @endif>{{ $dept->DeptName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='row'>
                                <label class="lbl_control mt-1" for="radio1" style="padding-left:0;">Optional Columns:</label>

                                <div class="col-12 mt-1" style="padding-left:0;">

                                    <input class="form-check-input " type="checkbox" id="ChequeNo" name="ChequeNo" value="ChequeNo" @if(!empty($chequeno)) checked @endif>
                                    <label class="check-1" for="ChequeNo"> Cheque No</label>

                                    {{-- Cheque No --}}

                                    <input class="form-check-input" type="checkbox" id="ChequeStatus" name="ChequeStatus" value="ChequeStatus"  @if(!empty($chequestatus)) checked @endif> <label class="check-1" for="ChequeStatus">Cheque Status</label>


                                    <input class="form-check-input" type="checkbox" id="ClearingDate" name="ClearingDate" value="ClearingDate"   @if(!empty($clearingdate)) checked @endif>
                                    <label class="check-1" for="ClearingDate">Clearing Date</label>
                                    {{-- Clearing Date --}}

                                    <input class="form-check-input" type="checkbox" id="CostCentre" name="CostCentre" value="CostCentre"    @if(!empty($costcentre)) checked @endif>
                                    <label class="check-1" for="CostCentre">Cost Center</label>
                                    {{-- Cost Centre --}}

                                    <input class="form-check-input" type="checkbox" id="Department" name="Department" value="Department"     @if(!empty($department)) checked @endif>
                                    <label class="check-1" for="Department">Department</label>
                                    {{-- Department --}}

                                    <input class="form-check-input" type="checkbox" id="Executive" name="Executive" value="Executive"   @if(!empty($executive)) checked @endif>
                                    <label class="check-1" for="Executive">Executive</label>
                                    {{-- Executive --}}

                                    <input class="form-check-input" type="checkbox" id="Project" name="Project" value="Project"  @if(!empty($project)) checked @endif>
                                    <label class="check-1" for="Project">Project</label>
                                    {{-- Project --}}

                                    <input class="form-check-input" type="checkbox" id="ForeignCurrency" name="ForeignCurrency" value="ForeignCurrency"   @if(!empty($foreigncurrency)) checked @endif> <label class="check-1" for="ForeignCurrency">Foreign Currency</label>
                                    {{-- Foreign Currency --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-4 p-2 pt-2">
                            <input type='submit' name='submit' class='btn btn-primary' id="submit" />
                            <button id="cancel" name="cancel" type="button" class='btn btn-primary'>Cancel</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div class='col-12'    id="divpagecontent">

        <div class="row" style="padding:0px;">
            <div class="col-12 " style="margin:0px;">

                <div class="clearfix">
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="Print" id="btn_print"
                        onclick="printReport();" />

                    <input type="button" class="btn btn-primary btn-md  btn_float_left" style="float: left;" value="Back"
                        id="back-btn" onclick="history.back();" />

                        <input type="button" class="btn btn-primary btn-md  btn_float_right" value="doc" id="doc-btn"
                                                onclick="dwonloaddoc()" />
                    <!-- 

                                            <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xls" id="xls-btn"
                                                onclick="dwonloadxls()" />

                                            <input type="button" class="btn btn-primary btn-md  btn_float_right" value="image" id="img-btn"
                                                onclick="dwonloadimg()" /> -->
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="image" id="img-btn"
                        onclick="dwonloadimg()" />

                    {{-- <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xlsx" id="xlsx-btn"
                    onclick="dwonloadxlsx()" /> --}}
                    <input type="button" class="btn btn-primary btn-md  btn_float_right" value="xls" id="xls-btn"
                        onclick="dwonloadxls()" />
                    
                        
 
                </div>
            </div>
            <div class="row">
                <div class="col-12 mx-auto" style="margin-top:20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="mx-auto table-responsive" id="table_row">

                                {{-- {{dd($arraydata);}} --}}

                                @if(count($arraydata)==0)

                                <h5 class='text-center'> No Data</h5>

                                @endif

                                @foreach ($arraydata as $responseData)
                                    @if (isset($responseData['accountName']))
                                        <table class="table table-striped"     width="100%">
                                            <tbody>
                                                <tr  >
                                                    <td>
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td colspan="8" style="text-align: center; padding:20px;">
                                                                    <h4> Account - General Ledger</h4>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr  >
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td style="text-align: left; padding:20px;width:30%">A/C
                                                                    No.: </td>
                                                                <td><strong>{{ $responseData['account_id'] }}</strong></td>
                                                                <td style="text-align: left; padding:20px;width:10%">Start
                                                                    Date.:
                                                                </td>
                                                                <td><strong>{{ $responseData['startDate'] }}</strong></td>
                                                                <td style="text-align: left; padding:20px;width:10%">End
                                                                    Date: </td>
                                                                <td><strong>{{ $responseData['toDate'] }}</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr >
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td style="text-align: left; padding:20px;width:20%">Account Name:
                                                                </td>
                                                                <td><strong>{{ $responseData['accountName'] }}</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="table table-striped" >
                                            <thead  >
                                                <tr  >
                                                    <th style="width:8%;">VchDate </th>
                                                    <th style="width:10%;">Vch No </th>
                                                    <th style="width:20%;">Account Name</th>
                                                    <th style="width:20%;">Particulars</th>
                                                    <th style="width:10%;">Naration</th>
                                                    <th style="width:10%;">Debit</th>
                                                    <th style="width:10%;">Credit</th>
                                                    <th style="width:10%;">Balance</th>

                                                    @if (isset($chequeno))
                                                        <th style="width:10%;">Cheque No</th>
                                                    @endif
                                                    @if (isset($chequestatus))
                                                        <th style="width:10%;">Cheque Status</th>
                                                    @endif
                                                    @if (isset($clearingdate))
                                                        <th style="width:10%;">Clearing Date</th>
                                                    @endif
                                                    @if (isset($costcentre))
                                                        <th style="width:10%;">Cost Centre</th>
                                                    @endif
                                                    @if (isset($department))
                                                        <th style="width:10%;">Department</th>
                                                    @endif
                                                    @if (isset($executive))
                                                        <th style="width:10%;">Executive</th>
                                                    @endif
                                                    @if (isset($project))
                                                        <th style="width:10%;">Project</th>
                                                    @endif
                                                    @if (isset($foreigncurrency))
                                                        <th style="width:10%;">FC Debit</th>
                                                        <th style="width:10%;">FC Credit</th>
                                                        <th style="width:10%;">FC Balance</th>
                                                        <th style="width:10%;">FC Exchange Rate</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr  >
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td colspan="3"><strong> Op. Bal.:
                                                            {{ $responseData['Openingbalance'] }} </strong></td>
                                                    @if (isset($chequeno))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($chequestatus))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($clearingdate))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($costcentre))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($Department))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($executive))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($project))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($foreigncurrency))
                                                        <td colspan="4"><strong>FC Op. Bal.:
                                                                {{ $responseData['OpeningFCbalance'] }} </strong></td>
                                                    @endif
                                                </tr>
                                         
                                                @foreach ($responseData as $keys => $data)
                                                    @if (is_numeric($keys))
                                                        <tr >
                                                            <td style="width:8%;">{{ $data['data']->VchDate }}</td>
                                                            <td style="width:10%;">{{ $data['data']->VchNo }}</td>
                                                            <td style="width:20%;">{{ $data['data']->ACName }}</td>
                                                            <td style="width:20%;">{!! $data['data']->perticulars !!}</td>
                                                            <td style="width:10%;">{{ $data['data']->Narration }}</td>
                                                            <td style="width:10%;">{{ $data['debit'] }}</td>
                                                            <td style="width:10%;">{{ $data['credit'] }}</td>
                                                            <td style="width:10%;">{{ $data['balance'] }}</td>

                                                            @if (isset($chequeno))
                                                                <td style="width:10%;">{{ $data['data']->chq_no }}</td>
                                                            @endif
                                                            @if (isset($chequestatus))
                                                                <td style="width:10%;">{{ $data['data']->ch_status }}</td>
                                                            @endif
                                                            @if (isset($clearingdate))
                                                                <td style="width:10%;">{{ $data['data']->cl_date }}</td>
                                                            @endif
                                                            @if (isset($costcentre))
                                                                <td style="width:10%;">{{ $data['data']->costcentre }}</td>
                                                            @endif
                                                            @if (isset($department))
                                                                <td style="width:10%;">{{ $data['data']->DDeptName }}</td>
                                                            @endif
                                                            @if (isset($executive))
                                                                <td style="width:10%;">{{ $data['data']->executive }}</td>
                                                            @endif
                                                            @if (isset($project))
                                                                <td style="width:10%;">{{ $data['data']->ProjectName }}
                                                                </td>
                                                            @endif
                                                            @if (isset($foreigncurrency))
                                                                <td style="width:10%;">{{ $data['fcdebit'] }}</td>
                                                                <td style="width:10%;">{{ $data['fccreadit'] }}</td>
                                                                <td style="width:10%;">{{ $data['FCbalance'] }}</td>
                                                                <td style="width:10%;">{{ $data['data']->fcexrate }}</td>
                                                            @endif
                                                        </tr>
                                                    @endif
                                                @endforeach
                                          
                                                <tr >
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>{{ $responseData['totalDebit'] }}</td>
                                                    <td>{{ $responseData['totalCredit'] }}</td>
                                                    <td>&nbsp;</td>
                                                    @if (isset($chequeno))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($chequestatus))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($clearingdate))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($costcentre))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($department))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($executive))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($project))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($foreigncurrency))
                                                        <td><strong>{{ $responseData['totalFCCredit'] }} </strong></td>
                                                        <td>{{ $responseData['totalFCDebit'] }}</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    @endif
                                                </tr>
                                                <tr  >
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td colspan="3"><strong>Cl. Bal.:
                                                            {{ $responseData['Closingbalance'] }}</strong></td>
                                                    <td>&nbsp;</td>
                                                    <!-- <td>&nbsp;</td>
                                                                        <td>&nbsp;</td> -->
                                                    @if (isset($chequeno))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($chequestatus))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($clearingdate))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($costcentre))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($department))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($executive))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($project))
                                                        <td>&nbsp;</td>
                                                    @endif
                                                    @if (isset($foreigncurrency))
                                                        <td colspan="3"><strong>FC Cl. Bal.:
                                                                {{ $responseData['ClosingFCbalance'] }}</strong></td>
                                                        <td>&nbsp;</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="previewImg" hidden>
            </div>
        </div>

        </div>
    </div>


    @endsection
    @section('js')
    <script src="{{ asset('js/checkboxtree.min.js') }}"></script>
<script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // add select2 dropdown

            // $(document).on('click', '#submit', function(e) {
            //     e.preventDefault();
            //     var selectedElmsIds = [];
            //     var selectedElms = $('#tree-container').jstree("get_selected", true);
            //     $.each(selectedElms, function() {
            //         selectedElmsIds.push(this.id);
            //     });

            //     console.log(selectedElmsIds);
            //     //setting to hidden field
            //     document.getElementById('jsfields').value = selectedElmsIds.join(",");
            // });

            $('select').select2();
            //hiding datatable on load
            // $('#table_row').hide();

            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            // var selectdata = null;
            // var dropdownvalue = null;
            // $('#cancel').on('click', function() {
            //     window.location.href = window.location.href;
            // });

            // var userTable = $('#subledgerdata').DataTable();

            // $('#tree-container').closest('form').submit(function(e) {
            //     // e.preventDefault();

            //     // jsTreeInstance = $('#tree-container').jstree(true);
            //     var selectedNode = $('#tree-container').jstree(true).get_selected(true);
            //     console.log(selectedNode);
            //     // return false;
            //     var id = [],
            //         parent = [],
            //         text = [];
            //     $(selectedNode).each(function(index, vals) {
            //         $(vals.original).each(function(i, val) {
            //             console.log(val.Id);
            //             $('#jsfields').append($('<input>', {
            //                 type: 'hidden',
            //                 name: 'myCheckboxField[]',
            //                 value: val.Id
            //             }));
            //         });
            //     });

            //     // $(this).submit();
            // });

            // $("#treeView").jstree("open_all");

            //fill data to tree  with AJAX call
            // $('#tree-container').jstree({
            //     'plugins': ["wholerow", "checkbox"],
            //     'core': {
            //         'data': {
            //             "url": '{{ route("company.getAccountsTree", $id) }}',
            //             "plugins": ["wholerow", "checkbox"],
            //             "type": 'GET',
            //             "dataType": "json" // needed only if you do not supply JSON headers
            //         }
            //     }
            // });
            // .bind("loaded.jstree", function(event, data) {
            //     $(this).jstree("open_all");
            // });

            // $("input#selectVchToDate, input#selectVchFromDate").datepicker({
            //     format: 'dd-mm-yyyy'
            // });

            $("input[type='date']").on("change", function() {
                this.setAttribute(
                    "data-date",
                    moment(this.value, "YYYY-MM-DD")
                    .format(this.getAttribute("data-date-format"))
                )
            }).trigger("change")

            // var date = $("input[type='date']").datepicker({ dateFormat: 'dd-mm-yy' }).val();

            // setTimeout(() => {
            //     $("input[type='date']").on("change", function() {
            //         $(this).val(moment(this.value, "YYYY-MM-DD").format('DD/MM/YYYY'));
            //         // this.setAttribute("value",moment(this.value, "YYYY-MM-DD").format('DD/MM/YYYY'))
            //     }).trigger("change")
            // }, 2000);



            
$("#menu_level_tree").on('keypress', '.fa-plus , .fa-minus', function(event) {

$(this).trigger('click');
});



$("#menu_level_tree").on('click', '.fa-plus , .fa-minus', function(event) {
var id = $(this).data('id');
if($(`#menu_level_tree li[data-id='${id}']`).data('hasdata') == 'yes') {
    return false;
}
var level= $(this).data('level');
level=level+1;
// <input type='checkbox'   name='accounts[]'  value='${account['id']}'>&nbsp
$.ajax({
    url: "{{url('/')}}/{{$companyname}}/get-child-accounts/" + id,
    type: "get",
    async: false,
    success: function(data) {
        var result = JSON.parse(JSON.stringify(data));
        var accounts = result['accounts'];
        var html = '<ul>';
        for(let account of accounts) {

            var ga_val=account['ga'].trim();

            html = html + `<li data-id='${account['id']}'> <i class='fa fa-plus'  tabIndex='1'  data-level='${level}'  data-id='${account['id']}' ></i> <a  data-id='${account['id']}'  data-level='${level}'   class='ga_link' href='javascript:void(0);' data-ga='${ga_val}'>
        
            <label  class='tree_checkbox_lbl'>     <input type='checkbox'  class='menu_level_menus' data-parentid='${id}'  value='${account['id']}'  name='selected_accounts[]' /> &nbsp;${account['account_name']} (Level ${level})</label></a></li>`;
        }
        html = html + '</ul>';
        $(`#menu_level_tree li[data-id='${id}']`).append(html);
        $(`#menu_level_tree li[data-id='${id}']`).data('hasdata', 'yes');
        $("#menu_level_tree").hummingbird();
    },
    error: function() {}
});
});
 

$("#menu_level_tree").on('change', '.menu_level_menus', function() {
 
    var ischecked=$(this).is(':checked');

    var chkid=$(this).val();

    if(ischecked==true){

        $(`#menu_level_tree .menu_level_menus[data-parentid='${chkid}']`).prop('checked',true);

    }
    else{


        $(`#menu_level_tree .menu_level_menus[data-parentid='${chkid}']`).prop('checked',false);
    } 
});;


        });
    </script>
 
<script type="text/javascript">
     
        function dwonloaddoc() {

            $("#table_row").wordExport();
        }
   
        function dwonloadxls( ) {
       

            var tabla = $('div[id$=table_row]').html();

            var myBlob = new Blob([tabla], {
                type: 'text/html'
            });
            var url = window.URL.createObjectURL(myBlob);
            var a = document.createElement("a");

            document.body.appendChild(a);
            a.href = url;
            
             a.download = "general_ledger.xls";
          
    
            a.click();

            setTimeout(function() {
                window.URL.revokeObjectURL(url);
            }, 0);
            // });
        }
 
        function dwonloadimg() {
            html2canvas(document.getElementById("divpagecontent"))
                .then(function(canvas) {
                    console.log(canvas);
                    var anchorTag = document.createElement("a");
                    document.body.appendChild(anchorTag);
                    document.getElementById("previewImg")
                        .appendChild(canvas);
                    anchorTag.download = "filename.jpg";
                    anchorTag.href = canvas.toDataURL();
                    anchorTag.target = '_blank';
                    anchorTag.click();
                });

            html2canvas(document.getElementById("table_row"), {
                allowTaint: true,
                useCORS: true
            }).then(function(canvas) {
                var anchorTag = document.createElement("a");
                document.body.appendChild(anchorTag);
                document.getElementById("previewImg").appendChild(canvas);
                anchorTag.download = "filename.jpg";
                anchorTag.href = canvas.toDataURL();
                anchorTag.target = '_blank';
                setTimeout(() => {
                    anchorTag.click();
                }, 5000);

            });
        }


        function printReport(){

            var html = document.getElementById("table_row").innerHTML;

            printFromHtml(html);
        }
      
    </script>



    @endsection
    {{-- </body>
    
</html> --}}