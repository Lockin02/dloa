$(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listUnitsJson',
		param : {
			weekId : $("#weekId").val()
		},
		type : 'view',
		title : '�ó���ϸ',
		colModel : [{
			display : 'ʹ������',
			name : 'useDate'
		}, {
			display : '��ʼ������',
			name : 'beginNum',
			tclass : 'txtshort'
		}, {
			display : '����������',
			name : 'endNum',
			tclass : 'txtshort'
		}, {
			display : '�����',
			name : 'mileage',
			tclass : 'txtshort'
		}, {
			display : 'ʹ��ʱ��',
			name : 'useHours',
			tclass : 'txtshort'
		}, {
			display : '��;',
			name : 'useReson'
		}, {
			display : '�˳���',
			name : 'travelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '�ͷ�',
			name : 'fuelFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '·�ŷ�',
			name : 'roadFee',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '��ЧLOG',
			name : 'effectiveLog'
		}]
	})
});