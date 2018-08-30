$(document).ready(function () {
	var productObj = $("#productOut");
	productObj.yxeditgrid({
		objName: 'picking[back]',
		url: '?model=produce_plan_pickingitem&action=listJson',
		param: {
			idArr: $("#idStr").val(),
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
			type: 'statictext',
			width: 120
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			type: 'statictext',
			isSubmit: true,
			width: 80
		}, {
			display: '��������',
			name: 'productName',
			type: 'statictext',
			width: 200,
			isSubmit: true
		}, {
			display: '����ͺ�',
			name: 'pattern',
			type: 'statictext',
			width: 90,
			isSubmit: true
		}, {
			display: '��λ',
			name: 'unitName',
			type: 'statictext',
			width: 80,
			isSubmit: true
		}, {
			display: '������',
			name: 'SCC',
			type: 'statictext',
			width: 60
		}, {
			display: '������������',
			name: 'applyNum',
			type: 'statictext',
			width: 70
		}, {
			display: '��������',
			name: 'realityNum',
			type: 'statictext',
			width: 70
		}, {
			display: '������������',
			name: 'proBackNum',
			tclass: 'txtshort',
			validation: {
				custom: ['onlyNumber']
			},
			process: function ($input, rowDate) {
				var validNum = parseInt(rowDate.realityNum);
				$input.val(validNum).blur(function () {
					if (accSub(validNum, $(this).val()) < 0 || $(this).val() == 0) {
						alert('��������������Ч��');
						$(this).val(validNum).focus();
					}
				});
			}
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			rows: 2,
			width: 150
		}]
	});

	validate({});
});

//����У��
function checkData() {
	var productObj = $("#productOut").yxeditgrid("getCmpByCol", "proBackNum");
	var result = true;
	if (productObj.length == 0) {
		alert('û���������ϵ����ϣ�');
		return false;
	} else if (productObj.length > 0) {
		productObj.each(function () {
			if (this.value == 0 || this.value < 0) {
				alert('��������������Ч��');
				$(this).focus();
				result = false;
				return false; //�˳�ѭ��
			}
		});
	}

	return result;
}