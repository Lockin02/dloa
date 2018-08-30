$(document).ready(function() {
	//
//		var url = "?model=hr_contract_contract&action&action=checkRepeat";
//		$("#conNo").ajaxCheck({
//			url : url,
//			alertText : "* 该合同编号已存在",
//			alertTextOk : "* 合同编号可用"
//		});
		validate({
					"userName" : {
						required : true
					},
//					"beginDate" : {
//						required : true
//					},
//					"closeDate" : {
//						required : true
//					},
					"jobName" : {
						required : true
					}
	 		});

	//员工
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		userNo : 'userNo',
//		formCode : 'userName'
//	});
//	$("#jobName").yxcombogrid_jobs({
//		hiddenId : 'jobId'
//	});
    // 合同类型
	conTypeArr = getData('HRHTLX');
	addDataToSelect(conTypeArr, 'conType');
	// 合同状态
	conStateArr = getData('HRHTZT');
	addDataToSelect(conStateArr, 'conState');
	// 合同次数
	conNumArr = getData('HRHTCS');
	addDataToSelect(conNumArr, 'conNum');
});

function checkForm() {
//	if ($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件") {
//		alert('请上传附件！');
//		return false;
//	}
	return true;
}
    //开始时间与结束时间差验证
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("开始时间不能比结束时间晚！");
		$t.value = "";
		return false;
	}
}

    //试用开始时间与结束时间差验证
function trialTimeCheck($t){
	var s = plusDateInfo('trialBeginDate','trialEndDate');
	if(s < 0) {
		alert("试用开始时间不能比试用结束时间晚！");
		$t.value = "";
		return false;
	}
}