$(document).ready(function() {

	// 收件人
	$("#TO_NAME").yxselect_user({
				mode : 'check',
				hiddenId : 'TO_ID'
			});


	// 验证信息
	validate({
				"TO_NAME" : {
					required : true
				},
				"mailContent" : {
					required : true
				}
			});
})