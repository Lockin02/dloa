$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"executionDate" : {
			required : true
		},
		"workStatus" : {
			required : true
		},
		"country" : {
			required : true
		},
		"province" : {
			required : true
		},
		"city" : {
			required : true
		},
		"inWorkRate" : {
			required : true
		}
	});

	//ʵ����������Ϣ
	initCity();

	//��������
	if($("#invbody").html() != ""){
		showAndHide('feeImg','feeTbl');
	}

	//����title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());

	//��Ʊ���ͻ���
	billTypeArr = getBillType();
});

// ��̨����������ɽ���
function calTaskProcess(workload){
	if(workload == ""){
		$("#workProcess").val('');
		return false;
	}
	if($("#id").length == 0){
		var worklogId = "";
	}else{
		var worklogId = $("#id").val();
	}
	var activityId = $("#activityId").val();
	if(activityId == "" || activityId*1 == 0){
		return false;
	}
	var workProcessObj = $("#workProcess");
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=calTaskProcess",
		data : {
			"workload" : workload,
			"id" : activityId,
			"worklogId" : worklogId
		},
		success : function(msg) {
			if(msg != -1){
				var processObj = eval("(" + msg + ")");
				if(workProcessObj.val()*1 != -1){
					workProcessObj.val(processObj.process);
				}
				$("#thisActivityProcess").val(processObj.thisActivityProcess);
				$("#thisProjectProcess").val(processObj.thisProjectProcess);
			}else{
				alert('��ȡ���ȴ���');
			}
		}
	});
}

//����֤
function checkForm(){
	//�����ж� -- ����������Ѿ���д����־�������������д
	$.ajax({
		type : "POST",
		url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
		data : {
			"executionDate" : $("#executionDate").val(),
			"activityId" : $("#activityId").val()
		},
	    async: false,
		success : function(msg) {
			if(msg == "1"){
				if(confirm('������־�Ѿ���д���Ƿ��������޸�?')){
					location = "";
				}else{
					return false;
				}
			}
		}
	});
}