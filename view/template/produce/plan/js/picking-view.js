$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		url : '?model=produce_plan_pickingitem&action=listJson',
		param : {
			pickingId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�����ƻ�����',
			name : 'planCode',
			width : 120
		},{
			display : '��ͬ���',
			name : 'relDocCode',
			width : 120
		},{
			display: '�������κ�',
			name: 'productionBatch',
			width: 120
		}, {
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
			display : '��������',
			name : 'realityNum',
			width : 50,
			process : function (v ,row) {
				if (row.proOutNum > 0) {
					return '<span class="blue">' + v + '</span>';
				} else {
					return v;
				}
			}
		},{
			display : '�ƻ�<br>��������',
			name : 'planDate',
			width : 80
		},{
			display : 'ʵ��<br>��������',
			name : 'realityDate',
			width : 80
		},{
			display : '��ע',
			name : 'remark',
			width : 150,
			align : 'left'
		}]
	});
});