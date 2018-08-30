/**
 * 下拉发货计划表格
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outplan', {
				options : {
					hiddenId : 'id',
					nameCol : 'planCode',
					gridOptions : {
						showcheckbox : false,
						model : 'stock_outplan_outplan',
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true
								}, {
									display : '发货计划编号',
									name : 'planCode'
								}, {
									display : '周次',
									name : 'week'
								}, {
									display : '源单类型',
									name : 'docType',
									process : function(v) {
										if (v == 'oa_contract_contract') {
											return "合同";
										} else if (v == 'oa_contract_exchangeapply') {
											return "换货申请";
										} else if (v == 'oa_borrow_borrow') {
											return "借试用申请";
										} else if (v == 'oa_present_present') {
											return "赠送申请";
										}
									}
								}, {
									display : '合同Id',
									name : 'docId',
									hide : true
								}, {
									display : '合同号',
									name : 'docCode'
								}, {
									display : '合同名称',
									name : 'docName',
									hide : true
								}, {
									display : '仓库Id',
									name : 'stockId',
									hide : true,
									width : 80
								}, {
									display : '仓库编码',
									name : 'stockCode',
									hide : true,
									width : 80
								}, {
									display : '仓库名称',
									name : 'stockName',
									width : 80
								}, {
									display : '发货方式',
									name : 'shipType',
									hide : true,
									width : 80
								}, {
									display : '客户名称',
									name : 'customerName',
									width : 80
								}, {
									display : '客户id',
									name : 'customerId',
									hide : true
								}],
						// 快速搜索
						searchitems : [{
									display : '计划编号',
									name : 'planCode'
								}],
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);