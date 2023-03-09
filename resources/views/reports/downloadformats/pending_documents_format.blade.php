<!DOCTYPE html> 
<html lang="en">
<head>
    <title>Pending Documents</title>
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
@php 
    function showHeaderField($header_field){
    
        $header_field=str_replace('_',' ',$header_field);
    
        
        $header_field=ucfirst(  $header_field);
        
        return $header_field;
    }
    @endphp

    <div class="container-fluid"   >
    <table class="table table-striped table-bordered" >
       <thead>
        <tr>
                    
       @foreach ( $headerfields as $headerfield ) 
          <th> {{ showHeaderField($headerfield) }} </th>
      
       @endforeach
       </tr>
        </thead>
        <tbody>
        <tr >
        <td>Report Name:</td>  
         <td>Pending Documents</td>  
            @for ($i=0;$i<(count($headerfields)-2);$i++)
            <td></td>  
            @endfor
        </tr>

        @foreach ($transaction_data as $data)
						 
                         <tr >
              
                         <td> {{$data['id']}} </td> 
                         
                         <td> {{ date('d/m/Y',strtotime($data['doc_date'])) }} </td> 
                             
                         <td> {{ $data['doc_no']}} </td> 
                         
                         <td> {!!$data['location']!!} </td> 

                         <td> {!! reportCorrect($data['cust_id']) !!} </td> 
                         
                         <td> {!!  $data['name'] !!} </td> 
                         
                         <td> {!!  reportCorrect($data['product']) !!} </td>  
                         
                         <td> {!!  $data['qty'] !!} </td> 
                         
                         <td> {!!  $data['rate'] !!} </td> 
                         
                         <td> {!!  $data['used_qty'] !!} </td> 
                         
                         <td> {!!  $data["Bal Qty"] !!} </td> 
                         
                         <td> {!!  $data['Ageing Days']  !!} </td> 

                          </tr> 
                          
                          
                          @endforeach 

        </tbody>
        <tfoot></tfoot>
        </table>


    </div>

</body>
 
</html>

     
