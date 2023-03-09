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
            <tr>
        <th style='font-weight:bold; '>Product Id</th>
        <th style='font-weight:bold; '>Product Name</th>
        <th style='font-weight:bold;  '>Parent Name</th>
        <th style='font-weight:bold; '>Product Type</th> 
        <th  style='font-weight:bold; '>Closing Qty</th>
        @if($show_amount==true)
        <th style='font-weight:bold; '>Closing Amount</th>
        @endif
        <th style='font-weight:bold; '>Avg Qty For Period</th>
        <th  style='font-weight:bold; '>Total Out</th>
        @foreach ($months as $month_key=>$month_name)
        <th  style='font-weight:bold;'>{{$month_name}} Out</th> 				
            @endforeach
</tr>

        </thead>
   
        <tbody>
            @php
                $no_of_columns=19;

                if($show_amount==true){
                    $no_of_columns=    $no_of_columns+1;  
                }

            @endphp
            <tr><td>Report Name:</td><td style='font-weight:bold; '>@if($mode=='fast') Stock Fast Moving Items @else Stock Slow Moving Items @endif</td>
        
            <td>Start Date</td>  <td style='font-weight:bold; '>{{$start_date}}</td>   <td>End Date</td>  <td style='font-weight:bold; '>{{$end_date}}</td>
                @for($i=0;$i<($no_of_columns-6);$i++)
                <td></td>
                @endfor
             </tr>

             <tr>
                <td>Location Name:</td>    <td  style='font-weight:bold; '>@if($location_id!='all') {{$locations[$location_id]}} @else All @endif </td> 
                <td>Qty:</td>    <td  style='font-weight:bold; '>{{$qty}} </td> 
                <td>Qty Period:</td>    <td  style='font-weight:bold; '>{{$qty_period}} </td> 
                <td>Valuation Method:</td>    <td  style='font-weight:bold; '> {{$valuation_method}}</td> 
                @for($i=0;$i<($no_of_columns-8);$i++)
                <td></td>
                @endfor
             </tr>

             

        @foreach ($selected_products as $selected_product)
							@php
							if(array_key_exists($selected_product,$products_data)==false){
								continue;
							}
						     $product_name=	$products_data[$selected_product]['product_name'];
							 $moving_data=$products_data[$selected_product]['moving_data']; 
							 $parent_name=	$products_data[$selected_product]['parent_name'];
							 $product_type=$products_data[$selected_product]['product_type'];
							 $closing_balance=	 $moving_data['closing_balance'];
							 $closing_amount=	 $moving_data['closing_amount'];
							$month_data= $moving_data['month_data'];
							$total_month= $moving_data['total_month'];
							$avg_qty_for_period=$moving_data['avg_qty_for_period'];
 
 
							@endphp
							<tr>
							<td >{{$selected_product}}</td> 
							<td  >{{   $product_name}}</td> 

							<td  >{{   $parent_name}}</td> 
							
							<td  >{{   $product_type}}</td> 
							
							<td  >{{  $closing_balance}}</td> 
							@if($show_amount==true)
							
							<td  >{{$closing_amount}}</td> 

							@endif

							<td  >{{$avg_qty_for_period}}</td> 
							
							<td  >{{$total_month}}</td> 
 
							@foreach ($months as $month_key=>$month_name)
								<td style='min-width:150px;'>{{	$month_data[$month_key]}}</td> 				
									@endforeach 
                              </tr>
							@endforeach

       

       </tbody>
    </table> 
</div>
</body>
</html>
