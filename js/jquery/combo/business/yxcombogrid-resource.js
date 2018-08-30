/**
 * 资源目录下拉combogrid
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_resource', {
				options : {
					hiddenId : 'id',
					nameCol : 'resourceName',
					gridOptions : {
						model : 'engineering_baseinfo_resource',

						// 表单
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '资源编码',
									name : 'resourceCode',
									sortable : true,
									width : 150
								}, {
									display : '资源名称',
									name : 'resourceName',
									sortable : true,
									width : 150
								}, {
									display : '资源类型',
									name : 'parentName',
									sortable : true,
									width : 150
								}],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : '资源编码',
									name : 'resourceCode'
								}],
						pageSize : 10,
						sortorder : "desc",
						title : '资源目录'
					}
				}
			});
})(jQuery);