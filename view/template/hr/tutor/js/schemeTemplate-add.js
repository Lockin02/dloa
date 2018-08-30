$(document).ready(function() {
	validate({
		"appraisal" : {
			required : true
		},
		"coefficient" : {
			required : true,
			custom : ['percentageNum']
		}
	});
})