/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stock', {
				options : {
					hiddenId : 'stockId',
					nameCol : 'stockName',
					gridOptions : {
						model : 'stock_stockinfo_stockinfo',
						// 列信息
						colModel : [{
									display : '仓库Id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '仓库名称',
									name : 'stockName',
									width:100
								}, {
									display : '仓库类型',
									name : 'stockUse',
									sortable : true,
									datacode : 'CKLX',
									width:'100',
									align : 'center'
								},{
									display : '仓库代码',
									name : 'stockCode',
									width:100
								},{
									display : '仓库管理员',
									name : 'chargeUser',
									width:150
								},{
									display : '仓库地址',
									name : 'adress',
									width:150
								}],
						// 快速搜索
						searchitems : [{
									display : '仓库名称',
									name : 'stockName'
								}],
						// 默认搜索字段名
						sortname : "stockName",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);