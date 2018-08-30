/**
 * 下拉产品采购发票组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invoice.js', {
		options : {
			hiddenId : 'id',
			nameCol : 'invoiceNo',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invoice_invoice',
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '发票号码',
							name : 'invoiceNo'
						}, {
							display : '发票单据编号',
							name : 'invoiceCode',
							width : 140
						}, {
							display : '客户名称',
							name : 'invoiceUnitName'
						},{
							display : '关联业务单据编号',
							name : 'objCode',
							width : 140
						},{
							display : '关联单据类型',
							name : 'objType',
							datacode : 'KPRK',
							hide : true
						}, {
							display : '日期',
							name : 'invoiceTime'
						}],
				// 快速搜索
				searchitems : [{
							display : '发票编号',
							name : 'invoiceNo'
						}],
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);