$(function() {
	//���ù���
	$("#costbelong").costbelong({
		objName : 'expense',
		url : '?model=finance_expense_expense&action=ajaxGet',
		data : {"id" : $("#id").val()},
		actionType : 'edit'
	});

	//��Ʊ���ͻ���
	billTypeArr = getBillType();

	//������黺��
	moduleArr = getData('HTBK');
	//��̯���ϼƳ�ʼ��
	countAllCostshare();
	
	//����title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());
});

//�����֤
function editCheck(){
	var orgMoney = $("#orgMoney").val()*1;
	var countMoney = $("#countMoney").val()*1;

	//�ж���Ա�Ƿ���ְ
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
		if(!confirm('���ݽ��������������Ҫ���ص������˽���ȷ�ϣ�ȷ��������޸���')){
			return false;
		}else{
			$("#thisAuditType").val('needConfirm');
		}
	}

	return checkForm();
}