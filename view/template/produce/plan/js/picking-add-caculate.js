$(document).ready(function () {
	var data = eval("(" + $("#productJson").text() + ")");
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName: 'picking[item]',
		data: data,
		isAdd : false,
		colModel: [{
			display: '�ƻ�Id',
			name: 'planId',
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
		},{
			display: '�������κ�',
			name: 'productionBatch',
			width: 120,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			width: 100,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��������',
			name: 'productName',
			width: 250,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '����ͺ�',
			name: 'pattern',
			width: 90,
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��λ',
			name: 'unitName',
			type: 'statictext',
			width: 60,
			isSubmit: true
		}, {
			display: '���豸��',
			name: 'JSBC',
			type: 'statictext',
			width: 60
		}, {
			display: '�����Ʒ',
			name: 'KCSP',
			type: 'statictext',
			width: 60
		}, {
			display: '������',
			name: 'SCC',
			type: 'statictext',
			width: 60
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

	//��֤��������
	validate({});
});

//���ôӱ�ƻ���������
function setPlanDate(e) {
	if (e.value != '') {
		var planDateObjs = $("#productItem").yxeditgrid('getCmpByCol', 'planDate');
		planDateObjs.each(function (k, v) {
			if (this.value == '') {
				$(this).val(e.value);
			}
		});
	}
}

// ����У��
function checkData() {
	var planDateObjs = $("#productItem").yxeditgrid('getCmpByCol', 'planDate');
	if (planDateObjs.length == 0) {
		alert('û�пɲ�����¼��');
		return false;
	}
	return true;
}

//ֱ���ύ
function toSubmit() {
	document.getElementById('form1').action = "?model=produce_plan_picking&action=addCaculate&actType=audit";
}