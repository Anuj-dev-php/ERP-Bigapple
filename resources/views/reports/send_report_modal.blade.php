
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
                    
                    <input type="hidden"     id="send_report_generateurl"  value="" /> 

					<div class="form-group row">
							<label class="lbl_control col-sm-4" >Select Report Format:</label>
							<div class="control-label col-sm-6"> 
								<select class="form-control"   name="reportformat" id="reportsend_format"> 
							 <option value="pdf">PDF</option>
                             <option value="xlsx">XLSX</option>
                             <option value="csv">CSV</option>
								</select>
							</div>
					</div>
 
				 
					<div class="email-send-report form-group row mt-3"   >
							<label class="lbl_control col-sm-4" >Enter Email:</label>
							<div class="control-label col-sm-6">  
								<input type='email' name="toemailid" class="form-control" placeholder="Enter Email"  id="reportsend_enteremail" />
							</div>
					</div>
			 
					
					<div class="whatsapp-send-report form-group row  mt-3  ">
							<label class="lbl_control col-sm-4" >Enter Whatsapp no.:</label>
							<div class="control-label col-sm-6">  

								<input type="text"  class="form-control" name="towhatsappno" placeholder="Enter Whatsapp No." id="reportsend_enterwhatsappno"  />
							  
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