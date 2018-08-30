$(function() {
	//费用归属
	$("#costbelong").costbelong({
		objName : 'expense',
		url : '?model=finance_expense_expense&action=ajaxGet',
		data : {"id" : $("#id").val()},
		actionType : 'edit'
	});

	//发票类型缓存
	billTypeArr = getBillType();

	//设置title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());

	//初始话部门检查 - 提交审批按钮
	initSubButton();
});