/**
 * 下拉配件表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_proconfig', {
				options : {
					hiddenId : 'id',
					nameCol : 'configName',
					gridOptions : {
						model : 'stock_productinfo_configuration',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width : 130

								}, {
									display : '配件名称',
									name : 'configName',
									width : 100
								}, {
									display : '配件型号',
									name : 'configPattern',
									width : 100
								}, {
									display : '配件数量',
									name : 'configNum',
									width : 150
								}, {
									display : '说明',
									name : 'explains',
									width : 100
								}],
						// 快速搜索
						searchitems : [{
									display : '配件名称',
									name : 'configName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);