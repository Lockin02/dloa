$(document).ready(function() {

	//Ա��
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetJob : [true, "jobId", "jobName"],
		isGetDept : [true, "deptIdS", "deptNameS"],
		formCode : 'userName'
	});

	//������Ϣ
	$("#deptNameS").yxselect_dept({
		hiddenId : 'deptIdS'
	});

	// ��֤��Ϣ
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