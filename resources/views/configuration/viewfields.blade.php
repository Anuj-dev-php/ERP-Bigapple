@extends('layout.layout')
<style>
table th,td{text-align:center;}

.fieldvalue{text-align:left;}
#divpagination nav{float:right;}

  </style>
@section('content')
 
<h2  class="menu-title">View Fields</h2>
  <div class="pagecontent"  >
    <div class="container-fluid mtb-2" style="height:120%;;">

    <div class="row"  >
    <div class="col-10 mx-auto mtb-2"> 

    <a href="/{{$companyname}}/create-transactions"  class="btn btn-primary" style="float:right;" >Back</a>

    <h5>Transaction Table Name:&nbsp;&nbsp;{{$tablename}} </h5>

    </div>

    
    <div class="col-5  mx-auto ">
						
						<form  action="{{url('/')}}/{{$companyname}}/view-transaction-fields/{{$tranid}}" method="post" >
							@csrf
							<label class="lbl_control mlr-1">Search Field:</label>
						<input type="text"  name="searchtext" style="width:200px; display:inline-block;"  value="{{Session::get('viewtransactionfields_searchtext')}}" />
						<input type="submit" class="btn btn-primary" value="Search" />
						</form>
				</div>


        <div class="col-10 mx-auto"> 

        <div class="clearfix">
        <a  href="/{{$companyname}}/add-edit-transaction-field/{{$tranid}}" style="text-decoration:none;" class="btn btn-primary btn-md btn_float_right">Add New Field</a>
        <input type="button" class="btn btn-primary btn-md  btn_float_right" value="Delete Field" id="btn_delete"/>
      </div>




        <div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
        <table class="table table-striped" >
                <thead><tr><th   >Select</th><th  > Id</th><th  >Txn Name</th><th  >Field Label</th><th>Field Name</th><th>Field Type</th><th>Field Size</th><th>Field Function</th><th >Field Value</th><th>Tab Id</th><th >Created By</th></tr></thead> 
                <tbody> 
                  @foreach ($fields  as $field )
                  <tr id="tr_{{$field->Field_Name}}">
                    <td ><input type="checkbox" value="{{$field->Field_Name}}" name="chkfields" /></td>
                    <td  ><a href="/{{$companyname}}/add-edit-transaction-field/{{$tranid}}/{{$field->Field_Name}}">{{$field->Id}}</a></td>
                    <td  >{{$tablename}}</td>
                    <td>{{$field->fld_label}}</td>
                    <td>{{$field->Field_Name}}</td>
                    <td>{{$field->Field_Type}}</td>
                    <td>{{$field->Field_Size}}</td>
                    <td>{{$field->Field_Function}}</td>
                    <td class="fieldvalue" >{{$field->Field_Value}}</td>
                    <td>{{$field->Tab_Id}}</td>
                    <td>{{$field->Created_By}}</td>

                  </tr>
                    
                  @endforeach
                 </tbody>
            </table>
            </div>
            </div>
            </div>
          </div>
                            

        </div>

        <div class="col-10 mx-auto" id="divpagination"  > 
          {{$fields->links()}}
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

      $("#btn_delete").click(function(){

        var cnf=confirm("Are you sure to delete selected formats ?");

        if(cnf==false){
          return false;
        }

       var chks= $("input[name='chkfields']:checked");

       var checkedids=[];

       chks.each(function(){
        checkedids.push($(this).val()); 
       });
 

       var url="/{{$companyname}}/delete-transaction-fields";
       $.post(url,{'fieldids':checkedids,'tablename':'{{$tablename}}'},function(data,status){

         for(let chkid of checkedids){
           $("#tr_"+chkid).remove();

         }
         SnackbarMsg(data);

       });


      });
 
 

      </script>

 
 
@endsection
