$(document).ready(function() {

	validate({
				"relationName" : {
					required : true,
					length : [0,200]
				}
			});
	$("#userName").yxselect_user({
		hidden: 'userAccount',
		userNo : 'userNo',
		formCode : 'userName'
	});
	
    });