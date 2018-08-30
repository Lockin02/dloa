$(document).ready(function() {

	//人资面试官
	$("#hrInterviewer").yxselect_user({
		hiddenId : 'hrInterviewerId',
		formCode : 'intHrInterviewer'
	});

	//招聘主管
	$("#hrCharger").yxselect_user({
		hiddenId : 'hrChargerId',
		formCode : 'intHrCharger'
	});

	//招聘经理
	$("#hrManager").yxselect_user({
		hiddenId : 'hrManagerId',
		formCode : 'intHrManager'
	});

	//人资负责人
	$("#manager").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'intManager'
	});

	//副总经理
	$("#deputyManager").yxselect_user({
		hiddenId : 'deputyManagerId',
		formCode : 'intDeputyManager'
	});

	// 验证信息
	validate({
		"userName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"sexy" : {
			required : true
		},
		"positionsName" : {
			required : true
		},
		"wageLevelCode" : {
			required : true
		}
	});
})