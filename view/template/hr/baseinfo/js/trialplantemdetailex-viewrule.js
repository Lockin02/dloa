$(document).ready(function() {
	$("#trialplantemdetailex").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetailex&action=listJson',
		tableClass : 'form_in_table',
		param : {
			'ids' : $("#id").val()
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��������(С�ڵ���)',
			name : 'upperLimit'
		}, {
			display : '��������(���ڵ���)',
			name : 'lowerLimit'
		}, {
			display : '��Ӧ����',
			name : 'score'
		}]
	})
})