$(document).ready(function() {
	var param = {
    	'mainId' : $("#id").val()
    };
	$("#noticeequ").yxeditgrid({
		objName : 'withdraw[items]',
		url : '?model=stock_withdraw_noticeequ&action=listJson',
		param : param,
		title : '�����嵥',
		type : 'view',
		colModel : [{
			name : 'productCode',
			display : '���ϱ��'
		},{
			name : 'productName',
			display : '��������'
		},{
			name : 'productModel',
			display : '�ͺ�/�汾'
		},{
			name : 'number',
			display : '����'
		},{
			name : 'executedNum',
			display : '���������'
		},{
			name : 'remark',
			display : '��ע'
		}]
	})
})