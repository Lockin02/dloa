var show_page = function() {
	$("#esmMyWorklogGrid").yxgrid("reload");
};

$(function() {
	$("#esmMyWorklogGrid").yxgrid({
		model: 'engineering_worklog_esmworklog',
		title: '我的工作日志',
		action: 'myPageJson',
		isDelAction: false,
		isAddAction: false,
		showcheckbox: false,
		customCode: 'myesmworklog',
		isOpButton: false,
		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'executionDate',
			display: '日期',
			sortable: true,
			width: 70,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
			}
		}, {
			name: 'country',
			display: '国家',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'province',
			display: '省',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'city',
			display: '市',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'provinceCity',
			display: '所在地',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'workStatus',
			display: '工作状态',
			sortable: true,
			width: 70,
			datacode: 'GXRYZT',
			hide: true
		}, {
			name: 'projectName',
			display: '项目',
			sortable: true,
			process: function(v) {
				return v;
			},
			width: 140
		}, {
			name: 'activityName',
			display: '任务',
			sortable: true,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='showActivity(" + row.activityId + ")'>" + v + "</a>";
			}
		}, {
			name: 'workloadAndUnit',
			display: '完成量',
			sortable: true,
			width: 60,
			process: function(v, row) {
				return v + " " + row.workloadUnitName;
			}
		}, {
			name: 'workloadDay',
			display: '完成量',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'workloadUnitName',
			display: '工作量单位',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'workProcess',
			display: '进度',
			sortable: true,
			width: 70,
			process: function(v) {
				if (v * 1 == -1) {
					return " -- ";
				} else {
					return v + " %";
				}
			}
		}, {
			name: 'inWorkRate',
			display: '投入工作比例',
			sortable: true,
			width: 70,
			process: function(v) {
				return v + " %";
			}
		}, {
			name: 'description',
			display: '完成情况描述',
			sortable: true,
			width: 150
		}, {
			name: 'remark',
			display: '备注说明',
			sortable: true,
			hide: true
		}, {
			name: 'status',
			display: '周报状态',
			sortable: true,
			width: 60,
			process: function(v) {
				if (v == "WTJ") {
					return "未提交";
				} else if (v == "YTJ") {
					return "已提交";
				} else if (v == 'YQR') {
					return "已确认";
				} else {
					return "不通过";
				}
			},
			hide: true
		}, {
			name: 'confirmStatus',
			display: '确认状态',
			sortable: true,
			width: 60,
			process: function(v) {
				if (v == "1") {
					return "已确认";
				} else {
					return "未确认";
				}
			}
		}, {
			name: 'costMoney',
			display: '录入费用',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (row.confirmStatus == '0') {
					return "<span class='green' title='未确认的费用'>" + moneyFormat2(v) + "</span>";
				} else {
					return "<span class='blue' title='已确认的费用'>" + moneyFormat2(v) + "</span>";
				}
			}
		}, {
			name: 'confirmMoney',
			display: '确认费用',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (row.confirmStatus == '1' && v != row.costMoney) {
					return "<span class='blue'>" + moneyFormat2(v) + "</span>";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name: 'backMoney',
			display: '打回费用',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (v > 0) {
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
//						return "<a href='javascript:void(0)' style='color:red;' onclick='reeditCost(\"" + row.id + "\")' title='点击重新编辑费用'>" + moneyFormat2(v) + "</a>";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name: 'confirmName',
			display: '确认人',
			sortable: true,
			width: 80
		}, {
			name: 'confirmDate',
			display: '确认日期',
			sortable: true,
			width: 70
		}, {
			name: 'assessResultName',
			display: '审核结果',
			sortable: true,
			width: 70
		}, {
			name: 'feedBack',
			display: '审核建议',
			sortable: true
		}, {
			name: 'thisActivityProcess',
			display: '本次任务进度',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'thisProjectProcess',
			display: '本次项目进度',
			sortable: true,
			width: 80,
			hide: true
		}
		],
		toAddConfig: {
			toAddFn: function() {
				var height = 800;
				var width = 1150;
				var url = "?model=engineering_worklog_esmworklog&action=toAdd";
				window.open(url, "鼎利OA",
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
					+ width + ',height=' + height);
			}
		},
		toEditConfig: {
			action: 'toEdit',
			showMenuFn: function(row) {
				return row.status == "WTJ" && row.confirmStatus == "0";
			},
			toEditFn: function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//判断
					if (rowData.activityId == "") {
						var height = 800;
						var width = 1150;
						var url = "?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl;
						window.open(url, "鼎利OA",
							'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
							+ width + ',height=' + height);
					} else {
						$.ajax({
							type: "POST",
							url: "?model=engineering_project_esmproject&action=isClose",
							data: {"id": rowData.projectId},
							async: false,
							success: function(data) {
								if (data == 1) {
									alert('项目已关闭，不能再对此日志进行操作');
								} else {
									var height = 800;
									var width = 1150;
									var url = "?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl;
									window.open(url, "鼎利OA",
										'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
										+ width + ',height=' + height);
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig: {
			action: 'toView',
			toViewFn: function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}

					var url = "?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl;
					showOpenWin(url, 1, 700, 1150);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		buttonsEx: [{
			text: '新增日志',
			icon: 'add',
			action: function() {
				showOpenWin("?model=engineering_worklog_esmworklog&action=toBatchAdd", 1, 668, 1150, '单日多日志新增');
			}
		}, {
			text: '导入日志',
			icon: 'excel',
			action: function() {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}, {
			text: '导出日志',
			icon: 'excel',
			action: function() {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toExportMyLog"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}/*,{
		 text : '导入日志费用',
		 icon : 'excel',
		 action : function() {
		 showThickboxWin("?model=engineering_worklog_esmworklog&action=toCostExcelIn"
		 + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		 }
		 }*/, {
			text: '批量删除日志',
			icon: 'delete',
			action: function() {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toDeleteLog"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}],
		menusEx: [{
			text: '查看周报',
			icon: 'view',
			showMenuFn: function(row) {
				return row.weekId != "";
			},
			action: function(row) {
				showOpenWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
				+ row.weekId);
			}
		}, {
			text: '删除',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.status == "WTJ" && row.confirmStatus == "0";
			},
			action: function(row) {
				if (row.activityId == "") {
					$.ajax({
						type: "POST",
						url: "?model=engineering_worklog_esmworklog&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page();
							} else {
								alert("删除失败! ");
							}
						}
					});
				} else {
					$.ajax({
						type: "POST",
						url: "?model=engineering_project_esmproject&action=isClose",
						data: {"id": row.projectId},
						async: false,
						success: function(data) {
							if (data == 1) {
								alert('项目已关闭，不能再对此日志进行操作');
							} else {
								if (confirm('确定要删除吗？')) {
									$.ajax({
										type: "POST",
										url: "?model=engineering_worklog_esmworklog&action=ajaxdeletes",
										data: {
											id: row.id
										},
										success: function(msg) {
											if (msg == 1) {
												alert('删除成功！');
												show_page();
											} else {
												alert("删除失败! ");
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}],
		searchitems: [{
			display: '填报日期',
			name: 'executionDateSearch'
		}, {
			display: '任务名称',
			name: 'activityNameSearch'
		}, {
			display: '项目名称',
			name: 'projectNameSearch'
		}, {
			display: '所在地',
			name: 'provinceCitySearch'
		}],
		sortorder: "DESC",
		sortname: "executionDate desc,activityName"
	});
});

//进入查看费用页面
//function reeditCost(worklogId) {
//	var url = "?model=engineering_worklog_esmworklog&action=toReeditNew&id=" + worklogId;
//	var height = 800;
//	var width = 1150;
//	window.open(url, "重新编辑费用",
//		'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
//		+ width + ',height=' + height);
//}