// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#supplierGrid").reload();
};
$(function() {
			$(".supplierGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_register_register',
						// action : 'pageJson',
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '供应商名称',
									name : 'suppName',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.suppName;
									}
								}, {
									display : '业务编号',
									name : 'basiCode',
									sortable : true
								}, {
									display : '主要产品',
									name : 'products',
									sortable : true
								}, {
									display : '地址',
									name : 'address',
									sortable : true
								}, {
									display : '传真',
									name : 'fax',
									sortable : true
								}, {
									display : '审核状态',
									name : 'ExaStatus',
									sortable : true
								}, {
									display : '供货生效日期',
									name : 'effectDate',
									sortable : true
								}, {
									display : '供货失效日期',
									name : 'failureDate',
									sortable : true
								}],
						//扩展按钮
//						buttonsEx : [{
//									name : 'Add',
//									text : "扩展按钮1",
//									icon : 'add'
//								}, {
//									separator : true
//								}, {
//									name : 'Delete',
//									text : "扩展按钮2",
//									icon : 'delete'
//								}],
						//扩展右键菜单
						menusEx : [{
									text : '提交审批',
						action : function(row) {
							}
							}],
						//快速搜索
						searchitems : [{
									display : '客户类型',
									name : 'customerType'
								}, {
									display : '客户名称',
									name : 'Name',
									isdefault : true
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '供应商',
						//默认搜索字段名
						sortname : "id",
						//默认搜索顺序
						sortorder : "ASC",
						//添加扩展信息
						toAddConfig : {
			// 添加信息在此扩展
						// action:123
						}
					});

		});