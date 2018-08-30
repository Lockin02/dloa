$(document).ready(function() {

	//员工
	$("#recipientName").yxselect_user({
		hiddenId : 'recipientId'
	});

//	var url = "?model=hr_leave_fromwork&action=checkRepeat";
//	$("#items").ajaxCheck({
//		url : url,
//		alertText : "* 该名称已存在",
//		alertTextOk : "* 该名称可用"
//	});
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