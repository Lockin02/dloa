/**
 * 维修申请单下拉combogrid
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_reduce', {
				options : {
					hiddenId : 'id',
					nameCol : 'docCode',
					gridOptions : {
						model : 'service_repair_repairapply',

						// 表单
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '维修申请单编号',
									name : 'docCode',
									sortable : true,
									width : 150
								}, {
									display : '客户名称',
									name : 'customerName',
									sortable : true,

									width : 150
								}, {
									display : '客户地址',
									name : 'adress',
									sortable : true,
									width : 150
								}, {
									display : '申请人名称',
									name : 'applyUserName',
									sortable : true,
									width : 150
								}, {
									display : '维修费用',
									name : 'subCost',
									sortable : true,
									width : 150
								}],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : '维修申请单编号',
									name : 'docCode'
								}],
						pageSize : 10,
						sortorder : "desc",
						title : '所有客户联系人'
					}
				}
			});
})(jQuery);