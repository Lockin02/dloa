$(document).ready(function() {

	// ����
	$("#closeRules").yxeditgrid({
		url: '?model=engineering_close_esmclosedetail&action=listConfirm',
		param: {projectId: $("#projectId").val()},
		tableClass: 'form_in_table',
		title: '�رչ���',
		type: 'view',
		colModel: [{
			display: 'ȷ���嵥',
			name: 'ruleName',
			wdith: 100,
			type: 'statictext',
			process: function(v, row) {
				if (parseInt(row.ruleId) <= 5) {
					return '<span class="red">' + v + '</span>';
				} else {
					return v;
				}
			}
		}, {
			display: '��ǰֵ',
			name: 'val',
			wdith: 100
		}, {
			display: '�������',
			name: 'content',
			wdith: 400,
			align: 'left'
		}]
	});
});