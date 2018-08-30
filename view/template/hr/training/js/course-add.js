$(document).ready(function() {

	//‘±π§
	$("#personsListName").yxselect_user({
		hiddenId : 'personsListAccount',
		formCode : 'hrCourseStudent',
		mode : 'check'
	});

	//≈‡—µΩ≤ ¶
	$("#teacherName").yxselect_user({
		hiddenId : 'teacherId',
		formCode : 'hrCourseTeacher'
	});
});