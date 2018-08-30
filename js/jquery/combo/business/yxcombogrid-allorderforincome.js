/**
 * 下拉租赁合同表格组件 create by can
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_allorderforincome', {
				options : {
					hiddenId : 'orgid',
					nameCol : 'orderCode',
					searchName : 'orderCodeOrTempSearch',
					focusoutCheckAction : 'getCountByNameForView',
					autoHiddenName : {
						'objCode' : 'rObjCode'
					},
					gridOptions : {
						model : 'projectmanagent_order_order',
						action : 'allOrderForIncomePj',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'tablename',
									display : '合同类型',
									sortable : true,
									datacode : 'KPRK',
									hide : true
								}, {
									name : 'orderCode',
									display : '鼎利合同号',
									sortable : true
								}, {
									name : 'orderTempCode',
									display : '临时合同号',
									sortable : true
								}, {
									name : 'customerName',
									display : '客户名称',
									sortable : true,
									hide : true
								}, {
									name : 'customerType',
									display : '客户类型',
									datacode : 'KHLX',
									hide : true
								}, {
									name : 'contractMoney',
									display : '合同金额',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'invoiceMoney',
									display : '开票金额',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'unInvoiceMoney',
									display : '待开票金额',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'incomeMoney',
									display : '到款金额',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'unIncomeMoney',
									display : '未收金额',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'orderPrincipal',
									display : '合同负责人',
									sortable : true,
									width : 80
								}, {
									name : 'areaPrincipal',
									display : '区域负责人',
									sortable : true,
									width : 80
								}, {
									name : 'areaName',
									display : '合同区域',
									sortable : true,
									width : 80
								}, {
									name : 'ExaStatus',
									display : '审批状态',
									sortable : true,
									width : 80
								}, {
									name : 'objCode',
									display : '业务编号',
									width : 120
								}],
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);