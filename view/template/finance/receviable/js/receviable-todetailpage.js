$(function() {
	// π©”¶…Ã
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
});