$(document).ready(function() {
validate({
				"hospital" : {
					required : true
				},
				"checkDate" : {
					required : true
				},
				"checkResult" : {
					required : true
				},
				"userName" : {
					required : true
				}
			});
$("#userName").yxselect_user({
		userNo : 'userNo',
		formCode : 'userName'
	});
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })