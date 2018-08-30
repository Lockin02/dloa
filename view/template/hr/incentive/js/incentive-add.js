$(document).ready(function() {
	//Ա��
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'userName'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//��¼��
	$("#recorderName").yxselect_user({
		hiddenId : 'recorderId',
		formCode : 'recorderName'
	});

	// ��֤��Ϣ
	validate({
		"userName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"incentiveType" : {
			required : true
		},
//		"rewardPeriod" : {
//			required : true
//		},
//		"incentiveMoney_v" : {
//			required : true
//		},
		"recorderName" : {
			required : true,
			length : [0,20]
		},
		"incentiveDate" : {
			required : true,
			custom : ['date']
		},
		"recordDate" : {
			required : true,
			custom : ['date']
		}
	});
})