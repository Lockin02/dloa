/**
 * 超权限申请下拉表格
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_exceptionapply', {
		options : {
			hiddenId : 'id',
			nameCol : 'formNo',
			gridOptions : {
				model : 'engineering_exceptionapply_exceptionapply',
				// 表单
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '单据编号',
						name : 'formNo',
						sortable : true,
						width : 80
					}, {
						display : '申请类型',
						name : 'applyTypeName',
						sortable : true,
						width : 50
					}, {
						display : '申请人',
						name : 'applyUserName',
						sortable : true,
						width : 80
					}, {
						display : '申请日期',
						name : 'applyDate',
						sortable : true,
						width : 80
					}, {
						display : '适用范围',
						name : 'useRangeName',
						sortable : true,
						width : 50
					}, {
						display : '申请原因',
						name : 'applyReson',
						sortable : true,
						width : 200
					}, {
						display : '归属项目',
						name : 'projectName',
						sortable : true,
						width : 120
					}],
				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '申请单号',
					name : 'formNoSearch'
				},{
					display : '申请日期',
					name : 'applyDateSearch'
				},{
					display : '申请人',
					name : 'applyUserSearch'
				},{
					display : '申请原因',
					name : 'applyResonSearch'
				},{
					display : '归属项目',
					name : 'projectNameSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '超权限申请'
			}
		}
	});
})(jQuery);