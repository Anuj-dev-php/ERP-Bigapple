@extends('layout.layout')
@inject('reportservice','App\Http\Controllers\Services\ReportService')
@php
$reportservice->user_id=\Auth::user()->id;
@endphp
 
<style>
.tree_checkbox_lbl {
	background-color: rgb(255, 255, 255);
	color: rgb(0, 0, 0);
	cursor: pointer;
}
</style> @section('content')
<h4 class="menu-title  mb-5 font-size-18 addeditformheading"> {{$report_name}}</h4>
 

<div class="pagecontent"> 
	<div class="container-fluid">
 
<form method='post'  action="{{url('/')}}/{{$companyname}}/balance-sheet"  >
  @csrf
		<div class='row'>
			<div class="col-md-4  " style=' height:200px;overflow-y:scroll;'>
			<input type='text'  name='search_account'  style='width:50%;outline:none;'  id='search_txt'  /> &nbsp; <input type='button' class='btn btn-primary' value='Search'  id="btn_search_account" />
	
			<div class='hummingbird-treeview'>
			<ul id="menu_level_tree" class="hummingbird-base" style="padding-left:0px!important;">
         @foreach ($accounts as $account  )
					<li data-id="{{$account->id}}"> <i class="fa fa-plus" data-level="1" tabIndex="1" data-id="{{$account->id}}"></i>
					<a class='ga_link'  data-id="{{$account->id}}"  data-level="1" data-ga="{{trim($account->ga)}}">
						<label class="tree_checkbox_lbl"> 
							{{$account->account_name}} (Level 1)</label>
					</a>
					</li> @endforeach 
				</ul>
				</div>
			</div>
		
			<div class="col-md-8" style=' height:250px;'>
				<div class='row'>

				<div class="form-group col-6 mtb-2 ">
						<label class="lbl_control_inline">Start Date :</label>
						<input type='text' class="form-control inline_control " name='start_date' value="@if(!empty($start_date_string)){{$start_date_string}}@endif" id='start_date' /> </div>
					<div class="form-group col-6  mtb-2">
						<label class="lbl_control_inline">End Date :</label>
						<input type='text' class="form-control inline_control " name='end_date' value="@if(!empty($end_date_string)){{$end_date_string}}@endif" id='end_date' /> </div>
			
						<div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Account Level :</label>
					 
						<select class="form-control inline_control"  name='selected_account_level' ><option value="">All</option>
					 
						@for ($i=1;$i<=25;$i++)
							
						<option value="{{$i}}"  @if(isset($selected_account_level) && $selected_account_level==$i ) selected @endif>{{$i}}</option>
						@endfor 
					
					    </select>
					 
					</div>

					<div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Cost Center :</label>
							<select  class="form-control inline_control"  name="cost_center">
								<option value="">All</option>
								 @foreach ($costcenters as $costcenter)
								 <option value="{{$costcenter->Id}}" @if(isset($cost_center) && $cost_center==$costcenter->Id ) selected @endif>{{$costcenter->Name}}</option>
									 
								 @endforeach
							</select>
						   </div>

						   <div class="form-group col-6 mtb-1 ">
						<label class="lbl_control_inline">Division :</label>

						<select  class="form-control inline_control"  name="division">
								<option value="">All</option>
								 @foreach ($divisions as $div_key=>$div_val)
								 <option value="{{$div_key}}"  @if(isset($division) && $division==$div_key) selected @endif>{{$div_val}}</option>
									 
								 @endforeach
							</select>
						   </div>
					
			 
			 

					<div class="form-group col-6 mtb-1 " style='text-align:left;'>
					
						<input class="form-check-input" type="checkbox"  name="showzeros" value="1"  @if(isset($showzeros) && $showzeros==1)) checked  @endif>
						<label class="check-1" for="showzeros">Show Zeros</label>

						&nbsp;&nbsp;

						
					<input class="form-check-input" type="checkbox"  name="showdetails" value="1"  @if(isset($showdetails) && $showdetails==1)) checked  @endif>
					<label class="check-1" for="showdetails">Show Details</label>
 

					
					</div>

					
					<div class="form-group col-6 mtb-1 " style='text-align:left;'>
					
					<input class="form-check-input" type="checkbox"  name="showforeigncurrency" value="1"  @if(isset($show_foreigncurrency) && $show_foreigncurrency==1)) checked  @endif>
						<label class="check-1" for="showzeros">Show Foreign Currency</label>
						&nbsp;&nbsp;


					</div>
					<div class="form-group col-12 mtb-1 " style='text-align:center;'>
						<input type='submit' class='btn btn-primary' value='Submit' />
						<input type='button' class='btn btn-primary' value='Cancel' id='btncancel' /> </div>
				</div>
			</div>
		</div>

</form> 

<ul class="nav nav-tabs" id="menutablist" role="tablist">
			<li class="nav-item" role="Vertical" id="tablayout"> <a class="nav-link @if($report_type=='vertical') active  @endif" id="vertical-report-tab" data-bs-toggle="tab" href="#vertical-report" role="tab" aria-controls="verticalreport" aria-selected="true" style="font-weight: 500;">Vertical Report</a> </li>
            <li class="nav-item" role="Horizontal" id="tablayout"> <a class="nav-link @if($report_type=='horizontal') active  @endif " id="horizontal-report-tab" data-bs-toggle="tab" href="#horizontal-report" role="tab" aria-controls="horizontalreport" aria-selected="true" style="font-weight: 500;">Horizontal Report</a> </li>

  </ul>

        <div class="tab-content"  >
			<div class="tab-pane fade show @if($report_type=='vertical')  active @endif small" id="vertical-report" role="tabpanel" aria-labelledby="vertical-report-tab">
          <input type="button" class="btn btn-primary" value="PDF" onclick="downloadDocument('pdf','vertical')"/>
		  &nbsp;	  &nbsp;
		  <input type="button" class="btn btn-primary" value="EXCEL"  onclick="downloadDocument('xlsx','vertical')" />
		  &nbsp;	  &nbsp;
		  <input type="button" class="btn btn-primary" value="CSV"  onclick="downloadDocument('csv','vertical')"  />
		  <div class="card" >
		  <div class="card-body" >
		  <div class=" mx-auto table-responsive">
			<table class="table  table-striped   taboncell"  >
                    <thead><th>Account Name</th>   
					<th>Account Type</th> 
                            <th>Parent Name</th>
                            <th>Amount</th>
							@if($show_foreigncurrency==1)
							<th>Fc Amount</th>
							@endif

							@if($showdetails==1)

							<th>Opening Debit Balances</th>
							<th>Opening Credit Balances</th><th>Total Debits</th><th>Total Credits</th><th>Closing Debit Balances</th><th>Closing Credit Balances</th>
								@if($show_foreigncurrency==1)
								<th>Fcamt Opening Debit Balances</th><th>Fcamt Opening Credit Balances</th><th>Fcamt Total Debits</th><th>Fcamt Total Credits</th><th>Fcamt Total Credit Balances</th><th>Fcamt Total Debit Balances</th>
								@endif

				         	@endif


                    </thead>
                    <tbody> 
						
					@if (count($selected_accounts_data_vertical)==0)
					<tr>
						<td  @if($show_foreigncurrency==1) colspan="5" @else colspan='4' @endif>No Data</td>

					</tr>
						
				   @endif

				   @php 

						$total_profit_loss=(array_key_exists('profit_loss',$profit_loss_detail)?$profit_loss_detail['profit_loss']:0);

						$total_fc_profit_loss=(array_key_exists('fc_profit_loss',$profit_loss_detail)?$profit_loss_detail['fc_profit_loss']:0);
 

						$diff_liabilities_assets=$total_liabilities-$total_assets;

						$diff_fc_liabilities_assets=$total_fcamt_liabilities-$total_fcamt_assets;
					@endphp



					@foreach (  $selected_accounts_data_vertical as $selected_account_id)
					@php
				      $result=	$reportservice->getBalanceSheetAccountDetail($selected_account_id);
					@endphp

					@if($selected_account_id==1)

					<tr>
						<td tabIndex="1" class="left-text"> </td>
						<td tabIndex="1" ></td>
						<td tabIndex="1"   ><strong>Profit / Loss<strong></td>
						<td tabIndex="1"  class="right-text"> <strong>{{$total_profit_loss}}</strong></td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text"><strong>{{$total_fc_profit_loss}}</strong></td>
						@endif
					</tr>

					
					<tr>
						<td tabIndex="1" class="left-text"> </td>
						<td tabIndex="1" ></td>
						<td tabIndex="1"   ><strong>Total Liabilities</strong></td>
						<td tabIndex="1"  class="right-text"> <strong>{{$total_liabilities}}</strong></td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text"><strong>{{$total_fcamt_liabilities}}</strong></td>
						@endif

						
						@if($showdetails==1)
							@if($show_foreigncurrency==1)
							<td colspan="12"></td>
							@else
							<td colspan="6"></td>
							@endif 
						@endif
					</tr> 
 
					<tr>
						<td tabIndex="1" class="left-text"> </td>
						<td tabIndex="1" ></td>
						<td tabIndex="1"   ></td>
						<td tabIndex="1"  class="right-text"></td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text"> </td>
						@endif

							
						@if($showdetails==1)
							@if($show_foreigncurrency==1)
							<td colspan="12"></td>
							@else
							<td colspan="6"></td>
							@endif 
						@endif

					</tr>
					<tr>
						<td tabIndex="1" class="left-text"> </td>
						<td tabIndex="1" ></td>
						<td tabIndex="1"   ></td>
						<td tabIndex="1"  class="right-text"></td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text"> </td>
						@endif

									
						@if($showdetails==1)
							@if($show_foreigncurrency==1)
							<td colspan="12"></td>
							@else
							<td colspan="6"></td>
							@endif 
						@endif
					</tr>
					@endif

					<tr>
						<td tabIndex="1" class="left-text">
						<a class="link_open_child_accounts" href="javascript:void(0);"  tabindex="1" data-accountid="{{$result['account_id']}}">@if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['account_name']}} @if(in_array($selected_account_id,array(2,1)))</strong>@endif</a></td>
						<td tabIndex="1" >
						<a class='link_open_general_sub_ledger' href="javascript:void(0);"  data-accounttype="{{$result['account_type']}}"  data-accountid="{{$result['account_id']}}">
						@if(in_array($selected_account_id,array(2,1)))<strong>@endif	{{  $result['account_type']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</a></td>
						<td tabIndex="1"   >@if(in_array($selected_account_id,array(2,1)))<strong>@endif{{  $result['parent_name']}}@if(in_array($selected_account_id,array(2,1)))</strong>@endif</td>
						<td tabIndex="1"  class="right-text">
						@if(in_array($selected_account_id,array(2,1)))<strong>@endif	
						{{  $result['amount']}}
						@if(in_array($selected_account_id,array(2,1)))</strong>@endif
					</td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text">
						@if(in_array($selected_account_id,array(2,1)))<strong>@endif		
						{{$result['fc_amount']}}
						@if(in_array($selected_account_id,array(2,1)))</strong>@endif	
					</td>
						@endif


						@if($showdetails==1)

						<td>{{$result['opening_debitbalance']}}</td>
						
						<td> {{$result['opening_creditbalance']}}</td>
						
						<td> {{$result['total_debit']}} </td>
						
						<td> {{$result['total_credit']}} </td>
						
						<td> {{$result['closing_debit_balance']}} </td>
						
						<td>{{$result['closing_credit_balance']}}  </td>
						@if($show_foreigncurrency==1)
						
						<td> {{$result['fcamt_opening_debitbalance']}} </td>
						
						<td>{{$result['fcamt_opening_creditbalance']}} </td>
						
						<td> {{$result['fcamt_total_debit']}} </td>
						
						<td> {{$result['fcamt_total_credit']}} </td>
						
						<td>{{$result['fcamt_closing_debit_balance']}}  </td>
						
						<td> {{$result['fcamt_closing_credit_balance']}}  </td>
						@endif
				 
						@endif

					</tr>
  
						
					@endforeach
					 
                    </tbody>
                    <tfoot>
					@if(count( $selected_accounts_data_vertical)>0 )


					<tr> <td  class='right-text'  tabIndex="1"   colspan='3'    ><strong>Total Assets:</strong></th><td tabIndex="1"  class="right-text"><strong>{{round((  $total_assets ) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td tabIndex="1"  class="right-text"><strong>{{round( (  $total_fcamt_assets) ,2)}}</strong></td>
					   @endif

					   @if($showdetails==1)

							<td>{{$total_opening_debitbalance}}</td>

							<td> {{$total_opening_creditbalance}}</td>

							<td> {{$total_total_debit}} </td>

							<td> {{$total_total_credit}} </td>

							<td> {{$total_closing_debit_balance}} </td>

							<td>{{$total_closing_credit_balance}}  </td>
							@if($show_foreigncurrency==1)

							<td> {{$total_fcamt_opening_debitbalance}} </td>

							<td>{{$total_fcamt_opening_creditbalance}} </td>

							<td> {{$total_fcamt_total_debit}} </td>

							<td> {{$total_fcamt_total_credit}} </td>

							<td>{{$total_fcamt_closing_debit_balance}}  </td>

							<td> {{$total_fcamt_closing_credit_balance}}  </td>
							@endif

							@endif  
					</tr> 

						 
                       <tr> <td  class='right-text'  tabIndex="1"   colspan='3'><strong>Total Liabilities:</strong></th><td tabIndex="1"  class="right-text"><strong>{{round((  $total_liabilities ) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td  tabIndex="1"   class="right-text"> <strong>{{round( (  $total_fcamt_liabilities ) ,2)}} </strong></td>
					   @endif 

					   @if($showdetails==1)

								@if($show_foreigncurrency==1)
								
								<td colspan="12"></td> 
								@else
								<td colspan="6"></td> 
								@endif

					   @endif
  
					</tr>
				
					<tr> <td   colspan='3'    tabIndex="1"  class='right-text'   ><strong>Difference:</strong></th><td tabIndex="1"  class="right-text"><strong>{{round((  $diff_liabilities_assets) ,2)}}</strong></td>
					   @if($show_foreigncurrency==1)
					   <td tabIndex="1"   class="right-text"><strong>{{round( ( $diff_fc_liabilities_assets) ,2)}}</strong></td>
					   @endif
					   
					@if($showdetails==1)
							<td colspan='2'>{{ $total_opening_debitcredit_diff }}</td> 
							<td colspan='2'>{{$total_total_debit_credit_diff}}</td> 
							<td colspan='2'>{{$total_closing_debit_credit_balance_diff}}</td> 

							@if($show_foreigncurrency==1)
							<td colspan='2'>{{$fcamt_total_opening_debitcredit_diff}}</td> 
							<td colspan='2'>{{$fcamt_total_total_debit_credit_diff }}</td> 
							<td colspan='2'>{{$fcamt_total_closing_debit_credit_balance_diff}}</td> 
							@endif
						@endif
					</tr> 
					@endif
                    </tfoot>
                </table>
				</div>
				<div>
					@if(count( $selected_accounts_data_vertical)>0 )
					{{ $selected_accounts_data_vertical->links()}}
					@endif
				</div>	

		</div >
		</div >


            </div>

            <div class="tab-pane fade show  @if($report_type=='horizontal') active @endif   small" id="horizontal-report" role="tabpanel" aria-labelledby="horizontal-report-tab">
                 <div class="row">
					 <div class="col-md-12 mx-auto"> 
					 <input type="button" class="btn btn-primary" value="PDF" onclick="downloadDocument('pdf','horizontal')"/>
		  &nbsp;	  &nbsp;
		  <input type="button" class="btn btn-primary" value="EXCEL"  onclick="downloadDocument('xlsx','horizontal')" />
		  &nbsp;	  &nbsp;
		  <input type="button" class="btn btn-primary" value="CSV"  onclick="downloadDocument('csv','horizontal')"  />

		  <div class="card" >
		  <div class="card-body" >

               <table class="table  table-bordered"  >
                    <thead><th >Expenses</th>   
                            <th  >Income</th> 
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <table class="table  table-bordered  taboncell" >
                                
                                    <tbody>
										<tr><td><strong>Account</strong></td><td><strong>Account Type</strong></td>  <td><strong>Parent</strong></td><td><strong>Amount</strong></td>
								@if($show_foreigncurrency==1)
									<td><strong>Fc Amount</strong></td>
								@endif </tr>


								@foreach ($liabilities_accounts_horizontal as $liabilities_account )


								@php
								$result= $reportservice->getBalanceSheetAccountDetail($liabilities_account);
								@endphp

								<tr>
									<td  tabIndex="1" class="left-text">
									<a class="link_open_child_accounts" href="javascript:void(0);"  tabindex="1" data-accountid="{{$result['account_id']}}">	@if(in_array($liabilities_account,array(2,1)))<strong>@endif {{  $result['account_name']}} 	@if(in_array($liabilities_account,array(2,1)))</strong>@endif	</a>
									</td>
									<td  tabIndex="1"  >
									<a class='link_open_general_sub_ledger' href="javascript:void(0);"  data-accounttype="{{$result['account_type']}}"  data-accountid="{{$result['account_id']}}">
									@if(in_array($liabilities_account,array(2,1)))<strong>@endif		{{  $result['account_type']}}	@if(in_array($liabilities_account,array(2,1)))</strong>@endif	
											</a>
									</td>
									<td  tabIndex="1"  >	@if(in_array($liabilities_account,array(2,1)))<strong>@endif	{{  $result['parent_name']}} 	@if(in_array($liabilities_account,array(2,1)))</strong>@endif	</td>
									<td   tabIndex="1"  class="right-text">	@if(in_array($liabilities_account,array(2,1)))<strong>@endif	{{  $result['amount']}}	@if(in_array($liabilities_account,array(2,1)))</strong>@endif	</td>
									@if($show_foreigncurrency==1)
									<td  tabIndex="1"   class="right-text">	@if(in_array($liabilities_account,array(2,1)))<strong>@endif	{{$result['fc_amount']}}	@if(in_array($liabilities_account,array(2,1)))</strong>@endif	</td>
									@endif
								</tr>
									
								@endforeach 

								<tr>
						<td tabIndex="1" class="left-text"> </td>
						<td tabIndex="1" ></td>
						<td tabIndex="1"   ><strong>Profit / Loss<strong></td>
						<td tabIndex="1"  class="right-text"> <strong>{{$total_profit_loss}}</strong></td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text"><strong>{{$total_fc_profit_loss}}</strong></td>
						@endif
					</tr>

					
					<tr>
						<td tabIndex="1" class="left-text"> </td>
						<td tabIndex="1" ></td>
						<td tabIndex="1"   ><strong>Total Liabilities</strong></td>
						<td tabIndex="1"  class="right-text"> <strong>{{$total_liabilities}}</strong></td>
						@if($show_foreigncurrency==1)
						<td  tabIndex="1"  class="right-text"><strong>{{$total_fcamt_liabilities}}</strong></td>
						@endif
					</tr> 


                                    </tbody>

									@if(count($liabilities_accounts_horizontal)>0)
									<tfoot>
										
								<tr><td    @if($show_foreigncurrency==1) colspan="5" @else colspan="4"  @endif >
                                     	{{$liabilities_accounts_horizontal->links()}}
									</td></tr>

									</tfoot>
									@endif
                         

                                </table>

                            </td>
                            <td>
                                    <table  class="table  table-bordered taboncell">
 
                                    <tbody>
									<tr><td><strong>Account</strong></td><td><strong>Account Type</strong></td>  <td><strong>Parent</strong></td><td><strong>Amount</strong></td>
								@if($show_foreigncurrency==1)
									<td><strong>Fc Amount</strong></td>
								@endif </tr>


								
								@foreach ($assets_accounts_horizontal as $assets_account )


								@php
								$result= $reportservice->getBalanceSheetAccountDetail($assets_account );
								@endphp

								<tr>
									<td  tabIndex="1"  class="left-text">
											<a class="link_open_child_accounts" href="javascript:void(0);"  tabindex="1" data-accountid="{{$result['account_id']}}"> 
											@if(in_array($assets_account,array(2,1)))<strong>@endif		{{  $result['account_name']}}	@if(in_array($assets_account,array(2,1)))</strong>@endif	
										</a>
									</td>
									<td  tabIndex="1" >	<a class='link_open_general_sub_ledger' href="javascript:void(0);"  data-accounttype="{{$result['account_type']}}"  data-accountid="{{$result['account_id']}}">
									@if(in_array($assets_account,array(2,1)))<strong>@endif	 {{  $result['account_type']}}	@if(in_array($assets_account,array(2,1)))</strong>@endif	</a></td>
									<td  tabIndex="1"   >	@if(in_array($assets_account,array(2,1)))<strong>@endif	{{  $result['parent_name']}}	@if(in_array($assets_account,array(2,1)))</strong>@endif	</td>
									<td  tabIndex="1"   class="right-text">	@if(in_array($assets_account,array(2,1)))<strong>@endif	{{  $result['amount']}}	@if(in_array($assets_account,array(2,1)))</strong>@endif	</td>
									@if($show_foreigncurrency==1)
									<td   tabIndex="1"  class="right-text">	@if(in_array($assets_account,array(2,1)))<strong>@endif	{{$result['fc_amount']}}	@if(in_array($assets_account,array(2,1)))</strong>@endif	</td>
									@endif
								</tr>
									
								@endforeach 
 
                                    </tbody>  

									@if(count($assets_accounts_horizontal )>0)
									
									<tfoot>
										
										<tr><td   @if($show_foreigncurrency==1) colspan="5" @else colspan="4"  @endif >
												 {{$assets_accounts_horizontal->links()}}
											</td></tr>
		
								     </tfoot>
									 @endif


                                    </table> 
                            </td>
                        </tr>
                    </tbody>
                    <tfoot> 
 
					
					<tr> <td  tabIndex="1" class="right-text"  ><strong>Total Liabilities :  {{round($total_liabilities,2)}}
								 


					</strong></td><td class="left-text"  tabIndex="1" ><strong>Total Assets: 	  {{round($total_assets,2)}}
									 
									</strong></td></tr>
									   			
					<tr> 
						<td  tabIndex="1" class="right-text"  ><strong>Difference</strong></td>
					<td  tabIndex="1" class="left-text"  ><strong> {{round(($total_liabilities-$total_assets),2)}} </strong></td>
				</tr>			
				 
					   			
					<tr> 
						<td  tabIndex="1" class="right-text"  ><strong>Total Fc Liabilities :{{round($total_fcamt_liabilities,2)}} </strong></td>
					<td  tabIndex="1" class="left-text"  ><strong>Total Fc Assets:{{round($total_fcamt_assets,2)}} </strong></td>
				</tr>

				<tr> 
						<td  tabIndex="1" class="right-text"  ><strong>Difference</strong></td>
					<td  tabIndex="1" class="left-text"  ><strong> {{round(($total_fcamt_liabilities-$total_fcamt_assets),2)}} </strong></td>
				</tr>			
				 


  
                    </tfoot>
                </table>
				</div >
				</div  >
				
						</div>
				</div>


            </div>
        </div>


	 
	</div>
</div> @endsection @section('js')
<script src="{{ asset('js/checkboxtree.min.js') }}"></script>
<script src="{{ asset('js/hummingbird-treeview.min.js') }}"></script>
<script src="{{ asset('js/taboneachcell.js') }}"></script>

<script type='text/javascript'>
$(document).ready(function() {
	$("#menu_level_tree").hummingbird();
	$('#start_date').datetimepicker({
		format: 'd-m-Y',
		timepicker: false,
		datepicker: true,
		dayOfWeekStart: 1,
		yearStart: 2016,
	});
	$('#end_date').datetimepicker({
		format: 'd-m-Y',
		timepicker: false,
		datepicker: true,
		dayOfWeekStart: 1,
		yearStart: 2016,
	});
});



$("#menu_level_tree").on('keypress', '.fa-plus , .fa-minus', function(event) {

	$(this).trigger('click');
});
  


$("#menu_level_tree").on('click', '.fa-plus , .fa-minus', function(event) {
	var id = $(this).data('id');
	if($(`#menu_level_tree li[data-id='${id}']`).data('hasdata') == 'yes') {
		return false;
	}
	var level= $(this).data('level');
	level=level+1;
	// <input type='checkbox'   name='accounts[]'  value='${account['id']}'>&nbsp
	$.ajax({
		url: "{{url('/')}}/{{$companyname}}/get-child-accounts/" + id,
		type: "get",
		async: false,
		success: function(data) {
			var result = JSON.parse(JSON.stringify(data));
			var accounts = result['accounts'];
			var html = '<ul>';
			for(let account of accounts) {

				var ga_val=account['ga'].trim();

				html = html + `<li data-id='${account['id']}'> <i class='fa fa-plus'  tabIndex='1'  data-level='${level}'  data-id='${account['id']}' ></i> <a  data-id='${account['id']}'  data-level='${level}'   class='ga_link' href='javascript:void(0);' data-ga='${ga_val}'><label  class='tree_checkbox_lbl'>&nbsp;${account['account_name']} (Level ${level})</label></a></li>`;
			}
			html = html + '</ul>';
			$(`#menu_level_tree li[data-id='${id}']`).append(html);
			$(`#menu_level_tree li[data-id='${id}']`).data('hasdata', 'yes');
			$("#menu_level_tree").hummingbird();
		},
		error: function() {}
	});
});

 
$("#menu_level_tree").on('click', '.ga_link', function(event) {
		var ga=$(this).data('ga');
		var dataid=$(this).data('id');
		var level=$(this).data('level');

		var reportname="{{$report_name}}";

		if(ga=='G'){

			
				if(reportname=="Trial Balances"){
					
		        	var url="{{url('/')}}/{{$companyname}}/trial-balances-of-g-type/"+dataid+'/'+level;
				}
				else{

					var url="{{url('/')}}/{{$companyname}}/treestyle-trial-balances-of-g-type/"+dataid+'/'+level;
				}


			window.open(url, "_blank"); 
		}
		else{
				 

			var url="{{url('/')}}/{{$companyname}}/treestyle-trial-balances-of-a-type/"+dataid;
			
			$.ajax({ type: "POST",
							async: false,
							url: "{{url('/')}}/{{$companyname}}/set-report-free-style-search-values-in-session",
							data:{  'start_date':$("#start_date").val().trim() ,'end_date':$("#end_date").val().trim() ,'cost_center':$("#ddn_cost_center").val().trim() ,'department':$("#ddn_department").val().trim() },
							success:function(data,status){  
 
		                     	window.open(url, "_blank"); 
							   
							} 
						});


		}
 
});


$("#btncancel").click(function() {
 
	var url="{{url('/')}}/{{$companyname}}/cancel-cache-report-input-by-name/balance-sheet-report";

	$.get(url,function(data,status){

		var result=JSON.parse(JSON.stringify(data));

		if(result['status']=='success'){ 
			
			window.location.href = "{{url('/')}}/{{$companyname}}/tree-style-trial-balances-p-and-l";
			
		
		}

		});


});


$("#btn_search_account").click(function(){

	var searchtxt=$("#search_txt").val();

	$.get("{{url('/')}}/{{$companyname}}/search-account-in-tree/"+searchtxt,function(data,status){

		var result=JSON.parse(JSON.stringify(data));

		var locations=result['locations'];

		if(result['status']==true && locations.length>0){

			for(let location of locations){
				
		     	SnackbarMsg({'status':'success','message':location});

			} 
		}
		else{
			SnackbarMsg({'status':'failure','message':"No location found by this name"});
		}

	

  
	});

});



$("#btncancel").click(function() { 

var url="{{url('/')}}/{{$companyname}}/cancel-cache-report-input-by-name/balance-report";
 
		$.get(url,function(data,status){

				var result=JSON.parse(JSON.stringify(data));

				if(result['status']=='success'){  

				window.location.href = "{{url('/')}}/{{$companyname}}/balance-sheet";
				
				}

		});


});

$("#vertical-report,#horizontal-report").on("click",".link_open_child_accounts",function(){
	var accountid=$(this).data("accountid"); 
 
 var url="{{url('/')}}/{{$companyname}}/treestyle-trial-balances-of-g-type/"+accountid+'/1';
 var fromreportname="_balance_report";
 
 $.post("{{url('/')}}/{{$companyname}}/set-treestyle-trial-balance-drilldown-settings",{'from_report':fromreportname},function(data,status){
 
	 var result=JSON.parse(JSON.stringify(data)); 
	 if(result['status']=="success"){ 
		 window.open( url, "_blank");  
	 }
 });
});

   
 $("#vertical-report,#horizontal-report").on("click",".link_open_general_sub_ledger",function(){

var accounttype=$(this).data("accounttype");

var accountid=$(this).data("accountid");

var fromreportname="_balance_report";

$.post("{{url('/')}}/{{$companyname}}/set-general-subledger-cache-inputs",{'fromreportname': fromreportname,'accounttype':accounttype,'accountid':accountid},function(data,status){

	var result=JSON.parse(JSON.stringify(data));

	if(result['status']=="success"){ 
		if(accounttype=='G'){
			
			window.open("{{route('company.general_ledger_new',['company_name'=>$companyname])}}", "_blank"); 
		}
		else{

			window.open("{{route('company.subledger_new',['company_name'=>$companyname])}}", "_blank"); 
		}
	}
});

});


function downloadDocument(format,report_type){



var url="{{url('/')}}/{{$companyname}}/download-balance-sheet-report/"+report_type+'/'+format;

window.open(url);

}



</script> @endsection