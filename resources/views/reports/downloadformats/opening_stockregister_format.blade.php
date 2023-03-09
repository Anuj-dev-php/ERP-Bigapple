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
                                <th     >Product Name</th>
								<th     >Parent Name</th>
								<th     >Product Type</th>
                                <th     >Location</th>
                                <th     >Doc No</th>
                                <th     >Doc Date</th>
                                <th     >Qty</th>
								@if ($show_amount==true)
								<th     >Rate</th>
                                <th     >Amount</th>
                           
								@endif

            </tr>
        </thead>
   
        <tbody>

        @foreach ($selected_stock_ids as $selected_stock_id)
						 

                         <tr>
                             <td  >{{ $product_data[$selected_stock_id]['product_id']}}</td> 
                             <td     >{{ $product_data[$selected_stock_id]['product_name']}}</td> 

                             <td     >{{ $product_data[$selected_stock_id]['parent_name']}}</td>  
                             
                             <td     >{{   $product_data[$selected_stock_id]['product_type'] }}</td> 
 
                             <td     >{{   $product_data[$selected_stock_id]['location']  }}</td> 

                             <td     >{{   $product_data[$selected_stock_id]['docno'] }}</td> 

                             <td     >{{  $product_data[$selected_stock_id]['docdate']}}</td> 

                             
                             <td     >{{  $product_data[$selected_stock_id]['qty']}}</td> 
                             @if ($show_amount==true)
                             <td     >{{  $product_data[$selected_stock_id]['rate']}}</td> 
                             
                             <td     >{{  $product_data[$selected_stock_id]['amount']}}</td> 
                             @endif

                         </tr>
                             
                         @endforeach 
 

        </tbody>
    </table> 
</div>
</body>
</html>
