$(document).ready(function() {
	validate({
		"certificates" : {
			required : true,
			length : [0,200]
		},
		"certifying" : {
			required : true,
			length : [0,200]
		},
		"userNo" : {
			required : true
		}
	});

	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo'
	});
});