$(document).ready(function() {
	//ģ��
	$("#scoredetail").yxeditgrid({
		url : '?model=hr_certifyapply_scoredetail&action=listJson',
		param : {"scoreId" : $("#id").val()},
		tableClass : 'form_in_table',
		type : 'view',
		title : '������ϸ',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'ģ��Id',
			name : 'modeId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��',
			name : 'moduleName'
		}, {
			display : '��ΪҪ��id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName'
		}, {
			display : 'Ȩ��(%)',
			name : 'weights'
		}, {
			display : '����',
			name : 'score'
		}]
	})
});