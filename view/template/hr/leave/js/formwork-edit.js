$(document).ready(function() {

	//员工
	$("#recipientName").yxselect_user({
		hiddenId : 'recipientId'
	});
	// 验证信息
	validate({
		"items" : {
			required : true
		},
		"recipientName" : {
			required : true
		}
	});
})