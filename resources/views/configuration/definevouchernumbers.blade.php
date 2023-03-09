@extends('layout.layout') @section('content')

<h2 class="menu-title">Define Voucher Numbers</h2>
<div class="pagecontent">
    <div class="container-fluid mtb-2">

        <div class="row mlr-5">


            <ul class="nav nav-tabs" id="menutablist" role="tablist">
                <li class="nav-item" role="createvouchertype">
                    <button class="nav-link active" id="create-voucher-type-tab" data-bs-toggle="tab" data-bs-target="#create-voucher-type" type="button" role="tab" aria-controls="createvouchertype" aria-selected="true">Create Voucher Types</button>
                </li>

                <li class="nav-item" role="definevouchernumber">
                    <button class="nav-link" id="define-voucher-number-tab" data-bs-toggle="tab" data-bs-target="#define-voucher-number" type="button" role="tab" aria-controls="definevouchernumber" aria-selected="true">Define Voucher No.</button>
                </li>


                <li class="nav-item" role="vouchernumberrenumbering">
                    <button class="nav-link" id="voucher-numbering-tab" data-bs-toggle="tab" data-bs-target="#voucher-numbering" type="button" role="tab" aria-controls="vouchernumberrenumbering" aria-selected="true">Voucher No. Renumbering</button>
                </li>

            </ul>



            <div class="tab-content">
                <div class="tab-pane fade show active small" id="create-voucher-type" role="tabpanel" aria-labelledby="create-voucher-type-tab">

                    <div class="row mtb-3">
                        <div class="col-2 text-start">
                            <label class="lbl_control">Select Voucher Type:</label>
                        </div>
                        <div class="col-3">
                            <select class="form-control select-configure " name="vouchertype" id="ddn_select_voucher_type" style="width:200px;">
                                <option value="0">Root</option>
                                @foreach($vchtypes as $vchtype)
                                <option value="{{$vchtype->Id}}">{{$vchtype->Name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-2">
                            <label class="lbl_control">Enter Sub Type Name:</label>
                        </div>

                        <div class="col-2">
                            <input type="text" id="txtsubtypename" class="form-control" />
                        </div>


                        <div class="col-2">
                            <input type="button" class="btn btn-primary btn-md" value="Submit" id="btn_create_voucher_type" />
                        </div>

                    </div>

                    <div class="row mtb-3">
                        <div class="col-3"></div>
                        <div class="col-6 ">
                        <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                            
                                <table data-order='[[ 0, "desc" ]]' id="table_define_voucher_types" style="width:100%;" class="table table-striped ">
                                    <thead>
                                        <th>Id</th>
                                        <th>Subtype Name</th>
                                    </thead>
                                </table>
                        </div>
                    </div>
                    </div>


                        </div>

                    </div>



                </div>
                <div class="tab-pane fade small" id="define-voucher-number" role="tabpanel" aria-labelledby="define-voucher-number-tab">


                    <div class="row mtb-3">
                        <div class="col-2 text-start">
                            <label class="lbl_control">Select Voucher Type:</label>
                        </div>
                        <div class="col-2">
                            <select class="form-control select-configure"  style="width:200px;" name="vouchertype" id="ddn_select_voucher_type_for_number">
                                <option value="">Select Voucher Type</option>
                                @foreach($vchtypes as $vchtype)
                                <option value="{{$vchtype->Id}}">{{$vchtype->Name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-1">
                            <label class="lbl_control">Enter New Series:</label>
                        </div>

                        <div class="col-1">
                            <input type="text" id="txtprefix" class="form-control "  maxlength="40" />
                        </div>


                        <div class="col-1">
                            <input type="number" id="txtnumber" class="form-control" min="1" />
                        </div>


                        <div class="col-1">
                            <input type="text" id="txtsuffix" class="form-control " maxlength="40" />
                        </div>


                        <div class="col-2">
                            <input type="button" class="btn btn-primary btn-md" value="Submit" id="btn_create_voucher_number" />
                        </div>

                    </div>



                    <div class="row mtb-3">
                    <div class="col-3"></div>
                        <div class="col-6"  >
                        <div class="card">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
                                <table data-order='[[ 0, "desc" ]]' id="table_define_voucher_numbers" style="width:100%;" class="table table-striped">
                                    <thead>
                                        <th>Id</th>
                                        <th>Prefix</th>
                                        <th>Number</th>
                                        <th>Suffix</th>
                                    </thead>
                                </table>
                            </div>
                            </div>
                            </div>


                        </div>

                    </div>




                </div>

                <div class="tab-pane fade small" id="voucher-numbering" role="tabpanel" aria-labelledby="voucher-numbering-tab">

                    <h1>Define voucher number renumbering</h1>
                </div>

            </div>




        </div>




    </div>
</div>

</div>
</div>


@endsection @section('js') {{-- ROLE --}}

<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>

<script type="text/javascript">
    var tbldefinevouchertypes,tbldefinevouchernumbers;

    $(function() {

        tbldefinevouchertypes = $('#table_define_voucher_types').DataTable({
            "paging":false,
            "pageLength":4,
            "bFilter": false,
            "bInfo": false,
            "ordering":false,
            "ajax": '/{{$companyname}}/get-sub-voucher-datatable-types',
            "lengthChange": false,  
            searching: false,
            "columns": [{
                data: 'Id', 
            }, {
                data: 'Name'
            }]
        });


        $('#table_define_voucher_types').on('draw.dt', function() {
            $('#table_define_voucher_types').Tabledit({
                url: '/{{$companyname}}/update-sub-voucher-type',
                dataType: 'json',
                columns: {
                    identifier: [0, 'Id'],
                    editable: [
                        [1, 'Name']
                    ]
                },
                buttons: {

                    edit: {
                        class: "btn btn-default",
                        html: '<span ><i class="fas fa-edit"></i></span>',
                        action: 'edit'
                    },
                    delete: {
                        class: "btn btn-default",
                        html: '<span ><i class="fa fa-trash" aria-hidden="true"></i></span>',
                        action: 'delete'
                    },


                },
                onSuccess: function(data, textStatus, jqXHR) {
                    if (data.action == 'delete') {
                        $('#' + data.id).remove();

                        var subvouchertypeurl = '/{{$companyname}}/get-sub-voucher-datatable-types' ;

                        tbldefinevouchertypes.ajax.url(subvouchertypeurl).load();
                        SnackBar({
                            message: "Sub Voucher Type Deleted successfully",
                            status: 'success'
                        });

                    } else if (data.action == 'edit') {

                        SnackBar({
                            message: "Sub Voucher Updated successfully",
                            status: 'success'
                        });
                    }
                }
            });
        });



        tbldefinevouchernumbers = $('#table_define_voucher_numbers').DataTable({
            "paging": false,
            "bFilter": false,
            "bInfo": false,
            "ordering":false,
            "ajax": '/{{$companyname}}/get-voucher-numbers/0',
            "lengthChange": false,
            searching: false,
            "columns": [{
                data: 'Id', 
            }, 
            {
                data: 'Prefix', 
            }
            ,
            {
                data: 'Number', 
            }
          ,
          {
                data: 'Suffix', 
            } 
          
          ]
        });
 


        
        $('#table_define_voucher_numbers').on('draw.dt', function() {
 
            $('#table_define_voucher_numbers').Tabledit({
                url: '/{{$companyname}}/update-voucher-number',
                dataType: 'json', 
                columns: {
                    identifier: [0, 'Id'],
                    editable: [
                        [1, 'Prefix'] ,
                        [2, 'Number'] ,
                        [3, 'Suffix'] 

                    ]
                },
                buttons: {

                    edit: {
                        class: "btn btn-default",
                        html: '<span ><i class="fas fa-edit"></i></span>',
                        action: 'edit'
                    },
                    delete: {
                        class: "btn btn-default",
                        html: '<span ><i class="fa fa-trash" aria-hidden="true"></i></span>',
                        action: 'delete'
                    },


                },
                onSuccess: function(data, textStatus, jqXHR) {
                    

                    if (data.action == 'delete') {
                        $('#' + data.id).remove();

                        var vouchernumberurl = '/{{$companyname}}/get-voucher-numbers/' + $("#ddn_select_voucher_type_for_number").val();

                        tbldefinevouchernumbers.ajax.url(vouchernumberurl).load();
                        SnackBar({
                            message: "Voucher Number Deleted successfully",
                            status: 'success'
                        });

                    } else if (data.action == 'edit') {

                        SnackBar({
                            message: "Voucher Number Updated successfully",
                            status: 'success'
                        });
                    }
                  
                }
                ,
                onFail:function() { 
                  alert("error");
                },

            });
        });








    })

    $("#btn_create_voucher_type").click(function() {

        var subtypename = $("#txtsubtypename").val();

        if (subtypename == '') {
            SnackBar({
                message: "Please enter Sub Voucher Type Name",
                status: 'error'
            });

            return false;
        }

        var parent = $("#ddn_select_voucher_type").val();

        $.post('/{{$companyname}}/addsubvouchertype', {
            'parent': parent,
            'subtypename': subtypename
        }, function(data, status) {
            SnackbarMsg(data);
            var resultarray = JSON.parse(JSON.stringify(data));

            $("#txtsubtypename").val("");


            tbldefinevouchertypes.ajax.url("/{{$companyname}}/get-sub-voucher-datatable-types").load();

        });;



    });

 

    $("#ddn_select_voucher_type_for_number").change(function() {
      var vouchernumberurl = '/{{$companyname}}/get-voucher-numbers/' + $("#ddn_select_voucher_type_for_number").val();
 
      tbldefinevouchernumbers.ajax.url(vouchernumberurl).load();

    });

    $("#btn_create_voucher_number").click(function(){

      var vouchertypeid=$("#ddn_select_voucher_type_for_number").val();
      var prefix=$("#txtprefix").val() ;
      var number=$("#txtnumber").val();
      var suffix=$("#txtsuffix").val();


      if(vouchertypeid==''){
        SnackBar({ 
            message: "Please select Voucher Type",status:'error'
        });
        return false;
      }

      if(prefix==''){
        SnackBar({ 
            message: "Please Enter Prefix",status:'error'
        });
        return false;

      }


      if(suffix==''){

        SnackBar({ 
            message: "Please Enter Suffix",status:'error'
        });
        return false;

      }

      
      if(number==''){

          SnackBar({ 
              message: "Please Enter Number",status:'error'
          });
          return false;

          }



      $.post('/{{$companyname}}/add-voucher-number-to-type',{'voucher_type':vouchertypeid,'prefix':prefix, 'number':number,'suffix':suffix},function(data,status){
        SnackbarMsg(data);
        var vouchernumberurl = '/{{$companyname}}/get-voucher-numbers/' + vouchertypeid;
          tbldefinevouchernumbers.ajax.url(vouchernumberurl).load();


      })








    });

 

</script>
@endsection
