var show_page = function(page) {
	$("#procompositebaseGrid").yxgrid("reload");
};
$(function() {
	$("#procompositebaseGrid")
			.yxgrid(
					{
						model : 'stock_extra_procompositebase',
						title : '常用设备备货时间及库存信息',
						isViewAction : false,
						isEditAction : false,

						// 列信息
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'reportName',
							display : '标题',
							sortable : true,
							width : '300'
						}, {
							name : 'activeYear',
							display : '年份',
							sortable : true,
							width : '50'
						}, {
							name : 'periodSeNum',
							display : '时段',
							sortable : true,
							width : '50',
							hide : true
						}, {
							name : 'periodType',
							display : '周期类型',
							sortable : true,
							process : function(v, row) {
								if (v == "0") {
									return "半个月";
								} else if (v == "1") {
									return "一个月";
								} else if (v == "2") {
									return "一个季度";
								} else {
									return v;
								}
							},
							hide : true
						}, {
							name : 'isActive',
							display : '是否生效',
							width:'50',
							process : function(v, row) {
								if (v == "0") {
									return "无效";
								} else {
									return "生效";
								}
							},
							sortable : true
						}, {
							name : 'remark',
							display : '备注',
							sortable : true
						}, {
							name : 'createName',
							display : '编制人',
							sortable : true
						}, {
							name : 'createTime',
							display : '创建日期',
							sortable : true,
							hide : true
						}, {
							name : 'updateName',
							display : '修改人',
							sortable : true
						}, {
							name : 'updateTime',
							display : '更新时间',
							width : '150',
							sortable : true
						} ],
						// 主从表格设置
						// subGridOptions : {
						// url :
						// '?model=stock_extra_procompositebaseitem&action=pageItemJson',
						// param : [ {
						// paramId : 'mainId',
						// colId : 'id'
						// } ],
						// colModel : [ {
						// name : 'XXX',
						// display : '从表字段'
						// } ]
						// },
						toAddConfig : {
							toAddFn : function(p) {
								action: showModalWin("?model=stock_extra_procompositebase&action=toAdd")
							},
							formWidth : 880,
							formHeight : 600
						},
						// toEditConfig : {
						// // action : 'toEdit'
						// toAddFn : function(p) {
						// action:
						// showModalWin("?model=stock_extra_procompositebase&action=toAdd")
						// },
						// },
						toViewConfig : {
							action : function(row) {
								// window
								// .open(
								// "?model=?model=stock_extra_procompositebase&action=toView&id="
								// + row.id, "",
								// "width=200,height=200,top=200,left=200");
							}
						},
						menusEx : [
								{
									name : 'view',
									text : "查看",
									icon : 'view',
									action : function(row, rows, grid) {
										showModalWin("?model=stock_extra_procompositebase&action=toView&id="
												+ row.id
												+ "&skey="
												+ row['skey_']);
									}
								},
								{
									name : 'edit',
									text : "编辑",
									icon : 'edit',
									action : function(row, rows, grid) {
										showModalWin("?model=stock_extra_procompositebase&action=toEdit&id="
												+ row.id
												+ "&skey="
												+ row['skey_']);
									}
								},
								{
									name : 'active',
									text : "激活",
									icon : 'business',
									action : function(row, rows, grid) {
										if (confirm("你确定要激活这份报表，其他报表将失效?")) {
											$
													.ajax({
														type : "POST",
														async : false,
														url : "?model=stock_extra_procompositebase&action=activeReport",
														data : {
															id : row.id
														},
														success : function(
																result) {
															if ("0" == result) {
																alert("激活成功！");
																show_page();
															}

														}
													})
										}
									}

								} ],
						searchitems : [ {
							display : "名称",
							name : 'reportName'
						} ],
						sortname : "id",
						// 默认搜索顺序
						sortorder : "asc"
					});
});