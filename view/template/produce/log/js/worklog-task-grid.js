var show_page = function(page) {
	$("#worklogGrid").yxgrid("reload");
};
$(function() {
	$("#worklogGrid").yxgrid({
		model : 'produce_log_worklog',
		title : '��������־',
		// ����Ϣ
		showcheckbox : false,
		param : {
			produceTaskId : $('#taskId').val()
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'produceTaskId',
			display : '������',
			sortable : true
		}, {
			name : 'produceTaskCode',
			display : '������',
			sortable : true
		}, {
			name : 'executionDate',
			display : 'ִ������',
			sortable : true
		}, {
			name : 'effortRate',
			display : '�����',
			sortable : true
		}, {
			name : 'warpRate',
			display : 'ƫ����',
			sortable : true
		}, {
			name : 'workloadDay',
			display : '����Ͷ�빤����',
			sortable : true
		}, {
			name : 'workloadSurplus',
			display : 'Ԥ��ʣ�๤����',
			sortable : true
		}, {
			name : 'planEndDate',
			display : 'Ԥ�����ʱ��',
			sortable : true
		}, {
			name : 'description',
			display : '����',
			sortable : true
		}, {
			name : 'problem',
			display : '��������',
			sortable : true
		}, {
			name : 'createName',
			display : 'Ա������',
			sortable : true
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "Ա������",
			name : 'createName'
		} ]
	});
});