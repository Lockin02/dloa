var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};
$(function() {
	$("#workflowGrid").yxgrid({
		model: 'common_workflow_workflow',
		title: '������',
		showcheckbox : false,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		//����Ϣ
		colModel: [{
			display: '��������',
			name: 'id',
			sortable: true
		},
		{
			name: 'name',
			display: '��������',
			sortable: true,
			width : 120
		},
		{
			name: 'Creator',
			display: '�ύ��',
			sortable: true,
			width : 130
		},
		{
			name: 'start',
			display: '�ύʱ��',
			sortable: true,
			width : 150
		}]
	});
});