var show_page = function(page) {
	$("#currencyGrid").yxgrid("reload");
};
$(function() {
	$("#currencyGrid").yxgrid({
		model : 'system_currency_currency',
		title : '货币换算',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'Currency',
			display : '币别',
			sortable : true
		}, {
			name : 'currencyCode',
			display : '币别编码',
			sortable : true
		}, {
			name : 'rate',
			display : '汇率',
			sortable : true
		}, {
			name : 'standard',
			display : '本位币',
			sortable : true
		}]
	});
});