$(document).ready(function() {

	validate({
		"chargeUserName" : {
			required : true
		}
	});

	$("#actorNames").yxselect_user({
		hiddenId : 'actorIds',
		mode : 'check'
	})
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserId'
	})
})

/**
 * ��������У��
 */
function checkTaskNum(taskNum) {
	if (parseInt(taskNum) > $("#lastTaskNum").val()) {
		alert("�����������ܴ���֮ǰ��������");
		$("#taskNum").val($("#lastTaskNum").val());
	}
}