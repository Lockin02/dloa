$(document).ready(function() {
//    $("#interviewer").yxselect_user({
//		hiddenId : 'interviewerId'
//	});

		$("#interviewer").yxselect_user({
							mode : 'check',
							hiddenId: 'interviewerId'
						});
	// ��֤��Ϣ
	validate({
		"interviewer" : {
			required : true
		}
	});
   })