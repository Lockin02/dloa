/**
 * 下拉产品采购发票组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invpurchase', {
		options : {
			hiddenId : 'invpurId',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invpurchase_invpurchase',
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '发票编号',
							name : 'objCode',
							width : 160
						},{
							display : '供应商名称',
							name : 'supplierName',
							width : 130
						}, {
							display : '日期',
							name : 'formDate'
						}, {
							display : '发票金额',
							name : 'amount',
							process : function(v){
								return moneyFormat2(v);
							}
						}],
				// 快速搜索
				searchitems : [{
							display : '发票编号',
							name : 'objCode'
						}],
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);