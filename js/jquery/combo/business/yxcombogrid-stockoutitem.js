/**
 * 下拉到货单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockoutitem', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				model : 'stock_outstock_stockoutitem',
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'mainId',
					display : '出库申请单id',
					sortable : true,
					hide : true
				}, {
					name : 'productId',
					display : '产品id',
					sortable : true,
					hide : true
				}, {
					name : 'productNo',
					display : '产品编号',
					sortable : true
				}, {
					name : 'productModel',
					display : '产品型号',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '产品名称',
					sortable : true
				}, {
					name : 'outPrice',
					display : '出库价',
					sortable : true,
					hide : true
				}, {
					name : 'stockId',
					display : '出货仓库id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '出货仓库名称',
					sortable : true,
					hide : true
				}, {
					name : 'stockCode',
					display : '出货仓库编码',
					sortable : true,
					hide : true
				}, {
					name : 'subOutNum',
					display : '已出库数量',
					sortable : true,
					hide : true
				}, {
					name : 'applyNum',
					display : '申请出库数量',
					sortable : true,
					hide : true
				}],
				// 快速搜索
				searchitems : [{
					display : '产品名称',
					name : 'productName'
				}],
				// 默认搜索字段名
				sortname : "productName",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);