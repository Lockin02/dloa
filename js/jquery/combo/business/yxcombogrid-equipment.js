/**
 * 下拉产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_equipment', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_equipment_equipment',
				// 列信息
				colModel : [{
							display : '产品编号',
							name : 'productNumber',
							width : 130
						}, {
							display : '产品名称',
							name : 'productName',
							width : 180
						}, {
							display : '总需求数量',
							name : 'amount'
						}, {
							display : '产品类型',
							name : 'ptype',
							hide : true
						}, {
							display : '产品Id',
							name : 'productId',
							hide : true
						}, {
							display : '产品型号',
							name : 'productModel',
							hide: true
						}, {
							display : '合同标志位',
							name : 'contOnlyId',
							hide: true
						}],
				// 快速搜索
				searchitems : [{
						display : '产品名称',
						name : 'productName'
					}],
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);