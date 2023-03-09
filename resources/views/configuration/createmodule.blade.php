@extends('layout.layout')

 @section('content')
<style>
	.nav-link 
	{
		color:#495057;
		
	}
	/* .nav-item:hover .nav-link
	{
	
		color:#556ee6;		
	} */
	.nav-link:focus, .nav-link:hover
	{
		color: #4458b8;
	}
	.nav-link:	 
	{
		color:red;
	}
	.nav-item .nav-link.active {
    color: #556ee6;
}

</style>

<h2 class="menu-title mb-5 " >&nbsp&nbsp&nbspCreate Module</h2>

<div class="pagecontent" style="margin-top:-28px;">
	<div class="container-fluid mtb-1">
		<ul class="nav nav-tabs" id="menutablist" role="tablist">
			<li class="nav-item" role="createmodule" id="tablayout"> <a class="nav-link active" id="create-module-tab" data-bs-toggle="tab" href="#create-module" role="tab" aria-controls="createmodule" aria-selected="true" style="font-weight: 500;">Create Module</a> </li>
			<li class="nav-item" role="addtransaction" id="tablayout"> <a class="nav-link" id="add-transaction-tab" data-bs-toggle="tab" href="#add-transaction" role="tab" aria-controls="addtransaction" aria-selected="true" style="font-weight: 500;">Add Transaction</a> </li>
			<li class="nav-item" role="txnsequence" id="tablayout"> <a class="nav-link" id="txn-sequence-tab" data-bs-toggle="tab" href="#txn-sequence" role="tab" aria-controls="txnsequence" aria-selected="true" style="font-weight: 500;">Txn Sequence</a> </li>
			<li class="nav-item" role="createtxnshortcut" id="tablayout"> <a class="nav-link" id="create-txn-shortcut-tab" data-bs-toggle="tab" href="#create-txn-shortcut" role="tab" aria-controls="createtxnshortcut" aria-selected="true" style="font-weight: 500;">Create Txn Shortcut</a> </li>
			<li class="nav-item" role="reportshortcut" id="tablayout"> <a class="nav-link" id="report-shortcut-tab" data-bs-toggle="tab" href="#report-shortcut" role="tab" aria-controls="reportshortcut" aria-selected="true" style="font-weight: 500;">Report Shortcut</a> </li>
			<li class="nav-item" role="acshortcut" id="tablayout"> <a class="nav-link" id="ac-shortcut-tab" data-bs-toggle="tab" href="#ac-shortcut" role="tab" aria-controls="acshortcut" aria-selected="true" style="font-weight: 500;">AC Shortcut</a> </li>
			<li class="nav-item" role="acreportshortcut" id="tablayout"> <a class="nav-link" id="ac-report-shortcut-tab" data-bs-toggle="tab" href="#ac-report-shortcut" role="tab" aria-controls="acreportshortcut" aria-selected="true" style="font-weight: 500;">AC Report Shortcut</a> </li>
			<li class="nav-item" role="createreportmodule" id="tablayout"> <a class="nav-link" id="create-report-module-tab" data-bs-toggle="tab" href="#create-report-module" role="tab" aria-controls="createreportmodule" aria-selected="true" style="font-weight: 500;">Create Report Module</a> </li>
			<li class="nav-item" role="addreportmodule" id="tablayout"> <a class="nav-link" id="add-report-tab" data-bs-toggle="tab" href="#add-report" role="tab" aria-controls="addreport" aria-selected="true" style="font-weight: 500;">Add Report</a> </li>
			<li class="nav-item" role="reportsequence" id="tablayout"> <a class="nav-link" id="report-sequence-tab" data-bs-toggle="tab" href="#report-sequence" role="tab" aria-controls="reportsequence" aria-selected="true" style="font-weight: 500;">Report Sequence</a> </li>
			<li class="nav-item" role="companynews" id="tablayout"> <a class="nav-link" id="company-news-tab" data-bs-toggle="tab" href="#company-news" role="tab" aria-controls="companynews" aria-selected="true" style="font-weight: 500;">Company News</a> </li>
			<li class="nav-item" role="emailconfigure" id="tablayout"> <a class="nav-link" id="email-configure-tab" data-bs-toggle="tab" href="#email-configure" role="tab" aria-controls="emailconfigure" aria-selected="true" style="font-weight: 500;">Email Configure</a> </li>
			<li class="nav-item" role="menusequence" id="tablayout"> <a class="nav-link" id="menu-sequence-tab" data-bs-toggle="tab" href="#menu-sequence" role="tab" aria-controls="menusequence" aria-selected="true" style="font-weight: 500;">Menu Sequence</a> </li>
			<li class="nav-item" role="transactionfields" id="tablayout"> <a class="nav-link" id="transaction-fields-tab" data-bs-toggle="tab" href="#transaction-fields" role="tab" aria-controls="transactionfields" aria-selected="true" style="font-weight: 500;">Transaction Fields</a> </li>
			<li class="nav-item" role="transactionfieldsequence" id="tablayout"> <a class="nav-link" id="transaction-fields-sequence-tab" data-bs-toggle="tab" href="#transaction-fields-sequence" role="tab" aria-controls="transactionfieldssequence" aria-selected="true" style="font-weight: 500;">Fields Sequence</a> </li>
		</ul>
		<div class="tab-content mt-5" style='height:500px;'>
			<div class="tab-pane fade show active small" id="create-module" role="tabpanel" aria-labelledby="create-module-tab">
				<div class='row mtb-2 mlr-2' style="margin-left:300px;margin-top:-20px">
					<div class="col-2">
						<label class="lbl_control">Enter Module Name:</label>
					</div>
					<div class="col-2">
						<input type="text" id="txtmodulename" class="form-control" /> </div>
					<div class="col-2">
						<label class="lbl_control">Enter Sequence:</label>
					</div>
					<div class="col-2">
						<input type="number" id="txtsequence" min='1' class="form-control" /> </div>
					<div class="col-2">
						<input type='button' class='btn btn-primary' value='Submit' id="btn_add_module" /> </div>
				</div>
				<div class="row">
				
					<div class="col-6  mtb-2 mlr-2" style="margin-left:400px">
						
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
								<center>
									<table data-order='[[ 0, "desc" ]]' id="table_modules" style="width:100%" class="table table-striped">
										<thead>
											<th>Id</th>
											<th>Module Name</th>
											<th>Sequence</th>
										</thead>
									</table>

									</center>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace" id="add-transaction" role="tabpanel" aria-labelledby="add-transaction-tab">
			
				<form method='post' id='frmmoduletransactions'>
					<div class="row div-controls mtb-1">
						<div class="col-6 text-end">
							<label class="lbl_control">Select Module:</label>
						</div>
						<div class="col-4 text-start">
							<select class="form-select form-select-sm select-configure" name="module" id="ddn_select_transaction_modules">
								<option value="">Select Module</option> @foreach ($modules as $module)
								<option value="{{$module->id}}">{{$module->mname}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="company_name" value="{{Session::get('company_name')}}" />
						<input type="hidden" id="module_transaction_selected" name="module_transaction_selected" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Transactions</p>
										<select size="4" name="unselected_transactions[]" multiple id="module_ddn_unselected_transactions" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_module_transactions_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_module_transactions_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Transactions</p>
										<select size="4" name="selected_transactions[]" multiple id="module_ddn_selected_transactions" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3">
										<input type="button" name="btnsubmit" value="Save" id="btn_save_module_transaction" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_cancel_module_transaction" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
				</form>
				</div>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace"  id="txn-sequence" role="tabpanel" aria-labelledby="txn-sequence-tab">
				<div class="row" >
					<div class="form-group col-5 mtb-2 mx-auto " style="margin-top:-1px">
						<label class="lbl_control_inline"> Module :</label>
						<select class="form-control inline_control select-configure" name="txnsequencemodule" id="ddntxnsequencemodule">
							<option value="">Select Module</option> @foreach ($modules as $module )
							<option value="{{$module->id}}">{{$module->mname}}</option> @endforeach </select>
					</div>
				</div>
				<div class="row mtb-3">
					<div class="col-6 mx-auto">
						<label class="lbl_control_inline" style="width:400px"> Drag And Drop To Change Below Item Sequence</label>
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table data-order='[[ 0, "desc" ]]' id="table_module_txns" class="table table-striped ">
										<thead>
											<th>Id</th>
											<th>Txn Name</th>
											<th>Sequence</th>
										</thead>
										<tbody id='tbody_table_module_txns'> </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace" id="create-txn-shortcut" role="tabpanel" aria-labelledby="create-txn-shortcut-tab">
				<form method='post' id='frmtxnshortcut'>
					<div class="row div-controls mtb-1">
						<div class="col-6 text-end">
							<label class="lbl_control">Select Roles:</label>
						</div>
						<div class="col-4 text-start">
							<select class="form-select form-select-sm select-configure" name="role" id="ddn_select_role_txhshortcut">
								<option value=''>Select Role</option> @foreach ($roles as $role)
								<option value="{{$role->id}}">{{$role->role_name}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="company_name" value="{{Session::get('company_name')}}" />
						<input type="hidden" id="role_txns_selected" name="role_txns_selected" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Transactions</p>
										<select size="4" name="unselected_transactions[]" multiple id="txnshortcut_unselected_transactions" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_txnshortcut_transactions_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_txnshortcut_transactions_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Transactions</p>
										<select size="4" name="selected_transactions[]" multiple id="txnshortcut_selected_transactions" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3">
										<input type="button" name="btnsubmit" value="Save" id="btn_txnshortcut_save_transaction" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_txnshortcut_cancel_transaction" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace" id="report-shortcut" role="tabpanel" aria-labelledby="report-shortcut-tab">
				<form method='post' id='frmreportshortcut'>
					<div class="row div-controls mtb-1">
						<div class="col-6 text-end">
							<label class="lbl_control">Select Roles:</label>
						</div>
						<div class="col-4 text-start">
							<select class="form-select form-select-sm select-configure" name="role" id="ddn_select_role_reportshortcut">
								<option value=''>Select Role</option> @foreach ($roles as $role)
								<option value="{{$role->id}}">{{$role->role_name}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="role_reportshortcut_selected" name="role_reportshortcut_selected" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Reports</p>
										<select size="4" name="unselected_reports[]" multiple id="reportshortcut_unselected_reports" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_reportshortcut_reports_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_reportshortcut_reports_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Reports</p>
										<select size="4" name="selected_reports[]" multiple id="reportshortcut_selected_reports" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3">
										<input type="button" name="btnsubmit" value="Save" id="btn_reportshortcut_save_report" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_reportshortcut_cancel_report" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace" id="ac-shortcut" role="tabpanel" aria-labelledby="ac-shortcut-tab">
				<form method='post' id='frmacshortcut'>
					<div class="row div-controls mtb-1">
						<div class="col-6 text-end">
							<label class="lbl_control">Select Roles:</label>
						</div>
						<div class="col-4 text-start">
							<select class="form-select form-select-sm select-configure" name="role" id="ddn_select_role_acshortcut">
								<option value=''>Select Role</option> @foreach ($roles as $role)
								<option value="{{$role->id}}">{{$role->role_name}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="role_acshortcut_selected" name="role_acshortcut_selected" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Vouchers</p>
										<select size="4" name="unselected_vouchers[]" multiple id="acshortcut_unselected_vouchers" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_acshortcut_vouchers_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_acshortcut_vouchers_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Vouchers</p>
										<select size="4" name="selected_vouchers[]" multiple id="acshortcut_selected_vouchers" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3" style='text-align:center;'>
										<input type="button" name="btnsubmit" value="Save" id="btn_acshortcut_save_vouchers" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_acshortcut_cancel_vouchers" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace" id="ac-report-shortcut" role="tabpanel" aria-labelledby="ac-report-shortcut-tab">
				<form method='post' id='frmacreportshortcut'>
					<div class="row div-controls mtb-1">
						<div class="col-6 text-end">
							<label class="lbl_control">Select Roles:</label>
						</div>
						<div class="col-4 text-start">
							<select class="form-select form-select-sm select-configure" name="role" id="ddn_select_role_acreportshortcut">
								<option value=''>Select Role</option> @foreach ($roles as $role)
								<option value="{{$role->id}}">{{$role->role_name}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="role_acreportshortcut_selected" name="role_acreportshortcut_selected" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Menus</p>
										<select size="4" name="unselected_menus[]" multiple id="acreportshortcut_unselected_menus" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_acreportshortcut_menus_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_acreportshortcut_menus_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Menus</p>
										<select size="4" name="selected_menus[]" multiple id="acreportshortcut_selected_menus" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3" style='text-align:center;'>
										<input type="button" name="btnsubmit" value="Save" id="btn_acreportshortcut_save_menus" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_acreportshortcut_cancel_menus" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="tab-pane fade show  small" id="create-report-module" role="tabpanel" aria-labelledby="create-report-module-tab" style="margin-left:200px">
				<div class='row mtb-2 mlr-2' style="margin-top:-20px">
					<div class="col-3">
						<label class="lbl_control">Enter Report Module Name:</label>
					</div>
					<div class="col-2">
						<input type="text" id="txtreportmodulename" class="form-control" /> </div>
					<div class="col-2">
						<label class="lbl_control">Enter Sequence:</label>
					</div>
					<div class="col-2">
						<input type="number" id="txtreportmodulesequence" min='1' class="form-control" /> </div>
					<div class="col-2">
						<input type='button' class='btn btn-primary' value='Submit' id="btn_add_report_module" /> </div>
				</div>
				<div class="row">
					<div class="col-6  mtb-2 mlr-2" style="margin-left:200px">
						<label class="lbl_control_inline" style="width:400px"> Drag And Drop To Change Below Item Sequence</label>
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table id="table_report_modules" style="width:100%" class="table table-striped ">
										<thead>
											<th>Id</th>
											<th>Report Module Name</th>
											<th>Sequence</th>
										</thead>
										<tbody id='tbodyreportmodules'> </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace " id="add-report" role="tabpanel" aria-labelledby="add-report-tab">
				<form method='post' id='frmaddreport'>
					<div class="row div-controls mtb-1">
						<div class="col-6 text-end">
							<label class="lbl_control">Select Report Module:</label>
						</div>
						<div class="col-4 text-start">
							<select class="form-select form-select-sm select-configure" name="reportmodule" id="ddn_module_select_report_module">
								<option value=''>Select Report Module</option> @foreach ($reportmodules as $reportmodule)
								<option value="{{$reportmodule->id}}">{{$reportmodule->rmname}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="module_addreport_report_selected" name="module_addreport_report_selected" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Report</p>
										<select size="4" name="unselected_reports[]" multiple id="addreport_unselected_reports" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_addreport_reports_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_addreport_reports_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Reports</p>
										<select size="4" name="selected_reports[]" multiple id="addreport_selected_reports" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3" style='text-align:center;'>
										<input type="button" name="btnsubmit" value="Save" id="btn_addreport_save_reports" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_addreport_cancel_reports" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace" id="report-sequence" role="tabpanel" aria-labelledby="report-sequence-tab">
				<div class="row" style="margin-top:-20px">
					<div class="form-group col-5 mtb-2 mx-auto">
						<label class="lbl_control_inline"> Report Module :</label>
						<select class="form-control inline_control select-configure" name="reportsequencemodule" id="ddnreportsequencemodule">
							<option value="">Select Report Module</option> @foreach ($reportmodules as $reportmodule)
							<option value="{{$reportmodule->id}}">{{$reportmodule->rmname}}</option> @endforeach </select>
					</div>
				</div>
				<div class="row mtb-3" style='height:500px;'>
					<div class="col-6 mx-auto">
						<label class="lbl_control_inline" style="width:400px"> Drag And Drop To Change Below Item Sequence</label>
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table data-order='[[ 0, "desc" ]]' id="table_reportmodule_rpts" style="width:100%" class="table table-striped  mtb-2">
										<thead>
											<th>Id</th>
											<th>Report Name</th>
											<th>Sequence</th>
										</thead>
										<tbody id='tbody_table_reportmodule_rpts'> </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade show  small createmodulebarSpace " id="company-news" role="tabpanel" aria-labelledby="company-news-tab">
				<div class='row mtb-2 mlr-2'>
					<div class="col-2  text-center">
						<label class="lbl_control">Enter News:</label>
					</div>
					<div class="col-2">
						<textarea class='form-control' id='txtcompanynews'></textarea>
					</div>
					<div class="col-2 text-center">
						<label class="lbl_control">Select Date:</label>
					</div>
					<div class="col-4">
						<input type='date' id='selectCompanyNewsDate' class='form-control select-configure' min="{{date('Y-m-d',strtotime('now'))}}" /> </div>
					<div class="col-2">
						<input type='submit' name='btnsubmit' class='btn btn-primary' id="btn_add_company_news" /> </div>
				</div>
				<div class="row mtb-3" style='height:500px;'>
					<div class="col-6 mx-auto">
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table data-order='[[ 0, "desc" ]]' id="table_company_news" class="table table-striped  mtb-2">
										<thead>
											<th>Id</th>
											<th>News</th>
											<th> Date</th>
											<th>Is Display </th>
										</thead>
										<tbody id='tbody_table_company_news'> </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade  small" id="email-configure" role="tabpanel" aria-labelledby="email-configure-tab">
				<form id='frmemailconfiguration' method='post'>
					<div class='row mtb-2 mlr-2'>
						<div class="col-4">
							<select class='form-control select-configure' required='true' id='emailconf_ddnselectuser'>
								<option value=''>Select User</option> @foreach ( $users as $user)
								<option value="{{$user}}">{{$user}}</option> @endforeach </select>
						</div>
						<div class="col-4">
							<input type='email' class='form-control select-configure' name='email' placeholder='Enter Email' id='emailconf_email' required/> </div>
						<div class="col-4">
							<input type='text' class='form-control select-configure' name='password' placeholder='Enter Password' id='emailconf_pwd' /> </div>
						<div class="col-4">
							<input type='text' class='form-control select-configure' name='smtp' placeholder='Enter SMTP HOST' id='emailconf_host' /> </div>
						<div class="col-4">
							<input type='number' class='form-control select-configure' name='port' placeholder='Enter PORT' id='emailconf_port' /> </div>
						<div class="col-4">
							<input type='button' value='Add New' name='btnsubmit' class='btn btn-primary' id="btn_add_email_conf" /> </div>
					</div>
				</form>
				<div class="row mtb-3" style='height:500px;'>
					<div class="col-8 mx-auto">
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table data-order='[[ 0, "desc" ]]' id="table_email_conf" style="width:100%" class="table table-striped mtb-2">
										<thead>
											<th>Id</th>
											<th>User</th>
											<th> Mail</th>
											<th>Password</th>
											<th>SMTP</th>
											<th>PORT</th>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade  small" id="menu-sequence" role="tabpanel" aria-labelledby="menu-sequence-tab">
				<div class="row mtb-3" style='height:500px;'>
					<div class="col-4 mx-auto">
						<label class="lbl_control_inline" style="width:400px"> Drag And Drop To Change Below Item Sequence</label>
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table id="table_menu_sequence" style="width:100%" class="table table-striped mtb-2">
										<thead>
											<th>Id</th>
											<th>Menu Name</th>
											<th>Sequence</th>
											<tbody id="tbody_menu_sequence"></tbody>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade  small createmodulebarSpace" id="transaction-fields" role="tabpanel" aria-labelledby="transaction-fields-tab">
				<form method='post' id='frmtransactionfields'>
					<div class="row div-controls mtb-1">
						<div class="col-1 text-end">
							<label class="lbl_control">Select Role:</label>
						</div>
						<div class="col-4">
							<select class="form-control  select-configure" name="role" id="transactionfields_ddn_roles">
								<option value=''>Select Role</option> @foreach ( $roles as $role)
								<option value="{{$role->id}}">{{$role->role_name}}</option> @endforeach </select>
						</div>
						<div class="col-2  text-end" style="margin-left:-100px">
							<label class="lbl_control">Select Transaction Table:</label>
						</div>
						<div class="col-4">
							<select class="form-select  select-configure select2" name="transaction" id="transactionfields_ddn_transaction">
								<option>Select Transaction</option>
								<option value=""></option> @foreach ($transactiontables as $transactiontablename=> $transactiontablelabel)
								<option value="{{$transactiontablename}}">{{ $transactiontablelabel}}</option> @endforeach </select>
						</div>
					</div>
					<div class='row'>
						<input type="hidden" id="transaction_fields_selected_hf" name="transaction_fields_selected_hf" value="" />
						<table style="width: 100%">
							<tbody>
								<tr>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Unselected Fields</p>
										<select size="4" name="unselected_fields[]" multiple id="transactionfields_unselected_fields" class='optionmultiselectwitharrow'></select>
									</td>
									<td class="div_arrows">
										<br>
										<input type="button" name="" value=">>" id="btn_transactionfields_fields_select" class="button">
										<br>
										<br>
										<br>
										<br>
										<input type="button" name="" value="<<" id="btn_transactionfields_fields_unselect" class="button"> </td>
									<td align="center" style="padding:20px 0px;">
										<p class="listheading">Selected Fields</p>
										<select size="4" name="selected_fields[]" multiple id="transactionfields_selected_fields" class='optionmultiselectwitharrow'></select>
										<br> </td>
								</tr>
								<tr>
									<td class="div_buttons" colspan="3" style='text-align:center;'>
										<input type="button" name="btnsubmit" value="Save" id="btn_transactionfields_save_fields" class="button btn-primary btn">
										<input type="button" name="btncancel" value="Cancel" id="btn_transactionfields_cancel_fields" class="button  btn-primary btn"> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="tab-pane fade  small createmodulebarSpace " id="transaction-fields-sequence" role="tabpanel" aria-labelledby="transaction-fields-sequence-tab">
				<div class="row  mtb-1">
					<div class="col-2 text-end">
						<label class="lbl_control">Select Role:</label>
					</div>
					<div class="col-4">
						<select class="form-control select-configure" name="role" id="transactionfields_sequence_ddn_roles">
							<option value=''>Select Role</option> @foreach ( $roles as $role)
							<option value="{{$role->id}}">{{$role->role_name}}</option> @endforeach </select>
					</div>
					<div class="col-2  text-end">
						<label class="lbl_control">Select Transaction Table:</label>
					</div>
					<div class="col-4">
						<select class="select-configure form-select" name="transaction" id="transactionfields_sequence_ddn_transaction">
							<option>Select Transaction</option>
							<option value=""></option> @foreach ($transactiontables as $transactiontablename=> $transactiontablelabel)
							<option value="{{$transactiontablename}}">{{ $transactiontablelabel}}</option> @endforeach </select>
					</div>
				</div>

				<div class="row  mtb-1">

				<div class="col-5 mx-auto">
						<label class="lbl_control"> Drag And Drop To Change Below Item Sequence</label>
						<div class="card">
							<div class="card-body">
								<div class=" mx-auto table-responsive">
									<table id="table_transaction_fields_sequence" style="width:100%" class="table table-striped mtb-2">
										<thead>
											<th>Id</th>
											<th>Field Name</th>
											<th>Sequence</th>
											<tbody id="tbody_transaction_fields_sequence"></tbody>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div> @endsection @section('js')
	<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
	<script type="text/javascript">
	var tblmodules, tblemailconfs;
	$(function() {
		tblmodules = $('#table_modules').DataTable({
			"paging": false,
			"bFilter": false,
			"bInfo": false,
			"ordering": false,
			"ajax": '/{{$companyname}}/get-All-Modules',
			"lengthChange": false,
			searching: false,
			"columns": [{
				data: 'id',
			}, {
				data: 'mname'
			}, {
				data: 'sequence'
			}]
		});
		$('#table_modules').on('draw.dt', function() {
			$('#table_modules').Tabledit({
				url: '/{{$companyname}}/update-module-name',
				dataType: 'json',
				columns: {
					identifier: [0, 'id'],
					editable: [
						[1, 'mname'],
						[2, 'sequence'],
					]
				},
				buttons: {
					edit: {
						class: "btn btn-default",
						html: '<span ><i class="fas fa-edit"></i></span>',
						action: 'edit'
					},
				},
				onSuccess: function(data, textStatus, jqXHR) {
					if(data.action == 'edit') {
						SnackBar({
							message: "Module Details Updated successfully",
							status: 'success'
						});
					}
				},
				onFail: function() {
					alert("error");
				},
			});
		});
		// tblmoduletxns = $('#table_module_txns').DataTable({
		// 	"paging": false,
		// 	"bFilter": false,
		// 	"bInfo": false,
		// 	"ordering": false,
		// 	"ajax": '/{{$companyname}}/get-Module-Transactions-For-Sequence/0',
		// 	"lengthChange": false,
		// 	searching: false,
		// 	"columns": [{
		// 		data: 'id',
		// 	}, {
		// 		data: 'txn'
		// 	}, {
		// 		data: 'sequence'
		// 	}]
		// });
		// $('#table_module_txns').on('draw.dt', function() {
		// 	$('#table_module_txns').Tabledit({
		// 		url: '/{{$companyname}}/update-module-txn-sequence',
		// 		dataType: 'json',
		// 		columns: {
		// 			identifier: [0, 'id'],
		// 			editable: [
		// 				[2, 'sequence'],
		// 			]
		// 		},
		// 		buttons: {
		// 			edit: {
		// 				class: "btn btn-default",
		// 				html: '<span ><i class="fas fa-edit"></i></span>',
		// 				action: 'edit'
		// 			},
		// 		},
		// 		onSuccess: function(data, textStatus, jqXHR) {
		// 			if(data.action == 'edit') {
		// 				SnackBar({
		// 					message: "Module Txn Sequence Updated successfully",
		// 					status: 'success'
		// 				});
		// 			}
		// 		},
		// 		onFail: function() {
		// 			alert("error");
		// 		},
		// 	});
		// });
		$("#ddntxnsequencemodule").change(function() {
			var moduleid = $(this).val();
			// tblmoduletxns.ajax.url('/{{$companyname}}/get-Module-Transactions-For-Sequence/' + moduleid).load();
			loadModuleTxnSequence(moduleid);
		});
		tblemailconfs = $('#table_email_conf').DataTable({
			"paging": false,
			"bFilter": false,
			"bInfo": false,
			"ordering": false,
			"ajax": '/{{$companyname}}/get-Module-All-Email-Confs',
			"lengthChange": false,
			searching: false,
			"columns": [{
				data: 'id',
			}, {
				data: 'get_user'
			}, {
				data: 'mailid'
			}, {
				data: 'pwd'
			}, {
				data: 'smtp'
			}, {
				data: 'port'
			}]
		});
		$('#table_email_conf').on('draw.dt', function() {
			$('#table_email_conf').Tabledit({
				url: '/{{$companyname}}/update-module-email-conf',
				dataType: 'json',
				columns: {
					identifier: [0, 'id'],
					editable: [
						[2, 'mail'],
						[3, 'password'],
						[4, 'smtp'],
						[5, 'port'],
					]
				},
				buttons: {
					edit: {
						class: "btn btn-default",
						html: '<span ><i class="fas fa-edit"></i></span>',
						action: 'edit'
					},
					delete: {
						class: "btn btn-default",
						html: '<span ><i class="fa fa-trash" aria-hidden="true"></i></span>',
						action: 'delete'
					}
				},
				onSuccess: function(data, textStatus, jqXHR) {
					if(data.action == 'edit') {
						SnackBar({
							message: "Email Configuration Updated successfully",
							status: 'success'
						});
					} else if(data.action == 'delete') {
						SnackBar({
							message: "Email Configuration Deleted Successfully",
							status: 'success'
						});
						$(`#table_email_conf tbody tr[id='${data.id}']`).remove();
					}
				},
				onFail: function() {
					alert("Something went wrong");
				},
			});
		});
		loadReportModules();
		loadModuleCompanyNews();
		// loadEmailConfTableEdit();
		loadModuleMenuSequence();
	});

        function changeModuleTxnSequence(moduleid) {
            var tablerows = $("#tbody_table_module_txns tr");
            var ids = [];
            tablerows.each(function() {
                ids.push($(this).attr('id'));
            });
            $.post('/{{ $companyname }}/update-module-txn-sequence', {
                'ids': ids
            }, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
				SnackbarMsg(data);
                if (result['status'] == 'success') {
                    loadModuleTxnSequence(moduleid);
                }
            });
        }
        $("#ddn_select_transaction_modules").change(function() {
            var moduleid = $(this).val();
            loadModuleTxns(moduleid);
        });
        $("#btn_module_transactions_select").click(function() {
            var selected = $("#module_ddn_unselected_transactions :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#module_ddn_selected_transactions").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_module_transactions_unselect").click(function() {
            var selected = $("#module_ddn_selected_transactions :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#module_ddn_unselected_transactions").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_cancel_module_transaction").click(function() {
            var moduleid = $("#ddn_select_transaction_modules").val();
            loadModuleTxns(moduleid);
        });
        $("#btn_save_module_transaction").click(function() {
            var companyname = $("#company_name").val();
            var url = '/' + companyname + '/submit-module-transactions';
            var trans = [];
            var selectedtrans = $("#module_ddn_selected_transactions option");
            selectedtrans.each(function() {
                trans.push($(this).val());
            });
            $("#module_transaction_selected").val(JSON.stringify(trans));
            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frmmoduletransactions").serialize(),
                success: function(data) {
                    SnackbarMsg(data);
                }
            });
        });
        $("#btn_add_module").click(function() {
            var modulename = $("#txtmodulename").val();
            var sequence = $("#txtsequence").val();
            if (sequence <= 0) {
                return;
            }
            if (modulename.trim() == '') {
                return;
            }
            $.post('/{{ $companyname }}/create-module-name', {
                'modulename': modulename,
                'sequence': sequence
            }, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                if (result['status'] == 'success') {
                    SnackbarMsg(data);
                    tblmodules.ajax.url('/{{ $companyname }}/get-All-Modules').load();
                } else {
                    SnackBar({
                        message: "Module By this name already exists",
                        status: 'error'
                    });
                }
            });
        });

        function loadModuleTxns(moduleid) {
            $("#module_ddn_unselected_transactions").empty();
            $("#module_ddn_selected_transactions").empty();
            $.get('/{{ $companyname }}/get-Module-Transactions/' + moduleid, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));
                var selected = resultarray['selected'];
                var unselected = resultarray['unselected'];
                for (let select of selected) {
                    $("#module_ddn_selected_transactions").append(
                        `<option class='ui-state-highlight' value='${select['id']}'>${select['text']}</option>`);
                }
                for (let unselect of unselected) {
                    $("#module_ddn_unselected_transactions").append(
                        `<option value='${unselect['id']}'>${unselect['text']}</option>`);
                }
            });
        }

        function loadTxnShortcuts(roleid) {
            $.get('/{{ $companyname }}/get-role-transactions-in-module/' + roleid, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));
                $("#txnshortcut_unselected_transactions").empty();
                $("#txnshortcut_selected_transactions").empty();
                for (let unselect of resultarray['unselected']) {
                    $("#txnshortcut_unselected_transactions").append(
                        `<option value='${unselect['id']}'>${unselect['text']}</option>`);
                }
                for (let select of resultarray['selected']) {
                    $("#txnshortcut_selected_transactions").append(
                        `<option value='${select['id']}'>${select['text']}</option>`);
                }
            });
        }
        $("#ddn_select_role_txhshortcut").change(function() {
            var roleid = $(this).val();
            loadTxnShortcuts(roleid);
        });
        $("#btn_txnshortcut_cancel_transaction").click(function() {
            var roleid = $("#ddn_select_role_txhshortcut").val();
            loadTxnShortcuts(roleid);
        });
        $("#btn_txnshortcut_transactions_select").click(function() {
            var selected = $("#txnshortcut_unselected_transactions :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#txnshortcut_selected_transactions").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_txnshortcut_transactions_unselect").click(function() {
            var selected = $("#txnshortcut_selected_transactions :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#txnshortcut_unselected_transactions").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_txnshortcut_save_transaction").click(function() {
            var companyname = $("#company_name").val();
            var url = '/' + companyname + '/submit-module-role-transactions';
            var trans = [];
            var selectedtrans = $("#txnshortcut_selected_transactions option");
            selectedtrans.each(function() {
                trans.push($(this).val());
            });
            $("#role_txns_selected").val(JSON.stringify(trans));
            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frmtxnshortcut").serialize(),
                success: function(data) {
                    SnackbarMsg(data);
                }
            });
        });

        function loadModuleRoleReportShortcuts(roleid) {
            $("#reportshortcut_unselected_reports").empty();
            $("#reportshortcut_selected_reports").empty();
            $.get('/{{ $companyname }}/get-module-role-report-shortcuts/' + roleid, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));
                for (let select of resultarray['selected']) {
                    $("#reportshortcut_selected_reports").append(
                        `<option value='${select['id']}'>${select['text']}</option>`);
                }
                for (let unselect of resultarray['unselected']) {
                    $("#reportshortcut_unselected_reports").append(
                        `<option value='${unselect['id']}'>${unselect['text']}</option>`);
                }
            });
        }
        $("#ddn_select_role_reportshortcut").change(function() {
            var roleid = $(this).val();
            loadModuleRoleReportShortcuts(roleid);
        });
        $("#btn_reportshortcut_reports_select").click(function() {
            var selected = $("#reportshortcut_unselected_reports :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#reportshortcut_selected_reports").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_reportshortcut_reports_unselect").click(function() {
            var selected = $("#reportshortcut_selected_reports :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#reportshortcut_unselected_reports").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_reportshortcut_cancel_report").click(function() {
            var roleid = $("#ddn_select_role_reportshortcut").val();
            loadModuleRoleReportShortcuts(roleid);
        });
        $("#btn_reportshortcut_save_report").click(function() {
            var companyname = $("#company_name").val();
            var url = '/' + companyname + '/submit-module-role-reportshortcuts';
            var reports = [];
            var selectedreports = $("#reportshortcut_selected_reports option");
            selectedreports.each(function() {
                reports.push($(this).val());
            });
            $("#role_reportshortcut_selected").val(JSON.stringify(reports));
            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frmreportshortcut").serialize(),
                success: function(data) {
                    SnackbarMsg(data);
                }
            });
        });

        function loadAcShortcutVoucherTypes(roleid) {
            $("#acshortcut_selected_vouchers").empty();
            $("#acshortcut_unselected_vouchers").empty();
            $.get('/{{ $companyname }}/get-module-acshortcut-vouchertypes/' + roleid, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));
                for (let select of resultarray['selected']) {
                    $("#acshortcut_selected_vouchers").append(
                        `<option value='${select['id']}'>${select['text']}</option>`);
                }
                for (let unselect of resultarray['unselected']) {
                    $("#acshortcut_unselected_vouchers").append(
                        `<option value='${unselect['id']}'>${unselect['text']}</option>`);
                }
            });
        }
        $("#ddn_select_role_acshortcut").change(function() {
            var roleid = $("#ddn_select_role_acshortcut").val();
            loadAcShortcutVoucherTypes(roleid);
        });
        $("#btn_acshortcut_vouchers_select").click(function() {
            var selected = $("#acshortcut_unselected_vouchers :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#acshortcut_selected_vouchers").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_acshortcut_vouchers_unselect").click(function() {
            var selected = $("#acshortcut_selected_vouchers :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#acshortcut_unselected_vouchers").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_acshortcut_cancel_vouchers").click(function() {
            var roleid = $("#ddn_select_role_acshortcut").val();
            loadAcShortcutVoucherTypes(roleid);
        });
        $("#btn_acshortcut_save_vouchers").click(function() {
            var companyname = $("#company_name").val();
            var url = '/' + companyname + '/submit-module-role-acshortcuts';
            var reports = [];
            var selectedvouchers = $("#acshortcut_selected_vouchers option");
            selectedvouchers.each(function() {
                reports.push($(this).val());
            });
            $("#role_acshortcut_selected").val(JSON.stringify(reports));
            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frmacshortcut").serialize(),
                success: function(data) {
                    SnackbarMsg(data);
                }
            });
        });

        function loadACReportShortcuts(roleid) {
            $("#acreportshortcut_selected_menus").empty();
            $("#acreportshortcut_unselected_menus").empty();
            $.get('/{{ $companyname }}/get-module-ac-report-shortcuts/' + roleid, function(data, status) {
                var resultarray = JSON.parse(JSON.stringify(data));
                for (let select of resultarray['selected']) {
                    $("#acreportshortcut_selected_menus").append(
                        `<option value='${select['id']}'>${select['text']}</option>`);
                }
                for (let unselect of resultarray['unselected']) {
                    $("#acreportshortcut_unselected_menus").append(
                        `<option value='${unselect['id']}'>${unselect['text']}</option>`);
                }
            });
        }
        $("#ddn_select_role_acreportshortcut").change(function() {
            var roleid = $(this).val();
            loadACReportShortcuts(roleid);
        });
        $("#btn_acreportshortcut_menus_select").click(function() {
            var selected = $("#acreportshortcut_unselected_menus :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#acreportshortcut_selected_menus").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_acreportshortcut_menus_unselect").click(function() {
            var selected = $("#acreportshortcut_selected_menus :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#acreportshortcut_unselected_menus").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_acreportshortcut_save_menus").click(function() {
            var companyname = $("#company_name").val();
            var url = '/' + companyname + '/submit-module-role-acreportshortcuts';
            var menus = [];
            var selectedmenus = $("#acreportshortcut_selected_menus option");
            selectedmenus.each(function() {
                menus.push($(this).val());
            });
            $("#role_acreportshortcut_selected").val(JSON.stringify(menus));
            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frmacreportshortcut").serialize(),
                success: function(data) {
                    SnackbarMsg(data);
                }
            });
        });
        $("#btn_acreportshortcut_cancel_menus").click(function() {
            var roleid = $("#ddn_select_role_acreportshortcut").val();
            loadACReportShortcuts(roleid);
        });
        $("#btn_add_report_module").click(function() {
            var mname = $("#txtreportmodulename").val();
            var seq = $("#txtreportmodulesequence").val();
            if (mname.trim() == '') {
                return;
            }
            $.post('/{{ $companyname }}/add-module-report-name', {
                'mname': mname,
                'sequence': seq
            }, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                if (result['status'] == 'success') {
                    loadReportModules();
                    SnackbarMsg(data);
                }
            });
        });

        function loadReportModules() {
            $("#tbodyreportmodules").empty();
            $.get('/{{ $companyname }}/get-module-report-modules', function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                for (let rpt of result) {
                    var sequence = (rpt['sequence'] == null) ? '' : rpt['sequence'];
                    $("#tbodyreportmodules").append(
                        `<tr id='${rpt['id']}'><td>${rpt['id']}</td><td>${rpt['rmname']}</td><td>${sequence}</td></tr>`
                        )
                }
                $("#tbodyreportmodules").sortable({
                    update: function() {
                        changeReportModuleSequence();
                    }
                });
                $('#table_report_modules').Tabledit({
                    url: '/{{ $companyname }}/update-module-report-module-name',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'id'],
                        editable: [
                            [1, 'rmname'],
                        ]
                    },
                    buttons: {
                        edit: {
                            class: "btn btn-default",
                            html: '<span ><i class="fas fa-edit"></i></span>',
                            action: 'edit'
                        },
                    },
                    onSuccess: function(data, textStatus, jqXHR) {
                        if (data.action == 'edit') {
                            SnackBar({
                                message: "Report Module Name Updated successfully",
                                status: 'success'
                            });
                        }
                    },
                    onFail: function() {
                        alert("error");
                    },
                });
            });
        }

        function changeReportModuleSequence() {
            var rows = $("#tbodyreportmodules tr");
            var ids = [];
            rows.each(function() {
                ids.push($(this).attr('id'))
            });
            $.post("/{{ $companyname }}/update-report-modules-sequences", {
                'ids': ids
            }, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                if (result['status'] == 'success') {
                    loadReportModules();
                }
            });
        }

        function loadAddReportReports(rmid) {
            $("#addreport_unselected_reports").empty();
            $("#addreport_selected_reports").empty();
            $.get('/{{ $companyname }}/get-module-report-module-reports/' + rmid, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                var resultarray = JSON.parse(JSON.stringify(data));
                for (let select of resultarray['selected']) {
                    $("#addreport_selected_reports").append(
                        `<option value='${select['id']}'>${select['text']}</option>`);
                }
                for (let unselect of resultarray['unselected']) {
                    $("#addreport_unselected_reports").append(
                        `<option value='${unselect['id']}'>${unselect['text']}</option>`);
                }
            });
        }
        $("#ddn_module_select_report_module").change(function() {
            var rmid = $(this).val();
            loadAddReportReports(rmid);
        });
        $("#btn_addreport_cancel_reports").click(function() {
            var rmid = $("#ddn_module_select_report_module").val();
            loadAddReportReports(rmid);
        });
        $("#btn_addreport_reports_select").click(function() {
            var selected = $("#addreport_unselected_reports :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#addreport_selected_reports").append("<option value='" + id + "'>" + text + "</option>");
            });
        });
        $("#btn_addreport_reports_unselect").click(function() {
            var selected = $("#addreport_selected_reports :selected");
            selected.each(function() {
                var id = $(this).val();
                var text = $(this).html();
                $(this).remove();
                $("#addreport_unselected_reports").append("<option value='" + id + "'>" + text +
                    "</option>");
            });
        });
        $("#btn_addreport_save_reports").click(function() {
            var companyname = $("#company_name").val();
            var url = '/' + companyname + '/submit-module-addreport-reports';
            var reports = [];
            var selectedreports = $("#addreport_selected_reports option");
            selectedreports.each(function() {
                reports.push($(this).val());
            });
            $("#module_addreport_report_selected").val(JSON.stringify(reports));
            $.ajax({
                method: 'POST',
                url: url,
                data: $("#frmaddreport").serialize(),
                success: function(data) {
                    SnackbarMsg(data);
                }
            });
        });

        function loadReportModuleRptsSequence(rmid) {
            $("#tbody_table_reportmodule_rpts").empty();
            $.get('/{{ $companyname }}/get-module-reportmodule-sequence-rpts/' + rmid, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                for (let rpt of result) {
                    $("#tbody_table_reportmodule_rpts").append(
                        `<tr id='${rpt['id']}'><td>${rpt['id']}</td><td>${rpt['reportname']}</td><td>${rpt['sequence']}</td></tr>`
                        );
                }
                $("#tbody_table_reportmodule_rpts").sortable({
                    update: function(event, ui) {
                        changeModuleReportSequence(rmid);
                    }
                });
            });
        }
        $("#ddnreportsequencemodule").change(function() {
            var rmid = $(this).val();
            loadReportModuleRptsSequence(rmid);
        });

        function changeModuleReportSequence(rmid) {
            var rows = $("#tbody_table_reportmodule_rpts tr");
            var ids = [];
            rows.each(function() {
                ids.push($(this).attr('id'));
            });
            $.post('/{{ $companyname }}/update-module-reportmodule-rpts-sequence', {
                'ids': ids
            }, function(data, status) {
                // SnackbarMsg(data);
            });
            loadReportModuleRptsSequence(rmid);
        }
        $("#btn_add_company_news").click(function() {
            var companynews = $("#txtcompanynews").val().trim();
            var companynewsdate = $("#selectCompanyNewsDate").val().trim();
            if (companynews == '' || companynewsdate == '') {
                return;
            }
            $.post('/{{ $companyname }}/submit-module-company-news', {
                'news': companynews,
                'date': companynewsdate
            }, function(data, status) {
                loadModuleCompanyNews();
            });
        });

        function loadModuleCompanyNews() {
            $("#tbody_table_company_news").empty();
            $.get('/{{ $companyname }}/get-module-company-news', function(data, status) {
                var news = JSON.parse(JSON.stringify(data));
                for (let nw of news) {
                    var stringdate = nw['date'];
                    var stringdatearray = stringdate.split('-');
                    var newsdate = stringdatearray[2] + '-' + stringdatearray[1] + '-' + stringdatearray[0];
                    var isdisplay = (nw['display'] == 0 ? 'False' : 'True');
                    $("#tbody_table_company_news").append(
                        `<tr id='tr_${nw['id']}'><td>${nw['id']}</td><td>${nw['News']}</td><td>${newsdate}</td><td>${isdisplay}</td></tr>`
                        );
                }
                $('#table_company_news').Tabledit({
                    url: '/{{ $companyname }}/submit-module-company-news',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'id'],
                        editable: [
                            [1, 'news'],
                            [2, 'date'],
                            [3, 'display', '{"0": "False", "1": "True"}'],
                        ]
                    },
                    buttons: {
                        edit: {
                            class: "btn btn-default",
                            html: '<span ><i class="fas fa-edit"></i></span>',
                            action: 'edit'
                        },
                        delete: {
                            class: "btn btn-default",
                            html: '<span ><i class="fa fa-trash" aria-hidden="true"></i></span>',
                            action: 'delete'
                        }
                    },
                    onSuccess: function(data, textStatus, jqXHR) {
                        if (data.action == 'edit') {
                            SnackBar({
                                message: "News Updated successfully",
                                status: 'success'
                            });
                        } else if (data.action == 'delete') {
                            loadModuleCompanyNews();
                            SnackBar({
                                message: "News Deleted successfully",
                                status: 'success'
                            });
                        }
                    },
                    onFail: function() {
                        alert("Something went wrong");
                    },
                });
            });
        }
        $("#btn_add_email_conf").click(function() {
            var user = $("#emailconf_ddnselectuser").val().trim();
            var email = $("#emailconf_email").val().trim();
            var pwd = $("#emailconf_pwd").val().trim();
            var host = $("#emailconf_host").val().trim();
            var port = $("#emailconf_port").val().trim();
            var data = [];
            if (user == '' || email == '' || pwd == '' || host == '' || port == '') {
                SnackBar({
                    message: 'Please enter all values',
                    status: 'error'
                });
                return false;
            }
            $.post('/{{ $companyname }}/submit-module-email-configuration', {
                data: {
                    'user': user,
                    'email': email,
                    'pwd': pwd,
                    'host': host,
                    'port': port
                }
            }, function(responsedata, status) {
                SnackbarMsg(responsedata);
                var responsearray = JSON.parse(JSON.stringify(responsedata));
                var newdata = responsearray['data'];
                tblemailconfs.ajax.url('/{{ $companyname }}/get-Module-All-Email-Confs').load();
                // $('#tbody_table_email_conf').append(`<tr><td>${newdata['id']}</td><td>${newdata['user']}</td><td>${newdata['email']}</td><td>${newdata['pwd']}</td><td>${newdata['host']}</td><td>${newdata['port']}</td></tr>`);
                // loadEmailConfTableEdit();
            });
        });

        function loadEmailConfTableEdit() {
            $('#table_email_conf').Tabledit({
                url: '/{{ $companyname }}/update-module-email-conf',
                dataType: 'json',
                columns: {
                    identifier: [0, 'id'],
                    editable: [
                        [2, 'mail'],
                        [3, 'password'],
                        [4, 'smtp'],
                        [5, 'port'],
                    ]
                },
                buttons: {
                    edit: {
                        class: "btn btn-default",
                        html: '<span ><i class="fas fa-edit"></i></span>',
                        action: 'edit'
                    },
                    delete: {
                        class: "btn btn-default",
                        html: '<span ><i class="fa fa-trash" aria-hidden="true"></i></span>',
                        action: 'delete'
                    }
                },
                onSuccess: function(data, textStatus, jqXHR) {
                    if (data.action == 'edit') {
                        SnackBar({
                            message: "Email Configuration Updated successfully",
                            status: 'success'
                        });
                    } else if (data.action == 'delete') {
                        SnackBar({
                            message: "Email Configuration Deleted Successfully",
                            status: 'success'
                        });
                        $(`#tbody_table_email_conf tr[id='${data.id}']`).remove();
                    }
                },
                onFail: function() {
                    alert("Something went wrong");
                },
            });
        }

        function loadModuleMenuSequence() {
            $.get('/{{ $companyname }}/get-Module-Menus-with-Sequence', function(responsedata, status) {
                $("#tbody_menu_sequence").empty();
                var menus = JSON.parse(JSON.stringify(responsedata));
                for (let menu of menus) {
                    var sequence = (menu['sequence'] == null ? '' : menu['sequence']);
                    $("#tbody_menu_sequence").append(
                        `<tr id='${menu['id']}'><td>${menu['id']}</td><td>${menu['Menu_name']}</td><td>${sequence}</td></tr>`
                        );
                }
                $("#tbody_menu_sequence").sortable({
                    update: function(event, ui) {
                        changeModuleMenuSequence();
                    }
                });
            });
        }

        function changeModuleMenuSequence() {
            var tablerows = $("#tbody_menu_sequence tr");
            var ids = [];
            tablerows.each(function() {
                ids.push($(this).attr('id'));
            });
            $.post('/{{ $companyname }}/update-module-menu-sequence', {
                'ids': ids
            }, function(data, status) {
                var result = JSON.parse(JSON.stringify(data));
                SnackbarMsg(data);
                if (result['status'] == 'success') {
                    loadModuleMenuSequence();
                }
            });
        }


		function loadTransactionFields(tranid){

			$("#transactionfields_unselected_fields").empty();
            $("#transactionfields_selected_fields").empty();


			$.get("{{url('/')}}/{{Session::get('company_name')}}/get-module-transaction-table-fields-selected-unselected/"+tranid,
			function(data,status){

				var result=JSON.parse(JSON.stringify(data)); 
				$("#transactionfields_unselected_fields").empty();
				$("#transactionfields_selected_fields").empty();
				var selected= result['selected'];
 
				var unselected=result['unselected'];
 
				for(let select of selected){ 
					// alert(JSON.stringify(select));
 
					$("#transactionfields_selected_fields").append(`<option value='${select['name']}'>${select['label']}</option>`);

				}


				for(let unselect of unselected){
 
					$("#transactionfields_unselected_fields").append(`<option value='${unselect['name']}'>${unselect['label']}</option>`);

				}
  
			}
			);



		}


		$("#transactionfields_ddn_transaction").on("change",function(){
			var tranid=$(this).val();
			loadTransactionFields(tranid);
			
		});



		$("#btn_monthlocking_role_select").click(function(){

var selected=  $("#monthlocking_role_ddn_unselected :selected") ;
selected.each(function(){ 
var id=$(this).val();
var text=$(this).html();
$(this).remove();
$("#monthlocking_role_ddn_selected").append("<option value='"+id+"'>"+text+"</option>"); 
}); 


});

$("#btn_transactionfields_fields_unselect").click(function(){

    var selected=  $("#transactionfields_selected_fields :selected") ;

    selected.each(function(){ 
    var id=$(this).val(); 
    var text=$(this).html();
    $(this).remove();
    $("#transactionfields_unselected_fields").append("<option value='"+id+"'>"+text+"</option>"); 
    }); 
});


$("#btn_transactionfields_cancel_fields").click(function(){

	var tranid=	$("#transactionfields_ddn_transaction").val();
	loadTransactionFields(tranid);

});



$("#btn_transactionfields_fields_select").click(function(){

var unselected=  $("#transactionfields_unselected_fields :selected") ;

			unselected.each(function(){ 
			var id=$(this).val();
			var text=$(this).html();
			$(this).remove();
			$("#transactionfields_selected_fields").append("<option value='"+id+"'>"+text+"</option>"); 
			}); 
});



$("#btn_transactionfields_save_fields").click(function(){

var role=	$("#transactionfields_ddn_roles").val();
var tran=$("#transactionfields_ddn_transaction").val();

if(role=="" || tran==""){
	return;
}

var url="{{url('/')}}/{{$companyname}}/submit-module-transaction-fields";

var fields=[];

var selectedfields=$("#transactionfields_selected_fields option");

selectedfields.each(function(){ 
	fields.push($(this).val());
});
 


$("#transaction_fields_selected_hf").val(JSON.stringify(fields)); 

$.ajax({
url:url,
method:'POST',
data:$("#frmtransactionfields").serialize(),
success:function(data){  
	SnackbarMsg(data); 
	}
}); 

});




function loadModuleTxnSequence(moduleid){


	$.get('/{{ $companyname }}/get-Module-Transactions-For-Sequence/'+moduleid, function(responsedata, status) {
                $("#tbody_table_module_txns").empty();
                var txns = JSON.parse(JSON.stringify(responsedata));
                for (let txn of txns) {
                    var sequence = (txn['sequence'] == null ? '' : txn['sequence']);
                    $("#tbody_table_module_txns").append(
                        `<tr id='${txn['id']}'><td>${txn['id']}</td><td>${txn['txn']}</td><td>${sequence}</td></tr>`
                        );
                }
                $("#tbody_table_module_txns").sortable({
                    update: function(event, ui) {
                       changeModuleTxnSequence(moduleid);
                    }
                });
            });

}


function loadFieldSequence(transactionid){

 

	var role=$("#transactionfields_sequence_ddn_roles").val();
 

	$("#tbody_transaction_fields_sequence").empty();

	$.post("{{url('/')}}/{{$companyname}}/get-transaction-table-fields-with-sequence",{'role':role,'tranid':transactionid},function(data,status){
 

		var result =JSON.parse(JSON.stringify(data));

		for(let dt of result['fields']){

			var sequence=(dt['sequence']==null?'':dt['sequence']);

			$("#tbody_transaction_fields_sequence").append(`<tr id='${dt['id']}'><td>${dt['id']}</td><td>${dt['label']}</td><td>${sequence}</td></tr>`);
 
		}


		if(result['fields'].length==0){
			$("#tbody_transaction_fields_sequence").append(`<tr><td colspan='3'>No Data</td></tr>`);

		}

		$("#tbody_transaction_fields_sequence").sortable({
                    update: function(event, ui) {
                       changeTxnFieldSequence(transactionid);
                    }
                }); 

	});
 



}

$("#transactionfields_sequence_ddn_transaction").change(function(){

	var tranid=$(this).val();
	loadFieldSequence(tranid);
	

});


function changeTxnFieldSequence(tranid){
 

	var tablerows = $("#tbody_transaction_fields_sequence tr");
            var ids = [];
            tablerows.each(function() {
                ids.push($(this).attr('id'));
            });
            $.post("{{url('/')}}/{{ $companyname }}/update-txn-field-sequence", {
                'ids': ids
            }, function(data, status) {
 
                var result = JSON.parse(JSON.stringify(data));
				SnackbarMsg(data);
                if (result['status'] == 'success') { 
					loadFieldSequence(tranid);
                }
            });


}




    </script>
@endsection
