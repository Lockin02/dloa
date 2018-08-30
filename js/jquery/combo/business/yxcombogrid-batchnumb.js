/**
 * 零配件订单下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_batchnumb', {
				options : {
					hiddenId : 'batchNumb',
					nameCol : 'batchNumb',
					gridOptions : {
						showcheckbox : false,
						model : 'purchase_plan_basic',
						action : 'batchNumbPageJson',
						pageSize : 10,
						// 列信息
						colModel : [{
									name : 'batchNumb',
									display : '批次号',
									sortable : true,
									width:300
								}],
						// 快速搜索
						searchitems : [{
									display : '批次号',
									name : 'batchNumb'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);