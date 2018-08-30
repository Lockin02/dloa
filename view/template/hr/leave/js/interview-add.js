$(document).ready(function() {
	$("#InvestigationMan").yxselect_user({
				hiddenId : 'InvestigationManId'
			});
	// 面谈者
	$("#interviewer").yxselect_user({
				mode : 'check',
				hiddenId : 'interviewerId'
			});


	// 验证信息
	validate({
				"interviewer" : {
					required : true
				}
			});
})