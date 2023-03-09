@extends('layout.layout')
@inject('reportservice','App\Http\Controllers\Services\ReportService')



<style>
    /** Progress bar css */
    .progress-outer {
        background: #fff;
        border-radius: 50px;
        padding: 25px;
        margin: 10px 0;
        box-shadow: 0 0 10px rgba(209, 219, 231, 0.7);
    }

    .progress {
        height: 27px;
        margin: 0;
        overflow: visible;
        border-radius: 50px;
        background: #eaedf3;
        box-shadow: inset 0 10px 10px rgba(244, 245, 250, 0.9);
    }

    .progress .progress-bar {
        border-radius: 50px;
    }

    .progress .progress-value {
        position: relative;
        left: -45px;
        top: 4px;
        font-size: 14px;
        font-weight: bold;
        color: #fff;
        letter-spacing: 2px;
    }

    .progress-bar.active {
        animation: reverse progress-bar-stripes 0.40s linear infinite, animate-positive 2s;
    }

    @-webkit-keyframes animate-positive {
        0% {
            width: 0%;
        }
    }

    @keyframes animate-positive {
        0% {
            width: 0%;
        }
    }

    #print_button_custon {
        margin-bottom: -3% !important;
        margin-top: 10px !important;
        position: absolute;
        margin-left: 32px;
        z-index: 1;
    }

    .dt-buttons {
        margin-left: 57px !important;
    }


    @media print {

        /* #data_header{ display: none !important; }  */
        #first_card_body {
            display: none !important;
        }

        .navbar-expand-md {
            display: none !important;
        }

        .dt-buttons {
            display: none !important;
        }

        #subledgerdata_length {
            display: none !important;
        }

        #subledgerdata_filter {
            display: none !important;
        }

        #print_button_custon {
            display: none !important;
        }

        #subledgerdata_info {
            display: none !important;
        }

        /* #subledgerdata_paginate {
            display: none !important;
        } */

       #subledgerdata{margin-top: auto !important;}
    }

    #custombutton {
        width: 100%;
    }

    #subledgerdata_length {
        margin: 10px;
    }

    #subledgerdata_filter {
        margin: 10px;
        width: 40%;
    }

    .btn.btn-primary {
        left: -22px;
        margin-left: 5px;
    }

    .select2-container {
        text-align: left !important;
    }

    .buttons-print {
        margin-top: 10px;
    }

    .buttons-pdf {
        margin-top: 10px;
    }

    .dt-buttons {
        float: left;
    }

    .buttons-csv {
        margin-top: 10px;
    }

    .buttons-excel {
        margin-top: 10px;
    }

    #subledgerdata_length {
        margin-top: 15px;
    }

    .doc_button {
        margin-top: 10px;
    }

    .img_button {
        margin-top: 10px;
    }

 
#reportSendModal .modal-dialog{max-width:50%;}
 
</style>



@section('content')
    <h2 class="menu-title ">Table - Sub Ledger</h2>
    {{-- <div class="container-fluid mtb-1"> --}}
    <div class="pagecontent">
        <div class="card-body" id="first_card_body">
            {{-- <canvas id='canvas' width="200" height="200"></canvas> --}}

            <form id='accountform' method='post' action="{{url('/')}}/{{$companyname}}/subledger" >
                @csrf
                <div class='row'>

                    <div class="col-2">
                        <label class="lbl_control"> Account Name :</label>
                        <select class='form-control' name="accountId" required id='accountId'>

                        </select>
                    </div>

                    <div class="col-2">
                        <label class="lbl_control">Start Date:</label>
                        <input type='date' name="selectVchFromDate" required id='selectVchFromDate' class='form-control'
                            @if (isset($companyDates)) value="{{ date('Y-m-d', strtotime($companyDates->fs_date)) }}" @else if(isset($free_style_array))  value="{{ $free_style_array['start_date'] }}" @endif />
                    </div>

                    <div class="col-2">
                        <label class="lbl_control">End Date:</label>
                        <input type='date' id='selectVchToDate' required name="selectVchToDate" class='form-control'
                            @if (isset($companyDates)) value="{{ date('Y-m-d', strtotime($companyDates->fe_date)) }}" @else if(isset($free_style_array))  value="{{ $free_style_array['end_date'] }}" @endif />
                    </div>
                    <div class="col-2">
                        {{-- <label class="lbl_control">Cost Center:</label>
                        <input type="text" id="costCenter" name="costCenter" class="form-control" /> --}}
                        <label class="lbl_control">Cost Center :</label>
                        <select class='form-control' name="costId" id='costId'>
                            <option value=''>Select Cost Center</option>
                            @foreach ($costdata as $cost)
                                <option value="{{ $cost->Id }}" @if (isset($free_style_array) &&
                                    array_key_exists('cost_center', $free_style_array) &&
                                    $cost->Id == $free_style_array['cost_center']) selected @endif   @if( isset($selected_costcenter) && $selected_costcenter== $cost->Id ) selected @endif>
                                    {{ $cost->Name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-2">
                        <label class="lbl_control">Division:</label>
                        <select class='form-control' name="selected_division"   >
                            <option value=''>Select Division</option>
                            @foreach ($divisions as  $division_key=>$division_value)
                                    <option value="{{$division_key}}"  @if(isset($selected_division) &&  $selected_division==$division_key ) selected  @endif>{{$division_value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class='row'>
                    <label class="lbl_control mt-1" for="radio1">Optional Columns:</label>

                    <div class="col-12 mt-1">

                        <input class="form-check-input " type="checkbox" id="ChequeNo" name="ChequeNo" value="ChequeNo"  @if(isset($chequeno)) checked @endif>
                        <label class="check-1" for="ChequeNo"> Cheque No</label>

                        {{-- Cheque
                        No --}}

                        <input class="form-check-input" type="checkbox" id="ChequeStatus" name="ChequeStatus"
                            value="ChequeStatus"   @if(isset($chequestatus)) checked @endif> <label class="check-1" for="ChequeStatus">Cheque Status</label>


                        <input class="form-check-input" type="checkbox" id="ClearingDate" name="ClearingDate"
                            value="ClearingDate"   @if(isset($clearingdate)) checked @endif>
                        <label class="check-1" for="ClearingDate">Clearing Date</label>
                        {{-- Clearing Date --}}

                        <input class="form-check-input" type="checkbox" id="CostCentre" name="CostCentre"
                            value="CostCentre"    @if(isset($costcenter)) checked @endif>
                        <label class="check-1" for="CostCentre">Cost Center</label>
                        {{-- Cost Centre --}}
 
          
 
                        <input class="form-check-input" type="checkbox" id="Executive" name="Executive" value="Executive"  @if(isset($executive)) checked @endif>
                        <label class="check-1" for="Executive" >Executive</label>
                        {{-- Executive --}}

                        <input class="form-check-input" type="checkbox"   name="division" value="Division"  @if(isset($division)) checked @endif>
                        <label class="check-1"   >Division</label>
                        {{-- Project --}}

                        <input class="form-check-input" type="checkbox" id="ForeignCurrency" name="ForeignCurrency"
                            value="ForeignCurrency"  @if(isset($foreigncurrency)) checked @endif> <label class="check-1" for="ForeignCurrency">Foreign
                            Currency</label>
                        {{-- Foreign Currency --}}
                    </div>
                </div>
                <div class="col-2 p-2 pt-2">
                    <!-- <a class='btn btn-primary' id="submit" href="javascript:void(0);">Submit</a> -->

                    <button   type="submit" class='btn btn-primary'
                         >Submit</button>
                    <button   id="btncancelsubledger" name="cancel" type="button" class='btn btn-primary'
                        title="Cancel filter">Cancel</button>
                </div>
            </form>
        </div>


<div id="reportSendModal" class="modal fade"  >
	<div class="modal-dialog"   >
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="reportsend_heading"> Report</h4>
				<button type="button" class="close" onclick=" $('#reportSendModal').modal('hide'); ">&times;</button>
			</div>
			<div class="modal-body"> 
				<div class="container">
			

				<form  class="form-horizontal"  id="frm_send_report"   >
					@csrf

                    <input type="hidden"  name="report_mode"  id="send_report_mode"  value="email" />
				 

					<div class="form-group row">
							<label class="lbl_control col-sm-4" >Select Report Format:</label>
							<div class="control-label col-sm-6"> 
								<select class="form-control"   name="reportformat"> 
							 <option value="pdf">PDF</option>
                             <option value="xlsx">XLSX</option>
                             <option value="csv">CSV</option>
								</select>
							</div>
					</div>

                    <div class=" form-group row mt-2">
							<label class="lbl_control col-sm-4" >Select Options:</label>
							<div class="control-label col-sm-8"  > 

							<div  class="whatsapp-send-report" >
							<i class="bx bxl-whatsapp"  style="font-size:16px;"></i>
							 
								<label  class="lbl_control_small mt-2" ><input type="checkbox" id="printcontrol_whatsapptocustomer" name='whatsapp_to_customer'  value="1"  >&nbsp;To Customer</label>
                                &nbsp;<label    class="lbl_control_small  mt-2"  ><input type="checkbox" id="printcontrol_whatsapptosalesman" name='whatsapp_to_salesman'  value="1"  >&nbsp;To Salesman</label>
			             
			               	</div>
                               <div class="clearfix mt-1"></div>
                               <div  class="email-send-report" >
								<i class="bx bx-mail-send"   style="font-size:16px;"></i>
								<label    class="lbl_control_small  mt-2"  ><input type="checkbox" id="printcontrol_emailtocustomer" name='email_to_customer'  value="1"   >&nbsp;To Customer</label>
								&nbsp;
								<label   class=" lbl_control_small  mt-2"   ><input type="checkbox" id="printcontrol_emailtosalesman" name='email_to_salesman'  value="1" >&nbsp;To Salesman</label>
			                 	</div>
                        </div>
                        </div>

				 
					<div class="email-send-report form-group row mt-3"   >
							<label class="lbl_control col-sm-4" >Enter Email:</label>
							<div class="control-label col-sm-6">  
								<input type='email' name="toemailid" class="form-control" placeholder="Enter Email"  id="printcontrol_enteremail" />
							</div>
					</div>
			 
					
					<div class="whatsapp-send-report form-group row  mt-3  ">
							<label class="lbl_control col-sm-4" >Enter Whatsapp no.:</label>
							<div class="control-label col-sm-6">  

								<input type="number"  class="form-control" name="towhatsappno" placeholder="Enter Whatsapp No." id="printcontrol_enterwhatsappno"  />
							  
							</div>
					</div> 
  
					<div class="form-group mt-4">        
					<div class="text-center">
						<button type="button" class="btn btn-primary"  id="btn_send_report">Submit</button>
					</div>
					</div>
  </form>
			 
				 
	         	</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->


        @if(!empty($account_id))
        <div class="row " style="width: 100%; "  >
            <div class="col-12">

            <input type="button" value="XLSX" class="btn btn-primary"  onclick="downloadDocument('xlsx')"  />
            
            <input type="button" value="PDF" class="btn btn-primary"  onclick="downloadDocument('pdf')"  />

            
            <input type="button" value="CSV" class="btn btn-primary"   onclick="downloadDocument('csv')"   />
            <input type="button" value="Print" class="btn btn-primary"   onclick="printReport()"   />
            <input type="button" value="Email" class="btn btn-primary"   onclick="openSendReport('email')"  />
            <input type="button" value="Whatsapp" class="btn btn-primary"   onclick="openSendReport('whatsapp')"  />

                <div class="card">

                <div class="card-body">
                            <div class="mx-auto table-responsive"  >

                        

                            <table class="table table-striped "   id="tblsubledger_head"    width="100%">
                                            <tbody>
                                                <tr  >
                                                    <td>
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td colspan="8"  tabIndex='1'  style="text-align: center; padding:20px;">
                                                                    <h4> Account - Sub Ledger</h4>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr  >
                                                    <td>

                                                        <table>
                                                            <tr><td  style="text-align:left;"   tabIndex='1' >Company Name:</td>
                                                            <td style="font-weight:bold;"  tabIndex='1' >{{$name_of_company}}</td>
                                                            <td  tabIndex='1' >Financial Year:</td>
                                                            <td  tabIndex='1'  style="font-weight:bold;" >{{$financial_year}}</td>
                                                            <td> </td>
                                                            <td> </td>
                                                        
                                                        </tr>
                                                            <tr>
                                                                <td  tabindex="1" style="text-align: left; padding:20px;width:30%">A/C
                                                                    No.: </td>
                                                                <td  tabindex="1"><strong>{{ $account_id}}</strong></td>
                                                                <td  tabindex="1" style="text-align: left; padding:20px;width:10%">Start
                                                                    Date.:
                                                                </td>
 
                                           

                                                                <td  tabindex="1"><strong>{{ $companyDates->fs_date }}</strong></td>
                                                                <td   tabindex="1" style="text-align: left; padding:20px;width:10%">End
                                                                    Date: </td>
                                                                <td  tabindex="1" ><strong>{{ $companyDates->fe_date }}</strong></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr >
                                                    <td>

                                              

                                                    @php

                                                    
                                                    $general_ledger_result=  $reportservice->getGeneralLedger($account_id,$companyDates->fs_date, $companyDates->fe_date, $selected_costcenter, $selected_division);
   
                                                    $found_account_name=    $general_ledger_result['account_name'];
 
                                                     $account_opening_balance= $reportservice->getOpeningBalance($account_id,$companyDates->fs_date, $selected_costcenter, $selected_division);
 
                                                    $account_closing_balance= $reportservice->getClosingBalance($account_opening_balance ,$account_id,$companyDates->fs_date,$companyDates->fe_date, $selected_costcenter, $selected_division);
  
                                                    
                                                    $balance = $account_opening_balance['openingbalance'];
                                                    $totalCredit = 0;
                                                    $totalDebit = 0;
                                                    $FCbalance = $account_opening_balance['OpeningFCbalance'];;
                                                    $totalFCCredit = 0;
                                                    $totalFCDebit = 0;
                                                     
                                                    @endphp


                                                        <table  >
                                                            <tr>
                                                                <td tabIndex="1" style="text-align: left; padding:20px;width:20%">Account Name:
                                                                </td>
                                                                <td  tabIndex="1" ><strong>{{     $found_account_name}}</strong></td>
                                                            </tr>
                                                        
                                          
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                   
                              
                                        <table class="table table-striped   taboncell"   id="tblsubledger"  >
                                            <thead  >
                                                <tr  >
                                                    <th style="width:8%;">VchDate </th>
                                                    <th style="width:10%;">Vch No </th>
                                                    <th style="width:20%;">Account Name</th>
                                                    <th style="width:20%;">Particulars</th>
                                                    <th style="width:10%;">Naration</th>
                                                    <th style="width:10%;">Debit</th>
                                                    <th style="width:10%;">Credit</th>
                                                    <th style="width:10%;">Balance</th>

                                                    @if (isset($chequeno))
                                                        <th style="width:10%;">Cheque No</th>
                                                    @endif
                                                    @if (isset($chequestatus))
                                                        <th style="width:10%;">Cheque Status</th>
                                                    @endif
                                                    @if (isset($clearingdate))
                                                        <th style="width:10%;">Clearing Date</th>
                                                    @endif
                                                    @if (isset($costcentre))
                                                        <th style="width:10%;">Cost Centre</th>
                                                    @endif
                                                    @if (isset($division))
                                                        <th style="width:10%;">Division</th>
                                                    @endif
                                                    @if (isset($executive))
                                                        <th style="width:10%;">Executive</th>
                                                    @endif
                                              
                                                    @if (isset($foreigncurrency))
                                                        <th style="width:10%;">FC Debit</th>
                                                        <th style="width:10%;">FC Credit</th>
                                                        <th style="width:10%;">FC Balance</th>
                                                        <th style="width:10%;">FC Exchange Rate</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>  
  
                                            <tr><td colspan="7" tabindex="1" style="text-align:right;"><strong>Opening Balance:</strong></td><td tabIndex="1" style="font-weight:bold;"  >{{round($account_opening_balance['openingbalance'],2)}} </td>
                                            @if($no_of_additional_columns>0)
                                            <td  tabindex="1"  colspan="{{$no_of_additional_columns}}"></td>
                                            @endif
                                        @if (isset($foreigncurrency))
                                        <td colspan="2"  tabindex="1" style="text-align:right;" ><strong>Opening Fc Balance:</strong></td>
                                        <td> <strong>{{round($account_opening_balance['OpeningFCbalance'],2)}} </strong></td>
                                        <td></td>
                                        @endif


                                        </tr>

                                    
                                       
    
                                            @foreach (  $general_ledger_result['data'] as $general_ledger_entry)


                                            <tr>
                                                <td  tabindex="1" >{{ date('d-m-Y', strtotime($general_ledger_entry->VchDate))}}</td>
                                                <td  tabindex="1"  style="width:10%;"><a href='javascript:void(0);' class='edit-tran-link' data-vchno="{{ $general_ledger_entry->VchNo }}">{{ $general_ledger_entry->VchNo }}</td>
                                                            <td  tabindex="1"  style="width:20%;">{{ $general_ledger_entry->ACName }}</td>

                                                            @php
                                                             $particulars= (array_key_exists($general_ledger_entry->Id,$general_ledger_result['particulars'])?$general_ledger_result['particulars'][$general_ledger_entry->Id]:"");
                                                            @endphp
                                                            <td  tabindex="1"  style="width:20%;">{!! $particulars !!}</td>
                                                            <td  tabindex="1"  style="width:10%;">{{ $general_ledger_entry->Narration }}</td>
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

                                                                <td  tabindex="1"  style="width:10%;">{{ $debit }}</td>
                                                            <td  tabindex="1"  style="width:10%;">{{abs($credit) }}</td>
                                                            <td  tabindex="1"  style="width:10%;">{{ $conbalance }}</td>

                                                            


                                                            @if (isset($chequeno))
                                                                <td  tabindex="1"  style="width:10%;">{{ $general_ledger_entry->chq_no }}</td>
                                                            @endif
                                                            @if (isset($chequestatus))
                                                                <td  tabindex="1"  style="width:10%;">{{ $general_ledger_entry->ch_status }}</td>
                                                            @endif
                                                            @if (isset($clearingdate))
                                                                <td  tabindex="1"  style="width:10%;">{{$general_ledger_entry->cl_date }}</td>
                                                            @endif
                                                            @if (isset($costcentre))
                                                                <td  tabindex="1"  style="width:10%;">{{$general_ledger_entry->costcentre }}</td>
                                                            @endif
                                                            @if (isset($division))
                                                                <td  tabindex="1"  style="width:10%;">{{ $general_ledger_entry->DivisionName }}</td>
                                                            @endif
                                                            @if (isset($executive))
                                                                <td  tabindex="1"  style="width:10%;">{{ $general_ledger_entry->exeName }}</td>
                                                            @endif
                                                       

                                                            @if (isset($foreigncurrency))
                                                           
                                                            <td  tabindex="1"  style="width:10%;">{{ $fcdebit }}</td>
                                                            <td   tabindex="1"  style="width:10%;">{{abs($fccredit) }}</td>
                                                            <td   tabindex="1"  style="width:10%;">{{ $conFCbalance }}</td>
                                                                <td   tabindex="1"  style="width:10%;">{{ $general_ledger_entry->fcexrate }}</td>
                                                            @endif 

                                            </tr>
                                         
                                            @endforeach
                                            <tr><td colspan="5"  tabindex="1"   style="text-align:right;"><strong >Total Debit & Credit</strong></td><td>{{  $totalDebit}}</td><td>{{  $totalCredit}}</td><td></td>
                                                                    


                                            @if(isset($foreigncurrency))
                                            @if($no_of_additional_columns>0)
                                            <td   tabIndex="1"  colspan="{{$no_of_additional_columns}}"  style="text-align:right;"><strong >Total Fc Debit & Credit</strong></td>
                                            @endif
                                            <td  tabIndex="1" >{{  $totalFCDebit}}</td><td  tabIndex="1" >{{  $totalFCCredit}}</td>
                                            <td  tabindex="1"></td>      <td  tabindex="1"></td>

                                            @else
                                            <td  tabindex="1" colspan="{{$no_of_additional_columns}}"></td>
                                            @endif
                                        
                                        
                                        </tr>
                                            <tr><td  tabindex="1" colspan="7" style="text-align:right;"><strong>Closing Balance:</strong></td><td  tabIndex="1" ><strong>{{ round($account_closing_balance['Closingbalance'],2)}}</strong></td>
                                           
                                            @for ($i=0;$i<$no_of_additional_columns;$i++)
                                            <td  tabindex="1"></td>
                                                
                                            @endfor
                                            @if(isset($foreigncurrency))
                                            <td colspan="2"  tabindex="1" style="text-align:right;"><strong>Closing Fc Balance:</strong></td>
                                            <td  tabIndex="1" >{{ round($account_closing_balance['ClosingFCbalance'],2)}}</td>
                                            <td></td>
                                            @endif

                                        </tr>
                                            </tbody>
                                        </table>
  
                        

                             
                            </div>
                     
                         
                        </div>
                     

                 
                </div>
            </div>
          
        </div>
        @endif
    </div>


    <!-- <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="progress-outer">
                        <div class="progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped active" style="width:0%; box-shadow:-1px 10px 10px rgba(91, 192, 222, 0.7);"></div>
                            <div class="progress-value">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
@endsection
@section('js')

<script src="{{ asset('js/taboneachcell.js') }}"></script>
    <script>
        $(".dt-buttons button").addClass('poojabutton');
        $(document).ready(function() {

            // add select2 dropdown
            // $('select').select2();

            //hiding datatable on load
            // $('#table_row').hide();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var selectdata = null;
            var dropdownvalue = null;
            $('#cancel').on('click', function() {
                window.location.href = window.location.href;
            });

            var searchaccounturl = "{{ url('/') }}/{{ $companyname }}/search-by-account-name-restricted";
            initSelect2Search("#accountId", searchaccounturl, "Select Account");
            @php
                   if(!empty($id )){
                    $found_account_id=$id ;
                   }
                   else if(!empty($account_id)){
                    $found_account_id=$account_id;
                   }
                   else{
                    $found_account_id="";  
                   }
                 
                @endphp


            @if (!empty( $found_account_id))
          

                initSelect2WithOnlyOneOption("#accountId", 'Select Account', {{   $found_account_id}},
                    "{{ $account_name }}");
            @endif


            // var userTable = $('#subledgerdata').DataTable();
            $(document).on('click', '#submit', function(e) {
                e.preventDefault();

                $('#submit').attr("disabled", true).text("Processing...");

                if ($.fn.DataTable.isDataTable('#subledgerdata')) {
                    $('#subledgerdata').DataTable().destroy();
                }
                $('#table_row').show();

                selectdata = $('#accountId :selected').val();

                selectacname = $('#accountId :selected').text();
                selectVchFromDate = $('#selectVchFromDate').val();
                selectVchToDate = $('#selectVchToDate').val();
                costCenter = $('#costId :selected').val();
                department = $('#deptId :selected').val();
                // dropdownvalue = $('#selectDbField :selected').val();
                // console.log(dropdownvalue);
                // opening = $('#opening').val();

                if ($('input#ChequeNo').is(':checked')) {
                    $ChequeNo = true;
                } else {
                    $ChequeNo = false;
                }

                if ($('input#ChequeStatus').is(':checked')) {
                    $ChequeStatus = true;
                } else {
                    $ChequeStatus = false;
                }

                if ($('input#ClearingDate').is(':checked')) {
                    $ClearingDate = true;
                } else {
                    $ClearingDate = false;
                }

                if ($('input#CostCentre').is(':checked')) {
                    $CostCentre = true;
                } else {
                    $CostCentre = false;
                }

                if ($('input#Department').is(':checked')) {
                    $Department = true;
                } else {
                    $Department = false;
                }

                if ($('input#Project').is(':checked')) {
                    $Project = true;
                } else {
                    $Project = false;
                }

                if ($('input#Executive').is(':checked')) {
                    $Executive = true;
                } else {
                    $Executive = false;
                }
                if ($('input#ForeignCurrency').is(':checked')) {
                    $ForeignCurrency = true;
                } else {
                    $ForeignCurrency = false;
                }

                if (!$ForeignCurrency) {
                    $('#op_fc_balance').hide();
                }

                $('#acno').text(selectdata).css("font-weight", "bold");
                $('#datefrom').text(selectVchFromDate).css("font-weight", "bold");
                $('#dateto').text(selectVchToDate).css("font-weight", "bold");
                $('#acname').text(selectacname).css("font-weight", "bold");
                // $('#opening').text().css("font-weight", "bold");

                var userTable = $('#subledgerdata').DataTable({

                    "serverMethod": "POST",
                    "sAjaxSource": "{{ route('company.subledger', $companyname) }}",
                    "processing": true,
                    "bPaginate": false,
                    "serverSide": true,
                    "searching": true,
                    "lengthMenu": [
                        [10, 25, 50, 100, 250, 500, 1000, -1],
                        [10, 25, 50, 100, 250, 500, 1000, 'All'],
                    ],
                    "iDisplayLength": 25,
                    "responsive": true,
                    // "dom": 'Blfrtip',
                    // buttons: [
                    //     {
                    //         extend: 'print',
                    //         footer: true,
                    //         className: 'btn btn-primary',
                    //         exportOptions: {
                    //             columns: [ 0, ':visible' ],
                    //         },bbb
                    //         autoPrint: false,
                    //     }
                    // ],
                    "dom": 'Blfrtip',
                    "buttons": [

                        // (1) print button
                        // {
                        //     extend: 'print',
                        //     className: 'btn btn-primary',
                        //     footer: true,
                        //     exportOptions: {
                        //         columns: ':visible',
                        //     },
                        //     autoPrint: true,
                        //     customize: function(win) {
                        //         var header_html = '';
                        //         header_html += '<div class="row" id="table_row">';
                        //         header_html += '<div class="col-12">';
                        //         header_html += '<div class="card">';
                        //         header_html += '<div class="card-header">';
                        //         header_html += $('#table_row .card .card-header').html()
                        //         header_html += '</div>';
                        //         header_html += '</div>';
                        //         header_html += '</div>';
                        //         header_html += '</div>';
                        //         $(win.document.body)
                        //             .css('font-size', '10pt')
                        //             .prepend(header_html);

                        //         $('print-preview-app').find('.dataTables_wrapper')
                        //             .addClass('card-header')
                        //             .css('font-size', 'inherit');
                        //     },
                        // },

                        // (2) pdf button
                        {
                            extend: 'pdfHtml5',
                            className: 'btn btn-primary',
                            footer: 'true',
                            exportOptions: {
                                columns: ':visible',
                            },
                            orientation : 'landscape',
                            pageSize : 'LEGAL',
                             customize: function(win) {
                                var header_htmll = '';
                                header_htmll += '<div class="row" id="table_row">';
                                header_htmll += '<div class="col-12">';
                                header_htmll += '<div class="card">';
                                header_htmll += '<div class="card-header">';
                                header_htmll += $('#table_row .card .card-header').html();
                                header_htmll += '</div>';
                                header_htmll += '</div>';
                                header_htmll += '</div>';
                                header_htmll += '</div>';
                                $(win.document)
                                    .css('font-size', '10pt')
                                    .prepend(header_htmll);

                                $('pdf-viewer').find('.dataTables_wrapper')
                                    .addClass('card-header')
                                    .css('font-size', 'inherit');
                            },
                        },

                        // (3) csv button
                        {
                            extend: 'csv',
                            className: 'btn btn-primary',
                            footer: true,
                            exportOptions: {
                                columns: ':visible',
                            }
                        },
                        // (4) xlsx button
                        {
                            extend: 'excel',
                            className: 'btn btn-primary',
                            action:function(){
                                 downloadExcel();
                            }
                            // footer: true,
                            // exportOptions: {
                            //     columns: ':visible',
                            // }
                         
                        },


                        // (5) xls button
                        {
                            text: 'xls',
                            extend: 'excelHtml5',

                            className: 'btn btn-primary',
                            footer: true,
                            exportOptions: {
                                columns: ':visible',
                            },
                            action: function() {
                                $("#table_row").table2excel({
                                    filename: "sub_ledger.xls", // do include extension
                                });
                            }
                        },

                        // (6)  doc button
                        {
                            text: 'Doc',
                            className: 'btn btn-primary doc_button',
                            footer: false,
                            action: function(e, dt, node, config) {
                                // var data = dt.buttons.exportData();
                                // var table = $('#subledgerdata')
                                //     .DataTable().rows().data().toArray();
                                $("#table_row").wordExport();
                            }
                        },

                        // {
                        //     text: 'text',
                        //     className: 'btn btn-primary text_button buttons-excel',
                        //     // footer: false,
                        //     action: function() {
                        //         $("#table_row").text({

                        //             filename: "sub_ledger.text",

                        //         });
                        //         // var result = docx({
                        //         //     DOM: $('#table_row')[0]
                        //         // });
                        //         // var blob = b64toBlob(result.base64,
                        //         //     "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                        //         //     );
                        //         // saveAs(blob, "test.docx");
                        //     }

                        // },

                        // (7) Image button 
                        {
                            text: 'image',
                            className: 'btn btn-primary img_button',
                            // orientation: 'portrait',
                            action: function() {
                                html2canvas(document.getElementById("table_row"))
                                    .then(function(canvas) {
                                        var anchorTag = document.createElement("a");
                                        document.body.appendChild(anchorTag);
                                        document.getElementById("previewImg")
                                            .appendChild(canvas);
                                        anchorTag.download = "filename.jpg";
                                        anchorTag.href = canvas.toDataURL();
                                        anchorTag.target = '_blank';
                                        anchorTag.click();
                                    });
                            },

                        }

                    ],

                    serverData: function(sSource, aoData, fnCallback, oSettings) {
                        // aoData.append('token',token)
                        aoData.push({
                            name: "accountId",
                            value: selectdata
                        }, {
                            name: "selectVchFromDate",
                            value: selectVchFromDate
                        }, {
                            name: "selectVchToDate",
                            value: selectVchToDate
                        }, {
                            name: "costCenter",
                            value: costCenter
                        }, {
                            name: "department",
                            value: department
                        }, {
                            name: "selectDbField",
                            value: dropdownvalue
                        });

                        oSettings = $.ajax({
                            dataType: "json",
                            type: "post",
                            async: false,
                            contenType: false,
                            crossDomain: true,
                            url: sSource,
                            data: aoData,
                            success: fnCallback
                        });
                    },
                    "columns": [{
                            target: 0,

                            "data": "data.VchDate",


                        },
                        {
                            target: 1,
                            "data": "data.VchNo",
                        },
                        {
                            target: 2,
                            "data": "data.perticulars",
                            render: function(data) {
                                if (data === null) {
                                    return "-";
                                }
                                return data;
                            }
                        },
                        {
                            target: 3,
                            "data": "data.Narration",
                            render: function(data) {
                                if (data === null) {
                                    return "-";
                                }
                                return data;
                            }
                        },
                        {
                            target: 4,
                            "data": "debit",

                        },
                        {
                            target: 5,
                            "data": "credit",

                        },
                        {
                            target: 6,
                            "data": "balance",
                        },

                        {
                            target: 7,
                            visible: $ChequeNo,
                            "data": "data.chq_no",
                        },
                        {
                            target: 8,
                            visible: $ChequeStatus,
                            "data": "data.ch_status",
                        },
                        {
                            target: 9,
                            visible: $ClearingDate,
                            "data": "data.cl_date",
                        },
                        {
                            target: 10,
                            visible: $CostCentre,
                            "data": "data.costName",
                            render: function(data) {
                                if (data === null) {
                                    return "-";
                                }
                                return data;
                            }
                        },

                        {
                            target: 11,
                            visible: $Department,
                            "data": "data.DDeptName",
                        },
                        {
                            target: 12,
                            visible: $Executive,
                            "data": "data.exeName",
                        },
                        {
                            // project

                            target: 13,
                            visible: $Project,
                            "data": "data.ProjectName",
                        },
                        {
                            target: 14,
                            visible: $ForeignCurrency,
                            "data": "fcdebit",
                        },
                        {
                            target: 15,
                            visible: $ForeignCurrency,
                            "data": "fccreadit",
                        },
                        {
                            target: 16,
                            visible: $ForeignCurrency,
                            "data": "FCbalance",
                        },
                        {
                            target: 17,
                            visible: $ForeignCurrency,
                            "data": "data.fcexrate",
                        },

                    ],
                    "fnFooterCallback": function(nRow, aaData, iStart, iEnd, aiDisplay) {
                        var api = this.api();
                        var OP_balance = 0;
                        var OP_FC_balance = 0;
                        var close_balance = 0;
                        var close_FC_balance = 0;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ? i : 0;
                        };

                        $(aaData).each(function(i, val) {
                            // console.log(i);
                            OP_balance = val.Openingbalance;
                            OP_FC_balance = val.OpeningFCbalance;
                            close_balance = val.Closingbalance;
                            close_FC_balance = val.ClosingFCbalance;

                        });

                        // $('#opening').text(OP_balance);
                        if ($ForeignCurrency) {
                            $('#op_fc_balance').show();
                            $('#openingfcbalance').text(OP_FC_balance);
                        }

                        // Total over this page
                        totalDebit = api
                            .column(4, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        totalCredit = api
                            .column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return Math.abs(intVal(a) + intVal(b));
                            }, 0);


                        totalFCDebit = api
                            // .append('<td style="width:15%;"> FC Opening Balance: '+OP_FC_balance+'</td>')
                            .column(14, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);


                        totalFCCredit = api
                            .column(15, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return Math.abs(intVal(a) + intVal(b));
                            }, 0);

                        // Update footer

                        $(api.column(4).footer()).html(parseFloat(totalDebit).toFixed(2));
                        $(api.column(5).footer()).html(parseFloat(totalCredit).toFixed(2));
                        // $(api.column(6).footer()).html(close_balance).toFixed(2));
                        $(api.column(6).footer()).html("Cl. Bal.: " + parseFloat(
                            close_balance).toFixed(2));

                        $(api.column(14).footer()).html(parseFloat(totalFCDebit).toFixed(2));
                        $(api.column(15).footer()).html(parseFloat(totalFCCredit).toFixed(2));
                        $(api.column(16).footer()).html("Closing FC Balance: " + parseFloat(
                            close_FC_balance).toFixed(2));

                        // $(api.column(3).footer()).html( );
                        $('#submit').attr("disabled", false).text("Submit");

                        const tr = $('<tr>')
                            .append('<td style="width:8%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:20%;"></td>')
                            .append('<td style="width:20%;"></td>')
                            .append('<td style="width:10%;">Op. Bal.: ' + OP_balance + '</td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"> FC Op. Bal.: ' + OP_FC_balance +
                                '</td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('<td style="width:10%;"></td>')
                            .append('</tr>');

                        setTimeout(() => {
                            userTable.row.add(tr[0]);
                            $('#subledgerdata tbody').prepend(tr[0]);
                        }, 500);

                    },

                    initComplete: function() {
                        $('#subledgerdata_filter label').hide();

                        var htmlSearch = '<div id="custombutton" class="row">';
                        htmlSearch += '<div class="col-md-12">';
                        htmlSearch += '<div class="input-group">';
                        htmlSearch += '<div class="col-md-4">';

                        htmlSearch +=
                            '<select class="form-control" name="selectDbField" id="selectDbField">';
                        htmlSearch += '<option value="">Select Column</option>';
                        htmlSearch += '<option value="VchMain.VchDate">VchDate</option>';
                        htmlSearch += '<option value="VchMain.VchNo">VchNo</option>';
                        htmlSearch += '<option value="Vchdet.Narration">Naration</option>';
                        htmlSearch += '<option value="Vchdet.Amount">Debit</option>';
                        htmlSearch += '<option value="Vchdet.Amount">Credit</option>';
                        if ($ChequeNo) {
                            htmlSearch += '<option value="VchMain.chq_no">Cheque No</option>';
                        }
                        if ($ChequeStatus) {
                            htmlSearch +=
                                '<option value="VchMain.ch_status">Cheque Status</option>';
                        }
                        if ($ClearingDate) {
                            htmlSearch +=
                                '<option value="VchMain.cl_date">Clearing Date</option>';
                        }
                        if ($CostCentre) {
                            htmlSearch +=
                                '<option value="Costcentre.Name">Cost Center</option>';
                        }
                        if ($Department) {
                            htmlSearch +=
                                '<option value="Department.DeptName">Department</option>';
                        }
                        if ($Executive) {
                            htmlSearch += '<option value="SalesMen.Name">Executive</option>';
                        }
                        if ($Project) {
                            htmlSearch +=
                                '<option value="Project.ProjectName">Project</option>';
                        }
                        if ($ForeignCurrency) {
                            htmlSearch += '<option value="Vchdet.FCamt">DebitFC</option>';
                            htmlSearch += '<option value="Vchdet.FCamt">CreditFC</option>';
                            htmlSearch +=
                                '<option value="VchMain.fcexrate">Exchange Rate</option>';
                        }
                        htmlSearch += '</select>';
                        htmlSearch += '</div>';
                        htmlSearch += '<div class="col-md-4" style="float: left;">';
                        htmlSearch += '<div class="form-outline" style="float: left;">';
                        htmlSearch +=
                            '<input type="search" class="form-control" name="searchtext" id="searchtext" placeholder="Search.." />';
                        htmlSearch += '</div>';
                        htmlSearch += '</div>';
                        htmlSearch += '<div class="col-md-3" style="float: left;">';
                        htmlSearch +=
                            '<button type="button" class="btn btn-primary" id="searchBtn">';
                        htmlSearch += '<i class="fas fa-search"></i>';
                        htmlSearch += '</button>';
                        htmlSearch +=
                            '<button type="button" class="btn btn-primary" id="clearBtn">';
                        htmlSearch += '<i class="fas fa-refresh" aria-hidden="true"></i>';
                        htmlSearch += '</button>';
                        htmlSearch += '</div>';
                        htmlSearch += '</div>';
                        htmlSearch += '</div>';
                        htmlSearch += '</div>';
                        // var htmlSearch = $('#custombutton').html();
                        $(htmlSearch).appendTo('#subledgerdata_filter');

                        // $('#subledgerdata_filter').css('width', '31%');
                        $('select#selectDbField').select2();

                        setTimeout(() => {
                            $('#searchBtn').on('click', function() {
                                dropdownvalue = $('#selectDbField :selected')
                                    .val();

                                userTable.search($('#searchtext').val()).draw(
                                    false);
                                // $('#searchBtn').unbind();
                            });
                            $('#clearBtn').on('click', function() {

                                $('#selectDbField').select2('destroy').val("")
                                    .select2();
                                // $('#selectDbField').empty();
                                // dropdownvalue = $('#selectDbField :selected').val();
                                userTable.search($('#searchtext').val('')).draw(
                                    true);
                                // $('#clearBtn').unbind();
                            });
                        }, 1000);
                    }
                });
            });


            @if (isset($treestyletrialbalance))

                $("#submit").trigger("click");
            @endif
        });

        function printReport(){

//         var report = document.getElementById("table_row");
// var parms = "scrollbars,resizable,width=500,height=500,left=50,top=50";
// var printWindow = window.open('',rpt, parms);
// printWindow.document.write('<html><body onload="window.focus();window.print()">');
// printWindow.document.write(report.innerHTML);       
// printWindow.document.write('</body></html>');
// printWindow.document.close();
// // printWindow.close(); // not sure this is ok either. 

        var dataheader = document.getElementById("tblsubledger_head").innerHTML;
        var databody=document.getElementById("tblsubledger").innerHTML;

       var senddatabody= "<table  style='border:1px solid black;'>"+dataheader+'</table>'+ "<table  style='border:1px solid black;'>"+databody+'</table>';
        printFromHtml(senddatabody);


    }


    function downloadExcel(){
        
        $.post("{{$companyname}}/download-subledger-excel",function(data,status){

        })
    }




    function downloadDocument(format){


        var url="{{url('/')}}/{{$companyname}}/download-subledger/"+format;

        
        window.open(url); 

    }



    $("#btncancelsubledger").click(function(){
                $.get("{{url('/')}}/{{$companyname}}/cancel-cache-report-input-by-name/sub-ledger",function(data,status){

                    var result=JSON.parse(JSON.stringify(data));

                    if(result['status']=='success'){ 

                        window.location.href = "{{url('/')}}/{{$companyname}}/subledger";
                    }

                });
            });



        //     var currCell = $('#tblsubledger td').first();
        //    var editing = false;

// User clicks on a cell
// $('td').click(function () {
//     currCell = $(this);

//     var col = $(this).parent().children().index($(this)) + 1;
//     var row = $(this).parent().parent().children().index($(this).parent()) + 1;
//     // alert('Row: ' + row + ', Column: ' + col + ', Value: ' + currCell.html());

//     //   edit();
// });

// Show edit box
// function edit() {
//     editing = true;
//     currCell.toggleClass("editing");
//     $('#edit').show();
//     $('#edit #text').val(currCell.html());
//     $('#edit #text').select();
// }

// User saves edits
// $('#edit form').submit(function (e) {
//     editing = false;
//     e.preventDefault();
//     // Ajax to update value in database
//     $.get('#', '', function () {
//         $('#edit').hide();
//         currCell.toggleClass("editing");
//         currCell.html($('#edit #text').val());
//         currCell.focus();
//     });
// });

// User navigates table using keyboard
//$('table').keydown(function (e) {
// $('table#tblsubledger').keydown(function (e) {
 
//     var c = "";
//     if (e.which == 39) {
//         // Right Arrow
//         c = currCell.next();
//     } else if (e.which == 37) {
//         // Left Arrow
//         c = currCell.prev();
//     } else if (e.which == 38) {
//         // Up Arrow
//         c = currCell.closest('tr').prev().find('td:eq(' + currCell.index() + ')');
//     } else if (e.which == 40) {
//         // Down Arrow
//         c = currCell.closest('tr').next().find('td:eq(' + currCell.index() + ')');
//     } else if (!editing && (e.which == 13 || e.which == 32 || e.which == 113)) {
//         // Enter, Spacebar, F2 - edit cell
//         e.preventDefault();
//         edit();
//     } else if (!editing && (e.which == 9 && !e.shiftKey)) {
//         // Tab
//         e.preventDefault();
//         c = currCell.next();
//     } else if (!editing && (e.which == 9 && e.shiftKey)) {
//         // Shift + Tab
//         e.preventDefault();
//         c = currCell.prev();
//     }

//     // If we didn't hit a boundary, update the current cell
//     if (c.length > 0) {
//         currCell = c;
//         currCell.focus();
//     }
// });

// User can cancel edit by pressing escape
// $('#edit').keydown(function (e) {
//     if (editing && e.which == 27) {
//         editing = false;
//         $('#edit').hide();
//         currCell.toggleClass("editing");
//         currCell.focus();
//     }
// });

function resetSendReport(){
    $("#printcontrol_whatsapptocustomer").prop('checked',false);
    $("#printcontrol_whatsapptosalesman").prop('checked',false); 
    $("#printcontrol_emailtocustomer").prop('checked',false);
    $("#printcontrol_emailtosalesman").prop('checked',false); 
    $("#printcontrol_enteremail").val('');
    $("#printcontrol_enterwhatsappno").val('');
}


function openSendReport(type){  

    resetSendReport();
    $("#reportSendModal").modal('show');

    if(type=="whatsapp"){

        $(".whatsapp-send-report").removeClass('d-none');  
          $(".email-send-report").addClass('d-none');

          $("#reportsend_heading").html("Whatsapp Report");

          $("#send_report_mode").val("whatsapp");
    }
    else{
 
          $(".email-send-report").removeClass('d-none');
        $(".whatsapp-send-report").addClass('d-none'); 
        
        $("#send_report_mode").val("email");
        
        $("#reportsend_heading").html("Email Report");

    }


}


$("#btn_send_report").click(function(){
    $.ajax({
                method: 'POST',
                url: "{{url('/')}}/{{$companyname}}/send-subledger-report-email-whatsapp",
                data: $("#frm_send_report").serialize(),
                success: function(data) { 
                    $("#reportSendModal").modal("hide");
                    SnackbarMsg(data);
                }
            });
});


$(".edit-tran-link").click(function(){
    var vchno=$(this).data("vchno");

    $.get("{{url('/')}}/{{$companyname}}/get-url-for-edit-transaction-data-by-bill-no/"+vchno,function(data,status){

        var result=JSON.parse(JSON.stringify(data));

        window.open(result['url'], '_blank');
    });
 
});


    </script>
@endsection
 