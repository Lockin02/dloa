$(document).ready(function() {

	//内训/外训
	$('select[name="trainingrecords[isInner]"] option').each(function() {
		if( $(this).val() == $("#isInnerSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//《外请讲师授课评估表》提交状态
	$('select[name="trainingrecords[isUploadTA]"] option').each(function() {
		if( $(this).val() == $("#isUploadTASelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//《培训反馈及改进计划》提交状态
	$('select[name="trainingrecords[isUploadTU]"] option').each(function() {
		if( $(this).val() == $("#isUploadTUSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event : {
			selectReturn : function(e,row){
				$("#jobName").val("");
				$("#jobId").val('');
				$("#jobName").yxcombogrid_position("remove");
				//职位选择
				$("#jobName").yxcombogrid_position({
					hiddenId : 'jobId',
					isShowButton : false,
					width:350,
					gridOptions : {
						param:{deptId:row.dept.id}
					}
				});
				$("#jobName").yxcombogrid_position("show");
			}
		}
	});

	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		isShowButton : false,
		width : 350,
		gridOptions : {
			param : {
				deptId : $("#deptId").val()
			}
		}
	});

	$("#orgDeptName").yxselect_dept({
		hiddenId : 'orgDeptId'
	});
});

function trainingDate(){
	var comeDay = $("#beginDate").val();
	var endDay = $("#endDate").val();
	comeDay = comeDay.replace(/-/g, '/');
	endDay = endDay.replace(/-/g, '/');
	var b = new Date(endDay);
	var c = new Date(comeDay);
	endDay = b.getTime();
	comeDay = c.getTime();
	if (endDay < comeDay) {
		alert("你输入的日期小于培训开始时间");
		$("#comeDate").val("");
	} else {
		var days = endDay - comeDay;
		$("#duration").val( parseInt(days / (1000 * 60 * 60 * 24)) + 1 + "天");
	}
}