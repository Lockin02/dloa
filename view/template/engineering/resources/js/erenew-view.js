$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_erenewdetail&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : '�豸����',
			width : 120,
			name : 'resourceTypeName',
			isSubmit : true
		}, {
			name : 'resourceName',
			width : 120,
			display : '�豸����',
			isSubmit : true
		}, {
			name : 'coding',
			display : '������',
			width : 120,
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '���ű���',
			width : 100,
			isSubmit : true
		}, {
			name : 'number',
			display : '����',
			width : 60,
			isSubmit : true
		}, {
			name : 'unit',
			display : '��λ',
			width : 60,
			isSubmit : true	
		}, {
			name : 'beginDate',
			display : 'Ԥ�ƿ�ʼ����',
			sortable : true,
			width : 80
		}, {
			name : 'endDate',
			display : 'Ԥ�ƹ黹����',
			sortable : true,
			width : 80
		}, {
			name : 'realDate',
			display : 'ʵ����������',
			width : 80,
			isSubmit : true
		}, {
			name : 'remark',
			display : '��ע',
			width : 120,
			isSubmit : true
		}]
	})
});