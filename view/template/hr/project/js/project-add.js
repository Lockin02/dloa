$(document).ready(function() {
	//Ô±¹¤
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		isGetDept : [true, "deptId", "deptName"],
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
							   		}
								}
							});
					}
		}
	});

	$("#projectManager").yxselect_user({
		hiddenId : 'projectManagerId',
		formCode : 'projectManager'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

//	$("#jobName").yxcombogrid_jobs({
//		hiddenId : 'jobId'
//	});

	validate({
		"userName" : {
			required : true
		},
		"beginDate" : {
			required : true
		},
		"closeDate" : {
			required : true
		}
	});

});