$(document).ready(function() {
	validate({
				"entryDate" : {
					required : true
				}
//				,
//				"TO_NAME" : {
//					required : true
//				}
			});
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode:'check'
	});
});