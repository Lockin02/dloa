/**
 * 下拉收料单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_arrival', {
				options : {
					hiddenId : 'id',
					nameCol : 'arrivalCode',
					gridOptions : {
						model : 'purchase_arrival_arrival',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width : 130
								}, {
									display : '收料单号',
									name : 'arrivalCode',
									width : 180
								}, {
									display : '采购订单编号',
									name : 'purchaseCode',
									width : 180
								}, {
									display : '采购订单id',
									name : 'purchaseId',
									hide : true,
									width : 180
								}, {
									display : '收料单类型',
									name : 'arrivalType',
									datacode : 'ARRIVALTYPE',
									width : 150
								}, {
									display : '供应商名称',
									name : 'supplierName',
									width : 150
								}, {
									display : '供应商Id',
									name : 'supplierId',
									width : 150,
									hide : true
								}, {
									display : '收料仓库',
									name : 'stockName',
									width : 150
								}, {
									display : '收料仓库id',
									name : 'stockId',
									width : 150,
									hide : true
								}, {
									display : '采购方式',
									name : 'purchMode',
									datacode : 'cgfs',
									width : 150,
									hide : true
								}],
						// 快速搜索
						searchitems : [{
					display : '收料单号',
					name : 'arrivalCode'
				}, {
					display : '采购员',
					name : 'purchManName'
				},{
					display : '供应商',
					name : 'supplierName'
				}],
						// 默认搜索字段名
						sortname : "arrivalCode",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);