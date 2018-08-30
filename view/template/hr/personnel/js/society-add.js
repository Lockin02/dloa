$(document).ready(function() {

	validate({
		"relationName" : {
			required : true,
			length : [0,200]
		},
		"userNo" : {
			required : true
		},
		"information" : {
			required : true
		}
	});

	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo'
	});
});