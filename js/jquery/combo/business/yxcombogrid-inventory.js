/**
 * 下拉库存产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_inventory', {
				options : {
					nameCol : 'productName',
					gridOptions : {
						model : 'stock_inventoryinfo_inventoryinfo',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'stockId',
									display : '仓库id',
									sortable : true,
									hide : true
								}, {
									name : 'stockName',
									display : '仓库名称',
									sortable : true
								}, {
									name : 'stockCode',
									display : '仓库代码',
									sortable : true,
									hide : true
								}, {
									name : 'proTypeId',
									display : '物料类型id',
									sortable : true,
									hide : true
								}, {
									name : 'proType',
									display : '物料类型名称',
									sortable : true,
									hide : true
								}, {
									name : 'productId',
									display : '产品id',
									sortable : true,
									hide : true
								}, {
									name : 'productCode',
									display : '物料编号',
									sortable : true
								}, {
									name : 'productName',
									display : '物料名称',
									width:'200',
									sortable : true
								}, {
									name : 'pattern',
									display : '规格型号',
									hide : true
								}, {
									name : 'unitName',
									display : '单位',
									hide : true
								}, {
									name : 'initialNum',
									display : '初始库存',
									sortable : true,
									hide : true
								}, {
									name : 'actNum',
									display : '现有库存',
									sortable : true
								}, {
									name : 'safeNum',
									display : '安全库存',
									sortable : true,
									hide : true
								}, {
									name : 'exeNum',
									display : '可执行库存',
									sortable : true,
									hide : true
								}, {
									name : 'price',
									display : '价格成本',
									sortable : true,
									hide : true
								}],
						// 快速搜索
						searchitems : [{
									display : '物料名称',
									name : 'productName'
								}, {
									display : '物料编号',
									name : 'productCode'
								}],
						// 默认搜索字段名
						sortname : "productId",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);