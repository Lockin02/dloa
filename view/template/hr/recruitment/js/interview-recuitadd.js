$(document).ready(function() {
	//������Ϣ
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});


	// ��֤��Ϣ
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