$(document).ready(function() {
	validate({
		"recruitManName" : {
			required : true
		}
//		,
//		"assistManName" : {
//			required : true
//		}
	});

	$("#recruitManName").yxselect_user({
		hiddenId : 'recruitManId',
		formCode:'recruitManName'
	});

	$("#assistManName").yxselect_user({
		mode : 'check',
		hiddenId : 'assistManId',
		formCode:'assistManName'
	});
});

function check(){
	if((!$("#assistManId")[0].value))
		return true;
	var arr = $("#assistManId")[0].value.split(",");
	for(var i = 0 ,j = arr.length; i < j; i++) {
		if(arr[i] == $("#recruitManId")[0].value)  {
			alert("负责人不能作为协助人员");
			return false;
		}
	}
	return true;
}
