var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		action: "myListJson",
		title: '我的增员申请',
		//列信息
		isAddAction: true,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'formCode',
			display: '单据编号',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		}, {
			name: 'stateC',
			display: '状态',
			width: 60
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 70
		}, {
			name: 'deptNameO',
			display: '直属部门',
			width: 70,
			sortable: true
		}, {
			name: 'deptNameS',
			display: '二级部门',
			width: 70,
			sortable: true
		}, {
			name: 'deptNameT',
			display: '三级部门',
			width: 70,
			sortable: true
		}, {
			name: 'deptNameF',
			display: '四级部门',
			width: 70,
			sortable: true
		}, {
			name: 'positionName',
			display: '需求职位',
			sortable: true
		}, {
			name: 'needNum',
			display: '需求人数',
			sortable: true,
			width: 60,
			process: function (v) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'entryNum',
			display: '已入职人数',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'beEntryNum',
			display: '待入职人数',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'stopCancelNum',
			display: '暂停/取消人数',
			sortable: true,
			width: 90
		}, {
			name: 'ingtryNum',
			display: '在招聘人数',
			width: 60,
			sortable: true,
			process: function (v, row) {
				return row.needNum - row.entryNum - row.beEntryNum - row.stopCancelNum;
			}
		}, {
			name: 'userName',
			display: '录用名单',
			sortable: true,
			width: 200,
			align: 'left',
			process: function (v, row) {
				if (v == '') {
					return row.employName;
				} else if (row.employName == '') {
					return v;
				} else {
					return v + ',' + row.employName;
				}
			}
		}, {
			name: 'createTime',
			display: '录用日期',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'projectGroup',
			display: '所在项目组',
			sortable: true
		}, {
			name: 'workPlace',
			display: '工作地点',
			sortable: true,
			width: 150
		}, {
			name: 'hopeDate',
			display: '希望到岗时间',
			sortable: true
		}, {
			name: 'addType',
			display: '增员类型',
			sortable: true
		}, {
			name: 'recruitManName',
			display: '招聘负责人',
			sortable: true
		}, {
			name: 'assistManName',
			display: '招聘协助人',
			sortable: true,
			width: 300
		}],

		lockCol: ['formCode', 'stateC', 'ExaStatus'], //锁定的列名

		toAddConfig: {
			toAddFn: function (p, g) {
				alert("您好，新OA已上线，请到新OA提交申请。谢谢！");
				return false;
				showModalWin("?model=hr_recruitment_apply&action=toAdd", 1);
			}
		},

		comboEx: [{
			text: '审批状态',
			key: 'ExaStatus',
			data: [{
				text: '未提交',
				value: '未提交'
			}, {
				text: '部门审批',
				value: '部门审批'
			}, {
				text: '完成',
				value: '完成'
			}]
		}],

		//扩展右键
		menusEx: [{
			text: '查看',
			icon: 'view',
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toView&id=" + row.id + "&skey=" + row['skey_'], 1);
				}
			}
		}, {
			text: '修改',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "未提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
//				if (row) {
//					showModalWin("?model=hr_recruitment_apply&action=toEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
//				}
				if (window.confirm(("您好，新OA已上线，跳转到新OA重新提交申请?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		}, {
			text: '修改',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "完成") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
//				if (row) {
//					if (row.deptId == '130' || row.postType == 'YPZW-WY') {
//						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'], 1);
//					} else {
//						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
//					}
//				}
				if (window.confirm(("您好，新OA已上线，跳转到新OA重新提交申请?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		}, {
			name: 'sumbit',
			text: '提交',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.stateC == '保存') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
//					if (window.confirm("确认要提交?")) {
//						$.ajax({
//							type: "POST",
//							url: "?model=hr_recruitment_apply&action=ajaxSubmit",
//							data: {
//								id: row.id
//							},
//							success: function (msg) {
//								if (msg == 1) {
//									alert('提交成功!');
//									show_page();
//								} else {
//									alert('提交失败!');
//									show_page();
//								}
//							}
//						});
//					}
					if (window.confirm(("您好，新OA已上线，跳转到新OA重新提交申请?"))) {
						location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '删除',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.stateC == "保存") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_recruitment_apply&action=ajaxdeletes",
							data: {
								id: row.id
							},
							success: function (msg) {
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
		}, {
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_apply&pid=" + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}, {
			text: '暂停',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id=" + row.id
						+ "&state=3&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		}, {
			text: '取消',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id=" + row.id
						+ "&state=7&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		}, {
			text: '启用',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 3) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id=" + row.id
						+ "&state=2&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		}],

		searchitems: [{
			display: "单据编号",
			name: 'formCode'
		}, {
			display: "直属部门",
			name: 'deptNameO'
		}, {
			display: "二级部门",
			name: 'deptNameS'
		}, {
			display: "三级部门",
			name: 'deptNameT'
		}, {
			display: "四级部门",
			name: 'deptNameF'
		}, {
			display: "职位类型",
			name: 'postTypeName'
		}, {
			display: "需求职位",
			name: 'positionName'
		}, {
			display: "工作地点",
			name: 'workPlaceSearch'
		}, {
			display: "所在项目组",
			name: 'projectGroupSearch'
		}, {
			display: '增员类型',
			name: 'addType'
		}, {
			display: '招聘负责人',
			name: 'recruitManName'
		}, {
			display: '招聘协助人',
			name: 'assistManNameSearch'
		}],

		sortname: 'id',
		sortorder: 'desc'
	});
});