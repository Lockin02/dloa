$(document).ready(function() {

	validate({
		"personName" : {
			required : true
		},
		"phone" : {
			required : true
		},
		"specialty" : {
			required : true
		}
	});

	var url = "?model=engineering_tempperson_tempperson&action=checkRepeat";
	$("#idCardNo").ajaxCheck({
		url : url,
		alertText : "* ���֤���Ѵ���",
		alertTextOk : "* ���֤�ſ���"
	});
})