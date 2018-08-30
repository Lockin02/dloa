$(document).ready(function() {

	var url = "?model=hr_basicinfo_socialplace&action=checkRepeat";
	if ($("#id").val()) {
		url += "&id=" + $("#id").val();
	}
	$("#socialCity").ajaxCheck({
		url : url,
		alertText : "* 该购买地已存在",
		alertTextOk : "* 可用"
	});
	//验证必填项
	validate({
//		"socialCity" : {
//			required : true
//		}
	});
   })
