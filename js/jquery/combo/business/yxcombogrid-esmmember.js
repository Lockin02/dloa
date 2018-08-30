/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmmember', {
		options : {
			hiddenId : 'id',
			nameCol : 'memberName',
			gridOptions : {
				model : 'engineering_member_esmmember',
				action : 'pageJsonOrg',
				// 表单
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'memberName',
					display : '姓名',
					sortable : true,
					width : 120
				}, {
					name : 'memberId',
					display : '成员id',
					sortable : true,
					hide : true
				}, {
					name : 'personLevel',
					display : '级别',
					sortable : true,
					width : 120
				}, {
					name : 'roleName',
					display : '角色',
					sortable : true,
					width : 120
				}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '姓名',
					name : 'memberNameSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '项目成员'
			}
		}
	});
})(jQuery);