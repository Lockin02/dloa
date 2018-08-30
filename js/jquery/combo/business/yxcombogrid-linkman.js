/**
 * 联系人表格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_linkman', {
		options : {
			hiddenId : 'id',
//			param : { 'customerId' :$('customerId').val() },
			nameCol : 'linkmanName',
//			openPageOptions : {
//				url : '?model=customer_linkman_linkman&action=selectLinkman'
//			},
			gridOptions : {
				model : 'customer_linkman_linkman',

					// 表单
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '客户',
					name : 'customerName',
					sortable : true,
					width : 150
				}, {
					display : '姓名',
					name : 'linkmanName',
					sortable : true,

					width : 150
				}, {
					display : '电话号码',
					name : 'phone',
					sortable : true,
					width : 150
				}, {
					display : '手机号码',
					name : 'mobile',
					sortable : true,
					width : 150
				}, {
					display : 'MSN',
					name : 'MSN',
					sortable : true,
					width : 150
				}, {
					display : 'QQ',
					name : 'QQ',
					sortable : true,
					width : 150
				}, {
					display : 'email',
					name : 'email',
					sortable : true,
					width : 150
				}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '姓名',
					name : 'linkmanName'
				}],
				sortorder : "ASC",
				title : '所有客户联系人'
			}
		}
	});
})(jQuery);