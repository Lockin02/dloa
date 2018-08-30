/**
 * 下拉发货计划表格
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_ship', {
		options : {
			hiddenId : 'id',
			nameCol : 'shipCode',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_outplan_ship',
				// param : {
				// docType : 'independent'
				// },
				pageSize : 10,
				// 列信息
				colModel : [{
						display : 'id',
						name : 'id',
						hide : true
					}, {
						display : '发货单编号',
						width : 120,
						name : 'shipCode'
					}, {
						display : "源单类型",
						width : 120,
						name : 'docType',
						process : function(v) {
							switch(v){
								case 'oa_sale_order' : return '销售发货';break;
								case 'oa_sale_lease' : return '租赁发货"';break;
								case 'oa_sale_service' : return '服务发货';break;
								case 'oa_sale_rdproject' : return '研发发货';break;
								case 'oa_borrow_borrow' : return '借用发货';break;
								case 'oa_service_accessorder' : return '零配件订单';break;
								case 'oa_service_repair_apply' : return '维修申请单';break;
								default : return '独立发货';
							}
						}
					}, {
						display : '源单Id',
						name : 'docId',
						hide : true
					}, {
						display : '源单编号',
						width : 180,
						name : 'docCode'
					}, {
						display : '源单名称',
						name : 'docName',
						width : 120,
						hide : true
					}, {
						display : '发货方式',
						name : 'shipType',
						width : 80,
						process : function(v) {
							if (v == 'order') {
								return "发货";
							} else if (v == 'borrow') {
								return "借用";
							} else if (v == 'lease') {
								return "租用";
							} else if (v == 'trial') {
								return "试用";
							} else if (v == 'change') {
								return "更换";
							}
						}
					}, {
						display : '客户名称',
						name : 'customerName',
						width : 120
					}, {
						display : '客户id',
						name : 'customerId',
						hide : true
					}],
				// 快速搜索
				searchitems : [{
					display : '发货单编号',
					name : 'shipCode'
				}],
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);