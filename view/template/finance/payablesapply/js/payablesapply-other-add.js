$(function() {
	countAll();

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	$("#feeDeptName").yxselect_dept({
		hiddenId : 'feeDeptId',
		unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : '',
	});

	//付款信息
	if($("#payFor").val() != 'FKLX-03' && $("#sourceType").val() == 'YFRK-02' && $("#isShare").val() == "1")
		initPayDetailGrid();
	
	//是否开据发票
	if($("#isInvoice").val() == 1){
		$("#isInvoiceYes").attr('checked',true);
	}else if($("#isInvoice").val() == 0){
		$("#isInvoiceNo").attr('checked',true);
	}
	//是否工资类付款
	if($("#isSalary").val() == 1){
		$("#isSalaryYes").attr('checked',true);
	}else{
		$("#isSalaryNo").attr('checked',true);
	}
});

//计算总金额
function countAll() {
	var invnumber = $('#coutNumb').val();
	var allAmount = 0;
	for (var i = 1; i <= invnumber; i++) {
		var thisMoney = $("#money" + i).val() * 1;
		if (thisMoney != 0 || thisMoney != "") {
			allAmount = accAdd(allAmount, thisMoney, 2);
		}
	}
	$("#payMoney").val(allAmount);
	$("#payMoney_v").val(moneyFormat2(allAmount));
}