$(document).ready(function() {
	//Ա��
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'userName'
	});

	validate({
		"userName" : {
			required : true
		}
	});


	//���ÿ��Խ��
	$("#baseResult").val($("#baseResultHidden").val());
	//������֤���
	$("#finalResult").val($("#finalResultHidden").val());
})