@php
use App\Models\VchType;
use App\Models\Account;
@endphp
@extends('layout.layout')
@section('content')
<style>
/* #fieldslist dd{font-weight:600;} */
    </style>
    <div>
        <span id="showID"></span>
    </div>
    
  <h2  class="menu-title   mb-5 font-size-18">@if(!empty($tranaccount)) Edit @else Add @endif Fa Integration  </h2>

  <div class="pagecontent"   id="divpagecontent" > 
    <form method='POST' class="mtb-3" id='frm_add_integration' action='{{url('/')}}/{{$companyname}}/fa-integration/submitadd'> 
        @csrf
        <div class="row mlr-2"   >
            
            @if(!empty($tranaccount))  <input type="hidden" name="tran_account_id"  value="{{$tranaccount->Id}}" /> @endif

            <div class="form-group col-4"><label class="lbl_control_inline ">Template Id:</label> 
            <input type="text" class="form-control select-configure faspace"  name="template_id" required="true"  autocomplete="off"  value="@if(!empty($tranaccount)){{$tranaccount->TemplateId}} @endif"/>
             </div>
            
            <div class="form-group col-4"><label class="lbl_control_inline">Description:</label> 
            <input type="text" class="form-control select-configure faspace"  autocomplete="off"  name="description"  required="true"  value="@if(!empty($tranaccount)){{$tranaccount->Description}} @endif" />
            </div>
            
            <div class="form-group col-4"><label class="lbl_control_inline">Transaction:</label> 
          
            <select  name="transaction"   class="form-control select-configure faspace"  required="true"  id="ddnTransactions"  >
                <option  value="">Select Transaction</option>
                @foreach($transactions as $transactionid=>$transactionlabel)
                <option value="{{$transactionid}}" @if(!empty($tranaccount) && $tranaccount->Transaction===$transactionid)  selected="selected" @endif>{{$transactionlabel}}</option>
                @endforeach
            
            </select>
           
        </div>

            <div class="form-group col-4 "><label class="lbl_control_inline">Voucher Type:</label> 
            <select name="vouchertype"  required="true"  class="form-control select-configure faspace" id="addfaintegration_ddnVoucherType" >
                <option value="">Select Voucher Type</option>
                @foreach($vchtypes as $vchtypeid=>$vchname)
                <option value="{{$vchtypeid}}" @if(!empty($tranaccount) && $tranaccount->VchType==$vchtypeid) selected="selected"  @endif>{{$vchname}}</option>
                @endforeach
            </select></div>
            @php
            if(!empty($tranaccount)){
                
                $vchtype=VchType::where('Id',$tranaccount->VchType)->first();

                $subvchtypes=$vchtype->subvchtypes()->get(); 
 
            }
         
            @endphp
            
            <div  class="form-group col-4 mtb-2"><label class="lbl_control_inline" style="width:200px">Voucher Sub Type:</label> 
                <select name="vouchersubtype"  required="true"  class="form-control select-configure faspace"  id="addfaintegration_ddnvouchersubtype"><option value="">Select Voucher Sub Type</option>
                 @if(!empty(  $subvchtypes))
                    @foreach($subvchtypes as $subvchtype)
                    <option value="{{$subvchtype->Id}}"  @if(!empty($tranaccount) && $subvchtype->Id==$tranaccount->VchSubTypes) selected="selected"  @endif>{{$subvchtype->Name}}</option>
                    @endforeach
                 
                 @endif
            </select></div>
            @php
 

            if(!empty($tranaccount)){

                $accountname=$tranaccount->Account;
                if($accountname=="Party Id" || $accountname=="Emp Id"){
                    $accountid= $accountname;
                }else{
                    $accountid=  Account::where('ACName', $accountname)->value('Id'); 
                }
            

            }

            @endphp
       
            <div  class="form-group col-4 mtb-2"><label class="lbl_control_inline" style="width:300px">Main A/C:</label> 
           
                <select name="mainaccount"  id="ddnMainAccount"   required="true"  class="form-control select-configure faspace"> 
                    <option>Select Main</option>
                </select>
             
            </div>

            <div  class="form-group col-4 mtb-2 spaceExa"><label class="lbl_control_inline">Main A/C By To:</label> 
            
                <select name="mainaccount_byto"   required="true"  class="form-control select-configure faspace"><option>Select Main A/C By To</option>
                <option value="By"  @if( !empty($tranaccount) && trim($tranaccount->mainaccount_byto)=="By") selected @endif>By</option> 
                    <option value="To"  @if( !empty($tranaccount) && trim($tranaccount->mainaccount_byto)=="To") selected @endif>To</option> 
            </select>
             
            </div>

            
            <div  class="form-group col-4 mtb-2 spaceExa"><label class="lbl_control_inline" style="width:300px">Main A/C Formula:</label> 
           
             <input type='text' name="main_account_formula"  class="form-control select-configure faspace" value="@if( !empty($tranaccount)){{$tranaccount->mainaccount_formula}} @endif"  required />
                
            </div>



            <div   class="form-group col-4  mtb-2 checkboxspace "><input type="checkbox" name="make_default"  @if(!empty($tranaccount) && trim($tranaccount->is_default)==='True') checked='checked'  @endif  /> &nbsp;&nbsp;<label class="lbl_control_inline">Make Default</label></div>
         
        </div>  

        <div class="row">
            <div class="col-6 mx-auto"      >
                @php
                if(!empty($tranaccount)){

                    $noofrows=count($tranaccdets);
                    
                }
                else{
                    $noofrows=1;
                }

                @endphp

                <div class="clearfix">

            <input type="button" class="btn btn-primary btn-sm  btn_float_right" value="Add Another"  id="btn_add_another"  data-row="{{$noofrows}}"  />
            
            </div>
            <div class="card mtb-2" style="width:100%;">
					<div class="card-body">
						<div class=" mx-auto table-responsive">
            
            <table  class="table table-striped" >
                    <thead  ><th style="width:140px">By / To</th><th >Account Name</th><th>Formula</th><th>Delete</th></thead>
                    <tbody id="tbodyaccounts">

                    @if(empty($tranaccount))
                    <tr id="tr_1">
                            <td><select  class="form-control inline_control"  name="byto[]" required>
                                            <option value="By">By</option>
                                            <option value="To">To</option>
                                    </select></td><td> <select style="width:200px" class=" form-select"  name="accounts[]" id="ddnselectaccounts_1" required></select></td><td><input type="text" name="formula[]" class="form-control" required/></td><td><a href="javascript:void(0);" class="lnk_delete_row" data-row="1"> <i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td>
                    </tr>

                    @else
                             @php $tranaccdetindex=1; @endphp
                             
                                @foreach($tranaccdets as $tranaccdet)
                                @php 
                                           $bytovalue=trim($tranaccdet['By/To']) ;
 

                                          
                                            @endphp
                                <tr id="tr_{{$tranaccdetindex}}">
                                        <td><select style="width:40%"  class="form-control inline_control"   name="byto[]" required="true">
                                                        <option value="">Select By Or To </option>
                                                        <option value="By"  @if($bytovalue=="By")  selected    @endif  >By</option>
                                                        <option value="To"  @if($bytovalue=="To") selected   @endif >To </option>
                                                </select></td><td>
                                                <select style="width:40%"  class="form-control inline_control"  name="accounts[]" id="ddnselectaccounts_{{$tranaccdetindex}}" required>
                                            
                                                </select></td><td><input type="text" name="formula[]"  autocomplete="off"   value="{{$tranaccdet['Formula']}}" class="form-control" required/></td><td><a href="javascript:void(0);" class="lnk_delete_row" data-row="{{$tranaccdetindex}}"> <i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td>
                                </tr>

                                @php  $tranaccdetindex++; @endphp
                                @endforeach



                    @endif
                   
               
            </tbody>
                </table>
                
            </div>
            
            </div>
            
            </div>



            </div>
            <div class="col-12 text-center">
            <input type="submit" class="btn btn-primary  btn-md" value="Submit" />
            </div>
            <div class="col-5 text-left mlr-5"  >
                <h5>Fields Are:</h5>
                <!-- id="fieldslist"  -->
                            <dl  style=" display: grid;
  grid-template-columns: auto auto auto auto auto auto auto;
  gap: 15px; ">
               
                <!-- <dd>Not Found</dd> -->
<!-- 
<dd>Pratibha</dd>

<dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd><dd>Pratibha</dd>     
           
                </dl> -->
                <table  class="table table-bordered" style="border:1px solid black">
                    <tbody id="fieldslist">
                        <tr><td colspan='7'>No Fields Found</td></tr>
               
                   </tbody>
            </table>
                            </div>

        </div> 
        </form>

    </div>

 
@endsection
@section('js')
    {{-- ROLE --}}
    
 
    <script type="text/javascript">

@if(!empty($tranaccount))
$(function(){
    loadFieldlist('{{$tranaccount->Transaction}}');
    
})

@endif

        $("#addfaintegration_ddnVoucherType").change(function(){
            var vouchertypeid=$(this).val();
            var companyname="{{$companyname}}";

            var url="{{url('/')}}"+'/'+companyname+'/get-sub-voucher-types/'+vouchertypeid; 

            $.get(url,function(data,status){

               var resultarray= JSON.parse(JSON.stringify(data));
                var subvchtypes= resultarray['subvchtypes'] ; 

                $("#addfaintegration_ddnvouchersubtype option:not(:first)"). remove(); 

                for(let subvchid in subvchtypes){

                    $("#addfaintegration_ddnvouchersubtype").append("<option value='"+subvchid+"'>"+subvchtypes[subvchid]+"</option>"); 

                }
            });
 
 

        });
 
        var companyname="{{$companyname}}";
        
        var searchaccounturl="{{url('/')}}"+'/'+companyname+'/search-accounts';
        var searchsubaccounturl="{{url('/')}}"+'/'+companyname+'/search-sub-accounts';
        $(document).ready(function() { 

            @if(!empty($tranaccount))
            
            initSelect2Search("#ddnMainAccount",searchaccounturl,"Select Main Account" ); 

            addSelect2SelectedOption("#ddnMainAccount",'{{$accountname}}','{{$mainaccountid}}');

            @else
            
            initSelect2Search("#ddnMainAccount",searchaccounturl,"Select Main Account"); 

            @endif

           

               @if(!empty($tranaccount) &&  !empty($tranaccdets))
                      @php
                       $tranaccdetindex=1;

                       foreach($tranaccdets as $tranaccdet){
                          $selectedaccountid=$tranaccdet['accountid'];
                          $selectedaccountname=$tranaccdet['accountname'];
                           @endphp

                                @if($selectedaccountid=='line_acc')
                                 initSelect2Search("#ddnselectaccounts_{{$tranaccdetindex}}",searchsubaccounturl,"Select  Account" );
                                addSelect2SelectedOption("#ddnselectaccounts_{{$tranaccdetindex}}",'{{$selectedaccountname}}','{{ $selectedaccountid}}');
                                
                             @else
                                    initSelect2Search("#ddnselectaccounts_{{$tranaccdetindex}}",searchsubaccounturl,"Select  Account" );
                                    addSelect2SelectedOption("#ddnselectaccounts_{{$tranaccdetindex}}",'{{$selectedaccountname}}',{{ $selectedaccountid}});
                                   
                                @endif


                           @php


                        $tranaccdetindex++;
                       }
                      
                      
                      @endphp 

               @else

                  initSelect2Search("#ddnselectaccounts_1",searchsubaccounturl,"Select  Account");


               @endif


  
             
              });


           $("#btn_add_another").click(function(){
               var row=$(this).data("row");
               row=row+1; 
               $("#tbodyaccounts").append("<tr id=\"tr_"+row+"\"><td><select name=\"byto[]\" class=\"form-control\" required=\"true\"><option value=\"\">Select By Or To</option><option value=\"By\">By</option><option value=\"To\">To</option></select></td><td> <select name=\"accounts[]\"  class=\"form-control\"  id=\"ddnselectaccounts_"+row+"\" required=\"true\"></select></td><td><input type=\"text\" autocomplete=\"off\" name=\"formula[]\" class=\"form-control\" required=\"true\"/></td><td><a href='javascript:void(0);' class='lnk_delete_row' data-row="+row+"> <i class=\"fa fa-lg fa-trash\" aria-hidden=\"true\"></i></a></td></tr>"); 
               $(this).data("row",row);
               
                initSelect2Search("#ddnselectaccounts_"+row,searchsubaccounturl,"Select  Account");
 

           });

           $("#tbodyaccounts").on("click",".lnk_delete_row",function(){
                 var row=$(this).data("row"); 
                 $("#tbodyaccounts #tr_"+row).remove();

           })


           function loadFieldlist(transactionid){

            $("#fieldslist").empty();



            // $("#fieldslist dd").remove();

                var url="{{url('/')}}"+'/{{$companyname}}/transaction-all-fields';

                $.post(url,{'tran_table':transactionid},function(data,status){

                    var fields=JSON.parse(JSON.stringify(data)); 

                    var size = 7; var arrayfieldnames = [];
                    for (var i=0; i<fields.length; i+=size) {
                        arrayfieldnames.push(fields.slice(i,i+size));
                    }

                    var tablerows='';

                    for(let arrayfields of arrayfieldnames ){

                        tablerows=tablerows+"<tr>";
                        for(let field of arrayfields){
                            tablerows=tablerows+`<td>${field['name']}</td>`;
                        }
                        tablerows=tablerows+"</tr>";
 

                    } 

                    
                    $("#fieldslist").append(tablerows);
                    // $("#fieldslist dd").remove();

                    // for(let field of fields){
                    // $("#fieldslist").append("<dd>"+field['name']+"</dd>");

                    // } 
                });


           }
        

           $("#ddnTransactions").change(function(){

            var transactionid=$("#ddnTransactions").val();
            loadFieldlist(transactionid);
 
             

           });
 
 
    </script>
     
 
@endsection
