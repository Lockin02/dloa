$(function() {
	//���ù���
	$("#costbelong").costbelong({
		objName : 'expense',
		url : '?model=finance_expense_expense&action=ajaxGet',
		data : {"id" : $("#id").val()},
		actionType : 'view'
	});
	//�ص�����
	$.scrolltotop({className: 'totop'});

	//��Ⱦ�ӳٱ�������
	if($("#isLate").val() == "1"){
		$("#mainTitle").css('color','red').html('���������ڱ�����');
	}
});