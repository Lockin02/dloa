$(document).ready(function (){
	//��֤������
	validate({
		"provinceName" : {
			required : true
		}
	});
	var url = "?model=system_procity_city&action=checkRepeat";
	$("#cityName").ajaxCheck({
		url : url,
		alertText : "* ���������Ѵ���",
		alertTextOk : "* ����"
	});

	$("#cityCode").ajaxCheck({
		url : url,
		alertText : "* ���б���Ѵ���",
		alertTextOk : "* ����"
	});

})