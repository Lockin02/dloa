$(document).ready(function() {
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

})