/**
 * 借试用基本下拉表格
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_present', {
		options : {
			hiddenId : 'id',
			nameCol : 'Code',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_present_present',
				action : 'pageJson',
				param : {
					"ExaStatus" : "完成"
				},
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '编号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'salesName',
					display : '申请人',
					sortable : true
				}, {
					name : 'reason',
					display : '申请理由',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 120
				}],
				// 快速搜索
				searchitems : [{
					display : '源单编号',
					name : 'orderCode'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '业务编号',
					name : 'objCode'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);