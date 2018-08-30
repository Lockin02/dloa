$(document).ready(function() {
//	//员工
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		isGetDept : [true, "deptId", "deptName"],
//		formCode : 'userName'
//	});

			validate({
					"jobName" : {
						required : true
					}
	 		});



	$("#jobName").yxcombogrid_jobs({
		hiddenId : 'jobId'
	});
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