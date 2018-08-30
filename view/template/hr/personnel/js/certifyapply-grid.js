var show_page = function (page) {
	$("#certifyapplyGrid").yxgrid("reload");
};

//删除重复项（考虑IE的兼容性问题）
function uniqueArray(a) {
	temp = new Array();
	for (var i = 0; i < a.length; i++) {
		if (!contains(temp, a[i])) {
			temp.length += 1;
			temp[temp.length - 1] = a[i];
		}
	}
	return temp;
}

function contains(a, e) {
	for (j = 0; j < a.length; j++)
		if (a[j] == e) return true;
	return false;
}

$(function () {
	//表头按钮数组
	buttonsArr = [{
		name: 'return',
		text: '认证申请通过',
		icon: 'edit',
		action: function (row, rows, grid) {
			if (rows) {
				var checkedRowsIds = $("#certifyapplyGrid").yxgrid("getCheckedRowIds"); //获取选中的id
				var states = []; //单据状态数组
				$.each(rows, function (i, n) {
					var o = eval(n);
					states.push(o.status);
				});
				states.sort();
				var uniqueState = uniqueArray(states);
				var stateLength = uniqueState.length;
				if (stateLength == 1 && uniqueState[0] == 1) { //判断单据的状态是否为“未审批”并且只有一种状态
					if (window.confirm("确认审批通过?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_personnel_certifyapply&action=aduitPass",
							data: {
								applyIds: checkedRowsIds
							},
							success: function (msg) {
								if (msg == 1) {
									alert('审批成功!');
									show_page();
								} else {
									alert('审批失败!');
									show_page();
								}
							}
						});
					}
				} else {
					alert("请选择状态为'审批中'的单据");
				}


			} else {
				alert("请选择单据。");
			}

		}
	}];

	//excel导入
	excelOutArr = {
		name: 'exportIn',
		text: "导入",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelIn" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};
	//excel导出
	excelOutButton = {
		name: 'exportIn',
		text: "导出",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelout" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=800");
		}
	};
	//excel导入更新
	exportUpdate = {
		name: 'exportUpdate',
		text: "导入更新",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelUpdate" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=800");
		}
	};

	$.ajax({
		type: 'POST',
		url: '?model=hr_personnel_personnel&action=getLimits',
		data: {
			'limitName': '导入权限'
		},
		async: false,
		success: function (data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutButton);
				buttonsArr.push(exportUpdate);
			}
		}
	});

	$("#certifyapplyGrid").yxgrid({
		model: 'hr_personnel_certifyapply',
		action: 'pageJsonForRead',
		title: '任职资格信息',
		isDelAction: false,
		isOpButton: false,
		bodyAlign: 'center',
		param: {
			statusArr: "1,2,3,4,5,6,7,8,9,10,11,12"
		},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'userNo',
			display: '员工编号',
			sortable: true,
			width: 70
		}, {
			name: 'userName',
			display: '员工姓名',
			sortable: true,
			width: 60,
			process: function (v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certifyapply&action=toView&id=" + row.id +
					'&skey=' + row.skey_ +
					"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			name: 'deptName',
			display: '所属部门',
			sortable: true
		}, {
			name: 'jobName',
			display: '现职务',
			sortable: true,
			width: 80
		}, {
			name: 'nowLevelName',
			display: '现属级别',
			sortable: true,
			width: 50
		}, {
			name: 'nowGradeName',
			display: '现属级等',
			sortable: true,
			width: 50
		}, {
			name: 'entryDate',
			display: '入职日期',
			sortable: true,
			width: 70
		}, {
			name: 'applyDate',
			display: '申请日期',
			sortable: true,
			width: 70
		}, {
			name: 'careerDirectionName',
			display: '申请通道',
			sortable: true,
			width: 70
		}, {
			name: 'baseLevelName',
			display: '申请级别',
			sortable: true,
			width: 50
		}, {
			name: 'baseGradeName',
			display: '申请级等',
			sortable: true,
			width: 50
		}, {
			name: 'status',
			display: '单据状态',
			sortable: true,
			width: 80,
			process: function (v) {
				switch (v) {
				case '0':
					return '未提交';
					break;
				case '1':
					return '审批中';
					break;
				case '2':
					return '认证表待生成';
					break;
				case '3':
					return '认证准备中';
					break;
				case '4':
					return '认证待审批';
					break;
				case '5':
					return '认证审批中';
					break;
				case '6':
					return '认证待答辩';
					break;
				case '7':
					return '认证结果审核中';
					break;
				case '8':
					return '认证失败';
					break;
				case '10':
					return '认证已审核';
					break;
				case '11':
					return '完成';
					break;
				case '12':
					return '打回';
					break;
				default:
					return v;
				}
			}
		}, {
			name: 'baseScore',
			display: '考试得分',
			sortable: true,
			process: function (v) {
				if (v == 0) {
					return '';
				} else {
					return v;
				}
			},
			width: 50
		}, {
			name: 'baseResult',
			display: '考试结果',
			sortable: true,
			process: function (v) {
				if (v == '1') {
					return '通过';
				} else if (v == '0') {
					return '不通过';
				}
			},
			width: 50
		}, {
			name: 'finalResult',
			display: '认证结果',
			sortable: true,
			process: function (v) {
				if (v == '1') {
					return '通过';
				} else if (v == '0') {
					return '不通过';
				}
			},
			width: 50
		}, {
			name: 'finalScore',
			display: '认证得分',
			sortable: true,
			process: function (v) {
				if (v == 0) {
					return '';
				} else {
					return v;
				}
			},
			width: 50
		}, {
			name: 'finalCareerName',
			display: '认证通道',
			sortable: true,
			width: 80
		}, {
			name: 'finalLevelName',
			display: '认证级别',
			sortable: true,
			width: 50
		}, {
			name: 'finalGradeName',
			display: '认证级等',
			sortable: true,
			width: 50
		}, {
			name: 'finalTitleName',
			display: '认证称谓',
			sortable: true,
			width: 80
		}, {
			name: 'finalDate',
			display: '认证结果生效日期',
			sortable: true,
			width: 100
		}, {
			name: 'certifyDirectionName',
			display: '认证方向',
			sortable: true,
			width: 100
		}, {
			name: 'backReason',
			display: '打回原因',
			sortable: true,
			width: 220,
			align: 'left'
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 220,
			align: 'left'
		}],

		lockCol: ['userNo', 'userName'], //锁定的列名

		buttonsEx: buttonsArr,
		menusEx: [{
			name: 'view',
			text: "查看认证申请表",
			icon: 'view',
			action: function (row) {
				showModalWin("?model=hr_personnel_certifyapply&action=toViewApply&id=" + row.id)
			}
		}, {
			name: 'delete',
			text: "打回",
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.status == '1') {
					return true;
				}
				return false;
			},
			action: function (row) {
				showThickboxWin("?model=hr_personnel_certifyapply&action=toBackApply&id=" + row.id +
					"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800")
			}
		}],

		toAddConfig: {
			formHeight: 500,
			formWidth: 900
		},
		toEditConfig: {
			action: 'toEdit',
			formHeight: 500,
			formWidth: 900,
			/**
			 * 默认点击编辑按钮触发事件
			 */
			toEditFn: function (p, g) {
				var c = p.toEditConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					if (rowData.ExaDT != '' && rowData.ExaStatus != '未提交') {
						alert('记录已存在审批信息，不允许编辑');
						return false;
					}
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showThickboxWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] +
						keyUrl + "&placeValuesBefore&TB_iframe=true&modal=false&height=" + h + "&width=" + w);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig: {
			action: 'toView',
			formHeight: 500,
			formWidth: 900
		},
		toDelConfig: {
			text: '删除',
			/**
			 * 默认点击删除按钮触发事件
			 */
			toDelFn: function (p, g) {
				var rowIds = g.getCheckedRowIds();
				var rowObj = g.getCheckedRows();
				var key = "";
				if (rowObj.length > 0) {
					for (var i = 0; i < rowObj.length; i++) {
						if (rowObj[i].ExaDT != "") {
							alert('记录[' + rowObj[i].id + ']' + rowObj[i].userName + ' 已经存在审批信息，不允许删除');
							return false;
						}
					}
				}
				if (rowIds[0]) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type: "POST",
							url: "?model=" + p.model + "&action=" + p.toDelConfig.action + p.toDelConfig.plusUrl,
							data: {
								id: g.getCheckedRowIds()
									.toString(),
								skey: key
							},
							success: function (msg) {
								if (msg == 1) {
									alert('删除成功');
									show_page();
								} else {
									alert('删除失败');
									show_page();
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		//下拉过滤
		comboEx: [{
			text: '申请通道',
			key: 'careerDirection',
			datacode: 'HRZYFZ'
		}, {
			text: '申请级别',
			key: 'baseLevel',
			datacode: 'HRRZJB'
		}, {
			text: '认证结果',
			key: 'finalResult',
			data: [{
				text: '不通过',
				value: '0'
			}, {
				text: '通过',
				value: '1'
			}]
		}, {
			text: '认证通道',
			key: 'finalCareer',
			datacode: 'HRZYFZ'
		}, {
			text: '认证级别',
			key: 'finalLevel',
			datacode: 'HRRZJB'
		}],

		searchitems: [{
			display: "员工编号",
			name: 'userNoSearch'
		}, {
			display: "员工姓名",
			name: 'userNameSearch'
		}, {
			display: "所属部门",
			name: 'deptName'
		}, {
			display: "申请日期",
			name: 'applyDateSearch'
		}, {
			display: "申请通道",
			name: 'careerDirectionNameSearch'
		}, {
			display: "认证方向",
			name: 'certifyDirection'
		}, {
			display: "认证结果生效日期",
			name: 'finalDateSearch'
		}, {
			display: "备注",
			name: 'remark'
		}]
	});
});