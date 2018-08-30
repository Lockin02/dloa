// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assworklogweekGrid").esmprojectgrid("reload");
};

(function($) {
	$.woo.yxgrid.subclass('woo.esmprojectgrid', {
		options: {
			sortname: "id",
			// 默认搜索顺序
			model : 'engineering_worklog_esmworklogweek',
			title : '周报考核信息',
			isAddAction: false,
			isDelAction: false,
			isViewAction: false,
			isEditAction: false,
			showcheckbox: false,
			sortorder: "ASC",
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTitle',
			display : '日志标题',
			width : 300,
			sortable : true
		}, {
			name : 'weekTimes',
			display : '周次',
			sortable : true
		}, {
			name : 'rankCode',
			display : '级别',
			sortable : true
		}, {
			name : 'directlyId',
			display : '直属上级id',
			sortable : true,
			hide : true
		}, {
			name : 'directlyName',
			display : '直属上级名称',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true
		}, {
			name : 'subStatus',
			display : '提交状态',
			datacode : 'ZBZT',
			sortable : true
		}],
			// 快速搜索
			searchitems: [{
			display : '日志标题',
			name : 'weekTitle'
		}],
			comboEx: [{
				text: "提交状态",
				key: 'subStatus',
				datacode: 'ZBZT'
			}]
		}
	});
})(jQuery);