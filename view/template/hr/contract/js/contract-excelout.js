$(document).ready(function() {
	$("#userName").yxselect_user({
		userNo : 'userNo'
	});

	$("#jobName").yxcombogrid_position({
		hiddenId: 'jobId',
		width : '400px'
	});
})