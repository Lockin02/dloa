/**
 * 下拉退货单产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_deliveredpro', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_delivered_deliveredpro',
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'deliveredId',
					display : '退货单Id',
					sortable : true,
					hide : true
				}, {
					name : 'productId',
					display : '产品id',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '产品名称',
					sortable : true
				}, {
					name : 'sequence',
					display : '产品编号',
					sortable : true
				}, {
					name : 'serialNumber',
					display : '序列号',
					sortable : true,
					hide : true
				}, {
					name : 'productNum',
					display : '退货数量',
					sortable : true
				}, {
					name : 'remark',
					display : '说明',
					sortable : true,
					hide : true
				}],
				// 快速搜索
				searchitems : [{
					display : '产品名称',
					name : 'productName'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);