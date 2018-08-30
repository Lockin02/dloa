/**
 * 换货申请 下拉
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_exchange', {
		options : {
			hiddenId : 'id',
			nameCol : 'exchangeCode',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_exchange_exchange',
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
					name : 'exchangeCode',
					display : '换货单编号',
					sortable : true
				}, {
					name : 'contractCode',
					display : '源单号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
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
					display : '换货原因',
					sortable : true
				}],
				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '换货单编号',
					name : 'exchangeCode'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);