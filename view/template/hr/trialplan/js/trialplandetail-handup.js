$(document).ready(function() {
	//审核人
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'trialplan'
	});

	/**
	 * 验证信息
	 */
	validate({
		"managerName" : {
			required : true
		}
	});
})

//表单验证
function checkform(){
	var uploadfileList = strTrim($("#uploadfileList").html());
	var taskType = $("#taskType").val();
	if(taskType == 'HRSYRW-05'){
		if(uploadfileList == ""){
			alert('附件类的任务必须上传附件');
			return false;
		}
	}
	return true;
}