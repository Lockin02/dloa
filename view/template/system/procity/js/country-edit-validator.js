$(document).ready(function (){

	var url = "?model=system_procity_country&action=checkRepeat";
	$("#countryCode").ajaxCheck({
		url : url,
		alertText : "* ���ұ���Ѵ���",
		alertTextOk : "* ����"
	});

	$("#countryName").ajaxCheck({
		url : url,
		alertText : "* ���������Ѵ���",
		alertTextOk : "* ����"
	});


})