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
});

//检查验证
function editCheck(){
	var orgMoney = $("#orgMoney").val()*1;
	var countMoney = $("#countMoney").val()*1;

	//判断人员是否离职
	var responseText = $.ajax({
		url : 'index1.php?model=hr_personnel_personnel&action=getPersonnelInfo',
		type : "POST",
		data : "userAccount=" + $("#CostMan").val(),
		async : false
	}).responseText;
	var personInfo = eval("(" + responseText + ")");

	try{
		personInfo;
	}catch(e){
		personInfo.employeesState = 'YGZTLZ';
	}

	if(orgMoney != countMoney && personInfo.employeesState != 'YGZTLZ'){
		if(!confirm('单据金额发生变更，单据需要返回到报销人进行确认，确定保存此修改吗？')){
			return false;
		}else{
			$("#thisAuditType").val('needConfirm');
		}
	}

	return checkForm();
}