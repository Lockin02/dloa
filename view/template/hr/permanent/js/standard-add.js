$(document).ready(function() {
	var url = "?model=hr_permanent_standard&action=checkRepeat";
	$("#standard").ajaxCheck({
		url : url,
		alertText : "* �������Ѵ���",
		alertTextOk : "* �����ƿ���"
	});
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })