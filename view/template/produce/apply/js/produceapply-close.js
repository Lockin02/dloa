$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'produceapply[items]',
		url : '?model=produce_apply_produceapplyitem&action=listJson',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'state',
			display : '�Ƿ�ر�',
			type : 'checkbox',
			checkVal : 1,
			process : function ($input ,row) {
				if ($input.val() == 1) {
					$input.change(function () {
						alert('�������ѹرգ�');
						$(this).attr('checked' ,true);
					});
				} else {
					$input.change(function () {
						if ($(this).attr('checked')) {
							if (row.exeNum > 0) {
								if (window.confirm("���������´�����ȷ��Ҫ�رգ�")) {
									$(this).attr('checked' ,true);
								} else {
									$(this).attr('checked' ,'');
								}
							}
						}
					});
				}
			}
		},{
			name : 'productCode',
			display : '���ϱ���',
			type : 'statictext'
		},{
			name : 'productName',
			display : '��������',
			type : 'statictext'
		},{
			name : 'pattern',
			display : '����ͺ�',
			type : 'statictext'
		},{
			name : 'unitName',
			display : '��λ',
			type : 'statictext'
		},{
			name : 'produceNum',
			display : '��������',
			type : 'statictext'
		},{
			name : 'exeNum',
			display : '���´�����',
			type : 'statictext'
		},{
			name : 'stockNum',
			display : '���������',
			type : 'statictext'
		},{
			name : 'inventory',
			display : '�������',
			type : 'statictext'
		},{
			name : 'onwayAmount',
			display : '��;����',
			type : 'statictext'
		},{
			name : 'planEndDate',
			display : '�ƻ�����ʱ��',
			type : 'statictext'
		},{
			name : 'remark',
			display : '��ע',
			width : '20%',
			align : 'left',
			type : 'statictext'
		}]
	});

	validate({
		"closeReason" : {
			required : true
		}
	});
});

//�ύ���ݼ���
function checkData() {
	var stateObjs = $("#itemTable").yxeditgrid('getCmpByCol' ,'state');
	var tmp = false; //�Ƿ���ѡ�м�¼�ı�־λ
	stateObjs.each(function () {
		if ($(this).attr('checked')) {
			tmp = true;
			return false; //����ֻ���˳�ѭ��
		}
	});
	if (tmp) {
		return true;
	} else {
		alert('û��ѡ�еļ�¼��');
		return false;
	}
}