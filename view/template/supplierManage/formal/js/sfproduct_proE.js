// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".sfproductGrid").yxgrid("reload");
};
$(function() {
			$(".sfproductGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
//						 '?model=supplierManage_formal_sfproduct&action=pageJson&parentId='+$("#parentId").val(),
						 model : 'supplierManage_formal_sfproduct',
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
									display : '产品名称',
									name : 'productName',
									sortable : true
								}
//								,{
//									display : '产品类型',
//									name : 'busiCode',
//									sortable : true
//								},{
//									display : '产品型号',
//									name : 'email',
//									sortable : true
//								}, {
//									display : '产品简介',
//									name : 'plane',
//									sortable : true
//								}, {
//									display : '传真',
//									name : 'fax',
//									sortable : true
//								}
								],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [],
						//快速搜索
						searchitems : [{
									display : '产品名称',
									name : 'productName'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '产品名称',
						//默认搜索字段名
						sortname : "productName",
						//默认搜索顺序
						sortorder : "ASC",
						isViewAction :false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : true,
						toViewConfig : {
							action : 'toRead',
							formWidth : 400,
							formHeight : 400
						},
						toAddConfig : {
									text : '新增',
									/**
									 * 默认点击新增按钮触发事件
									 */

									toAddFn : function(p) {
										showThickboxWin("?model=supplierManage_formal_sfproduct&action=toAdd" +
												"&parentId=" + $("#parentId").val() +"&parentCode="+$("#parentCode").val()+
												"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600");

									},
									/**
									 * 新增表单调用的后台方法
									 */
									action : 'toAdd',
									plusUrl : '?model=supplierManage_formal_sfproduct'
						}
					});

		});