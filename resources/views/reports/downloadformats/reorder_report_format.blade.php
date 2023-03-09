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
            <th  >Product Id</th>
                                <th  >Product Name</th>
								<th  >Parent Name</th>
								<th  >Product Type</th>

								@foreach ($selected_locations as $selected_location_id  )

								@if ($selected_location_id!="all")
								@if($show_amount==true)	
								 	<th   >{{$locations[$selected_location_id]}} Cl. Amount</th>
									@endif	
                            
                               
								 <th   > {{$locations[$selected_location_id]}} Cl. Bal Qty</th>	
							
									<th  >{{$locations[$selected_location_id]}} Reorder Qty</th>	
								
								<th  >{{$locations[$selected_location_id]}} Difference</th>	
								@else
								@if($show_amount==true)	
								<th  >Total Cl. Amount</th>	
								@endif
                                <th   > Total Cl. Bal Qty</th>		
							
								<th   >Total Reorder Qty</th>	
								
								<th   >Total Difference</th>	
                                
								@endif 
									
								@endforeach

            </tr>
        </thead>
   
        <tbody>

        
							@foreach ($found_products  as $selected_product)
							@php
							if(!array_key_exists($selected_product,$products_data)){
								continue;
							}
						     $product_name=	$products_data[$selected_product]['product_name'];
							 $reorder_data=$products_data[$selected_product]['reorder_data'];
							 $parent_name=	$products_data[$selected_product]['parent_name'];
							 $product_type=$products_data[$selected_product]['product_type'];
 
							@endphp
							<tr>
							<td       >{{$selected_product}}</td> 
							<td        >{{   $product_name}}</td> 

							<td        >{{   $parent_name}}</td> 
							
							<td        >{{   $product_type}}</td> 

								@foreach ($selected_locations as $selected_location_id  )
 
								@if ($selected_location_id!="all")
								@if($show_amount==true)	
									
									<td      >{{  $reorder_data[$selected_location_id]['closing_amount']}}</td>
									@endif	
                                  
                                    <td       > {{  $reorder_data[$selected_location_id]['closing_balance']}}</td>		
							
									<td      >{{  $reorder_data[$selected_location_id]['reorder_qty']}}</td>
								<td >{{  $reorder_data[$selected_location_id]['diff']}}</td>
                                @else	
								@if($show_amount==true)		
												<td      >{{ $reorder_data['all']['closing_amount']}}</td>
												@endif	
 
                                                                
											<td       > {{ $reorder_data['all']['closing_balance']}}</td>	
										
												<td      >{{  $reorder_data['all']['reorder_qty']}}</td>
								<td  >{{  $reorder_data['all']['diff']}}</td>
									
                                @endif

								@php
									
								@endphp
						
										
									@endforeach


							</tr>
							@endforeach

        </tbody>
    </table> 
</div>
</body>
</html>
