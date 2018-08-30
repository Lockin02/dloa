/**
 * 下拉退料单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_purchdelivered', {
				options : {
					hiddenId : 'id',
					nameCol : 'returnCode',
					gridOptions : {
						model : 'purchase_delivered_delivered',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '退料单号',
									name : 'returnCode',
									width:180
								},{
									display : '退料类型',
									name : 'returnType',
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
									display : '退料单号',
									name : 'returnCode'
								}],
						// 默认搜索字段名
						sortname : "returnCode",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);