$(document).ready(function() {
	if ($("#closeId").val() != "") {
		$("#closeTbl").show();
		$("#closeEmpty").hide();
	} else {
		$("#closeTbl").hide();
		$("#closeEmpty").show();
	}

	// 规则
	$("#closeRules").yxeditgrid({
		url: '?model=engineering_close_esmclosedetail&action=listRuleJson',
		param: {projectId: $("#projectId").val()},
		tableClass: 'form_in_table',
		type: 'view',
		colModel: [{
			display: '名目',
			name: 'ruleName',
			width: 100
		}, {
			display: '当前值',
			name: 'val',
			align: 'left',
			width: 200
		}, {
			display: '规则描述',
			name: 'content',
			tclass: 'readOnlyTxtLong',
			align: 'left'
		}, {
			display: '项目经理书面描述',
			name: 'reply',
			align: 'left'
		}]
	});
});