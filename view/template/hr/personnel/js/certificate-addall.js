$(document).ready(function() {

   validate({
				"certificates" : {
					required : true,
					length : [0,200]
				},
				"level" : {
					required : true
				}
			});
	$("#userName").yxselect_user({
		hidden: 'userAccount',
		userNo : 'userNo',
		formCode : 'userName'
	});
 })