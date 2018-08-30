$(document).ready(function() {
	if ($("#closeId").val() != "") {
		$("#closeTbl").show();
		$("#closeEmpty").hide();
	} else {
		$("#closeTbl").hide();
		$("#closeEmpty").show();
	}

	// ����
	$("#closeRules").yxeditgrid({
		url: '?model=engineering_close_esmclosedetail&action=listRuleJson',
		param: {projectId: $("#projectId").val()},
		tableClass: 'form_in_table',
		type: 'view',
		colModel: [{
			display: '��Ŀ',
			name: 'ruleName',
			width: 100
		}, {
			display: '��ǰֵ',
			name: 'val',
			align: 'left',
			width: 200
		}, {
			display: '��������',
			name: 'content',
			tclass: 'readOnlyTxtLong',
			align: 'left'
		}, {
			display: '��Ŀ������������',
			name: 'reply',
			align: 'left'
		}]
	});
});