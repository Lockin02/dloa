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

	//��ʼ�����ż�� - �ύ������ť
	initSubButton();
});