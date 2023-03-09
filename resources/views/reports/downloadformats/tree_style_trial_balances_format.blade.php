<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    @include('reports.downloadformats.external_files')
     <style>
thead{display: table-header-group;}
tfoot {display: table-row-group;}
tr {page-break-inside: avoid;}
      </style>
 
    
</head>
<body>

    <div class="container-fluid">

     
       <table class="table table-striped table-bordered"  >
           <thead  > 
                <tr>
                <th style="width:90px;"><strong>Account Id</strong></th>  
                    <th style="width:150px;"><strong>Account Name</strong></th>  
                    <th style="width:150px;"><strong>Account Type</strong></th>  
                     <th  style="width:150px;"><strong>Parent Name</strong></th>  
                    <th><strong>Opening Debit Balances</strong></th><th><strong>Opening Credit Balances</strong></th><th><strong>Total Debits</strong></th><th><strong>Total Credits</strong></th><th><strong>Closing Debit Balances</strong></th><th><strong>Closing Credit Balances</strong></th>
                 @if($show_foreigncurrency==1)
                 <th><strong>Fcamt Opening Debit Balances</strong></th><th><strong>Fcamt Opening Credit Balances</strong></th><th><strong>Fcamt Total Debits</strong></th><th><strong>Fcamt Total Credits</strong></th><th><strong>Fcamt Total Credit Balances</strong></th><th><strong>Fcamt Total Debit Balances</strong></th>
                 @endif
            </tr>
           </thead>
           <tbody>  

           
           <tr>
                    <th>Account Report:</th>  
                     <th> {{$report_name}}     @if($open_childaccounts==true) (Drilldown) @endif </th>  
                    <td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td> <td> </td> 
                 @if($show_foreigncurrency==1)
                 <td> </td><td> </td><td> </td><td></td><td></td><td></td>
                 @endif
            </tr>
            @if($open_childaccounts==true)
            <tr>
                    <th>Account Name:</th>  
                     <th> {{$parent_account_name}} </th>  
                    <td> </td><td> </td><td> </td><td> </td><td> </td><td> </td> <td> </td> <td> </td> 
                    @if($show_foreigncurrency==1)
                     <td> </td><td> </td><td> </td><td></td><td></td><td></td> 
                     @endif
            </tr>
            @endif


           <tr>
                    <th>Company Name:</th>  
                     <th> {{$name_of_company}} </th>  
                    <th>Financial Year:</th><th>{{$financial_year}} </th><td> </td><td> </td><td> </td><td> </td> <td> </td> <td> </td> 
                 @if($show_foreigncurrency==1)
                 <td> </td><td> </td><td> </td><td></td><td></td><td></td>
                 @endif
            </tr>

            <tr>
                    <th>From Date:</th>  
                     <th>{{$start_date}}</th>  
                    <th>To Date: </th><th>{{$end_date}}</th><th>  @if($open_childaccounts==false) Account Level: @endif</th><th> @if($open_childaccounts==false) {{$account_level}} @endif</th><td> </td><td> </td> <td> </td>  <td> </td> 
                 @if($show_foreigncurrency==1)
                 <td> </td><td> </td><td> </td><td></td><td></td><td></td>
                 @endif
            </tr>


                            
                    
                @foreach ($accounts_data as  $account_id) 

                @php

                $tree_account_detail=$all_balances[$account_id]; 
			      	  @endphp


				  <tr>
                         <td>{{$account_id}}</td>
                         
                      <td>{{  $tree_account_detail['account_name']}}</td> <td>{{  $tree_account_detail['account_type']}}</td> 

					    <td> @if(!empty($tree_account_detail['parent_name'])) {{  $tree_account_detail['parent_name'] }} @endif </td>
					 
					 <td>{{$tree_account_detail['opening_debitbalance']}}</td><td>{{$tree_account_detail['opening_creditbalance']}}</td><td>{{$tree_account_detail['total_debit']}}</td><td>{{$tree_account_detail['total_credit']}}</td><td>{{$tree_account_detail['closing_debit_balance']}}</td><td>{{$tree_account_detail['closing_credit_balance']}}</td>
						 
						   @if($show_foreigncurrency==1)

							   <td>{{$tree_account_detail['fcamt_opening_debitbalance']}}</td>
							   <td>{{$tree_account_detail['fcamt_opening_creditbalance']}}</td>
							   <td>{{$tree_account_detail['fcamt_total_debit']}}</td>
							   <td>{{$tree_account_detail['fcamt_total_credit']}}</td>
							   <td>{{$tree_account_detail['fcamt_closing_debit_balance']}}</td>
							   <td>{{$tree_account_detail['fcamt_closing_credit_balance']}}</td> 
						   @endif

						   </tr>
                            
                        
                    

                @endforeach


 
           </tbody>

           <tfoot>
           <tr>
						<td  colspan="4"  class="text-center"   ><strong>Total:</strong></td>  
							  <td class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[0]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[1]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[2]}} @endif </strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[3]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[4]}} @endif</strong></td>  
				  			<td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[5]}} @endif</strong></td>
						@if($show_foreigncurrency==1)
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[6]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[7]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[8]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[9]}} @endif</strong></td>
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[10]}} @endif</strong></td>  
							  <td  class="text-center"><strong>@if(count($alltotals)>0){{$alltotals[11]}} @endif</strong></td> 
						@endif
					</tr>
      
					<tr>
						<th    colspan="4"  class='text-center'  ><strong>Difference:</strong></th>
 
						<th colspan="2" class='text-center'><strong>{{round($total_opening_debitcredit_diff,2)}}</strong></th><th  class='text-center' colspan="2"><strong>{{round($total_total_debit_credit_diff,2)}}</strong></th><th  class='text-center' colspan="2"><strong>{{round($total_closing_debit_credit_balance_diff,2)}}</strong></th>
 
						@if($show_foreigncurrency==1)
						<th colspan="2"  class='text-center'><strong>{{round($fcamt_total_opening_debitcredit_diff,2)}}</strong></th>
						<th colspan="2"  class='text-center'><strong>{{round($fcamt_total_total_debit_credit_diff,2)}}</strong></th>
						<th colspan="2"  class='text-center'><strong>{{round($fcamt_total_closing_debit_credit_balance_diff,2)}}</strong></th>

						@endif
					</tr>
 
				</tfoot>


       </table>

  

        </div>

</body>
</html>