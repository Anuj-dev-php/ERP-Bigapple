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
                <th  style='font-weight:bold;'  > {{$locations[$selected_location_id]}} Op. Bal Qty</th>		
                @if($show_amount==true)
                <th   style='font-weight:bold;' >{{$locations[$selected_location_id]}} Op. Amount</th>	
                @endif
                                            
                <th   style='font-weight:bold;' > {{$locations[$selected_location_id]}} IN</th>		
                	<th  style='font-weight:bold;' >{{$locations[$selected_location_id]}} Out</th>	

                <th  style='font-weight:bold;' > {{$locations[$selected_location_id]}} Cl. Bal Qty</th>		
                @if($show_amount==true)	
                <th  style='font-weight:bold;'  >{{$locations[$selected_location_id]}} Cl. Amount</th>	
                @endif
                @else

                <th   style='font-weight:bold;' > Total Op. Bal Qty</th>		
                @if($show_amount==true)	
                <th  style='font-weight:bold;' >Total Op. Amount</th>	
                @endif

                <th  style='font-weight:bold;' > Total IN</th>			<th   style='font-weight:bold;' >Total Out</th>	

                <th  style='font-weight:bold;' > Total Cl. Bal Qty</th>		
                @if($show_amount==true)	
                <th  style='font-weight:bold;'  >Total Cl. Amount</th>	
                @endif

                @endif
@endforeach
@php
    $no_of_locations=count($selected_locations);
   
    if($show_amount==true){
        $no_of_columns=   $no_of_locations*6+4;
    }
    else{
        $no_of_columns=   $no_of_locations*5+3;  
    }



@endphp

</thead>
   
        <tbody>
            <tr>
                <td>Report Name:</td><td   ><b>Stock Movement</b></td><td></td>

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
						     $product_name=	$products_data[$selected_product]['product_name'];
							 $movement_data=$products_data[$selected_product]['movement_data']; 
                             $parent_name=	$products_data[$selected_product]['parent_name'];
							 $product_type=$products_data[$selected_product]['product_type'];
							@endphp
        <tr>
                             <td  >{{$selected_product}}</td> 
							<td >{{   $product_name}}</td> 

							<td  >{{   $parent_name}}</td> 
							
							<td  >{{   $product_type}}</td> 


								@foreach ($selected_locations as $selected_location_id  )
 
								@if ($selected_location_id!="all")

                                           
							     	 <td>{{	 $movement_data[$selected_location_id]['opening_balance']}}</td>		
                                      @if($show_amount==true)	
                                     <td>{{	 $movement_data[$selected_location_id]['opening_amount']}}</td>	
                                     @endif
                                                                
                                    <td  >{{ $movement_data[$selected_location_id]['in']}}</td>			<td   >{{ $movement_data[$selected_location_id]['out']}}</td>	
                                    
                                    <td  > {{ $movement_data[$selected_location_id]['closing_balance']}}</td>		
                                    @if($show_amount==true)	
                                    <td>{{ $movement_data[$selected_location_id]['closing_amount']}}</td>
                                    @endif	
                                @else

                                <td>{{	 $movement_data['all']['opening_balance']}}</td>		
                                @if($show_amount==true)	
                                <td>{{	 $movement_data['all']['opening_amount']}}</td>	
                                @endif
                                                                
                                                                <td  >{{ $movement_data['all']['in']}}</td>			<td   >{{ $movement_data['all']['out']}}</td>	
                                                                @if($show_amount==true)	
                                                                <td  > {{ $movement_data['all']['closing_balance']}}</td>
                                                                @endif
                                                                
                                                                <td>{{ $movement_data['all']['closing_amount']}}</td>	
                                                      
                                @endif
										
									@endforeach


							</tr> 
							@endforeach

    </tbody>
    </table> 
</div>
</body>
</html>
