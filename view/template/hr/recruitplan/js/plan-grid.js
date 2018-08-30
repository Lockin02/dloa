var show_page = function(page) {
	$("#planGrid").yxgrid("reload");
};
$(function() {
	$("#planGrid")
			.yxgrid(
					{
						isEditAction : false,
						isDelAction : false,
						model : 'hr_recruitplan_plan',
						action:	'MyPageJson',
						title : '招聘计划',
						// 列信息
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'formCode',
									display : '单据编号',
									sortable : true,
									width : 120,
									process : function(v, row) {
										return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitplan_plan&action=toView&id="
												+ row.id + "\")'>" + v + "</a>";
									}
								}, {
									name : 'stateC',
									display : '状态',
									sortable : true,
									width : 60
								}, {
									name : 'ExaStatus',
									display : '审批状态',
									sortable : true,
									width : 70
								}, {
									name : 'deptName',
									display : '需求部门',
									sortable : true
								}, {
									name : 'positionName',
									display : '需求职位',
									sortable : true
								}, {
									name : 'needNum',
									display : '需求人数',
									sortable : true,
									width : 60
								}, {
									name : 'entryNum',
									display : '已入职人数',
									sortable : true,
									width : 70
								}, {
									name : 'beEntryNum',
									display : '待入职人数',
									sortable : true,
									width : 70
								}, {
									name : 'hopeDate',
									display : '希望到岗时间',
									sortable : true
								}, {
									name : 'addType',
									display : '增员类型',
									sortable : true
								}, {
									name : 'recruitManName',
									display : '招聘负责人',
									sortable : true
								}, {
									name : 'assistManName',
									display : '招聘协助人',
									sortable : true,
									width : 300
								}

						],
						lockCol:['formCode','stateC','positionName'],//锁定的列名
						menusEx : [

								/*{
									text : '修改',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == "完成") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											location = "?model=hr_recruitplan_plan&action=toAuditEdit&id="
													+ row.id
													+ "&skey="
													+ row['skey_'];
										}
									}
								},*/
								{
									text : '修改',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == "未提交" || row.ExaStatus == "打回") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showModalWin("?model=hr_recruitplan_plan&action=toEdit&id="
													+ row.id
													+ "&skey="
													+ row['skey_']);
										}
									}
								},
								{
									text : '删除',
									icon : 'delete',
									showMenuFn : function(row) {
										if (row.ExaStatus == "未提交") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											if (window.confirm("确认要删除?")) {
												$
														.ajax( {
															type : "POST",
															url : "?model=hr_recruitplan_plan&action=ajaxdeletes",
															data : {
																id : row.id
															},
															success : function(
																	msg) {
																if (msg == 1) {
																	alert('删除成功!');
																	show_page();
																} else {
																	alert('删除失败!');
																	show_page();
																}
															}
														});
											}
										}
									}
								},
								{
									name : 'sumbit',
									text : '提交审批',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == '未提交'
												|| row.ExaStatus == '打回') {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
										//	console.info( row);
											showThickboxWin('controller/hr/recruitplan/ewf_index.php?actTo=ewfSelect&billDept='
													+ row.deptId+"&billId="+row.id+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=850");
										} else {
											alert("请选中一条数据");
										}
									}
								},
								{
									name : 'aduit',
									text : '审批情况',
									icon : 'view',
									showMenuFn : function(row) {
										if (row.ExaStatus == '打回'
												|| row.ExaStatus == '完成'
												|| row.ExaStatus == '部门审批') {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitplan_plan&pid="
													+ row.id
													+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
										}
									}
								}

						],

						toViewConfig : {
							toViewFn : function(p, g) {
								if (g) {
									var get = g.getSelectedRow().data('data');
									showModalWin("?model=hr_recruitplan_plan&action=toView&id="
											+ get[p.keyField]);
								}
							}
						},
						/*
						 * // 主从表格设置 subGridOptions : { url :
						 * '?model=hr_recruitplan_NULL&action=pageItemJson',
						 * param : [{ paramId : 'mainId', colId : 'id' }],
						 * colModel : [{ name : 'XXX', display : '从表字段' }] },
						 */
						toAddConfig : {
							formHeight : 500,
							formWidth : 900,
							toAddFn : function(p, g) {
								showModalWin("?model=hr_recruitplan_plan&action=toAdd");
							}
						},
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "需求部门",
							name : 'deptName'
						}, {
							display : "需求职位",
							name : 'positionName'
						}, {
							display : '增员类型',
							name : 'addType'
						} ],
						comboEx : [ {
							text : '审批状态',
							key : 'ExaStatus',
							data : [ {
								text : '未提交',
								value : '未提交'
							}, {
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '完成',
								value : '完成'
							} ]
						} ]

					});
});