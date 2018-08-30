$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}

	//������
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserId'
	});

	//����
	var planNum = $("#planNum").val();
	$("#planNum").blur(function () {
		if ($(this).val() > parseInt(planNum)) {
			alert('�ƻ�������������������');
			$(this).val(planNum);
		} else if ($(this).val() < 1) {
			alert('�ƻ���������С��1��');
			$(this).val(planNum);
		}
	});

	validate({
		"urgentLevelCode" : {
			required : true
		},
		"chargeUserName" : {
			required : true
		},
		"planStartDate" : {
			required : true
		},
		"planEndDate" : {
			required : true
		},
		"produceCode" : {
			required : true
		},
		"planNum" : {
			required : true,
			custom : ['onlyNumber']
		}
	});
});

//��������
function checkData() {
	var planStartDate = $("#planStartDate").val();
	var planEndDate = $("#planEndDate").val();
	if (planStartDate && planEndDate && planEndDate < planStartDate) {
		alert('�ƻ���ʼʱ�䲻�ܴ��ڼƻ�����ʱ�䣡');
		return false;
	}
	if ($("#planNum").val() < 1) {
		alert('�ƻ���������С��1��');
		return false;
	}
}