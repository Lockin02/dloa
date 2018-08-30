/**
 * 省份表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_province', {
				options : {
					hiddenId : 'provinceId',
					nameCol : 'provinceName',
					width:550,
					gridOptions : {
						model : 'system_procity_province',
						colModel : [{

									display : '省份名称	',
									name : 'provinceName',
									sortable : true,
									width : 200
								}, {
									display : '省份编号	',
									name : 'provinceCode',
									sortable : true,
									width : 200,
									hide : true
								}],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : '省份名称',
									name : 'provinceName'
								}],
						sortorder : "ASC",
						title : '省份名称'
					}
				}
			});

})(jQuery);
