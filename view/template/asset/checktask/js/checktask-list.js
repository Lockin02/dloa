// 用于新增/修改后回调刷新表格

var show_page = function(page) {
	$("#taskGrid").yxgrid('reload');
};

$(function() {
	$("#taskGrid").yxgrid({

		model : 'asset_checktask_checktask',
		title : '盘点任务信息',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '任务编号',
			name : 'billNo',
			sortable : true
		}, {
			display : '盘点时间',
			name : 'checkDate',
			sortable : true
		}, {
			display : '预计盘点结束时间',
			name : 'endDate',
			sortable : true
		}, {
			display : '发起人id',
			name : 'initiatorId',
			sortable : true,
			hide : true

		}, {
			display : '发起人',
			name : 'initiator',
			sortable : true

		}, {
			display : '盘点部门id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '盘点部门',
			name : 'deptName',
			sortable : true,
			width : 230
		}, {
			display : '参与人id',
			name : 'participantId',
			sortable : true,
			hide : true

		}, {
			display : '参与人',
			name : 'participant',
			sortable : true,
			width : 230

		}, {
			display : '任务说明',
			name : 'remark',
			sortable : true
		}],

		isViewAction : true,
		isEditAction : true,
		toAddConfig : {
			formWidth : 1000,
			formHeight : 450
		},
		toEditConfig : {
			formWidth : 800,
			formHeight : 400
		},
		toViewConfig : {
			formWidth : 800,
			formHeight : 350
		},

		// 快速搜索
		searchitems : [{
			display : '任务编号',
			name : 'billNo'
		}, {
			display : '盘点时间',
			name : 'checkDate'
		}, {
			display : '盘点部门',
			name : 'deptName'
		}],
		sortname : "id",
		sortorder : "ASC"

	});

});