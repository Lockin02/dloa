/**
 * 下拉补表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_fillup', {
				options : {
					hiddenId : 'id',
					nameCol : 'fillupCode',
					gridOptions : {
						model : 'stock_fillup_fillup',
						// 列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								name : 'fillupCode',
								display : '补库计划单号',
								sortable : true
							}, {
								name : 'stockId',
								display : '仓库id',
								sortable : true,
								hide : true
							}, {
								name : 'stockName',
								display : '仓库名称',
								sortable : true
							}, {
								name : 'stockCode',
								display : '仓库代码',
								sortable : true
							}, {
								name : 'remark',
								display : '补库原因与备注',
								sortable : true
							}, {
								name : 'auditStatus',
								display : '提交状态',
								sortable : true,
								hide : true
							}, {
								name : 'ExaStatus',
								display : '补库计划审批状态',
								sortable : true,
								hide : true
							}, {
								name : 'ExaDT',
								display : '审批时间',
								sortable : true,
								hide : true
							}, {
								name : 'updateId',
								display : '修改人id',
								sortable : true,
								hide : true
							}, {
								name : 'updateName',
								display : '修改人',
								sortable : true,
								hide : true
							}, {
								name : 'createTime',
								display : '创建日期',
								sortable : true,
								hide : true
							}, {
								name : 'createName',
								display : '创建人',
								sortable : true,
								hide : true
							}, {
								name : 'createId',
								display : '创建人id',
								sortable : true,
								hide : true
							}, {
								name : 'updateTime',
								display : '修改日期',
								sortable : true,
								hide : true
							}],
						// 快速搜索
						searchitems : [{
									display : '仓库名称',
									name : 'stockName'
								}],
						// 默认搜索字段名
						sortname : "updateTime",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);