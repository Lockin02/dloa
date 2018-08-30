$(document).ready(function(){
	/**
	 * 验证信息
	 */
	validate({
		"problemType" : {
			required : true,
			length : [0,50]
		},
		"qualityProblem" : {
			required : true,
			length : [0,300]
		},
		"solution" : {
			required : true,
			length : [0,300]
		}
	});
});
