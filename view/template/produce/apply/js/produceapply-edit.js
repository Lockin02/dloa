$(document).ready(function () {
	$("#items").yxeditgrid({
		objName: 'produceapply[items]',
		url: '?model=produce_apply_produceapplyitem&action=listJson',
		param: {
			mainId: $("#id").val(),
			isTemp: 0
		},

		isAdd: false,

		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '�����嵥id',
			name: 'relDocItemId',
			type: 'hidden'
		}, {
			display: '��������',
			name: 'proType',
			type: 'statictext'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			type: 'statictext'
		}, {
			display: '��������',
			name: 'productName',
			type: 'statictext'
		}, {
			display: '����ͺ�',
			name: 'pattern',
			type: 'statictext'
		}, {
			display: '��λ����',
			name: 'unitName',
			type: 'statictext'
		}, {
			display: '��������',
			name: 'needNum',
			type: 'statictext'
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
			name: 'exeNum',
			type: 'statictext'
		}, {
			display: '��������',
			name: 'produceNum',
			tclass: 'txtshort',
			process: function ($input, rowData) {
				var produceNum = rowData.needNum - rowData.exeNum;
				$input.change(function () {
					if ($(this).val() > produceNum) {
						alert('���벻�ܴ��ڣ���������-���´�������');
						$input.val(produceNum).focus();
					}
				});
				$input.val(rowData.produceNum);
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
		}]
	});
});