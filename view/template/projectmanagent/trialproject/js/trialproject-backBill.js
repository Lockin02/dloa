$(function(){
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	/**
	*��֤��Ϣ
	*/
	validate({
		"TO_NAME" : {
			required : true
		}
	});
});