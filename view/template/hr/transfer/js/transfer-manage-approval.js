$(document).ready(function() {

	//验证必填项
	validate({
		"reportDate" : {
			required : true
		},
		"handoverDate" : {
			required : true
		},
		"handoverRemark" : {
			required : true
		}
	});


})