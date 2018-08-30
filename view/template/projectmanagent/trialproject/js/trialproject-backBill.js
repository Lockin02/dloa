$(function(){
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	/**
	*验证信息
	*/
	validate({
		"TO_NAME" : {
			required : true
		}
	});
});