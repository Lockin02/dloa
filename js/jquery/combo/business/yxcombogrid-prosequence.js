/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_prosequence', {
				options : {
					hiddenId : 'id',
					nameCol : 'sequence',
					gridOptions : {
						model : 'stock_productserialno_serialno',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width : 130
								}, {
									display : '入库申请单id',
									name : 'storageAppId',
									hide : true,
									width : 130

								},{
									display : '产品id',
									name : 'productId',
									hide : true,
									width : 130
								}, {
									display : '产品名称',
									name : 'productName',
									width : 100
								}, {
									display : '产品编号',
									name : 'productCode',
									width : 100
								}, {
									display : '序列号',
									name : 'sequence',
									width : 150
								}, {
									display : '入库单编号',
									name : 'storageAppCode',
									hide:true,
									width : 100
								}, {
									display : '出库单编号',
									name : 'outStockCode',
									hide:true,
									width : 100
								}, {
									display : '出库单id',
									name : 'outStockId',
									hide : true,
									width : 150
								}, {
									display : '备注',
									name : 'remark',
									width : 150
								}],
						// 快速搜索
						searchitems : [{
									display : '序列号',
									name : 'sequence'
								}],
						// 默认搜索字段名
						sortname : "sequence",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);