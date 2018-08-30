$(document).ready(function() {
	$("#TO_NAME").yxselect_user({
		mode : 'check',
		hiddenId : 'defaultUserId',
		formCode : 'defaultUserName'
	});
	$("#ADDNAMES").yxselect_user({
		mode : 'check',
		hiddenId : 'ccUserId',
		formCode : 'ccUserName'
	});
	validate({
		"costBack_v": {
			required: true
		},
		"changeReason": {
			required: true
		},
		"auditDate": {
			required: true
		}
	});
});

//计算
function calMoney() {
	var actualCost = $("#actualCost").val();
	var costBack = $("#costBack").val();
	if(costBack*1 > actualCost*1){
		alert('退票金额不能大于机票总金额');
		setMoney('costBack','');
		return false;
	}
	if (costBack != "") {
		var feeBack = accSub(actualCost, costBack,2);
		setMoney('feeBack',feeBack);
	}
}