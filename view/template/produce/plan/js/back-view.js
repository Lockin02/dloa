$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		url : '?model=produce_plan_backitem&action=listJson',
		param : {
			backId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode',
			width : 75
		},{
			display : '��������',
			name : 'productName',
			width : 250
		},{
			display : '����ͺ�',
			name : 'pattern',
			width : 90
		},{
			display : '��λ',
			name : 'unitName',
			width : 80
		},{
			display : '���豸��',
			name : 'JSBC',
			width : 50
		},{
			display : '�����Ʒ',
			name : 'KCSP',
			width : 50
		},{
			display : '������',
			name : 'SCC',
			width : 50
		},{
			display : '��������',
			name : 'applyNum',
			width : 50
		},{
			display : '�������',
			name : 'backNum',
			width : 50
		},{
			display : '��ע',
			name : 'remark',
			width : 150,
			align : 'left'
		}]
	});
});