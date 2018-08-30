var show_page = function(page) {
	$(".protasklist").yxgrid("reload");
};

$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;
	var pjId = $('#projectId').val();
	$(".protasklist").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装

       model : 'engineering_task_protask',

            /**
			 * 是否显示添加按钮/菜单
             */
			isAddAction : false,
			/**
			 * 是否显示查看按钮/菜单
             */
			isViewAction : false,
			/**
			 * 是否显示修改按钮/菜单
             */
			isEditAction : false,
			/**
			 * 是否显示删除按钮/菜单
			 */
			isDelAction : false,

		colModel : [ {
			display : '任务名称',
			name : 'name',
			sortable : true,
			width : 80
		},

//			{
//			display : '所属项目',
//			name : 'projectName',
//			sortable : true,
//			width : 80
//		},

			{
			display : '优先级',
			name : 'priority',
			sortable : true,
			width : 100
		}, {
			display : '状态',
			name : 'status',
			sortable : true,
			datacode : 'XMRWZT',

			width : 100
		}, {
			display : '完成率',
			name : 'effortRate',
			sortable : true,
			width : 100
		}, {
			display : '偏差率',
			name : 'warpRate',
			sortable : true,
			width : 100
		}, {
			display : '责任人',
			name : 'chargeName',
			sortable : true,
			width : 100
		}, {
			display : '发布人',
			name : 'publishName',
			sortable : true,
			width : 100
		}, {
			display : '计划开始时间',
			name : 'planBeginDate',
			sortable : true,
			width : 100
		}, {
			display : '计划完成时间',
			name : 'planEndDate',
			sortable : true,
			width : 100
		}, {
			display : '任务类型',
			name : 'taskType',
			width : 100,
			sortable : true
		}],

	    param:{
			"projectId":$("#projectId").val()
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '#',
			name : '#'
		}],
		sortorder : "ASC",
		title : '项目任务'
	});
});