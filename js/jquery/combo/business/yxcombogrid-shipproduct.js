/**
 * 下拉发货单产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_shipproduct', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				model : 'stock_shipapply_shipproduct',
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'shipApplyId',
					display : '发货申请单id',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '产品名称',
					sortable : true
				}, {
					name : 'productNo',
					display : '产品编号',
					sortable : true
				}, {
					name : 'productId',
					display : '产品id',
					sortable : true,
					hide : true
				}, {
					name : 'shipNum',
					display : '发货数量',
					sortable : true
				}, {
					name : 'stockId',
					display : '仓库Id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true,
					hide : true
				}, {
					name : 'equOnlyId',
					display : '产品清单编号',
					sortable : true,
					hide : true
				}, {
					name : 'contractNo',
					display : '业务编号',
					sortable : true,
					hide : true
				}, {
					name : 'version',
					display : '业务版本号',
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