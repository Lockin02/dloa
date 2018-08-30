$(document).ready(function() {

	// 规则
	$("#closeRules").yxeditgrid({
		url: '?model=engineering_close_esmclosedetail&action=listConfirm',
		param: {projectId: $("#projectId").val()},
		tableClass: 'form_in_table',
		title: '关闭规则',
		type: 'view',
		colModel: [{
			display: '确认清单',
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
			display: '当前值',
			name: 'val',
			wdith: 100
		}, {
			display: '所需操作',
			name: 'content',
			wdith: 400,
			align: 'left'
		}]
	});
});