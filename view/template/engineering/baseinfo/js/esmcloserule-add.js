$(document).ready(function() {
	//负责人
	$("#confirmName").yxselect_user({
		hiddenId: 'confirmId',
		mode: 'check',
		formCode: 'confirmName'
	});

	// 验证信息
	validate({
		ruleName: {
			required: true
		}
	});
});