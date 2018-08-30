$(document).ready(function() {
	$("#items").yxeditgrid({
		objName : 'produceapply[items]',
		url : '?model=produce_apply_produceapplyitem&action=listJson',
		param : {
			mainId : $("#id").val(),
			isTemp : 0
		},

		isAddAndDel : false,

		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�����嵥id',
			name : 'relDocItemId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode',
			type : 'statictext'
		},{
			display : '��������',
			name : 'productName',
			type : 'statictext'
		},{
			display : '����ͺ�',
			name : 'pattern',
			type : 'statictext'
		},{
			display : '��λ����',
			name : 'unitName',
			type : 'statictext'
		},{
			display : '��������',
			name : 'needNum',
			type : 'statictext'
		},{
			display : '���´�����',
			name : 'exeNum',
			type : 'statictext'
		},{
			display : '��������',
			name : 'produceNum',
			tclass : 'txtshort',
			process : function($input ,rowData) {
				$input.change(function () {
					if (parseInt($(this).val()) > parseInt(rowData.needNum)) {
						alert('�����������ܴ�������������');
						$input.val(rowData.needNum).focus();
					} else if (parseInt($(this).val()) < parseInt(rowData.exeNum)) {
						alert('������������С�����´�������');
						$input.val(rowData.exeNum).focus();
					}
				});
			},
			validation : {
				custom : ['onlyNumber']
			}
		},{
			display : '��������ʱ��',
			name : 'planEndDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true
		},{
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			rows : 2
		}]
	});

	validate({
		"changeReason" : {
			required : true
		}
	});
});