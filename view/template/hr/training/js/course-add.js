$(document).ready(function() {

	//Ա��
	$("#personsListName").yxselect_user({
		hiddenId : 'personsListAccount',
		formCode : 'hrCourseStudent',
		mode : 'check'
	});

	//��ѵ��ʦ
	$("#teacherName").yxselect_user({
		hiddenId : 'teacherId',
		formCode : 'hrCourseTeacher'
	});
});