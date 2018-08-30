$(document).ready(function() {
	//������״̬Ϊ��أ��Ҵ��ڴ��ԭ������ʾ
	if($('#status').val() == '���' && $('#backReason').html() != ''){
		$('#backReason').parents('tr:first').show();
	}
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listByRequireJson',
		objName : 'requirein[items]',
		title : '������Ϣ',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			display : '�豸����',
			name : 'name',
			tclass : 'txt',
			width : 120
		}, {
			display : '�豸����',
			name : 'description',
			tclass : 'txt',
			width : 120
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
			display : '���Ͻ��',
			name : 'productPrice',
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '����Ʒ��',
			name : 'brand',
			tclass : 'txt',
			width : 80
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txt',
			width : 120
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txt',
			width : 60
		}, {
			display : '�ѳ�������',
			name : 'executedNum',
			tclass : 'txt',
			width : 60
		}, {
			display : '���ɿ�Ƭ����',
			name : 'cardNum',
			tclass : 'txt',
			width : 80
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
});