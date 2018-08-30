$(document).ready(function() {
		var url = "?model=hr_contract_contract&action&action=checkRepeat";

		validate({
					"userName" : {
						required : true
					}
	 		});

	//员工
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		formCode : 'userName',
		event : {
			select : function(e,row){
							$.ajax({
							    type: "POST",
							    url: "?model=hr_personnel_personnel&action=getPersonnelSimpleInfo_d",
							    data: {"userAccount" : row.val },
							    async: false,
							    success: function(data){
							   		if(data != ""){
							   			var dataObj = eval("(" + data +")");
							   			$("#userNo").val(dataObj.userNo);
							   			$("#jobName").val(dataObj.jobName);
							   			$("#jobId").val(dataObj.jobId);
							   			$("#trialBeginDate").val(dataObj.entryDate);
							   			$("#trialEndDate").val(dataObj.becomeDate);
							   		}
								}
							});
					}
		}
	});
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