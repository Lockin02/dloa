$(document).ready(function() {
	//Ô±¹¤
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'userName'
	});
})