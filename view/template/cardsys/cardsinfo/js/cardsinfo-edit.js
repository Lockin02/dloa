$(document).ready(function() {

	$("#openerName").yxselect_user({
		hiddenId : 'openerId'
	});

	validate({
		"packageType" : {
			required : true
		},
		"ratesOf" : {
			required : true
		},
		"cardNo" : {
			required : true,
			length : [0,100]
		},
		"pinNo" : {
			required : true
		},
		"openDate" : {
			custom : ['date']
		},
		"openerName" : {
			required : true
		}
	});
 })