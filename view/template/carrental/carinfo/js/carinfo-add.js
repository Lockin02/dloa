$(document).ready(function() {

	/**
	 * 验证信息
	 */
	validate({
		"carType" : {
			required : true
		},
		"brand" : {
			required : true
		},
		"displacement" : {
			required : true
		},
		"owners" : {
			required : true
		},
		"driver" : {
			required : true
		},
		"linkPhone" : {
			required : true
		}
	});

	var url = "?model=carrental_carinfo_carinfo&action=checkRepeat";
	$("#carNo").ajaxCheck({
		url : url,
		alertText : "* 该号码已存在",
		alertTextOk : "* 该号码可用"
	});
})