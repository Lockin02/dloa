/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_contract', {
		options : {
			hiddenId : 'contractId',
			nameCol : 'contName',
			gridOptions : {
				model : 'contract_sales_sales',
				// 列信息
				colModel : [{
							display : '订单编号',
							name : 'temporaryNo',
							sortable : true,
							hide :true
						}, {
							display : '合同号',
							name : 'contNumber',
							width:130
						}, {
							display : '合同名称',
							name : 'contName',
							sortable : true,
							width:130
						}, {
							display : '合同单位Id',
							name : 'customerId',
							hide :true
						}, {
							display : '负责Id',
							name : 'principalId',
							sortable : true,
							hide :true
						}, {
							display : '负责人',
							name : 'principalName',
							sortable : true
						}, {
							display : '客户名称',
							name : 'customerName',
							sortable : true,
							width:170
						}, {
							display : '合同状态',
							name : 'contStatus',
							sortable : true,
							hide:true
						}],
				// 快速搜索
				searchitems : [{
							display : '合同号',
							name : 'contNumber'
						}, {
							display : '合同名称',
							name : 'contName',
							isdefault : true
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);