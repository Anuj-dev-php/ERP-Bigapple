@inject('function4filter','App\Http\Controllers\Services\Function4FilterService')
  @php


   
  $function4filter->tablename=$fieldvalue_table;

  $function4_fields_values=$function4filter->getTableAllFunction4FieldValuesInArray();


  if(!empty($detail_tablename)){ 

  $function4filter->tablename=$detail_tablename;

   $function4_fields_det_values=$function4filter->getTableAllFunction4FieldValuesInArray();

  }
  else{
    $function4_fields_det_values=array();
  }
  
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
                <th><strong>Id</strong></th>
				@endif

		 
               
               @foreach ( $headerfields as $headerfield )
			       
					 
				   <th><strong>{{trim($headerfield['fld_label'])}}</strong></th>
                @endforeach


               </thead>
               @php
                   $nooffields=count($headerfields)+1;
               @endphp
           <tbody>  

           <tr>  
            @for ($i=0;$i<$nooffields;$i++)  <td></td>  @endfor
           </tr>

           <tr>  
            <td><strong>Report Name:</strong></td>
            <td> Salesman Report </td>
            <td><strong>Field Name:</strong></td>
            <td>{{$field_selection}} </td>

            <td><strong>Field Value:</strong></td>
            <td>{{$fieldvalue_selection_text}} </td>

            
            <td><strong>Field Table:</strong></td>
            <td>{{$fieldvalue_table}} </td>
            @for ($i=8;$i<$nooffields;$i++)  <td></td>  @endfor
           </tr>

           
           @php
                $det_fields=array('rate','quantity','disc','product'); 
                @endphp

                @if(count($tabledata)>0)	
								@foreach ($tabledata as $data)
								@php
								 $data=(array)$data; 
								 $data=array_change_key_case($data,CASE_LOWER)


								@endphp
									<tr class="transactiondatarow"  id="{{$data['id']}}">
								 
									<td class='text-center'>{{$data['id']}}</td> @foreach ( $headerfields as $headerfield ) 
							
									 @php 
										if($headerfield['fld_label']=='Id'){
												continue; 
											}
										
										$headerfieldname=strtolower($headerfield['Field_Name']);

                                     
                                       

										if( $headerfield['Field_Function']==4){

                                               
                                        if(in_array($headerfield['Field_Name'],$det_fields)){
 
                                            if(array_key_exists($data[$headerfieldname],$function4_fields_det_values)){
                                                $showdata=$function4_fields_det_values[$data[$headerfieldname]];
                                            }
                                            else{
                                                $showdata='';
                                            }

                                           
                                        }
                                        else{
                                         
                                            if(array_key_exists($data[$headerfieldname],$function4_fields_values)){
                                                $showdata=$function4_fields_values[$data[$headerfieldname]];
                                            }
                                            else{
                                                $showdata='';
                                            }
                                        }
										}
										else if( $headerfield['Field_Function']==31 || $headerfield['Field_Function']==27 || $headerfield['Field_Function']==6 )
										{
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
										}
										else{
											$showdata=$data[$headerfieldname]; 
										}
										@endphp

										@if ($headerfield['Field_Function']==4)

                                      

											@php

                                            if(in_array($headerfield['Field_Name'],$det_fields)){

                                                
                                                        if(array_key_exists($data[$headerfieldname],$function4_fields_det_values)){
                                                            $showdata=$function4_fields_det_values[$data[$headerfieldname]];
                                                        }
                                                        else{
                                                            $showdata='';
                                                        }
                                       
										     
                                                }
                                                else{

                                                    if(array_key_exists($data[$headerfieldname],$function4_fields_values)){
                                                            $showdata=$function4_fields_values[$data[$headerfieldname]];
                                                        }
                                                        else{
                                                            $showdata='';
                                                        }
                                       
                                                }

											@endphp
											
										<td>{{ reportCorrect($showdata) }}</td>
										@elseif ($headerfield['Field_Function']==31 || $headerfield['Field_Function']==27 || $headerfield['Field_Function']==6)
											@php
												
											$showdata=date("d/m/Y",strtotime($data[$headerfieldname]));
											@endphp
											
										<td>{{ $showdata }}</td>
										@elseif($headerfield['Field_Function']==5)
										@php
											$showdata=$data[$headerfieldname]; 
											@endphp
 
											<td>
 
											{{reportCorrect($showdata)}} 
											</td>
											@else
											@php
											$showdata=$data[$headerfieldname]; 
											@endphp
											<td>{{	reportCorrect($showdata)}}</td>

										@endif



										
										
										@endforeach
									 </tr> 
									 
									 
									 @endforeach 
								@else

								<tr><td class='text-center' colspan="{{count($headerfields)+1}}">No Data</td></tr>
 
								@endif
		    
 
 

 
           </tbody>

           <tfoot>
 
 
				</tfoot>


       </table>

  

        </div>

</body>
</html>