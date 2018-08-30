// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#supplinkmanGrid").reload();
};
$(function() {
			$(".supplinkmanGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_register_supplinkman',
						// action : 'pageJson',
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '名称',
									name : 'name',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.name;
									}
								},{
									display : '邮箱地址',
									name : 'email',
									sortable : true
								}, {
									display : '座机',
									name : 'plane',
									sortable : true
								}, {
									display : '传真',
									name : 'fax',
									sortable : true
								}],
						//扩展按钮
						buttonsEx : [{
									name : 'goon',
									text : "下一步",
									icon : 'add',
									action : function(){
										location="?model=supplierManage_register_suppProducts&action=toAdd";
									}
								}],
						//扩展右键菜单
						menusEx : [{
									text : '提交审批',
						action : function(row) {
							}
							}],
						//快速搜索
						searchitems : [{
									display : '名称',
									name : 'name'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '供应商联系人',
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