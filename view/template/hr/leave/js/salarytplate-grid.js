var show_page = function(page) {
	$("#salarytplateGrid").yxsubgrid("reload");
};
$(function() {
	$("#salarytplateGrid").yxgrid({
		model : 'hr_leave_salarytplate',
		title : '离职工资结算清单模板',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {

			name : 'schemeName',
			display : '方案名称',
			width:200,
			sortable : true
		}, {
			name : 'jobName',
			display : '职位名称',
			sortable : true
		}, {
			name : 'companyName',
			display : '编制（公司）',
			sortable : true
		}, {
			name : 'leaveTypeCode',
			display : '离职类型',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			width:300,
			sortable : true

		}, {
			name : 'createName',
			display : '创建人',
			sortable : true

		} ],
//		// 主从表格设置
//		subGridOptions : {
//			url : '?model=hr_leave_salarytplateitem&action=pageItemJson',
//			param : [ {
//				paramId : 'mainId',
//				colId : 'id'
//			} ],
//			colModel : [ {
//				name : 'XXX',
//				display : '从表字段'
//			} ]
//		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "搜索字段",
			name : 'XXX'
		} ]
	});
});