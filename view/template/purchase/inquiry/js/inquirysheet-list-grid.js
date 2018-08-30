
(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_inquirysheet', {
		options : {
			model : 'purchase_inquiry_inquirysheet',
			action : 'myPageJson',
			isTitle : false,
			isToolBar : false,

			// 列信息
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '询价单编号',
				name : 'inquiryCode',
				sortable : true,
				width : 160
			}, {
				display : '采购员',
				name : 'purcherName',
				sortable : true
			}, {
				display : '询价日期',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : '报价截止日期',
				name : 'inquiryEndDate',
				sortable : true
			}, {
				display : '生效日期',
				name : 'effectiveDate',
				sortable : true
			}, {
				display : '失效日期',
				name : 'expiryDate',
				sortable : true
			}, {
				display : '状态',
				name : 'stateName',
//				sortable : true,
				width:60
			}, {
				display : '指定供应商',
				name : 'suppName',
				sortable : true
			}, {
				display : '指定人名称',
				name : 'amaldarName',
				sortable : true
			}, {
				display : '指定备注',
				name : 'amaldarRemark',
				sortable : true,
				hide:true
			}],
			searchitems : [{
				display : '询价单编号',
				name : 'inquiryCode'
			}],
		// 默认搜索顺序
		sortorder : "DESC",
		sortname:"updateTime"

		}
	});
})(jQuery);