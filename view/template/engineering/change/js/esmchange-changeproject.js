//�����ܷ��ý���
function calBudgetAll(){
	var newBudgetField = $("#newBudgetField").val();
	var newBudgetOther = $("#newBudgetOther").val();
	var newBudgetOutsourcing = $("#newBudgetOutsourcing").val();

	var newBudgetAll = accAdd(newBudgetField,newBudgetOther,2);
	newBudgetAll = accAdd(newBudgetAll,newBudgetOutsourcing,2);

	setMoney('newBudgetAll',newBudgetAll);
}

//�ύ����
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add";
	}
}

//�༭ʱ�ύ����
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=edit";
	}
}

$(document).ready(function(){
	/**
	 * ��֤��Ϣ
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
