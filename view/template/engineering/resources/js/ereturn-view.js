$(document).ready(function() {
	$("#importTable").yxeditgrid({
		url : '?model=engineering_resources_ereturndetail&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : '�豸����',
			name : 'resourceTypeName',
			width : 150,
			isSubmit : true
		}, {
			name : 'resourceName',
			display : '�豸����',
			width : 150,
			isSubmit : true
		}, {
			name : 'coding',
			display : '������',
			width : 120,
			isSubmit : true
		}, {
			name : 'dpcoding',
			display : '���ű���',
			width : 120,
			isSubmit : true
		}, {
			name : 'number',
			display : '��������',
			width : 80,
			isSubmit : true
		}, {
			name : 'confirmNum',
			display : '�ѹ黹����',
			width : 80,
			isSubmit : true
		}, {
			name : 'unit',
			display : '��λ',
			width : 80,
			isSubmit : true	
		}, {
			name : 'resourceId',
			display : '�豸ID',
			isSubmit : true,
			type : 'hidden'
		}, {
			name : 'resourceTypeId',
			display : '�豸����ID',
			isSubmit : true,
			type : 'hidden'
		}]
	})
});