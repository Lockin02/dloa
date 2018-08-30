$(document).ready(function () {

	var productObj = $("#productItem")
	productObj.yxeditgrid({
		objName: 'picking[item]',
		url: '?model=produce_plan_pickingitem&action=listJson',
		param: {
			pickingId: $("#id").val(),
			dir: 'ASC'
		},
		isAdd: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '�����ƻ�����',
			name: 'planCode',
			type: 'statictext',
			width: 120
		}, {
			display: '��ͬ���',
			name: 'relDocCode',
			width: 120,
			type: 'statictext'
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			type: 'statictext',
			width: 100
		}, {
			display: '��������',
			name: 'productName',
			type: 'statictext',
			width: 250
		}, {
			display: '����ͺ�',
			name: 'pattern',
			width: 100,
			type: 'statictext'
		}, {
			display: '��λ',
			name: 'unitName',
			width: 80,
			type: 'statictext'
		}, {
			display: '���豸��',
			name: 'JSBC',
			type: 'statictext',
			width: 80
		}, {
			display: '�����Ʒ',
			name: 'KCSP',
			type: 'statictext',
			width: 80
		}, {
			display: '������',
			name: 'SCC',
			type: 'statictext',
			width: 80
		}, {
			display: '��������',
			name: 'applyNum',
			tclass: 'txtmin',
			validation: {
				custom: ['onlyNumber']
			}
		}, {
			display: '�ƻ���������',
			name: 'planDate',
			tclass: 'txtshort',
			type: 'date',
			readonly: true,
			validation: {
				required: true
			}
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			rows: 2,
			width: 100
		}]
	});
});

//ֱ���ύ
function toSubmit() {
	document.getElementById('form1').action = "?model=produce_plan_picking&action=edit&actType=audit";
}