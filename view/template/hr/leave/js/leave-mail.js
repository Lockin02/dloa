$(document).ready(function() {

	// �ռ���
	$("#TO_NAME").yxselect_user({
				mode : 'check',
				hiddenId : 'TO_ID'
			});


	// ��֤��Ϣ
	validate({
				"TO_NAME" : {
					required : true
				},
				"mailContent" : {
					required : true
				}
			});
})