$(document).ready(function() {
	//�����
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'trialplan'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"managerName" : {
			required : true
		}
	});
})

//����֤
function checkform(){
	var uploadfileList = strTrim($("#uploadfileList").html());
	var taskType = $("#taskType").val();
	if(taskType == 'HRSYRW-05'){
		if(uploadfileList == ""){
			alert('���������������ϴ�����');
			return false;
		}
	}
	return true;
}