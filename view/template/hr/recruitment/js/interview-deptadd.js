$(document).ready(function() {
	//用人部门负责人
	$("#useManager").yxselect_user({
		hiddenId : 'useManagerId',
		formCode : 'intUseManager'
	});

	//面试官
	$("#useInterviewer").yxselect_user({
		hiddenId : 'useInterviewerId',
		formCode : 'intUseInterviewer'
	});
})