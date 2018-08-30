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

function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=add";
	}
}

function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit";
	}
}