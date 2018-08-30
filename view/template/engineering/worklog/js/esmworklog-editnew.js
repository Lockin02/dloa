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

//����������Ϣ
function initActivity(projectId){
	$("#activityName").yxcombogrid_esmactivity("remove");
	//�����Ŀidδ���壬���ȡҳ���е���Ŀid
	if(projectId == undefined){
		projectId = $("#projectId").val();
	}

	//������Ŀ��Ⱦ
	$("#activityName").yxcombogrid_esmactivity({
		hiddenId : 'activityId',
		nameCol : 'activityName',
		isShowButton : false,
		height : 250,
		gridOptions : {
			param : {"projectId":projectId , 'isLeaf' : 1},
			isTitle : true,
			showcheckbox : false
		}
	});
}

// ��̨����������ɽ���
function calTaskProcess(workload){
	if(workload == "" || workload *1 == 0){
		$("#workProcess").val('');
		return false;
	}
	if($("#id").length == 0){
		var worklogId = "";
	}else{
		var worklogId = $("#id").val();
	}
	var activityId = $("#activityId").val();
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
				$("#workProcess").val(msg);
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