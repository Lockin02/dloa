$(document).ready(function() {

	//Ա��
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		formCode : 'userName'
	});

	//������
	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

})