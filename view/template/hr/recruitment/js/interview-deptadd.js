$(document).ready(function() {
	//���˲��Ÿ�����
	$("#useManager").yxselect_user({
		hiddenId : 'useManagerId',
		formCode : 'intUseManager'
	});

	//���Թ�
	$("#useInterviewer").yxselect_user({
		hiddenId : 'useInterviewerId',
		formCode : 'intUseInterviewer'
	});
})