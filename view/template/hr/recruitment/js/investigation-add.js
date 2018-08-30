$(document).ready(function() {
	// 验证信息
	validate({
		"consultationName" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"positionsName" : {
			required : true
		},
		"consultationTel" : {
			required : true,
			custom : ['onlyNumber']
		},
//		"workBeginDate" : {
//			required : true,
//			custom : ['date']
//		},
//		"workEndDate" : {
//			required : true,
//			custom : ['date']
//		},
		"userPosition" : {
			required : true
		},
//		"consultationEmail" : {
//			required : true,
//			custom : ['email']
//		},
		"userCompany" : {
			required : true
		},
		"relationshipCode" : {
			required : true
		},
		"sexd" : {
			required : true
		}
	});
	if($("#sex").val()!=''){
		$("#sexd").val($("#sex").val());
	}

//	$("#userName").yxselect_user({
//		hiddenId : 'positionsId'
//	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

  })