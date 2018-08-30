(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_inventory', {
		options : {
			model : 'hr_invent_inventory',
			// 表单
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true
			}, {
//				name : 'userAccount',
//				display : '员工账号',
//				sortable : true
//			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true
			}, {
//				name : 'companyType',
//				display : '公司类型',
//				sortable : true
//			}, {
				name : 'companyName',
				display : '公司名称',
				sortable : true
			}, {
//				name : 'deptNameS',
//				display : '二级部门',
//				sortable : true
//			}, {
//				name : 'deptNameT',
//				display : '三级部门',
//				sortable : true
//			}, {
				name : 'entryDate',
				display : '入职日期',
				sortable : true
			}, {
				name : 'inventoryDate',
				display : '盘点日期',
				sortable : true
			}, {
				name : 'alternative',
				display : '可替代',
				sortable : true
			}, {
				name : 'matching',
				display : '匹配度',
				sortable : true
			}, {
				name : 'critical',
				display : '员工关键性',
				sortable : true
			}, {
				name : 'isCore',
				display : '是否核心',
				sortable : true
			}, {
				name : 'recruitment',
				display : '招聘难度',
				sortable : true
			}, {
				name : 'examine',
				display : '考核',
				sortable : true
			}, {
				name : 'preEliminated',
				display : '预淘汰',
				sortable : true
			}, {
				name : 'remark',
				display : '是否可能流失',
				sortable : true
			}],
			/**
			 * 快速搜索
			 */
			searchitems : [{
				display : '员工名称',
				name : 'userName'
			}],
			sortorder : "DESC",
			sortname : "id",
			title : '员工盘点信息'
		}
	});
})(jQuery);