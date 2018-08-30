/**
 * 退货申请 下拉
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_return', {
		options : {
			hiddenId : 'id',
			nameCol : 'returnCode',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_return_return',
				action : 'pageJson',
				param : {
					'ExaStatus' : '完成'
				},
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'returnCode',
					display : '退货单编号',
					sortable : true
				}, {
					name : 'contractCode',
					display : '源单号',
					sortable : true
				}, {
					name : 'saleUserName',
					display : '销售负责人',
					sortable : true
				}, {
					name : 'createName',
					display : '创建人',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批日期',
					sortable : true
				}, {
					name : 'reason',
					display : '退料原因',
					sortable : true
				}],
				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '退货单编号',
					name : 'renturnCode'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);