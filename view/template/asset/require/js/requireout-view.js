$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireoutitem&action=listByRequireJson',
		objName : 'requireout[items]',
		title : '��Ƭ��Ϣ',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			display : '�ʲ����',
			name : 'assetCode',
			tclass : 'txt',
			width : 120
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			tclass : 'txt',
			width : 120
		}, {
			display : '�ʲ���ֵ',
			name : 'salvage',
			tclass : 'txt',
			width : 120,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt',
			width : 120
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txt',
			width : 120
		}, {
			display : '����ͺ�',
			name : 'spec',
			width : 120
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txtshort',
			width : 60
		}, {
			display : '���������',
			name : 'executedNum',
			tclass : 'txtshort',
			width : 60
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
});