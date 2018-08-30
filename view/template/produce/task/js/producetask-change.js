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
 * 任务数量校验
 */
function checkTaskNum(taskNum) {
	if (parseInt(taskNum) > $("#lastTaskNum").val()) {
		alert("任务数量不能大于之前的数量！");
		$("#taskNum").val($("#lastTaskNum").val());
	}
}