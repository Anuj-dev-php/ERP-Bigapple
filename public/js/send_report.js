


$(".btnsendreport").click(function(){
	var mode=$(this).data('mode');
	$("#reportsend_enteremail").val("");
	$("#reportsend_enterwhatsappno").val("");

	var generateurl=$(this).data('generateurl');
	
	
	$("#send_report_mode").val(mode);
	$(".whatsapp-send-report").removeClass('d-none');
	$(".email-send-report").removeClass('d-none');
	if(mode=="email"){ 
		$(".whatsapp-send-report").addClass('d-none');
	}
	else{
		$(".email-send-report").addClass('d-none');
	}

	$("#send_report_generateurl").val(generateurl); 
	$("#reportSendModal").modal("show");



});


$("#btn_send_report").click(function(){

	var mode=$("#send_report_mode").val();
	var generateurl=$("#send_report_generateurl").val(); 

	var emails=$("#reportsend_enteremail").val().trim();
	var whatsappno=$("#reportsend_enterwhatsappno").val().trim();
	var format=$("#reportsend_format").val().trim(); 

	$.post(generateurl,{ 'mode':mode,'emails':emails,'whatsapp_no':whatsappno,'format':format},function(data){
		$("#reportSendModal").modal('hide');
		SnackbarMsg(data); 
	
	}) 

});