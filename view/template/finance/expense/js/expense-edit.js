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
	
	//所属板块缓存
	moduleArr = getData('HTBK');
	//分摊金额合计初始化
	countAllCostshare();

	//设置title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());

	//初始话部门检查 - 提交审批按钮
	initSubButton();
});