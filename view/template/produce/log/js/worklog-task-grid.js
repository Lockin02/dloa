var show_page = function(page) {
	$("#worklogGrid").yxgrid("reload");
};
$(function() {
	$("#worklogGrid").yxgrid({
		model : 'produce_log_worklog',
		title : '任务工作日志',
		// 列信息
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
			display : '任务编号',
			sortable : true
		}, {
			name : 'produceTaskCode',
			display : '任务编号',
			sortable : true
		}, {
			name : 'executionDate',
			display : '执行日期',
			sortable : true
		}, {
			name : 'effortRate',
			display : '完成率',
			sortable : true
		}, {
			name : 'warpRate',
			display : '偏差率',
			sortable : true
		}, {
			name : 'workloadDay',
			display : '当日投入工作量',
			sortable : true
		}, {
			name : 'workloadSurplus',
			display : '预计剩余工作量',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '预计完成时间',
			sortable : true
		}, {
			name : 'description',
			display : '描述',
			sortable : true
		}, {
			name : 'problem',
			display : '存在问题',
			sortable : true
		}, {
			name : 'createName',
			display : '员工名称',
			sortable : true
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "员工名称",
			name : 'createName'
		} ]
	});
});