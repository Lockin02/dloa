$(document).ready(function() {

	$("#deptName").yxselect_dept({
		hiddenId: 'deptId'
	});


	$("#positionsName").yxcombogrid_jobs({
		hiddenId : 'positionsId',
		width : 280
	});
});