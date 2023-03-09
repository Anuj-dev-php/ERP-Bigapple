@extends('layout.layout')
@section('content')
 
<h2  class="menu-title">Email Configuration</h2>
  <div class="pagecontent"  >
    <div class="container-fluid mtb-2">

    <div class="row">
        <div class="col-10 mx-auto mtb-4"> 
          <div class="clearfix">
        <a  href="/{{$companyname}}/add-edit-email-configuration" style="text-decoration:none;" class="btn btn-primary btn-md btn_float_right">Add </a>
        <input type="button" class="btn btn-primary btn-md  btn_float_right" value="Delete" id="btn_delete"/>
        </div>

        <div class="card mtb-2">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
        <table class="table table-striped  mtb-2"  >
                <thead><tr><th>Select</th><th>Email Conf Name</th><th>Table Name</th><th>Print Template</th> <th>Edit</th></tr></thead> 
                <tbody>
                  @if(count($emailconfigs)==0)

                  <tr><td colspan='5'>No Email Configuration</td></tr>

                  @else

                  @foreach($emailconfigs as $emailconfig)

                  <tr id="tr_{{$emailconfig->id}}"><td><input type="checkbox"  class="chkemailconfiggroup" value="{{$emailconfig->id}}" /></td><td>{{$emailconfig['email_configuration_name']}}</td><td>{{$emailconfig->table_name}}</td><td>{{$emailconfig->TempName}}</td> <td><a href="/{{$companyname}}/add-edit-email-configuration/{{$emailconfig->id}}">Edit</a></td></tr>

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

        var chks=$(".chkemailconfiggroup:checked");

        if(chks.length==0){
          SnackBar({ 
            message:"Please select at least 1 Email Configuration",status:'error'
        });
          return false;
        }


        var checked=[];

        chks.each(function(){
          checked.push($(this).val()); 
        });
 

        var url='/{{$companyname}}/email-configuration-delete';
        $.post(url,{'ids':checked},function(data,status){
          SnackbarMsg(data);

          for(let grpid of checked){
            $("#tr_"+grpid).remove();
          }


        })
 
        

      });

      </script>

 
 
@endsection
