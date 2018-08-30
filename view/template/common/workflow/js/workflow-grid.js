var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};
$(function() {
	$("#workflowGrid").yxgrid({
		model: 'common_workflow_workflow',
		title: '工作流',
		showcheckbox : false,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		//列信息
		colModel: [{
			display: '审批单号',
			name: 'id',
			sortable: true
		},
		{
			name: 'name',
			display: '审批类型',
			sortable: true,
			width : 120
		},
		{
			name: 'Creator',
			display: '提交人',
			sortable: true,
			width : 130
		},
		{
			name: 'start',
			display: '提交时间',
			sortable: true,
			width : 150
		}]
	});
});