$(document).ready(function() {
	//部门信息
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
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
		}
	});
})