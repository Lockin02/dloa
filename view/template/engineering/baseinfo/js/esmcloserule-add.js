$(document).ready(function() {
	//������
	$("#confirmName").yxselect_user({
		hiddenId: 'confirmId',
		mode: 'check',
		formCode: 'confirmName'
	});

	// ��֤��Ϣ
	validate({
		ruleName: {
			required: true
		}
	});
});