/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_licenseType', {
				options : {
					hiddenId : 'id',
					nameCol : 'typeName',
//					checkbox : true,
					gridOptions : {
						model : 'product_licensetype_licensetype',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true
								}, {
									display : 'license类型',
									name : 'typeName',
									sortable : true
								}],
//						// 快速搜索
						searchitems : [{
									display : 'license类型',
									name : 'typeName'
								}],
						// 默认搜索字段名
						sortname : "typeName",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);