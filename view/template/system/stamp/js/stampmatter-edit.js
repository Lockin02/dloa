$(document).ready(function() {
	
	//�Ƿ���Ҫ����
	if($("#needAudit").val() == 1){
		$("#isNeedAuditYes").attr('checked',true);
	}else{
		$("#isNeedAuditNo").attr('checked',true);
	}
	
	//״̬�Ƿ���
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