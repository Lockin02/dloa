$(document).ready(function() {


	$("#preBelongDeptName").yxselect_dept({
		hiddenId : "preBelongDeptId"
	});

	$("#afterBelongDeptName").yxselect_dept({
		hiddenId : "afterBelongDeptId"
	});

	$("#userName").yxselect_user({
		userNo : 'userNo'
	})


	$("#afterJobName").yxcombogrid_position({
		width : 350,
		hiddenId : 'afterJobId'
	});

	$("#preJobName").yxcombogrid_position({
		width : 350,
		hiddenId : 'preJobId'
	});


})