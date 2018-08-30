$(document).ready(function() {
	// 抄送人
	$("#TO_CC").yxselect_user({
				mode : 'check',
				hiddenId : 'TO_CCID'
			});

	// 验证信息
	validate({
				"receiver" : {
					required : true
				},
				"TO_CC" : {
					required : true
				},
				"mailContent" : {
					required : true
				}
			});
})