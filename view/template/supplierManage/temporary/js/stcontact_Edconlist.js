// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".stcontactGrid").yxgrid("reload");
};
$(function() {
			$(".stcontactGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
//						 '?model=supplierManage_formal_sfcontact&action=pageJson&parentId='+$("#parentId").val(),
						model : 'supplierManage_temporary_stcontact',
						action : 'pageJson&parentId='+$("#parentId").val()+"&parentCode="+$("#parentCode").val(),
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},{
									display : 'parentId',
									name : 'parentId',
									sortable : true,
									hide : true
								},{
									display : 'parentCode',
									name : 'parentCode',
									sortable : true,
									hide : true
								}, {
									display : '联系人姓名',
									name : 'name',
									sortable : true
//									//特殊处理字段函数
//									process : function(v,row) {
//										return row.name;
//									}
								},{
									display : '业务编号',
									name : 'busiCode',
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
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=supplierManage_temporary_stcontact&action=init"
										+ "&id="
										+ row.id
										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 300 + "&width=" + 640);
								}else{
									alert("请选中一条数据");
								}
							}

						},
						{
							text : '编辑',
							icon : 'edit',
							action : function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=supplierManage_temporary_stcontact&action=init"
										+ "&id="
										+ row.id
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 400 + "&width=" + 640);
								}else{
									alert("请选中一条数据");
								}
							}
						}

						],
						//显示查看按钮
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : true,
						//查看扩展信息
						toViewConfig : {
							action : 'toRead',
							formWidth : 400,
							formHeight : 340
						},
						toAddConfig : {
									text : '新增',
									/**
									 * 默认点击新增按钮触发事件
									 */

									toAddFn : function(p) {
										showThickboxWin("?model=supplierManage_temporary_stcontact&action=tordAdd" +
												"&parentId=" + $("#parentId").val() +"&parentCode="+$("#parentCode").val()+
												"&placeValuesBefore&TB_iframe=true&modal=false&height=340&width=500");

									},
									/**
									 * 新增表单调用的后台方法
									 */
									action : 'tordAdd',
									plusUrl : '?model=supplierManage_formal_sfcontact'
						},
						toEditConfig : {
							formWidth : 400,
							formHeight : 320,
							action : 'toEdEdit'
						},
						//快速搜索
						searchitems : [{
									display : '联系人姓名',
									name : 'name'
								}],
						 title : '供应商联系人信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "updateTime",
						//默认搜索顺序
						sortorder : "DESC"
					});

		});