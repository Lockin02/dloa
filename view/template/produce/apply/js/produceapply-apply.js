$(document).ready(function () {
	$("#items").yxeditgrid({
		objName: 'produceapply[items]',
		url: '?model=contract_contract_equ&action=listJsonWith',
		param: {
			equIds: $("#equIds").val()
		},

		isAdd: false,

		colModel: [{
			display: 'Դ���嵥Id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '��ƷId',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '��������',
			name: 'proType',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��������Id',
			name: 'proTypeId',
			type: 'hidden'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��������',
			name: 'productName',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '����Id',
			name: 'productId',
			type: 'hidden'
		}, {
			display: '����ͺ�',
			name: 'productModel',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��λ����',
			name: 'unitName',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��������',
			name: 'number',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '�������',
			name: 'inventory',
			type: 'statictext'
		}, {
			display: '���Ʒ<br>�������',
			name: 'bcpkcsl',
			type: 'statictext',
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct" +
					"&code=" + row.productCode +
					"\",1)'><img title='�����Ϣ' src='js/jquery/images/grid/view.gif' align='absmiddle'/></a>";
			}
		}, {
			display: '���´�����',
			name: 'issuedProNum',
			type: 'statictext',
			isSubmit: true
		}, {
			display: '��������',
			name: 'produceNum',
			tclass: 'txtshort',
			process: function ($input, rowData) {
				var produceNum = rowData.number - rowData.issuedProNum;
				$input.change(function () {
					if ($(this).val() > produceNum) {
						alert('���벻�ܴ��ڣ���������-���´�������');
						$input.val(produceNum).focus();
					}
				});
				$input.val(produceNum);
			},
			validation: {
				custom: ['onlyNumber']
			}
		}, {
			display: '��������ʱ��',
			name: 'planEndDate',
			tclass: 'txtshort',
			type: 'date',
			readonly: true
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			rows: 2
		}, {
			display: 'license',
			name: 'license',
			type: 'hidden'
		}]
	});
});