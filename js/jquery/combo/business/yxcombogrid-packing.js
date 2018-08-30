/**
 * 下拉产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_packing', {
			options : {
				hiddenId : 'id',
				nameCol : 'productName',
				gridOptions : {
					showcheckbox : false,
					model : 'stock_inventoryinfo_inventoryinfo',
					param : {'stockCode':'PACKAGING'},
					pageSize : 10,
					// 列信息
					colModel : [{
								name : 'id',
								display : 'id',
								hide : true
							},{
								display : '产品名称',
								name : 'productName',
								width : 180
							},{
								display : ' 产品ID',
								name : 'productId',
								hide : true
							}, {
								display : '产品编号',
								name : 'sequence',
								width : 130
							},{
								display : '仓库Id',
								name : 'stockId',
								width : 130,
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