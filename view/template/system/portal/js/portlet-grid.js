(function($) {

	$.woo.yxgrid.subclass('woo.yxgrid_portlet', {
				options : {
					model : 'system_portal_portlet',
					// 表单
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'portlet名称',
								name : 'portletName'
							}, {
								display : '类型',
								name : 'typeName'
							}, {
								display : '路径',
								name : 'url',
								width : 300
							}],
					/**
					 * 快速搜索
					 */
					searchitems : [{
								display : 'portlet名称',
								name : 'portletName'
							}],
					sortorder : "DESC",
					sortname : "id",
					title : 'portlet信息'
				}
			});
})(jQuery);