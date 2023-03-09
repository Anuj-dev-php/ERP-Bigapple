<!DOCTYPE html>
@inject('reportservice','App\Http\Controllers\Services\ReportService')
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

    
    @foreach ($accounts_data as  $account_id) 
               
               @php
                   $account_opening_balance= $reportservice->getOpeningBalance($account_id,$companyDates->fs_date,$selected_costcenter,$selected_division);

                   $account_closing_balance= $reportservice->getClosingBalance($account_opening_balance,$account_id,$companyDates->fs_date,$companyDates->fe_date,$selected_costcenter,$selected_division);


                   $balance = $account_opening_balance['openingbalance'];
                   $totalCredit = 0;
                   $totalDebit = 0;
                   $FCbalance = $account_opening_balance['OpeningFCbalance'];;
                   $totalFCCredit = 0;
                   $totalFCDebit = 0;

                   @endphp
     
     
       <table class="table table-striped table-bordered"  >
           <thead  >
               <tr  >
                   <th style="font-weight:bold;" > VchDate </th>
                   <th style="font-weight:bold;;width:150px;"  >Vch No </th>
                   <th style="font-weight:bold;width:200px;"  >Account Name</th>
                   <th style="font-weight:bold;width:500px;"  >Particulars</th>
                   <th style="font-weight:bold;;width:500px;"  >Naration</th>
                   <th style="font-weight:bold;"  >Debit</th>
                   <th style="font-weight:bold;"  >Credit</th>
                   <th style="font-weight:bold;"   >Balance</th>

                   @if (isset($chequeno))
                       <th style="font-weight:bold;"  >Cheque No</th>
                   @endif
                   @if (isset($chequestatus))
                       <th style="font-weight:bold;" >Cheque Status</th>
                   @endif
                   @if (isset($clearingdate))
                       <th style="font-weight:bold;"  >Clearing Date</th>
                   @endif
                   @if (isset($costcentre))
                       <th style="font-weight:bold;"  >Cost Centre</th>
                   @endif
                   @if (isset($division))
                       <th style="font-weight:bold;" >Division</th>
                   @endif
                   @if (isset($executive))
                       <th style="font-weight:bold;" >Executive</th>
                   @endif
              
                   @if (isset($foreigncurrency))
                       <th style="font-weight:bold;"  >FC Debit</th>
                       <th style="font-weight:bold;" >FC Credit</th>
                       <th style="font-weight:bold;"  >FC Balance</th>
                       <th style="font-weight:bold;" >FC Exchange Rate</th>
                   @endif
               </tr>
           </thead>
           <tbody>  


           <tr  >
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>     
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>

                   @if (isset($chequeno))
                   <td></td>
                   @endif
                   @if (isset($chequestatus))
                   <td></td>
                   @endif
                   @if (isset($clearingdate))
                   <td></td>
                   @endif
                   @if (isset($costcentre))
                   <td></td>
                   @endif
                   @if (isset($division))
                   <td></td>
                   @endif
                   @if (isset($executive))
                   <td></td>
                   @endif
             
                   @if (isset($foreigncurrency))
                   <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                   @endif
               </tr> 
           <tr  >
                    <td>Account - {{$report_name}}</td>
                    <td></td>
                    <td></td>
                    <td></td>     
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>

                   @if (isset($chequeno))
                   <td></td>
                   @endif
                   @if (isset($chequestatus))
                   <td></td>
                   @endif
                   @if (isset($clearingdate))
                   <td></td>
                   @endif
                   @if (isset($costcentre))
                   <td></td>
                   @endif
                   @if (isset($division))
                   <td></td>
                   @endif
                   @if (isset($executive))
                   <td></td>
                   @endif
               
                   @if (isset($foreigncurrency))
                   <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                   @endif
               </tr>


               <tr  >
                    <td><strong>Company Name:</strong></td>
                    <td><strong>{{$name_of_company}}</strong></td>
                    <td><strong>Financial Year:</strong></td>
                    <td><strong>{{$financial_year}}</strong></td>     
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>

                   @if (isset($chequeno))
                   <td></td>
                   @endif
                   @if (isset($chequestatus))
                   <td></td>
                   @endif
                   @if (isset($clearingdate))
                   <td></td>
                   @endif
                   @if (isset($costcentre))
                   <td></td>
                   @endif
                   @if (isset($division))
                   <td></td>
                   @endif
                   @if (isset($executive))
                   <td></td>
                   @endif
              
                   @if (isset($foreigncurrency))
                   <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                   @endif
               </tr>
               @php
                               
                               $general_ledger_result=  $reportservice->getGeneralLedger($account_id,$companyDates->fs_date, $companyDates->fe_date, $selected_costcenter, $selected_division);

                           $found_account_name=  $general_ledger_result['account_name'];

                           $found_account_type= $general_ledger_result['account_type'];
                       @endphp 


               <tr  >
                  
                    <td><strong>A/C
                                   No.:</strong></td>
                    <td><strong>{{ $account_id}}</strong></td>   
                    <td><strong>Account Type</strong></td>
                     <td><strong>{{$found_account_type}}</strong></td>  
                     <td><strong>From Date.:</strong></td>
                     <td><strong>{{ date('d/m/Y',strtotime( $companyDates->fs_date)) }}</strong></td>
                     <td><strong>To Date:</strong> </td>
                     <td><strong>{{  date('d/m/Y',strtotime($companyDates->fe_date)) }}</strong></td>
                 

                   @if (isset($chequeno))
                   <td></td>
                   @endif
                   @if (isset($chequestatus))
                   <td></td>
                   @endif
                   @if (isset($clearingdate))
                   <td></td>
                   @endif
                   @if (isset($costcentre))
                   <td></td>
                   @endif
                   @if (isset($division))
                   <td></td>
                   @endif
                   @if (isset($executive))
                   <td></td>
                   @endif
             
                   @if (isset($foreigncurrency))
                   <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                   @endif
               </tr> 
               <tr  >
                    <td> </td>
                    <td></td>
                    <td></td>
                    <td></td>     
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>

                   @if (isset($chequeno))
                   <td></td>
                   @endif
                   @if (isset($chequestatus))
                   <td></td>
                   @endif
                   @if (isset($clearingdate))
                   <td></td>
                   @endif
                   @if (isset($costcentre))
                   <td></td>
                   @endif
                   @if (isset($division))
                   <td></td>
                   @endif
                   @if (isset($executive))
                   <td></td>
                   @endif
               
                   @if (isset($foreigncurrency))
                   <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                   @endif
               </tr> 

           <tr> <td></td> <td></td> <td> <strong>{{     $found_account_name}}</strong></td> <td></td> <td   style="text-align:right;"><strong>Opening Balance:</strong></td><td></td><td></td> <td><strong>{{round($account_opening_balance['openingbalance'],2)}}</strong></td>
           @if($no_of_additional_columns>0)
         
               @for ($i=0;$i<$no_of_additional_columns;$i++)
               <td  ></td>
               @endfor
           @endif
       @if (isset($foreigncurrency))
       <td  ></td>   <td  ><strong>Opening Fc Balance:</strong></td>   <td  ><strong>{{round($account_opening_balance['OpeningFCbalance'],2)}}</strong></td>   <td  ></td>
       @endif


       


       </tr>




          

       @foreach (  $general_ledger_result['data'] as $general_ledger_entry)


<tr>
    <td>{{ date('d-m-Y', strtotime($general_ledger_entry->VchDate))}}</td>
    <td  >{{ $general_ledger_entry->VchNo }}</td>
                <td  >{{ str_replace('&','and',$general_ledger_entry->ACName)  }}</td>

                @php
                 $particulars= (array_key_exists($general_ledger_entry->Id,$general_ledger_result['particulars'])?$general_ledger_result['particulars'][$general_ledger_entry->Id]:"");
                @endphp
                <td  >{!!  str_replace('&','and',$particulars) !!}</td>
                <td  >{!!   str_replace('&','and',$general_ledger_entry->Narration)  !!}</td>
                @php
                    $credit = ($general_ledger_entry->vcAmount < 0) ?$general_ledger_entry->vcAmount : "0.00";
                    $debit = ($general_ledger_entry->vcAmount > 0) ?$general_ledger_entry->vcAmount : "0.00";

                    $balance = (float)$balance + (float)$debit - abs((float)$credit);

                    if ($balance < 0) {
                        $conbalance = round(abs($balance), 2) . " CR.";
                    } else {
                        $conbalance = round($balance, 2) . " DR.";
                    }

                                                        
                    $fccredit = ($general_ledger_entry->FcFCamt < 0) ? $general_ledger_entry->FcFCamt : "0.00";
                    $fcdebit = ($general_ledger_entry->FcFCamt > 0) ? $general_ledger_entry->FcFCamt : "0.00";

                    $FCbalance = (float)$FCbalance + (float)$fcdebit - abs((float)$fccredit);

                        if ($FCbalance < 0) {
                            $conFCbalance = round(abs($FCbalance), 2) . " CR.";
                        } else {
                            $conFCbalance = round($FCbalance, 2) . " DR.";
                        }

                        $totalCredit += (float) $credit;
                        $totalDebit += (float) $debit;

                        $totalFCCredit += (float) $fccredit;
                        $totalFCDebit += (float) $fcdebit;


                    @endphp

                    <td  >{{ $debit }}</td>
                <td  >{{abs($credit) }}</td>
                <td >{{ $conbalance }}</td>

                


                @if (isset($chequeno))
                    <td  >{{ $general_ledger_entry->chq_no }}</td>
                @endif
                @if (isset($chequestatus))
                    <td >{{ $general_ledger_entry->ch_status }}</td>
                @endif
                @if (isset($clearingdate))
                    <td >{{$general_ledger_entry->cl_date }}</td>
                @endif
                @if (isset($costcentre))
                    <td >{{$general_ledger_entry->costcentre }}</td>
                @endif
                @if (isset($division))
                    <td >{{ $general_ledger_entry->DivisionName }}</td>
                @endif
                @if (isset($executive))
                    <td  >{{ $general_ledger_entry->exeName }}</td>
                @endif
              

                @if (isset($foreigncurrency))
               
                <td  >{{ $fcdebit }}</td>
                <td  >{{abs($fccredit) }}</td>
                <td  >{{ $conFCbalance }}</td>
                    <td  >{{ $general_ledger_entry->fcexrate }}</td>
                @endif 

</tr>

@endforeach



      
           <tr><td></td><td></td><td>{{     $found_account_name}}</td><td></td><td    style="text-align:right;"><strong >Total Debit And Credit</strong></td><td>{{  $totalDebit}}</td><td>{{  $totalCredit}}</td><td></td>



           @if(isset($foreigncurrency))
           
           @for ($i=0;$i<($no_of_additional_columns-1);$i++)
           <td></td>
               
           @endfor
           @if($no_of_additional_columns>0)
           <td   style="text-align:right;"><strong >Total Fc Debit And Credit</strong></td>
           @endif
           <td>{{  $totalFCDebit}}</td><td>{{  $totalFCCredit}}</td>
           <td></td>      <td></td>

           @else

           @for ($i=0;$i<($no_of_additional_columns);$i++)
           <td></td>
               
           @endfor



           @endif
       
       
       </tr>
           <tr><td></td><td></td><td>{{     $found_account_name}}</td><td></td> <td   style="text-align:right;"><strong>Closing Balance:</strong></td> <td></td> <td></td> <td><strong>{{ round($account_closing_balance['Closingbalance'],2)}}</strong></td>
           @if(isset($foreigncurrency))
                   @for ($i=0;$i<($no_of_additional_columns+2-1);$i++)
                   <td></td>
                       
                   @endfor
           <td  style="text-align:right;"><strong>Closing Fc Balance:</strong></td>
           <td>{{ round($account_closing_balance['ClosingFCbalance'],2)}}</td>
           <td></td> 

           @else

           @for ($i=0;$i<($no_of_additional_columns );$i++)
                   <td></td>
                       
                   @endfor

           @endif

       </tr>


       <tr  >
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>     
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>

                   @if (isset($chequeno))
                   <td></td>
                   @endif
                   @if (isset($chequestatus))
                   <td></td>
                   @endif
                   @if (isset($clearingdate))
                   <td></td>
                   @endif
                   @if (isset($costcentre))
                   <td></td>
                   @endif
                   @if (isset($division))
                   <td></td>
                   @endif
                   @if (isset($executive))
                   <td></td>
                   @endif
                 
                   @if (isset($foreigncurrency))
                   <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                   @endif
               </tr>

           </tbody>
       </table>


@endforeach

  

        </div>

</body>
</html>