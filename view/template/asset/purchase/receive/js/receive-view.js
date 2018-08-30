$(function() {
	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
//		delTagName : 'isDelTag',
		type : 'view',
		url : '?model=asset_purchase_receive_receiveItem&action=listJson',
		param : {
			receiveId : $("#receiveId").val()
		},
		colModel : [{
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '���',
			name : 'spec'
		}, {
			display : '����',
			name : 'checkAmount',
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
			name : 'amount',
			tclass : 'txtshort',
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����',
			name : 'deploy',
			tclass : 'txt'
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