$(document).ready(function(){
	/**
	 * 验证信息
	 */
	validate({
		"riskName" : {
			required : true,
			length : [0,100]
		},
		"solution" : {
			required : true,
			length : [0,300]
		}
	});
});
