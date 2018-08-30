var show_page = function(page) {
	$("#planfeedbackGrid").yxgrid("reload");
};

$(function() {
	$("#planfeedbackGrid").yxgrid({
		model: 'produce_plan_planfeedback',
		action : 'planPageJson',
		title: '进度反馈',
		param : {
			planId : $("#planId").val()
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'feedbackDate',
			display : '反馈日期',
			sortable : true
		},{
			name : 'feedbackName',
			display : '反馈者',
			sortable : true
		},{
			name : 'feedbackNum',
			display : '反馈次数',
			sortable : true,
			process : function (v ,row) {
				return '第 ' + v + ' 次反馈';
			}
		},{
			name : 'recipientNum',
			display : '接收数量',
			sortable : true
		},{
			name : 'qualifiedNum',
			display : '合格数量',
			sortable : true
		},{
			name : 'unqualifiedNum',
			display : '不合格数量',
			sortable : true
		}],

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var row = g.getSelectedRow().data('data');
					showModalWin('?model=produce_plan_planfeedback&action=toViewPlan&planId='
						+ row.planId + '&id=' + row.id);
				}
			}
		},

		searchitems: [{
			display : "反馈日期",
			name : 'feedbackDate'
		},{
			display : "反馈者",
			name : 'feedbackName'
		}]
	});
});