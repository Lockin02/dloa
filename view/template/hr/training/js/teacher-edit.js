$(document).ready(function() {

	//��ѵ��ʦ
	$("#teacherName").yxselect_user({
		hiddenId : 'teacherAccount',
		formCode : 'hrTeacher'
	});
	$("#belongDeptName").yxselect_dept({
		hiddenId : 'belongDeptId',
	});

	//�Ƿ�����
	$("#isInner").val($("#isInnerHidden").val());
})