$(document).ready(function() {
	
	//是否需要审批
	if($("#needAudit").val() == 1){
		$("#isNeedAuditYes").attr('checked',true);
	}else{
		$("#isNeedAuditNo").attr('checked',true);
	}
	
	//状态是否开启
	if($("#status").val() == 1){
		$("#isStatusYes").attr('checked',true);
	}else{
		$("#isStatusNo").attr('checked',true);
	}

	validate({
		"matterName" : {
			required : true
		}
	});

});