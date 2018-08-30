$(document).ready(function() {
//    $("#interviewer").yxselect_user({
//		hiddenId : 'interviewerId'
//	});

		$("#interviewer").yxselect_user({
							mode : 'check',
							hiddenId: 'interviewerId'
						});
	// 验证信息
	validate({
		"interviewer" : {
			required : true
		}
	});
   })