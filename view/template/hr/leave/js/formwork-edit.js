$(document).ready(function() {

	//Ա��
	$("#recipientName").yxselect_user({
		hiddenId : 'recipientId'
	});
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