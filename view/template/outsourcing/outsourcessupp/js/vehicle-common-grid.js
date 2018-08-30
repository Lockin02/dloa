$(document).ready(function() {
	validate({
		"rentPrice" : {
			required : true,
			custom : ['percentageNum']
		},
		"suppName" : {
			required : true
		},
		"carNumber" : {
			required : true
		},
		"phoneNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"idNumber" : {
			required : true
		}
	});
})