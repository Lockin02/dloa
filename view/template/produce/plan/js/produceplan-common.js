$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}

	//责任人
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserId'
	});

	//数量
	var planNum = $("#planNum").val();
	$("#planNum").blur(function () {
		if ($(this).val() > parseInt(planNum)) {
			alert('计划数量超过任务数量！');
			$(this).val(planNum);
		} else if ($(this).val() < 1) {
			alert('计划数量不能小于1！');
			$(this).val(planNum);
		}
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
		},
		"produceCode" : {
			required : true
		},
		"planNum" : {
			required : true,
			custom : ['onlyNumber']
		}
	});
});

//检验数据
function checkData() {
	var planStartDate = $("#planStartDate").val();
	var planEndDate = $("#planEndDate").val();
	if (planStartDate && planEndDate && planEndDate < planStartDate) {
		alert('计划开始时间不能大于计划结束时间！');
		return false;
	}
	if ($("#planNum").val() < 1) {
		alert('计划数量不能小于1！');
		return false;
	}
}