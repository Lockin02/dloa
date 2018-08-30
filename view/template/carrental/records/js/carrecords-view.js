$(document).ready(function() {

	$("#importTable").yxeditgrid({
		objName : 'carrecords[carrecordsdetail]',
		url : '?model=carrental_records_carrecordsdetail&action=listJson',
		param : {
			recordsId : $("#recordsId").val()
		},
		type : 'view',
		title : '�ó���ϸ',
		colModel : [{
			display : 'ʹ������',
			name : 'useDate'
		}, {
			display : '��ʼ������',
			name : 'beginNum'
		}, {
			display : '����������',
			name : 'endNum'
		}, {
			display : '�����',
			name : 'mileage'
		}, {
			display : 'ʹ��ʱ��',
			name : 'useHours'
		}, {
			display : '��;',
			name : 'useReson'
		}, {
			display : '�˳���',
			name : 'travelFee',
			type : 'money'
		}, {
			display : '�ͷ�',
			name : 'fuelFee',
			type : 'money'
		}, {
			display : '·�ŷ�',
			name : 'roadFee',
			type : 'money'
		}, {
			display : '��ЧLOG',
			name : 'effectiveLog'
		}]
	})
 })