/**
 * 下拉产品采购发票组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_logistics.js', {
		options : {
			hiddenId : 'id',
			nameCol : 'companyName',
			gridOptions : {
				showcheckbox : false,
				model : 'mail_logistics_logistics',
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '公司名称',
							name : 'companyName'
						}, {
							display : '联系电话',
							name : 'phone'
						},{
							display : '承运范围',
							name : 'rangeDelivery',
							width : 120
						},{
							display : '货运速度',
							name : 'speed'
						}, {
							display : '货运安全性',
							name : 'security',
							width : 80
						}],
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);