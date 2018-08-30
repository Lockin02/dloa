$(document).ready(function() {

	validate({
		"company" : {
			required : true,
			length : [0,200]
		},
		"position" : {
			required : true,
			length : [0,200]
		},
		"userNo" : {
			required : true
		}
	});

	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo'
	});
});




    //开始时间与结束时间差验证
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("开始时间不能比结束时间晚！");
		$t.value = "";
		return false;
	}
}