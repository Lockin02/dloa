$(document).ready(function() {
	// ������
	$("#TO_CC").yxselect_user({
				mode : 'check',
				hiddenId : 'TO_CCID'
			});

	// ��֤��Ϣ
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