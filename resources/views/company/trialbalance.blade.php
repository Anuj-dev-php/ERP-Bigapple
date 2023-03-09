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

    #selectVchFromDate,
    #selectVchToDate {
        position: relative;
        width: 150px;
        height: 30px;
        color: white;
        line-height: 1.8
    }

    #selectVchFromDate:before,
    #selectVchToDate:before {
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

    #selectVchFromDate::-webkit-calendar-picker-indicator,
    #selectVchToDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>

@section('content')
<h2 class="menu-title ">Table - Trial balance</h2>
{{-- <div class="container-fluid mtb-1"> --}}
<div class="pagecontent">
    <div class="card-body">
        <form id="accountform" name="accountform" method="post" action="{{route('company.showTrialBalance',$id)}}">
            <div id="jsfields"></div>
            @csrf
            <!-- <div class="col-12"> -->
            <div class="col-12">
                <div class='row'>
                    <div class="col-3 float-start">
                        <label class="lbl_control"> Account Name :</label>
                        <div id="tree-container" style="height:500px; overflow: auto;"></div>
                    </div>

                    <div class="col-9">
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
                                    <option value="{{ $cost->Id }}">{{ $cost->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="lbl_control">Department:</label>
                                <select class='form-control' name="deptId" id='deptId'>
                                    <option value=''>Select Department</option>
                                    @foreach ($deptdata as $dept)
                                    <option value="{{ $dept->Id }}">{{ $dept->DeptName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='row'>
                                <label class="lbl_control mt-1" for="radio1">Optional Columns:</label>

                                <div class="col-12 mt-1">

                                    <input class="form-check-input " type="checkbox" id="ChequeNo" name="ChequeNo" value="ChequeNo">
                                    <label class="check-1" for="ChequeNo"> Cheque No</label>

                                    {{-- Cheque No --}}

                                    <input class="form-check-input" type="checkbox" id="ChequeStatus" name="ChequeStatus" value="ChequeStatus"> <label class="check-1" for="ChequeStatus">Cheque Status</label>


                                    <input class="form-check-input" type="checkbox" id="ClearingDate" name="ClearingDate" value="ClearingDate">
                                    <label class="check-1" for="ClearingDate">Clearing Date</label>
                                    {{-- Clearing Date --}}

                                    <input class="form-check-input" type="checkbox" id="CostCentre" name="CostCentre" value="CostCentre">
                                    <label class="check-1" for="CostCentre">Cost Center</label>
                                    {{-- Cost Centre --}}

                                    <input class="form-check-input" type="checkbox" id="Department" name="Department" value="Department">
                                    <label class="check-1" for="Department">Department</label>
                                    {{-- Department --}}

                                    <input class="form-check-input" type="checkbox" id="Executive" name="Executive" value="Executive">
                                    <label class="check-1" for="Executive">Executive</label>
                                    {{-- Executive --}}

                                    <input class="form-check-input" type="checkbox" id="Project" name="Project" value="Project">
                                    <label class="check-1" for="Project">Project</label>
                                    {{-- Project --}}

                                    <input class="form-check-input" type="checkbox" id="ForeignCurrency" name="ForeignCurrency" value="ForeignCurrency"> <label class="check-1" for="ForeignCurrency">Foreign Currency</label>
                                    {{-- Foreign Currency --}}

                                    <input class="form-check-input" type="checkbox" id="ForeignExchange" name="ForeignExchange" value="ForeignExchange"> <label class="check-1" for="ForeignExchange">Foreign Exchange</label>
                                    {{-- Foreign Exchange --}}

                                    <input class="form-check-input" type="checkbox" id="show_zero_balance" name="show_zero_balance" value="1"> <label class="check-1" for="show_zero_balance">Show 0 Balance</label>
                                    {{-- Show 0 balance --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-3 p-2 pt-2">
                            <input type='submit' name='submit' class='btn btn-primary' id="submit" />
                            <button id="cancel" name="cancel" type="button" class='btn btn-primary'>Cancel</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>


    @endsection
    @section('js')
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


            $('#tree-container').closest('form').submit(function(e) {
                // e.preventDefault();

                // jsTreeInstance = $('#tree-container').jstree(true);
                var node = $('#tree-container').jstree("get_selected", true);

                // $("#breadcrumbs").text($('#drives_tree').jstree().get_path(node[0], ' > '));
                // var selectedNode = $('#tree-container').jstree().get_path(node[0]);
                // var selectedNode1 = $('#tree-container').jstree().get_path(node[1]);
                // var selectedNode2 = $('#tree-container').jstree().get_path(node[2]);
                var selected_nodes = [];
                // var parentN = [];
                var selectedNode = $('#tree-container').jstree(true).get_selected(true);
                // console.log(selectedNode);
                //     return false;
                // console.log(selectedNode);
                // return false;
                // console.log(selectedNode1);
                // console.log(selectedNode2);
                $.each(node, function(index, vals) {
                    // $(vals.original).each(function(i, val) {
                    //     console.log(i);
                    //     console.log(val);
                    // });
                    if ($.inArray(vals, selected_nodes) == -1) {
                        selected_nodes.push($('#tree-container').jstree().get_path(node[index]));
                    }
                });
                
                var parentN = selected_nodes.join(',');
                var ParentNodeText = parentN.split(',').filter(function(item, pos, self) {
                    return self.indexOf(item) == pos;
                });

                var id = [],
                    parent = [],
                    text = [];
                    
                $(selectedNode).each(function(index, vals) {
                    $(vals.original).each(function(i, val) {
                        $('#jsfields').append($('<input>', {
                            type: 'hidden',
                            name: 'myCheckboxField[]',
                            value: val.Id
                        }));
                    });
                });
                // console.log("selected Node="+ParentNodeText);

                $(ParentNodeText).each(function(index, vals) {
                    console.log(index);
                    console.log(vals);

                    $('#jsfields').append($('<input>', {
                        type: 'hidden',
                        name: 'myCheckboxFieldText[]',
                        value: vals
                    }));
                });


                // console.log(selected_nodes);
                // console.log("nodes="+selectedNode);
                // return false;

                // $(this).submit();
            });

            // $("#treeView").jstree("open_all");

            //fill data to tree  with AJAX call
            var parentNodes = [];
            $('#tree-container').jstree({
                'plugins': ["wholerow", "checkbox"],
                'core': {
                    'data': {
                        "url": '{{ route("company.getAccountsTree", $id) }}',
                        "plugins": ["wholerow", "checkbox"],
                        "type": 'GET',
                        "dataType": "json" // needed only if you do not supply JSON headers
                    }
                }
            }).bind("loaded.jstree", function(event, data) {
                $(this).jstree("open_all");
            }).on('select_node.jstree', function(event, data) {
                var glue = ' > ';
                parentNodes.push($('#tree-container').jstree().get_path(data.node, glue, false));
            });

            /*$('#tree-container').on('changed.jstree', function(e, data) {
                var i, j, r = [];
                // console.log(JSON.stringify(data, null, 2));
                var nodesOnSelectedPath = Object.keys(data.selected.reduce(function(acc, nodeId) {
                    var node = data.instance.get_node(nodeId);
                    console.log(node);
                    node.parents.forEach(function(id) {
                        acc[id + '_anchor'] = 1;
                    });
                    acc[node.text] = 1;
                    return acc;
                }, {}));

                alert('Selected: ' + nodesOnSelectedPath.join(', '));
                console.log('Selected: ' + nodesOnSelectedPath.join(', '));


            });*/

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
        });
    </script>
    @endsection
    {{-- </body>
    
</html> --}}