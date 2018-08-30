$(document).ready(function() {

	//责任人
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserId'
	});

	validate({
		"urgentLevelCode" : {
			required : true
		},
		"chargeUserName" : {
			required : true
		},
		"planStartDate" : {
			required : true
		},
		"planEndDate" : {
			required : true
		}
	});
});

//检验数据
function checkData() {
	var planStartDate = $("#planStartDate").val();
	var planEndDate = $("#planEndDate").val();
	if (planStartDate && planEndDate && planEndDate < planStartDate) {
		alert('计划开始时间不能大于计划结束时间！');
	}
}