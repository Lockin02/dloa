/**
 * 仓库基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockinfo', {
				options : {
					hiddenId : 'id',
					nameCol : 'stockCode',
					gridOptions : {
						showcheckbox : false,
						model : 'stock_stockinfo_stockinfo',
						action : 'pageJson',
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'stockCode',
									display : '仓库代码',
									sortable : true
								}, {
									name : 'stockName',
									display : '仓库名称',
									width:150,
									sortable : true
								}, {
									name : 'chargeUserName',
									display : '仓库管理员',
									sortable : true
								}, {
									name : 'stockUseCode',
									display : '仓库用途',
									datacode:'CKYT',
									width:70,
									sortable : true
								}, {
									name : 'stockType',
									display : '仓库类型',
									datacode:'CKLX',
									width:50,
									sortable : true
								}],
						// 快速搜索
						searchitems : [{
									display : '仓库代码',
									name : 'stockCode'
								}, {
									display : '仓库名称',
									name : 'stockName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "desc"
					}
				}
			});
})(jQuery);