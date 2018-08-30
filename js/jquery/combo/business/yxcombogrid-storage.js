/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_storage', {
				options : {
					hiddenId : 'id',
					nameCol : 'storageAppCode',
					gridOptions : {
						model : 'stock_storage_storageapply',
						action : 'pageComboJson',//过滤条件(已入库)

						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '入库申请单号',
									name : 'storageAppCode',
									width:100
								},{
									display : '入库申请单类型',
									name : 'storageType',
									datacode : 'STORAGEAPPLY',
									width:150,
									hide : true
								},{
									display : '入库状态',
									name : 'isInStock',
									width:150,
									hide : true
								},{
									display : '入库仓库名称',
									name : 'inStockName',
									width:150
								},{
									display : '入库仓库Id',
									name : 'inStockId',
									width:150,
									hide : true
								},{
									display : '入库仓库代码',
									name : 'inStockCode',
									width:150
								},{
									display : '供应商名称',
									name : 'supplierName',
									width:150
								},{
									display : '供应商Id',
									name : 'supplierId',
									width:150,
									hide : true
								}],

						// 快速搜索
						searchitems : [{
									display : '入库申请单号',
									name : 'storageAppCode'
								}],
						// 默认搜索字段名
						sortname : "storageAppCode",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);