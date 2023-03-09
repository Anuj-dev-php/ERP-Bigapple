@extends('layout.layout') @section('content')
<h2 class="menu-title">@if(!empty($template)) Edit @else Add @endif Create Format</h2>
<div class="pagecontent">
	<div class="container mtb-2">
		<form method="post" action="{{url('/')}}/{{$companyname}}/submitcreateformat"> @csrf @if(!empty($template))
			<input type="hidden" name="tempid" value="{{$template->Tempid}}" /> @endif
			<div class="row  mtb-3 marginpart1">
				<div class="col-2 text-end">
					<label class="lbl_control">Template Name:</label>
				</div>
				<div class="col-4 text-start">
					<input type="text" name="template_name" class="form-control  select-configure" @if(!empty($template)) value="{{ $template->TempName}}" @endif required />
				</div>
				<div class="col-2 text-end">
					<label class="lbl_control">Header Size:</label>
				</div>
				<div class="col-4 text-start">
					<input type="number" name="header_size" class="form-control select-configure" min="1" @if(!empty($template)) value="{{ $template->Head_Size}}" @endif required />
				</div>
			</div>
			<div class="row mtb-3 marginpart1">
				<div class="col-2 text-end">
					<label class="lbl_control">Transaction:</label>
				</div>
				<div class="col-4 text-start">
					<select name="transaction" id="ddnselecttransaction" class="form-select select-configure" required>
						<option value="">Select Transaction</option> @foreach ( $transactions as $tranid=>$tranname )
						<option value="{{$tranid}}" @if(!empty($template) && $template->Txn_Name==$tranid) selected @endif>{{$tranname}}</option> @endforeach
						<select>
				</div>
				<div class="col-2 text-end">
					<label class="lbl_control">Body Size:</label>
				</div>
				<div class="col-4 text-start">
					<input type="number" name="body_size" class="form-control select-configure" min="1" @if(!empty($template)) value="{{$template->Body_Size}}" @endif required />
				</div>
			</div>
			<div class="row mtb-3 marginpart1">
				<div class="col-2 text-end">
					<label class="lbl_control">Height:</label>
				</div>
				<div class="col-4 text-start">
					<input type="number" name="height" class="form-control select-configure" min="1" @if(!empty($template)) value="{{$template->Height}}" @endif required />
				</div>
				<div class="col-2 text-end">
					<label class="lbl_control">Footer Size:</label>
				</div>
				<div class="col-4 text-start">
					<input type="number" name="footer_size" class="form-control select-configure" min="1" @if(!empty($template)) value="{{$template->Footer_Size}}" @endif required />
				</div>
			</div>
			<div class="row mtb-3 marginpart1">
				<div class="col-2 text-end">
					<label class="lbl_control">Width:</label>
				</div>
				<div class="col-4 text-start">
					<input type="number" class="form-control select-configure" name="width" min="1" @if(!empty($template)) value="{{$template->Width}}" @endif required />
				</div>
				<div class="col-2 text-end">
					<label class="lbl_control">Maxlines Body:</label>
				</div>
				<div class="col-4 text-start">
					<input type="number" name="maxlines_body" class="form-control select-configure" min="1" @if(!empty($template)) value="{{$template->Max_Body_lines}}" @endif required />
				</div>
			</div>
			<div class="row mtb-3 marginpart1">
				<div class="col-1"></div>
				<div class="col-2   text-end">
					<label class="lbl_control">
						<input type="checkbox" name="print_body_columns" value="1" @if(!empty($template) && trim($template->print_cols)=="True") checked @endif />&nbsp;Print Body Columns</label>
				</div>
				<div class="col-2 text-end">
					<label class="lbl_control">
						<input type="checkbox" name="print_borders" @if(!empty($template) && trim($template->print_border)=="True") checked @endif value="1"/>&nbsp;Print Borders</label>
				</div>
				<div class="col-1"></div>
				<div class="col-2 text-end">
					<label class="lbl_control">Crystal Template</label>
				</div>
				<div class="col-4 text-end">
					<input type="text" name="crystaltemplate" class="form-control select-configure" @if(!empty($template) && !empty($template->crystal) ) value="{{$template->crystal}}" @endif /> </div>


               <div class="col-2 text-end">
					<label class="lbl_control">Link:</label>
				</div>
				<div class="col-6 text-start"> 
                    <textarea name="link" class="form-control select-configure"  required >@if(!empty($template) && !empty($template->link) ){{$template->link}}@endif</textarea>
				</div>


				<div class="col-12 text-center mtb-2">
					<input type="submit" class="btn btn-primary btn-md" value="Submit" /> &nbsp;&nbsp;
					<input type="button" class="btn btn-primary btn-md" value="Cancel" id="btn_cancel_reload" /> </div>
			</div>
		</form>
	</div>
</div> @endsection @section('js') {{-- ROLE --}}
<script type="text/javascript">
$(function() {
	$("#ddnselecttransaction").select2();
})
</script> @endsection