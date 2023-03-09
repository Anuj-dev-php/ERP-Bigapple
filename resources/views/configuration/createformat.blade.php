@extends('layout.layout')
@section('content')
<h2  class="menu-title">Create Format</h2>
  <div class="pagecontent"  >
 
    <div class="container-fluid mtb-2">

    <div class="row">
        <div class="col-10 mx-auto "> 
          <div class="clearfix">
        <a  href="/{{$companyname}}/add-edit-create-format" style="text-decoration:none;" class="btn btn-primary btn-md btn_float_right">Add </a>
        <input type="button" class="btn btn-primary btn-md btn_float_right" value="Delete" id="btn_delete"/>
      </div>
      	<div class="card mtb-2 ">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
        <table class="table table-striped"  >
                <thead><tr><th  style="width:10%;" >Select</th><th  style="width:20%;">Temp Id</th><th style="width:20%;">Temp Name</th><th  style="width:20%;">Txn Name</th><th>Crystal Report</th><th style="width:10%;">Edit</th></tr></thead> 
                <tbody>

                @if(count($printheaders)==0)
                <tr><td colspan='5' class='text-left'>No Format Found</td></tr>
                @else
                  @foreach ( $printheaders as $printheader)
                  <tr id="tr_{{$printheader->Tempid}}">
                    <td><input type="checkbox" name="chktemp" value="{{$printheader->Tempid}}" ></td>
                    <td>{{$printheader->Tempid}}</td>
                    <td>{{$printheader->TempName}}</td>
                    <td>{{$printheader->Txn_Name}}</td>
                    <td>@if(!empty($printheader->crystal)) {{$printheader->crystal}} @endif</td>
                    <td>
                      <button class="tbl_btn">
                      <a class="tbl_link" href="/{{$companyname}}/add-edit-create-format/{{$printheader->Tempid}}">Edit </a></button></td>
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

 
@endsection
@section('js')
    {{-- ROLE --}}

    <script type="text/javascript">

      $("#btn_delete").click(function(){

        var cnf=confirm("Are you sure to delete selected formats ?");

        if(cnf==false){
          return false;
        }

       var chks= $("input[name='chktemp']:checked");

       var checkedids=[];

       chks.each(function(){
        checkedids.push($(this).val()); 
       });
 

       var url="/{{$companyname}}/delete-create-format";
       $.post(url,{'tempids':checkedids},function(data,status){

         for(let chkid of checkedids){
           $("#tr_"+chkid).remove();

         }
         SnackbarMsg(data);

       });


      });
 
 

      </script>

 
 
@endsection
