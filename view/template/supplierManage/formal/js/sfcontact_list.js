// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".sfcontactGrid").yxgrid("reload");
};
$(function() {
			$(".sfcontactGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
//						 '?model=supplierManage_formal_sfcontact&action=pageJson&parentId='+$("#parentId").val(),
						 model : 'supplierManage_formal_sfcontact',
						action : 'pageJson&parentId='+$("#parentId").val(),
//						isToolBar : false,
						//显示查看按钮
						isViewAction : true,
						//隐藏添加按钮
						isAddAction : false,
						//隐藏删除按钮
						isDelAction : false,
						showcheckbox:false,
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '联系人姓名',
									name : 'name',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.name;
									}
								},{
									display : '职位',
									name : 'position',
									sortable : true
								},{
									display : '供应商编号',
									name : 'busiCode',
									sortable : true,
									hide : true
								},{
									display : '联系电话',
									name : 'mobile1',
									sortable : true
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
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [],
						//快速搜索
						searchitems : [{
									display : '联系人姓名',
									name : 'name'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "updateTime",
						//默认搜索顺序
						sortorder : "DESC",
						//显示查看按钮
						isViewAction : true,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						//查看扩展信息
						toViewConfig : {
							action : 'toRead',
							formWidth : 500,
							formHeight : 340
						}

					});

		});