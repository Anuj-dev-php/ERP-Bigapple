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
    <table class="table table-striped table-bordered" >
       <thead>
       <th>Docno</th><th>Doc Date</th><th>Product Name</th><th>Particulars</th><th>Quantity</th><th>Balance</th>@if($show_rate==true)<th>Rate</th>@endif @if($show_specrate==true)<th>Spec rate</th> @endif
	   @if($show_rate==true || $show_specrate==true)	<th>Amount</th> @endif
      </thead>
      <tbody>
      <tr><td>Report Name:</td> <td><strong>Stock Ledger</strong></td><td> </td><td></td><td></td><td></td> @if($show_rate==true)<td></td>@endif @if($show_specrate==true)<td> </td> @endif
	  @if($show_rate==true || $show_specrate==true)		<td></td> @endif </tr>
        <tr><td>Start Date:</td> <td><strong>{{$start_date_string}}</strong></td><td>End Date:</td><td><strong>{{$end_date_string}}</strong></td><td></td> <td></td>@if($show_rate==true)<td></td>@endif @if($show_specrate==true)<td> </td> @endif
		@if($show_rate==true || $show_specrate==true)	<td></td> @endif </tr>
    <tr><td>Location:</td> <td><strong>{{ $location_name}}</strong></td><td>Valuation Method:</td><td><strong>{{$valuation_method}}</strong></td><td></td><td></td> @if($show_rate==true)<td></td>@endif @if($show_specrate==true)<td> </td> @endif
	@if($show_rate==true || $show_specrate==true)	 <td></td> @endif </tr>
                     @php
								$no_of_columns=7;
								if($show_rate==true){
									$no_of_columns++;

								}
								if($show_specrate==true){
									$no_of_columns++;
								}

							@endphp

    @foreach ($selected_products as $product_id )

                             	@php
									if(!array_key_exists($product_id,$products_data)){
										continue;
									}
								@endphp


								<tr><td   tabIndex="1"><strong>Product Name:</strong></td><td   tabIndex="1">{{$products_data[ $product_id]['product_name']}}</td> @if($show_rate==true || $show_specrate==true)	 <td></td> @endif @for($i=0;$i<($no_of_columns-3);$i++) <td></td> @endfor </tr>
							
								<tr><td></td> <td></td> <td>{{$products_data[ $product_id]['product_name']}}</td> <td  > </td><td style="text-align:right"><strong>Opening Balance:</strong></td>
								<td>{{$products_data[ $product_id]['opening_stock']}}</td> 
								@if ($show_rate==true) 
								<td  >{{$products_data[ $product_id]['opening_rate']}}</td>
								@endif
								@if ($show_specrate==true) 
								<td></td>
								@endif
								@if($show_rate==true || $show_specrate==true)
								<td>{{round($products_data[ $product_id]['opening_rate']*$products_data[ $product_id]['opening_stock'] ,2)}}</td></tr>
								@endif
								@php
								$row_index=0;
								$stock_detail_amounts=$products_data[ $product_id]['stock_detail_amounts']; 
								$stock_details=$products_data[ $product_id]['stock_details'] ;
								@endphp

							

								@foreach (	$stock_details as  $stock_detail)
										@php
										

										$stock_date=date("Y-m-d H:i:s",strtotime($stock_detail['docdate']));

										$start_date_stock=date("Y-m-d 00:00:00",strtotime(formatDateInYmd($start_date_string)));

										$end_date_stock=date("Y-m-d 23:59:59",strtotime(formatDateInYmd($end_date_string)));

										if(	$stock_date<$start_date_stock || 	$stock_date>	$end_date_stock){
										continue;
										}
										
										@endphp
										<tr>
										<td   tabIndex="1">{{$stock_detail['docno']}}</td>
										<td   tabIndex="1">{{date('d/m/Y',strtotime($stock_detail['docdate'])) }}</td>
										<td    tabIndex="1">{{$products_data[ $product_id]['product_name']}}</td>
										<td   tabIndex="1"> 
											@if(array_key_exists($stock_detail['partyid'],$all_customers))
											{{$all_customers[$stock_detail['partyid']]}}
											@endif
										</td>

										@php 
											$current_balance= $stock_detail['balance_qty'];

										$calc_spec=$stock_detail['spec_rate'];



										$final_amount=$stock_detail_amounts[$stock_detail['id']];




										$qty=$stock_detail['Qty'];



										@endphp

										<td   tabIndex="1">{{	 $qty}}</td>
										<td   tabIndex="1"> {{$current_balance}}</td>
										@if($show_rate==true)
										<td   tabIndex="1">{{$stock_detail['CRate']}}</td>
										@endif

										@if($show_specrate==true)
										<td   tabIndex="1">{{	 $calc_spec}} </td>
										@endif
										@if($show_rate==true || $show_specrate==true)
										<td   tabIndex="1">{{$final_amount}} 
										</td>
										@endif 
										</tr>
										@php
										$row_index++;
										@endphp

										@endforeach
										<tr><td></td> <td></td>  <td>{{$products_data[ $product_id]['product_name']}}</td>  <td></td>  <td    tabIndex="1" style='text-align:right;'><strong>Closing Balance:</strong></td> <td   tabIndex="1">{{$products_data[ $product_id]['closing_stock']}}</td>
										<td></td>
										@if($show_rate==true)
										<td></td>
										@endif
										@if($show_specrate==true)
										<td></td>
										@endif
									</tr>

								@endforeach


        </tbody>
									</table>


     </div>
</body>
</html>