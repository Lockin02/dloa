$(document).ready(function() {

	//员工
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetJob : [true, "jobId", "jobName"],
		isGetDept : [true, "deptIdS", "deptNameS"],
		formCode : 'userName'
	});

	//部门信息
	$("#deptNameS").yxselect_dept({
		hiddenId : 'deptIdS'
	});

	// 验证信息
	validate({
		"userName" : {
			required : true
		},
		"jobName" : {
			required : true
		},
		"deptNameS" : {
			required : true
		},
		"rewardPeriod" : {
			required : true
		}
	});
});