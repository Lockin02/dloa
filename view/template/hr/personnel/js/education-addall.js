$(document).ready(function() {
	validate({
		"organization" : {
			required : true,
			length : [0, 100]
		},
		"content" : {
			required : true,
			length : [0, 200]
		}
	});
	$("#userName").yxselect_user({
		userNo : 'userNo',
		formCode : 'userName'
	});
});

//开始时间与结束时间差验证
function timeCheck($t) {
	var s = plusDateInfo('beginDate', 'closeDate');
	if (s < 0) {
		alert("开始时间不能比结束时间晚！");
		$t.value = "";
		return false;
	}
}