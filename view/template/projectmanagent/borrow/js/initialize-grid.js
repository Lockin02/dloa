var show_page = function(page) {
	$("#initializeGrid").yxsubgrid("reload");
};
$(function() {
			$("#initializeGrid").yxsubgrid({
				model : 'projectmanagent_borrow_borrow',
				param : {
					"initTip" : "1"
				},
				title : '借试用初始化数据表',
				isAddAction : false,
				isViewAction : false,
				isEditAction : false,
				isDelAction : false,
				showcheckbox : false,
				customCode : "initializeGrid",
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'limits',
							display : '类型',
							sortable : true
						}, {
							name : 'Code',
							display : '编号',
							sortable : true
						}, {
							name : 'customerName',
							display : '客户名称',
							sortable : true
						}, {
							name : 'salesName',
							display : '销售负责人',
							sortable : true
						}, {
							name : 'createName',
							display : '申请人',
							sortable : true
						}, {
							name : 'beginTime',
							display : '开始日期',
							sortable : true
						}, {
							name : 'closeTime',
							display : '截止日期',
							sortable : true
						}, {
							name : 'ExaStatus',
							display : '审批状态',
							sortable : true,
							width : 90
						}, {
							name : 'DeliveryStatus',
							display : '发货状态',
							sortable : true,
							process : function(v) {
								if (v == '0') {
									return "未发货";
								} else if (v == '1') {
									return "已发货";
								} else if (v == '2') {
									return "部分发货";
								}
							}
						}, {
							name : 'remark',
							display : '备注',
							sortable : true
						}, {
							name : 'objCode',
							display : '业务编号',
							width : 120
						}],
				comboEx : [{
							text : '审批状态',
							key : 'ExaStatus',
							data : [{
										text : '未审批',
										value : '未审批'
									}, {
										text : '部门审批',
										value : '部门审批'
									}, {
										text : '完成',
										value : '完成'
									}]
						}, {
							text : '发货状态',
							key : 'DeliveryStatus',
							data : [{
										text : '未发货',
										value : '0'
									}, {
										text : '已发货',
										value : '1'
									}, {
										text : '部分发货',
										value : '2'
									}]
						}, {
							text : '借试用类型',
							key : 'limits',
							data : [{
										text : '客户',
										value : '客户'
									}, {
										text : '员工',
										value : '员工'
									}]
						}],
				// 主从表格设置
				subGridOptions : {
					url : '?model=projectmanagent_borrow_borrowequ&action=pageJson',// 获取从表数据url
					// 传递到后台的参数设置数组
					param : [{
								paramId : 'borrowId',// 传递给后台的参数名称
								colId : 'id'// 获取主表行数据的列名称

							}],
					// 显示的列
					colModel : [{
								name : 'productNo',
								width : 200,
								display : '产品编号',
								process : function(v, row) {
									return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
								}
							}, {
								name : 'productName',
								width : 200,
								display : '产品名称',
								process : function(v, row) {
									return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
								}
							}, {
								name : 'number',
								display : '申请数量',
								width : 80
							}, {
								name : 'executedNum',
								display : '已执行数量',
								width : 80
							}, {
								name : 'backNum',
								display : '已归还数量',
								width : 80
							}]
				},
				/**
				 * 快速搜索
				 */
				searchitems : [{
							display : '编号',
							name : 'Code'
						}, {
							display : '客户名称',
							name : 'customerName'
						}, {
							display : '业务编号',
							name : 'objCode'
						}, {
							display : '销售负责人',
							name : 'salesName'
						}, {
							display : '申请人',
							name : 'createNmae'
						}, {
							display : '申请日期',
							name : 'createTime'
						}, {
							display : 'K3物料名称',
							name : 'productNameKS'
						}, {
							display : '系统物料名称',
							name : 'productName'
						}, {
							display : 'K3物料编码',
							name : 'productNoKS'
						}, {
							display : '系统物料编码',
							name : 'productNo'
						}],
				buttonsEx : [{
					text : "初始化数据",
					icon : 'edit',
					action : function() {
						window.open("?model=projectmanagent_borrow_borrow&action=initializeBorrowData"
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height=100&width=100")
					}
				}, {
					name : 'import',
					text : "借试用数据导入",
					icon : 'excel',
					action : function(row) {
						showThickboxWin("?model=projectmanagent_borrow_initialize&action=toImportExcel"
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
					}
				}, {
					name : 'renew',
					text : "更新归还数量",
					icon : 'edit',
					action : function(row) {
						if (confirm("确认更新归还数量？")) {
							$.ajax({
										url : '?model=projectmanagent_borrow_initialize&action=renewInitBorrowEquNum',
										success : function(data) {
											if (data == 1) {
												alert("更新成功.");
											} else {
												alert("更新失败." + data);
											}
										}
									});
						}
					}
				}],
				// 扩展右键菜单

				menusEx : [{
							text : '查看',
							icon : 'view',
							action : function(row) {
								if (row) {
									showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id=" + row.id + "&skey=" + row['skey_']);

								}
							}

						}],
				toEditConfig : {
					action : 'toEdit'
				},
				toViewConfig : {
					action : 'toView'
				}
			});
		});