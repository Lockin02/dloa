(function($) {

	$.woo.yxgrid.subclass('woo.yxgrid_portlettype', {
				options : {
					model : 'system_portal_portlettype',
					// 表单
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '上级名称',
								name : 'parentName'
							}, {
								display : '类型名称',
								name : 'typeName'
							}],
					/**
					 * 快速搜索
					 */
					searchitems : [{
								display : '类型名称',
								name : 'typeName'
							}],
					sortorder : "DESC",
					sortname : "id",
					title : '类型名称'
				}
			});
})(jQuery);