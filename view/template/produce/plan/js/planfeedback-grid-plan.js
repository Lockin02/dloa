var show_page = function(page) {
	$("#planfeedbackGrid").yxgrid("reload");
};

$(function() {
	$("#planfeedbackGrid").yxgrid({
		model: 'produce_plan_planfeedback',
		action : 'planPageJson',
		title: '���ȷ���',
		param : {
			planId : $("#planId").val()
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'feedbackDate',
			display : '��������',
			sortable : true
		},{
			name : 'feedbackName',
			display : '������',
			sortable : true
		},{
			name : 'feedbackNum',
			display : '��������',
			sortable : true,
			process : function (v ,row) {
				return '�� ' + v + ' �η���';
			}
		},{
			name : 'recipientNum',
			display : '��������',
			sortable : true
		},{
			name : 'qualifiedNum',
			display : '�ϸ�����',
			sortable : true
		},{
			name : 'unqualifiedNum',
			display : '���ϸ�����',
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
			display : "��������",
			name : 'feedbackDate'
		},{
			display : "������",
			name : 'feedbackName'
		}]
	});
});