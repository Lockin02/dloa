$(document).ready(function() {
//	//Ô±¹¤
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		isGetDept : [true, "deptId", "deptName"],
//		formCode : 'userName'
//	});
	$("#projectManager").yxselect_user({
		hiddenId : 'projectManagerId',
		formCode : 'projectManager'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	$("#jobName").yxcombogrid_jobs({
		hiddenId : 'jobId'
	});

			validate({
					"userName" : {
						required : true
					},
					"beginDate" : {
						required : true
					},
					"closeDate" : {
						required : true
					},
					"jobName" : {
						required : true
					}
	 		});


});