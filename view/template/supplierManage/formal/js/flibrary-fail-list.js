// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#flibraryFailGrid").yxgrid("reload");
};
$(function() {
			$("#flibraryFailGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_formal_flibrary',

					    action : 'suppcontJson',
						title:'不合格供应商库',
						showcheckbox : false,	//取消显示checkbox
						isToolBar : false,		//取消显示列表上方的工具栏
						param:{"suppGrade":"D"},


						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'manageUserId',
								name : 'manageUserId',
								sortable : true,
								hide : true
							},{
								display : '供应商编号',
								name : 'busiCode',
								sortable : true
							},{
								display : '供应商名称',
								name : 'suppName',
								sortable : true,
								//特殊处理字段函数
								process : function(v,row){
									return "<a href='#' onclick='showThickboxWin(\"?model=supplierManage_formal_flibrary&action=toRead&id="+row.id+"&objCode="
											+row.objCode+"&skey="+row['skey_']+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + row.suppName+ "</a>";
								},
								width:"200"
							},{
								display : '供应商类别',
								name : 'suppCategoryName'
							},{
								display : '主营产品',
								name : 'products',
								sortable : true,
								//特殊处理字段函数
								process : function(v,row) {
									return row.products;
								},
								width:"200"
							},{
								display : '联系人',
								name : 'linkman',
								sortable : true
							},  {
								display : '职位',
								name : 'position'
							},{
								display : '联系电话',
								name : 'mobile1'
							},{
								display : '注册人',
								name : 'createName',
								sortable : true
							}, {
								display : '注册时间',
								name : 'createTime',
								sortable : true,
								width:120
							}
						],
						//扩展右键菜单
						menusEx : [{
									text : '查看',
									icon : 'view',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="+row.id+"&objCode="
											+row.objCode+"&skey="+row['skey_']+"&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
										}else{
										   alert("请选中一条数据");
										}
									}

								}
//								,{
//									text : '删除供应商',
//									icon : 'delete',
//									action : function(row) {
//										if(confirm('确认删除？')){
//											$.ajax({
//												type : "POST",
//												url : "?model=supplierManage_formal_flibrary&action=delSupplier",
//												data : {
//													id : row.id
//												},
//												success : function(msg) {
//													if (msg == 1) {
//														alert('删除成功！');
//														$(".flibraryGrid").yxgrid("reload");
//													}else if(msg ==0){
//														alert('删除失败!');
//													}else if(msg ==2){
//														alert('没有权限进行操作!');
//													}
//												}
//											});
//										}
//									}
//								}
		],
						//快速搜索
						searchitems : [{
									display : '供应商编号',
									name : 'busiCode'
								},{
									display : '供应商名称',
									name : 'suppName'
								},{
                            display : '曾用名',
                            name : 'usedName'
                        }],
						// title : '客户信息',
						//业务对象名称
						boName : '供应商名称',
						//默认搜索字段名
						sortname : "updateTime",
						//默认搜索顺序
						sortorder : "DESC",
						//显示查看按钮
						isViewAction : false,
						//隐藏添加按钮
						isAddAction : false,
						//隐藏删除按钮
						isDelAction : false,
						isEditAction : false,
						//查看扩展信息
						toViewConfig : {
											text : '查看',
											/**
											 * 默认点击查看按钮触发事件
											 */
											toViewFn : function(p, g) {
												var c = p.toViewConfig;
												var w = c.formWidth ? c.formWidth : p.formWidth;
												var h = c.formHeight ? c.formHeight : p.formHeight;
												var rowObj = g.getSelectedRow();
												if (rowObj) {
													showThickboxWin("?model="
															+ p.model
															+ "&action="
															+ p.toViewConfig.action
															+ c.plusUrl
															+ "&id="
															+ rowObj.data('data').id
															+"&objCode="
															+ rowObj.data('data').objCode
															+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
															+ 600 + "&width=" + 800);
												} else {
													alert('请选择一行记录！');
												}
											},
											/**
											 * 加载表单默认调用的后台方法
											 */
											action : 'toRead'
			}
					});

		});