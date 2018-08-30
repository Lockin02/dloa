$(document).ready(function() {

	//≈‡—µΩ≤ ¶
	$("#teacherName").yxselect_user({
		hiddenId : 'teacherAccount',
		formCode : 'hrTeacher'
	});
	$("#belongDeptName").yxselect_dept({
		hiddenId : 'belongDeptId',
	});

	// «∑Ò…Ë÷√
	$("#isInner").val($("#isInnerHidden").val());
})