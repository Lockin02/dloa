//计算总费用进度
function calBudgetAll(){
	var newBudgetField = $("#newBudgetField").val();
	var newBudgetOther = $("#newBudgetOther").val();
	var newBudgetOutsourcing = $("#newBudgetOutsourcing").val();

	var newBudgetAll = accAdd(newBudgetField,newBudgetOther,2);
	newBudgetAll = accAdd(newBudgetAll,newBudgetOutsourcing,2);

	setMoney('newBudgetAll',newBudgetAll);
}

//提交审批
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add";
	}
}

//编辑时提交审批
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit";
	}
}

$(document).ready(function(){
	/**
	 * 验证信息
	 */
	validate({
		"planEndDate" : {
			required : true
		},
		"newBudgetOther_v" : {
			required : true
		},
		"newBudgetField_v" : {
			required : true
		},
		"changeDescription" : {
			required : true
		}
	});
});
