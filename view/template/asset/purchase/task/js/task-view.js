$(function() {
	$("#taskTable").yxeditgrid({
		objName : 'task[taskItem]',
		delTagName : 'isDelTag',
		type : 'view',
		url : '?model=asset_purchase_task_taskItem&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
			display : '��������',
			name : 'productName',
			tclass : 'txtshort'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'txtshort'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			tclass : 'txtshort'
		}, {
			display : '�ɹ�����',
			name : 'purchAmount',
			tclass : 'txtshort'
		}, {
			display : '��������',
			name : 'taskAmount',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'moneyAll',
			tclass : 'txtshort',
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// �ж��Ƿ���ʾ�رհ�ť
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});