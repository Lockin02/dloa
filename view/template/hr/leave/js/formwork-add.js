$(document).ready(function() {

	//Ա��
	$("#recipientName").yxselect_user({
		hiddenId : 'recipientId'
	});

//	var url = "?model=hr_leave_fromwork&action=checkRepeat";
//	$("#items").ajaxCheck({
//		url : url,
//		alertText : "* �������Ѵ���",
//		alertTextOk : "* �����ƿ���"
//	});
	// ��֤��Ϣ
	validate({
		"items" : {
			required : true
		},
		"recipientName" : {
			required : true
		}
	});
})