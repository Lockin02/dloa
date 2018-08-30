/**
 * 联系人表格组件
 */

(function($) {

	$.woo.yxcombogrid.subclass('woo.yxcombogrid_handoverscheme', {
				options : {
					hiddenId : 'id',
					nameCol : 'schemeName',
					title : '离职清单方案',
					showcheckbox : false,
					gridOptions : {
						model : 'hr_leave_handoverScheme&action=page',

						// 表单
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'formCode',
									display : '单据编码',
									sortable : true,
									hide : true
								}, {
									name : 'formDate',
									display : '单据日期',
									sortable : true,
									hide : true
								}, {
									name : 'schemeCode',
									display : '方案编号',
									sortable : true,
									hide : true
								}, {
									name : 'schemeTypeCode',
									display : '方案类型Code',
									sortable : true,
									hide : true
								}, {
									name : 'schemeTypeName',
									display : '方案类型名称',
									sortable : true,
									hide : true
								}, {
									name : 'schemeName',
									display : '方案名称',
									sortable : true
								}, {
									name : 'jobName',
									display : '职位名称',
									sortable : true
								}, {
									name : 'companyName',
									display : '编制（公司）',
									sortable : true
								}, {
									name : 'companyId',
									display : '编制id',
									sortable : true,
									hide : true
								}, {
									name : 'leaveTypeCode',
									display : '离职类型',
									sortable : true,
									hide : true
								}, {
									name : 'leaveTypeName',
									display : '离职类型',
									sortable : true
								}, {
									name : 'state',
									display : '状态',
									sortable : true,
									hide : true
								}, {
									name : 'remark',
									display : '备注',
									width : 250,
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '审核状态',
									sortable : true,
									hide : true
								}, {
									name : 'ExaDT',
									display : '审核日期',
									sortable : true,
									hide : true
								}],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : "方案名称",
									name : 'schemeName'
								}],
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);