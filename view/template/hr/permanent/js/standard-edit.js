$(document).ready(function() {
	var url = "?model=hr_permanent_standard&action=checkRepeat";
	if ($("#standard").val()) {
		url += "&id=" + $("#id").val();
	}
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