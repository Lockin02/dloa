/**
 * 借试用基本下拉表格
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_borrow', {
				options : {
					hiddenId : 'id',
					nameCol : 'Code',
					gridOptions : {
						showcheckbox : false,
						model : 'projectmanagent_borrow_borrow',
						action : 'pageJson',
						param : {
							"ExaStatus" : "完成",
							"limits" : "客户"
						},
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'chanceId',
									display : '商机Id',
									sortable : true,
									hide : true
								}, {
									name : 'Code',
									display : '编号',
									sortable : true
								}, {
									name : 'Type',
									display : '类型',
									sortable : true
								}, {
									name : 'customerName',
									display : '客户名称',
									sortable : true
								}, {
									name : 'salesName',
									display : '销售负责人',
									sortable : true
								}, {
									name : 'scienceName',
									display : '技术负责人',
									sortable : true
								}, {
									name : 'remark',
									display : '备注',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '审批状态',
									sortable : true,
									width : 90,
									hide : true
								}, {
									name : 'ExaDT',
									display : '审批时间',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '创建人',
									sortable : true,
									hide : true
								}],
						// 快速搜索
						searchitems : [{
									display : '编号',
									name : 'Code'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);