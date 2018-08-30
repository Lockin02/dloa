/**
 * 测试卡下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_cardsinfo', {
		options : {
			hiddenId : 'id',
			nameCol : 'cardNo',
			gridOptions : {
				model : 'cardsys_cardsinfo_cardsinfo&action=listJson1',
				// 表单
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '卡号',
						name : 'cardNo',
						sortable : true,
						width : 80
					},{
			            name : 'pinNo',
			            display : '密码',
			            sortable : true,
						width : 70
			        } ,{
			            name : 'openerName',
			            display : '开卡人',
			            sortable : true,
						width : 70
			        }, {
			            name : 'cardTypeName',
			            display : '类型',
			            sortable : true,
						width : 70
			        }, {
						name : 'location',
						display : '归属地',
						sortable : true,
						width : 70
					}, {
						name : 'packageType',
						display : '套餐',
						sortable : true,
						width : 70
					}, {
						name : 'ratesOf',
						display : '资费描述',
						sortable : true,
						width : 150
					}
				],
				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '卡号',
					name : 'cardNoSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				title : '测试卡'
			}
		}
	});
})(jQuery);