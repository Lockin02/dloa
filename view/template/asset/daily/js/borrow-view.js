$(document).ready(function() {
	$("#borrowTable").yxeditgrid({
		objName : 'borrow[borrowitem]',
		url : '?model=asset_daily_borrowitem&action=listJson',
		type : 'view',
		param : {
			borrowId : $("#borrowId").val(),
			assetId : $("#assetId").val()

		},
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '��������',
			name : 'buyDate',
			type : 'date'
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort'
		}, {
			display : 'Ԥ��ʹ���ڼ���',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : 'ʣ��ʹ���ڼ���',
			name : 'residueYears',
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

});