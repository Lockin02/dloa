$(document).ready(function() {
	$("#InvestigationMan").yxselect_user({
				hiddenId : 'InvestigationManId'
			});
	// ��̸��
	$("#interviewer").yxselect_user({
				mode : 'check',
				hiddenId : 'interviewerId'
			});


	// ��֤��Ϣ
	validate({
				"interviewer" : {
					required : true
				}
			});
})