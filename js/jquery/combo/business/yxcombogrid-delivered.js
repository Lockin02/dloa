/**
 * 下拉退货单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_delivered', {
				options : {
					hiddenId : 'id',
					nameCol : 'deliveredCode',
					gridOptions : {
						model : 'stock_delivered_delivered',
						// 列信息
					colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'deliveredCode',
							display : '退货单号',
							sortable : true
						}, {
							name : 'supplierName',
							display : '供应商名称',
							sortable : true
						}, {
							name : 'supplierId',
							display : '供应商id',
							sortable : true,
							hide : true
						}, {
							name : 'deStockName',
							display : '退货仓库',
							sortable : true
						}, {
							name : 'deStockCode',
							display : '退货仓库代码',
							sortable : true
						}, {
							name : 'deStockId',
							display : '退货仓库id',
							sortable : true,
							hide : true
						}],
						// 快速搜索
						searchitems : [{
									display : '退货单号',
									name : 'deliveredCode'
								}],
						// 默认搜索字段名
						sortname : "deliveredCode",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);