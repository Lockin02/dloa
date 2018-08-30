$(document).ready(function (){
	//验证必填项
	validate({
		"provinceName" : {
			required : true
		}
	});
	var url = "?model=system_procity_city&action=checkRepeat";
	$("#cityName").ajaxCheck({
		url : url,
		alertText : "* 城市名称已存在",
		alertTextOk : "* 可用"
	});

	$("#cityCode").ajaxCheck({
		url : url,
		alertText : "* 城市编号已存在",
		alertTextOk : "* 可用"
	});

})