/**
 * 下拉产品采购发票组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invoiceYearPlan.js', {
		options : {
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invoice_yearPlan',
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '年度',
							name : 'year'
						}, {
							display : '第一季度',
							name : 'salesOne',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '第二季度',
							name : 'salesTwo',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '第三季度',
							name : 'salesThree',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '第四季度',
							name : 'salesFour',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '销售可开金额',
							name : 'salesAll',
							process : function(v){
								return moneyFormat2(v);
							}
						}, {
							display : '第一季度',
							name : 'serviceOne',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '第二季度',
							name : 'serviceTwo',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '第三季度',
							name : 'serviceThree',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '第四季度',
							name : 'serviceFour',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '服务可开金额',
							name : 'serviceAll',
							process : function(v){
								return moneyFormat2(v);
							}
						}],
				// 快速搜索
				searchitems : [{
							display : '年',
							name : 'year'
						}]
			}
		}
	});
})(jQuery);