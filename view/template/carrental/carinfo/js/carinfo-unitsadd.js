$(document).ready(function() {

	$.formValidator.initConfig({
		theme : "Default",
		submitOnce : true,
		formID : "form1",
		onError : function(msg, obj, errorlist) {
			alert(msg);
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"carType" : {
			required : true
		},
		"carNo" : {
			required : true
		},
		"limitedSeating" : {
			required : true

		}
	});
})