$(document).ready(function() {

	//������
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserId'
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
		}
	});
});

//��������
function checkData() {
	var planStartDate = $("#planStartDate").val();
	var planEndDate = $("#planEndDate").val();
	if (planStartDate && planEndDate && planEndDate < planStartDate) {
		alert('�ƻ���ʼʱ�䲻�ܴ��ڼƻ�����ʱ�䣡');
	}
}