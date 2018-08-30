/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmrole', {
		options : {
			hiddenId : 'id',
			nameCol : 'roleName',
			gridOptions : {
				model : 'engineering_role_esmrole',
				action : 'pageJsonOrg',
				// 表单
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '角色名称',
							name : 'roleName',
							sortable : true
						}, {
							display : '工作任务',
							name : 'activityName',
							sortable : true,
							width : 200
						}, {
							display : '工作描述',
							name : 'jobDescription',
							sortable : true,
							width : 150
						}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '角色名称',
					name : 'roleNameSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '项目角色'
			}
		}
	});
})(jQuery);