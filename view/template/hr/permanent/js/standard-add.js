$(document).ready(function() {
	var url = "?model=hr_permanent_standard&action=checkRepeat";
	$("#standard").ajaxCheck({
		url : url,
		alertText : "* 该名称已存在",
		alertTextOk : "* 该名称可用"
	});
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })