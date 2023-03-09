<!DOCTYPE html>
 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
thead{display: table-header-group;}
tfoot {display: table-row-group;}
tr {page-break-inside: avoid;}

.right-text{text-align:right!important;}
    .left-text{text-align:left!important;}
      </style>
 
    
</head>
<body>

    <div class="container-fluid">

     @if($report_type=="vertical")
       <table class="table table-striped table-bordered"  >
           <thead  > 
                <tr>
                    <th style="width:150px;"><strong>Account Name</strong></th>  
                    <th  style="width:150px;"><strong>Account Type</strong></th>  
                     <th  style="width:150px;"><strong>Parent Name</strong></th>  
                      <th  style="width:300px;" ><strong>Amount</strong></th> 
                 @if($show_foreigncurrency==1)
                 <th  style="width:300px;"><strong>Fc Amount</strong></th> 
                 @endif
            </tr>
           </thead>
           <tbody>  

           
           <tr>
                    <th>Account Report:</th>  
                     <th> {{$report_name}} ({{$report_type}})    </th>  
                    <td> </td><td> </td> 
                 @if($show_foreigncurrency==1)
                 <td> </td> 
                 @endif
            </tr>
       


           <tr>
                    <th>Company Name:</th>  
                     <th> {{$name_of_company}} </th>  
                    <th>Financial Year:</th><th>{{$financial_year}} </th> 
                 @if($show_foreigncurrency==1)
                 <td> </td> 
                 @endif
            </tr>

            <tr>
                    <th>From Date:</th>  
                     <th>{{$start_date}}</th>  
                    <th>To Date: </th><th>{{$end_date}}</th> 
                 @if($show_foreigncurrency==1)
                 <td> </td> 
                 @endif
            </tr>


            @foreach($all_accounts as $account_id)

            @php
				      $result=  $all_balances[$account_id];
					@endphp
 
                         @if (in_array($account_id,array(3,4)))

                         
					<tr>
						<td class="left-text"><strong>{{  $result['account_name']}}</strong></td>
						<td><strong>{{  $result['account_type']}}</strong></td>
						<td  ><strong>{{  $result['parent_name']}}</strong></td>
						<td  class="right-text"><strong>{{  $result['amount']}}</strong></td>
						@if($show_foreigncurrency==1)
						<td   class="right-text"><strong>{{$result['fc_amount']}}</strong></td>
						@endif
					</tr>

                         @else

                         
					<tr>
						<td class="left-text">{{  $result['account_name']}}</td>
						<td>{{  $result['account_type']}}</td>
						<td  >{{  $result['parent_name']}}</td>
						<td  class="right-text">{{  $result['amount']}}</td>
						@if($show_foreigncurrency==1)
						<td   class="right-text">{{$result['fc_amount']}}</td>
						@endif
					</tr>
                              
                         @endif




            @endforeach
 
           </tbody>

           <tfoot> 


           <tr> <td></td> <td></td> <td  class='right-text'       ><strong>Total Expenses:</strong></td><td  class="right-text"><strong>{{round((  $total_expenses ) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td    class="right-text"> <strong>{{round( (  $total_fcamt_expenses ) ,2)}} </strong></td>
					   @endif
					</tr>
					<tr>  <td></td> <td></td> <td  class='right-text'       ><strong>Total Incomes:</strong></td><td  class="right-text"><strong>{{round((  $total_incomes ) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td  class="right-text"><strong>{{round( (  $total_fcamt_incomes ) ,2)}}</strong></td>
					   @endif
					</tr>
                         @php
						$total_profit_amt=( ($total_incomes-$total_expenses)>0?($total_incomes-$total_expenses):0 );
						$total_profit_fc_amt=( ($total_fcamt_incomes-$total_fcamt_expenses)>0?($total_fcamt_incomes-$total_fcamt_expenses):0 );

						$total_loss_amt=( ($total_expenses-$total_incomes)>0?($total_expenses-$total_incomes ):0 );

						$total_loss_fc_amt=( ($total_fcamt_expenses-$total_fcamt_incomes)>0?($total_fcamt_expenses-$total_fcamt_incomes):0 );

					@endphp


                         <tr> <td></td>  <td></td><td     class='right-text'   ><strong>Total Profit:</strong></td><td  class="right-text"><strong>{{round((  $total_profit_amt) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td  class="right-text"><strong>{{round( ( $total_profit_fc_amt) ,2)}}</strong></td>
					   @endif
					</tr>

					<tr> <td></td>  <td></td> <td    class='right-text'    ><strong>Total Loss:</strong></td><td  class="right-text"><strong>{{round((  $total_loss_amt) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td  class="right-text"><strong>{{round( ( $total_loss_fc_amt) ,2)}}</strong></td>
					   @endif
					</tr> 

           </tfoot>


       </table>

       @else

       <table class="table table-striped table-bordered"  >
           <thead  > 
                <tr>
                    <th style="width:150px;"><strong>Account Name</strong></th>  
                    <th  style="width:150px;"><strong>Account Type</strong></th>  
                     <th  style="width:150px;"><strong>Parent Name</strong></th>  
                      <th  style="width:300px;" ><strong>Amount</strong></th> 
                 @if($show_foreigncurrency==1)
                 <th  style="width:300px;"><strong>Fc Amount</strong></th> 
                 @endif
                 <th style="width:150px;"><strong>Account Name</strong></th>  
                    <th  style="width:150px;"><strong>Account Type</strong></th>  
                     <th  style="width:150px;"><strong>Parent Name</strong></th>  
                      <th  style="width:300px;" ><strong>Amount</strong></th> 
                 @if($show_foreigncurrency==1)
                 <th  style="width:300px;"><strong>Fc Amount</strong></th> 
                 @endif
            </tr>
           </thead>
           <tbody>
           <tr>
                <td> <strong>Account Report:</strong></td>  <td><strong> {{$report_name}} ({{$report_type}})     </strong></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1) <td></td> @endif
              
                <td></td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1)<td></td> @endif
               </tr>

              <tr>
              <td><strong>Company Name:</strong></td>  <td><strong>{{$name_of_company}}</strong> </td>  <td><strong>Financial Year:</strong></td>  <td><strong>{{$financial_year}}</strong> </td>   @if($show_foreigncurrency==1)<td></td> @endif
              <td></td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1)<td></td> @endif
            
               </tr>

               
              <tr>
              <td><strong>From Date:</strong></td>  <td><strong>{{$start_date}}</strong> </td>  <td><strong>To Date:</strong></td>  <td><strong>{{$end_date}} </strong></td>   @if($show_foreigncurrency==1)<td></td> @endif
              <td></td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1)<td></td> @endif
            
               </tr>


           <tr>
                <td>Expenses</td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1) <td></td> @endif
                <td>Incomes</td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1)<td></td> @endif
              </tr>
              @php
                   $no_of_accounts=(count($expense_accounts)>count($income_accounts)?count($expense_accounts):count($income_accounts));
              @endphp


              @for ($i=0;$i<$no_of_accounts;$i++)
              <tr>

                    @if(array_key_exists($i,$expense_accounts))

                    @php
                         $expense_account_id=$expense_accounts[$i]; 
                         $result=  $all_balances[$expense_account_id];
                    @endphp
                    <td class="left-text">@if($expense_account_id==4)<strong>@endif{{  $result['account_name']}} @if($expense_account_id==4)</strong>@endif</td>
									<td>@if($expense_account_id==4)<strong>@endif{{  $result['account_type']}}@if($expense_account_id==4)</strong>@endif</td>
									<td  >@if($expense_account_id==4)<strong>@endif{{  $result['parent_name']}}@if($expense_account_id==4)</strong>@endif</td>
									<td  class="right-text">@if($expense_account_id==4)<strong>@endif{{  $result['amount']}}@if($expense_account_id==4)</strong>@endif</td>
									@if($show_foreigncurrency==1)
									<td   class="right-text">@if($expense_account_id==4)<strong>@endif{{$result['fc_amount']}}@if($expense_account_id==4)</strong>@endif</td>
									@endif

                    @else
                    <td></td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1) <td></td> @endif
                    @endif


                    @if(array_key_exists($i,$income_accounts))

                         @php
                              $income_account_id=$income_accounts[$i]; 
                              $result=  $all_balances[ $income_account_id];
                         @endphp
                         <td class="left-text">@if($income_account_id==3)<strong>@endif{{  $result['account_name']}}@if($income_account_id==3)</strong>@endif</td>
                              <td> @if($income_account_id==3)<strong>@endif {{  $result['account_type']}}@if($income_account_id==3)</strong>@endif</td>
                              <td  > @if($income_account_id==3)<strong>@endif{{  $result['parent_name']}}@if($income_account_id==3)</strong>@endif</td>
                              <td  class="right-text">@if($income_account_id==3)<strong>@endif{{  $result['amount']}}@if($income_account_id==3)</strong>@endif</td>
                              @if($show_foreigncurrency==1)
                              <td   class="right-text">@if($income_account_id==3)<strong>@endif {{$result['fc_amount']}} @if($income_account_id==3)</strong>@endif</td>
                              @endif

                         @else
                         <td></td>  <td></td>  <td></td>  <td></td>   @if($show_foreigncurrency==1) <td></td> @endif
                         @endif

 
               </tr>   
              @endfor

          </tbody>
          <tfoot>
          @php
						$total_profit_amt=( ($total_incomes-$total_expenses)>0?($total_incomes-$total_expenses):0 );
						$total_profit_fc_amt=( ($total_fcamt_incomes-$total_fcamt_expenses)>0?($total_fcamt_incomes-$total_fcamt_expenses):0 );

						$total_loss_amt=( ($total_expenses-$total_incomes)>0?($total_expenses-$total_incomes ):0 );

						$total_loss_fc_amt=( ($total_fcamt_expenses-$total_fcamt_incomes)>0?($total_fcamt_expenses-$total_fcamt_incomes):0 );

					@endphp
         <tr>
              <td></td>
              <td></td>
              <td><strong>Total Expenses</strong></td>
              <td  class='right-text'><strong>{{round($total_expenses,2)}}  </strong></td>
              @if($show_foreigncurrency==1)
              <td  class='right-text'><strong>{{round($total_fcamt_expenses,2)}} </strong></td>
              @endif
              <td></td>
              <td></td>
              <td><strong>Total Incomes</strong></td>
              <td class='right-text'><strong>{{round($total_incomes,2)}}</strong></td>
              @if($show_foreigncurrency==1)
              <td  class='right-text'><strong>{{round($total_fcamt_incomes,2)}}</strong></td>
              @endif

         </tr>


         <tr>
              <td></td>
              <td></td>
              <td><strong>Total Loss</strong></td>
              <td  class='right-text'><strong>{{round( $total_loss_amt,2)}}  </strong></td>
              @if($show_foreigncurrency==1)
              <td  class='right-text'><strong>{{round($total_loss_fc_amt,2)}}</strong></td>
              @endif
              <td></td>
              <td></td>
              <td><strong>Total Profit</strong></td>
              <td class='right-text'><strong>{{round( $total_profit_amt,2)}}</strong></td>
              @if($show_foreigncurrency==1)
              <td  class='right-text'><strong>{{round(	$total_profit_fc_amt,2)}} </strong></td>
              @endif

         </tr>



          
          </tfoot>
          </table>

          
       @endif
  

        </div>

</body>
</html>