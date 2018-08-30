var objTypeArr = [];// 业务类型数组

$(function() {

	// 单选客户
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		isGetDept : [true, "deptId", "deptName"]
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	$("#feeDeptName").yxselect_dept({
		hiddenId : 'feeDeptId',
        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
	});
});