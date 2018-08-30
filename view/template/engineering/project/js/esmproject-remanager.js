

$(document).ready(function(){

	//单选项目经理
	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

	/**
	 * 验证信息
	 */
	validate({
		"managerName" : {
			required : true
		}
	});
});


function checkform(){
	if($("#orgManagerId").val() == $("#managerId").val()){
		alert('原项目经理和新项目经理不能是同一人');
		return false;
	}
	return true;
}