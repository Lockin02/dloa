$(function() {
	// ��Ӧ��
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
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