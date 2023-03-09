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
        <th style='font-weight:bold;'>Product Id</th>
        <th style='font-weight:bold;'>Product Name</th>
        <th style='font-weight:bold;'>Parent Name</th>
        <th style='font-weight:bold;'>Product Type</th>
            @foreach ($selected_locations as $selected_location_id  ) 
@if ($selected_location_id!="all")
  <th  style='font-weight:bold;'> {{$locations[$selected_location_id]}} Balance</th>	
  @if($show_amount==true)	
      <th  style='font-weight:bold;'>{{$locations[$selected_location_id]}} Amount</th>	
      @endif
@else 
 <th  style='font-weight:bold;'>Total Balance</th>	
 @if($show_amount==true)		
<th  style='font-weight:bold;'>Total Amount</th>
@endif	
@endif 
@endforeach
@php
    $no_of_locations=count($selected_locations);

    if($show_amount==true){
        $no_of_columns=   $no_of_locations*2+4;
    }
    else{
        $no_of_columns=   $no_of_locations+4;
    }



@endphp

</thead>
   
        <tbody>
            <tr>
                <td>Report Name:</td><td   ><b>Stock Statement</b></td><td></td>

                @for ($i=3;$i<$no_of_columns;$i++)
                <td></td>
                    
                @endfor
         
            </tr>
            <tr>
                <td>From Date: <b>{{$start_date}}</b> </td><td  >To Date: <b>{{$end_date}}</b>  </td><td></td>
                
                @for ($i=3;$i<$no_of_columns;$i++)
                <td></td>
                    
                @endfor
         
            </tr>

            <tr>
                @php
                $location_names=array();


                if(in_array('all',$selected_locations)){
                    array_push(    $location_names,"All");
                } 
                else{

                    foreach($selected_locations as $selected_location){

                        array_push(    $location_names,$locations[$selected_location]); 

                        } 
                }
 

                @endphp
                <td>Valuation Method: <b>{{ $valuation_method}}</b> </td><td  >Locations:{{implode(',',$location_names)}}</td><td></td>
                
                @for ($i=3;$i<$no_of_columns;$i++)
                <td></td>
                    
                @endfor
         
            </tr>


        @foreach ($selected_products as $selected_product)
							@php

                            if(!array_key_exists($selected_product,	$products_data)){
								continue;
							}
							 

						     $product_name=	$products_data[$selected_product]['product_name'];
                             $parent_name=	$products_data[$selected_product]['parent_name'];
							 $product_type=$products_data[$selected_product]['product_type'];
							 $statement_data=$products_data[$selected_product]['statement_data'];
							@endphp
							<tr>
                            <td  >{{$selected_product}}</td> 
							<td >{{   $product_name}}</td> 

							<td  >{{   $parent_name}}</td> 
							
							<td  >{{   $product_type}}</td> 

								@foreach ($selected_locations as $selected_location_id  )

									@if ($selected_location_id!="all")
									 <td   >{{$statement_data[$selected_location_id]['balance']}}</td>		
                                     @if($show_amount==true)
                                     	<td>{{$statement_data[$selected_location_id]['amount']}}</td>	
                                        @endif
									@else

									<td  >{{$statement_data[$selected_location_id]['balance']}}</td>	
                                    @if($show_amount==true)
                                    	<td>{{$statement_data[$selected_location_id]['amount']}}</td>
                                        @endif	
									@endif

										
									@endforeach


							</tr>
							@endforeach

    </tbody>
    </table> 
</div>
</body>
</html>
