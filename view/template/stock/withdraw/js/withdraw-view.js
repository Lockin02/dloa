$(document).ready(function() {
	$("#withdrawequ").yxeditgrid({
		objName : 'withdraw[items]',
		url : '?model=stock_withdraw_equ&action=listJson',
		param : {
	    	'mainId' : $("#id").val()
	    },
		title : '�����嵥',
		type : 'view',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'Դ���嵥id',
			name : 'contEquId',
			type : 'hidden'
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '���ϱ��'
		},{
			name : 'productName',
			display : '��������'
		},{
			name : 'productModel',
			display : '�ͺ�/�汾'
		},{
			name : 'stockName',
			display : '���ϲֿ�',
			width : 80
		},{
			name : 'number',
			display : '����',
			width : 60
		},{
			name : 'qualityNum',
			display : '��������',
			width : 60
		},{
			name : 'qPassNum',
			display : '�ϸ�����',
			width : 60
		},{
			name : 'qBackNum',
			display : '���ϸ�����',
			width : 60
		}, {
			name : 'executedNum',
			display : '���ջ�����',
			width : 60
		},{
			name : 'compensateNum',
			display : '�⳥����',
			width : 60
		},{
			name : 'remark',
			display : '��ע'
		}]
	})
})