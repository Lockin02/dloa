$(document).ready(function(){
	/**
	 * ��֤��Ϣ
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
