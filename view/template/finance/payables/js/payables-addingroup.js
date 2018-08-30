$(function(){
	var rowCount = $("#rowCount").val()*1;
	for(var i = 1 ;i<= rowCount ;i++){
		$("#mailman" + i).yxselect_user({
			mode : 'check',
			hiddenId : 'mailmanId' + i
		});
	}
})