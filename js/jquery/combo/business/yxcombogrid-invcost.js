/**
 * 下拉产品采购发票组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invCost.js', {
		options : {
			hiddenId : 'invCostId',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invcost_invcost',
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '发票编号',
							name : 'objCode'
						}, {
							display : '发票号码',
							name : 'objNo'
						},{
							display : '供应商名称',
							name : 'supplierName',
							width : 150
						}, {
							display : '日期',
							name : 'createTime',
							process:function (v){
								return v.substr(0,10);
							},
							width : 80
						},{
							display : '金额',
							name : 'amount',
							process :function(v){
								return moneyFormat2(v);
							},
							width : 80
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