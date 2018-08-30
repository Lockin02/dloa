/**
 * 下拉质检单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_inspection', {
				options : {
					hiddenId : 'id',
					nameCol : 'documentCode',
					gridOptions : {
						model : 'quality_inspection_inspection',
					// 列信息
					colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'documentCode',
						display : '单据号',
						sortable : true
					}, {
						name : 'inspectDate',
						display : '检验日期',
						sortable : true,
						hide : true
					}, {
						name : 'inspectType',
						display : '检验申请单类型',
						sortable : true
					}, {
						name : 'materialCode',
						display : '物料编码',
						sortable : true,
						hide : true
					}, {
						name : 'materialName',
						display : '物料名称',
						sortable : true
					}, {
						name : 'materialId',
						display : '物料id',
						sortable : true,
						hide : true
					}, {
						name : 'inspectProgram',
						display : '检验方案',
						sortable : true,
						hide : true
					}, {
						name : 'recStockName',
						display : '收料仓库名称',
						sortable : true,
						hide : true
					}, {
						name : 'recStockCode',
						display : '收料仓库代码',
						sortable : true,
						hide : true
					}, {
						name : 'recStockId',
						display : '收料仓库id',
						sortable : true,
						hide : true
					}, {
						name : 'supplierId',
						display : '供应商id',
						sortable : true,
						hide : true
					}, {
						name : 'supplierName',
						display : '供应商名称',
						sortable : true,
						hide : true
					}, {
						name : 'batchNum',
						display : '批号',
						sortable : true,
						hide : true
					}, {
						name : 'checkNum',
						display : '检验数量',
						sortable : true
					}, {
						name : 'acceptNum',
						display : '合格数',
						sortable : true
					}, {
						name : 'rejectNum',
						display : '不合格数',
						sortable : true
					}, {
						name : 'checkResult',
						display : '检验结果',
						sortable : true,
						hide : true
					}],
						// 快速搜索
						searchitems : [{
									display : '单据号',
									name : 'documentCode'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);