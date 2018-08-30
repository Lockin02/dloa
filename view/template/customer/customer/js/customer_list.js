// 用于新增/修改后回调刷新表格

var show_page = function(page) {
	$(".customerGrid").yxgrid('reload');
};
$(function() {
			$(".customerGrid").yxgrid({
						// 如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						// param : {
						// status : 1
						// },
						model : 'customer_customer_customer',
						// showcheckbox:false,
						// action : 'pageJson',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},  {
									display : '客户编号',
									name : 'objectCode',
									sortable : true
								}, {
									display : '客户名称',
									name : 'Name',
									sortable : true,
									// 特殊处理字段函数
									process : function(v, row) {
										return row.Name;
									}
								}, {
									display : '区域负责人',
									name : 'AreaLeader',
									sortable : true
								}, {
									display : '销售工程师',
									name : 'SellMan',
									sortable : true
								}, {
									display : '客户性质',
									name : 'TypeOne',
									datacode : 'KHLX',// 数据字典编码
									sortable : true
								}, {
									display : '省份',
									name : 'Prov',
									sortable : true
								}],
						comboEx : [{
							text : "客户类型",
							key : 'TypeOne',
							datacode : 'KHLX'
								// value : 'XTS'
								// data : [{
								// text : 'A级客户',
								// value : 'A'
								// }, {
								// text : 'B级客户',
								// value : 'B'
								// }]
							}, {
							text : "客户类型1",
							key : 'TypeOne',
							datacode : 'KHLX'
								// value : 'XTS'
								// data : [{
								// text : 'A级客户',
								// value : 'A'
								// }, {
								// text : 'B级客户',
								// value : 'B'
								// }]
							}],
						// 扩展按钮
						buttonsEx : [{
									name : 'Add',
									// hide : true,
									text : "扩展按钮1",
									icon : 'add',
									/**
									 * row 最后一条选中的行 rows 选中的行（多选） rowIds
									 * 选中的行id数组 grid 当前表格实例
									 */
									action : function(row, rows, rowIds, grid) {
										$.showDump(rows)
									}
								}, {
									separator : true
								}, {
									name : 'Delete',
									text : "扩展按钮2",
									icon : 'delete',
									action : function() {
										alert(333)
									}
								}],
						// 扩展右键菜单
						menusEx : [{
									text : '扩展菜单',
									hide : true,
									/**
									 * row 最后一条选中的行 rows 选中的行（多选） rowIds
									 * 选中的行id数组 grid 当前表格实例
									 */
									action : function(row, rows, rowIds, grid) {
										$.showDump(rowIds);
									}
								}],
						// 快速搜索
						searchitems : [{
									display : '客户类型',
									name : 'customerType'
								}, {
									display : '客户名称',
									name : 'Name',
									isdefault : true
								}],
						// title : '客户信息',
						// 业务对象名称
						boName : '客户',
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC",
						// 添加扩展信息
						toAddConfig : {
							text : 123,
							toAddFn : function() {
								alert(123)
							}
						}
					});

		});