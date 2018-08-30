$(document).ready(function() {

	//员工
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		formCode : 'userName'
	});

	//经办人
	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

})