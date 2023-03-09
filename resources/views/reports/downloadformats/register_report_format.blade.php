  @inject('function4filter','App\Http\Controllers\Services\Function4FilterService')
  @php
   
  $function4filter->tablename=$register_table;

  $function4_fields_values=$function4filter->getTableAllFunction4FieldValuesInArray();
  
  @endphp
  
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

    <div class="container-fluid"   >

     
       <table class="table table-striped table-bordered" >
       <thead>
					@if(count($headerfields)>0)
                <th>Id</th>
				@endif

		 
               
               @foreach ( $headerfields as $headerfield )
			           @php 
			   			if($headerfield->fld_label=='Id'){
							continue;
						}
						@endphp
				
					 
				   <th>{{trim($headerfield->fld_label)}}</th>
                @endforeach


               </thead>
               @php
                   $nooffields=count($headerfields);
               @endphp
           <tbody>  

           <tr>  
            @for ($i=0;$i<$nooffields;$i++)  <td></td>  @endfor
           </tr>
		    

		   <tr>
            <td><strong>Report Name:</strong></td> 
			<td><strong>{{$register_name}}</strong></td>
			<td><strong>Table Name:</strong></td> 
			<td><strong>{{$register_table}}</strong></td>
            @for ($i=4;$i<$nooffields;$i++)  <td></td>  @endfor
           </tr> 
          

           @if(count($transactiondata)>0)	
								@foreach ($transactiondata as $data)
								@php
								 $data=(array)$data; 
								 $data=array_change_key_case($data,CASE_LOWER)

								@endphp
									<tr class="transactiondatarow"  id="{{$data['id']}}">
								 
									<td class='text-center'>{{$data['id']}}</td> @foreach ( $headerfields as $headerfield ) 
							
									 @php 
										if($headerfield->fld_label=='Id'){
												continue; 
											}
										
										$headerfieldname=strtolower($headerfield->Field_Name);
									 
										@endphp

										@if ($headerfield->Field_Function==4)
											@php
                                            
                                            if(  array_key_exists($data[$headerfieldname],$function4_fields_values[$headerfieldname]) &&  !empty($function4_fields_values[$headerfieldname][$data[$headerfieldname]])  )
											{
                                                $showdata=$function4_fields_values[$headerfieldname][$data[$headerfieldname]]  ;
                                            }
                                            else{
                                                $showdata='';
                                            }

										
											@endphp
											
										<td>{{ $showdata }}</td>
										@elseif ($headerfield->Field_Function==31 || $headerfield->Field_Function==27 || $headerfield->Field_Function==6)
											@php
												
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
											@endphp
											
										<td>{{ $showdata }}</td>
										@elseif($headerfield->Field_Function==5)
										@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td>
											 {{$showdata}} 
											</td>
											@else
											@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td>{{	$showdata}}</td>

										@endif



										
										
										@endforeach
									 </tr> 
									 
									 
									 @endforeach 
								@else
 
								@endif

 
 

 
           </tbody>

           <tfoot>
 
 
				</tfoot>


       </table>

  

        </div>

</body>
</html>