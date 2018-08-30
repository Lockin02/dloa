$(document).ready(function (){

	var url = "?model=system_procity_country&action=checkRepeat";
	$("#countryCode").ajaxCheck({
		url : url,
		alertText : "* 国家编号已存在",
		alertTextOk : "* 可用"
	});

	$("#countryName").ajaxCheck({
		url : url,
		alertText : "* 国家名称已存在",
		alertTextOk : "* 可用"
	});


})