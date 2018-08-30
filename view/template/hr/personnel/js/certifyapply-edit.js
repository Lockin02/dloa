$(document).ready(function() {
	//员工
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


	//设置考试结果
	$("#baseResult").val($("#baseResultHidden").val());
	//设置认证结果
	$("#finalResult").val($("#finalResultHidden").val());
})