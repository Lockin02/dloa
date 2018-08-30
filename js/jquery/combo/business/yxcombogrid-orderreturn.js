/**
 * 物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderreturn', {
				options : {
					hiddenId : 'id',
					nameCol : 'renturnCode',
					gridOptions : {
						showcheckbox : false,
						model : 'projectmanagent_return_return',
						action : 'pageJson',
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'renturnCode',
									display : '单据编号',
									sortable : true
								}, {
									name : 'orderCode',
									display : '合同号',
									sortable : true
								}, {
									name : 'orderName',
									display : '合同名称',
									hide : true
								}, {
									name : 'orderId',
									display : '合同Id',
									hide : true
								}, {
									name : 'prinvipalName',
									display : '合同负责人',
									sortable : true
								}, {
									name : 'returnCause',
									display : '退料原因',
									sortable : true
								}, {
									name : 'saleWay',
									display : '销售方式',
									sortable : true
								}, {
									name : 'storage',
									display : '收货仓库',
									sortable : true
								}, {
									name : 'createName',
									display : '创建人',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '审批状态',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '审批日期',
									sortable : true
								}],
						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : '退货单编号',
									name : 'renturnCode'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);