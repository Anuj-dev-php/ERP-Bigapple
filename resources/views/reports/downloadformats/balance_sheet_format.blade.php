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
      </style> 
</head>
<body>

    <div class="container-fluid">
    @php 

$total_profit_loss=(array_key_exists('profit_loss',$profit_loss_detail)?$profit_loss_detail['profit_loss']:0);

$total_fc_profit_loss=(array_key_exists('fc_profit_loss',$profit_loss_detail)?$profit_loss_detail['fc_profit_loss']:0);
 

$diff_liabilities_assets=$total_liabilities-$total_assets;

$diff_fc_liabilities_assets=$total_fcamt_liabilities-$total_fcamt_assets;
@endphp



    @if($report_type=='vertical')

                <table class="table  table-striped  table-bordered"  >
                    <thead  > 
                            <tr>
                                <th style="width:150px;"><strong>Account Name</strong></th>  
                                <th  style="width:150px;"><strong>Account Type</strong></th>  
                                <th  style="width:150px;"><strong>Parent Name</strong></th>  
                                <th  style="width:300px;" ><strong>Amount</strong></th> 
                            @if($show_foreigncurrency==1)
                            <th  style="width:300px;"><strong>Fc Amount</strong></th> 
                            @endif

                            
							@if($show_details==1)

                            <th  style="width:150px;">Opening Debit Balances</th>
                            <th  style="width:150px;">Opening Credit Balances</th><th  style="width:150px;">Total Debits</th><th  style="width:150px;">Total Credits</th><th  style="width:150px;">Closing Debit Balances</th><th  style="width:150px;">Closing Credit Balances</th>
                                @if($show_foreigncurrency==1)
                                <th  style="width:150px;">Fcamt Opening Debit Balances</th><th  style="width:150px;">Fcamt Opening Credit Balances</th><th  style="width:150px;">Fcamt Total Debits</th><th  style="width:150px;">Fcamt Total Credits</th><th  style="width:150px;">Fcamt Total Credit Balances</th><th  style="width:150px;">Fcamt Total Debit Balances</th>
                                @endif

                            @endif


                        </tr>
                    </thead>
                    <tbody> 

                    <tr>
						<th  >Account Report: </th>
						<th  >{{$report_name}} ({{$report_type}})  </th>
						<td ></td>
						<td >  </td>
						@if($show_foreigncurrency==1)
						<td  > </td>
						@endif

                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
					</tr>

                    <tr>
						<th  >Company Name:</th>
						<th  >{{$name_of_company}}  </th>
                        <th>Financial Year:</th>
                        <th>{{$financial_year}} </th>  
						@if($show_foreigncurrency==1)
						<td  > </td>
						@endif

                        
                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
					</tr>     
                    <tr>
                            <th>From Date:</th>  
                            <th>{{$start_date}}</th>  
                            <th>To Date: </th><th>{{$end_date}}</th> 
                        @if($show_foreigncurrency==1)
                        <td> </td> 
                        @endif

                        
                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
                    </tr> 


                    @foreach ($all_accounts as $selected_account_id )
                        @php 
                        $result=$all_balances[$selected_account_id];
                        @endphp

                        @if($selected_account_id==1)

					<tr>
						<td   > </td>
						<td  ></td>
						<td    ><strong>Profit / Loss</strong></td>
						<td   class="right-text"> <strong>{{$total_profit_loss}}</strong></td>
						@if($show_foreigncurrency==1)
						<td    class="right-text"><strong>{{$total_fc_profit_loss}}</strong></td>
						@endif

                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
					</tr>

					
					<tr>
						<td   > </td>
						<td  ></td>
						<td    ><strong>Total Liabilities</strong></td>
						<td   class="right-text"> <strong>{{$total_liabilities}}</strong></td>
						@if($show_foreigncurrency==1)
						<td    class="right-text"><strong>{{$total_fcamt_liabilities}}</strong></td>
						@endif
                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
                     
					</tr> 
 
					<tr>
						<td   > </td>
						<td  ></td>
						<td    ></td>
						<td   class="right-text"></td>
						@if($show_foreigncurrency==1)
						<td    class="right-text"> </td>
						@endif
                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
					</tr>
					<tr>
						<td   > </td>
						<td  ></td>
						<td    ></td>
						<td   class="right-text"></td>
						@if($show_foreigncurrency==1)
						<td    class="right-text"> </td>
						@endif
                        @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
					</tr>
					@endif

                    
					<tr>
						<td   > @if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['account_name']}} @if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
						<td  >
						 @if(in_array($selected_account_id,array(2,1)))<strong>@endif	{{  $result['account_type']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
						<td    >@if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['parent_name']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
						<td   class="right-text">
						@if(in_array($selected_account_id,array(2,1)))<strong>@endif	
						{{  $result['amount']}}
						@if(in_array($selected_account_id,array(2,1)))</strong>@endif
					</td>
						@if($show_foreigncurrency==1)
						<td    class="right-text">
						@if(in_array($selected_account_id,array(2,1)))<strong>@endif		
						{{$result['fc_amount']}}
						@if(in_array($selected_account_id,array(2,1)))</strong>@endif	
					</td>
						@endif
 
						
						@if($show_details==1)

						<td>{{$result['opening_debitbalance']}}</td>
						
						<td> {{$result['opening_creditbalance']}}</td>
						
						<td> {{$result['total_debit']}} </td>
						
						<td> {{$result['total_credit']}} </td>
						
						<td> {{$result['closing_debit_balance']}} </td>
						
						<td>{{$result['closing_credit_balance']}}  </td>
						@if($show_foreigncurrency==1)
						
						<td> {{$result['fcamt_opening_debitbalance']}} </td>
						
						<td>{{$result['fcamt_opening_creditbalance']}} </td>
						
						<td> {{$result['fcamt_total_debit']}} </td>
						
						<td> {{$result['fcamt_total_credit']}} </td>
						
						<td>{{$result['fcamt_closing_debit_balance']}}  </td>
						
						<td> {{$result['fcamt_closing_credit_balance']}}  </td>
						@endif
				 
						@endif
					</tr>
  

                        
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>  <td></td>  <td></td>   <td ><strong>Total Assets:</strong></td><td   class="right-text"><strong>{{round((  $total_assets ) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td   class="right-text"><strong>{{round( (  $total_fcamt_assets) ,2)}}</strong></td>
					   @endif

                       @if($show_details==1)

                        <td>{{$total_opening_debitbalance}}</td>

                        <td> {{$total_opening_creditbalance}}</td>

                        <td> {{$total_total_debit}} </td>

                        <td> {{$total_total_credit}} </td>

                        <td> {{$total_closing_debit_balance}} </td>

                        <td>{{$total_closing_credit_balance}}  </td>
                        @if($show_foreigncurrency==1)

                        <td> {{$total_fcamt_opening_debitbalance}} </td>

                        <td>{{$total_fcamt_opening_creditbalance}} </td>

                        <td> {{$total_fcamt_total_debit}} </td>

                        <td> {{$total_fcamt_total_credit}} </td>

                        <td>{{$total_fcamt_closing_debit_balance}}  </td>

                        <td> {{$total_fcamt_closing_credit_balance}}  </td>
                        @endif

                        @endif 

					</tr> 

                    <tr> <td></td>  <td></td>   <td  ><strong>Total Liabilities:</strong></td><td   class="right-text"><strong>{{round((  $total_liabilities ) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td     class="right-text"> <strong>{{round( (  $total_fcamt_liabilities ) ,2)}} </strong></td>
					   @endif

                               
                       @if($show_details==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @if($show_foreigncurrency==1)
                        <td></td>  <td></td>  <td></td>  <td></td>  <td></td>  <td></td>
                        @endif 
                        @endif
 
					</tr>
				
					<tr> <td></td>  <td></td>   <td   ><strong>Difference:</strong></td><td   class="right-text"><strong>{{round((  $diff_liabilities_assets) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td    class="right-text"><strong>{{round( ( $diff_fc_liabilities_assets) ,2)}}</strong></td>
					   @endif
                    

                        @if($show_details==1)
                             <td></td>
							<td  >{{ $total_opening_debitcredit_diff }}</td> 
                            <td></td>
							<td  >{{$total_total_debit_credit_diff}}</td> 
                            <td></td>
							<td  >{{$total_closing_debit_credit_balance_diff}}</td> 

							@if($show_foreigncurrency==1)
                            <td></td>
							<td  >{{$fcamt_total_opening_debitcredit_diff}}</td> 
                            <td></td>
							<td >{{$fcamt_total_total_debit_credit_diff }}</td> 
                            <td></td>
							<td  >{{$fcamt_total_closing_debit_credit_balance_diff}}</td> 
							@endif
						@endif


					</tr> 

                    </tfoot>
                </table>


    @else



    
    <table class="table  table-striped  table-bordered"  >
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
						<th  ><strong>Account Report: </strong></th>
						<th  ><strong>{{$report_name}} ({{$report_type}}) </strong> </th>
						<td ></td>
						<td >  </td>
						@if($show_foreigncurrency==1)
						<td  > </td>
						@endif
                        <th  > </th>
						<th  >  </th>
						<td ></td>
						<td >  </td>  
                        @if($show_foreigncurrency==1)
						<td  > </td>
						@endif
					</tr>

                    <tr>
						<th  ><strong>Company Name:</strong></th>
						<th  ><strong>{{$name_of_company}}</strong>  </th>
                        <th><strong>Financial Year:</strong></th>
                        <th><strong>{{$financial_year}}</strong> </th>  
						@if($show_foreigncurrency==1)
						<td  > </td>
						@endif
                        <th  > </th>
						<th  > </th>
                        <th> </th>
                        <th>  </th>   
                        @if($show_foreigncurrency==1)
						<td  > </td> 
                        @endif
					</tr>     
                    <tr>
                            <th><strong>From Date:</strong></th>  
                            <th><strong>{{$start_date}}</strong></th>  
                            <th><strong>To Date: </strong></th><th><strong>{{$end_date}}</strong></th> 
                        @if($show_foreigncurrency==1)
                        <td> </td> 
                        @endif

                        <th></th>  
                            <th> </th>  
                            <th> </th><th></th> 
                        @if($show_foreigncurrency==1)
                        <td> </td> 
                        @endif
                    </tr> 



                        @php
                            $no_of_accounts=( count($assets_accounts)>count($liabilities_accounts)?count($assets_accounts):count($liabilities_accounts));


                        @endphp
                        @for ($i=0;$i<$no_of_accounts;$i++)
                        <tr>

                            @if(array_key_exists($i,$liabilities_accounts))

                            @php 
                            $result=  $all_balances[$liabilities_accounts[$i]];

                            $selected_account_id=$liabilities_accounts[$i];
                            @endphp
                                     <td   > @if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['account_name']}} @if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
                                        <td  >
                                        @if(in_array($selected_account_id,array(2,1)))<strong>@endif	{{  $result['account_type']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
                                        <td    >@if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['parent_name']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
                                        <td   class="right-text">
                                        @if(in_array($selected_account_id,array(2,1)))<strong>@endif	
                                        {{  $result['amount']}}
                                        @if(in_array($selected_account_id,array(2,1)))</strong>@endif
                                    </td>
                                        @if($show_foreigncurrency==1)
                                        <td    class="right-text">
                                        @if(in_array($selected_account_id,array(2,1)))<strong>@endif		
                                        {{$result['fc_amount']}}
                                        @if(in_array($selected_account_id,array(2,1)))</strong>@endif	
                                    </td>
                                        @endif 
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @if($show_foreigncurrency==1)
                                <td></td>
                                @endif
                             
                            @endif

                            @if(array_key_exists($i,$assets_accounts))

                                @php
                                    $result= $all_balances[$assets_accounts[$i]]; 
                                    $selected_account_id=$assets_accounts[$i];
                                @endphp
                                   

                                <td   > @if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['account_name']}} @if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
                                        <td  >
                                        @if(in_array($selected_account_id,array(2,1)))<strong>@endif	{{  $result['account_type']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
                                        <td    >@if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['parent_name']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
                                        <td   class="right-text">
                                        @if(in_array($selected_account_id,array(2,1)))<strong>@endif	
                                        {{  $result['amount']}}
                                        @if(in_array($selected_account_id,array(2,1)))</strong>@endif
                                    </td>
                                        @if($show_foreigncurrency==1)
                                        <td    class="right-text">
                                        @if(in_array($selected_account_id,array(2,1)))<strong>@endif		
                                        {{$result['fc_amount']}}
                                        @if(in_array($selected_account_id,array(2,1)))</strong>@endif	
                                    </td>
                                        @endif  
                            @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @if($show_foreigncurrency==1)
                                        <td></td>
                                        @endif

                            @endif

                        </tr>


                            
                        @endfor

                   </tbody>
                   <tfoot>
                       
                   <tr>
                                         <td></td>
                                        <td></td>
                                        <th><strong>Total Liabilities</strong></th>
                                        <th> <strong>{{round($total_liabilities,2)}}</strong></th>
                                        @if($show_foreigncurrency==1)
                                        <th><strong>{{round($total_fcamt_liabilities,2)}}</strong></th>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <th><strong>Total Assets</strong></th>
                                        <th> <strong>{{round($total_assets,2)}}</strong></th>
                                        @if($show_foreigncurrency==1)
                                        <th><strong>{{round($total_fcamt_assets,2)}}</strong></th>
                                        @endif

                        </tr>

                        
                        <tr>
                                         <td></td>
                                        <td></td>
                                        <th><strong>Diff</strong></th>
                                        <th><strong>{{round(($total_liabilities-$total_assets),2)}}</strong></th>
                                        @if($show_foreigncurrency==1)
                                        <th><strong>{{round(($total_fcamt_liabilities-$total_fcamt_assets),2)}}</strong></th>
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        @if($show_foreigncurrency==1)
                                        <td></td> 
                                        @endif

                        </tr>


                </tfoot>
</table>

    @endif
        

    </div>

</body>
</html>
