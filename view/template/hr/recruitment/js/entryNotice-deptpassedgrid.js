var show_page = function (page) {
	$("#entryNoticeGrid").yxgrid("reload");
};

$(function () {
	$("#entryNoticeGrid").yxgrid({
		model: 'hr_recruitment_entryNotice',
		title: '部门入职名单',
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		param: {
			stateArr: "2,3"
		},

		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'formCode',
			display: '单据编号',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			},
			width: 120
		}, {
			name: 'resumeCode',
			display: '简历编号',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" +
					v + "\",1)'>" + v + "</a>";
			},
			width: 90
		}, {
			name: 'hrSourceType2Name',
			display: '简历来源小类',
			sortable: true
		}, {
			name: 'entryDate',
			display: '入职日期',
			sortable: true,
			width: 70
		}, {
			name: 'userName',
			display: '姓名',
			sortable: true,
			width: 60
		}, {
			name: 'stateC',
			display: '状态',
			width: 80
		}, {
			name: 'assistManName',
			display: '入职协助人',
			sortable: true,
			width: 60
		}, {
			name: 'sex',
			display: '性别',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'phone',
			display: '联系电话',
			sortable: true,
			hide: true
		}, {
			name: 'email',
			display: '电子邮箱',
			sortable: true,
			hide: true
		}, {
			name: 'deptName',
			display: '用人部门',
			sortable: true,
			width: 80
		}, {
			name: 'workPlace',
			display: '工作地点',
			sortable: true,
			width: 80,
			process: function (v, row) {
				return row.workProvince + ' - ' + row.workCity;
			}
		}, {
			name: 'socialPlace',
			display: '社保购买地',
			sortable: true,
			width: 60
		}, {
			name: 'hrJobName',
			display: '录用职位',
			sortable: true,
			width: 80
		}, {
			name: 'hrIsManageJob',
			display: '是否管理岗',
			sortable: true,
			hide: true,
			hide: true
		}, {
			name: 'useHireTypeName',
			display: '录用形式',
			sortable: true,
			width: 60
		}, {
			name: 'useAreaName',
			display: '归属区域或支撑中心',
			sortable: true
		}, {
			name: 'sysCompanyName',
			display: '归属公司',
			sortable: true,
			width: 60
		}, {
			name: 'personLevel',
			display: '技术等级',
			sortable: true,
			width: 60
		}, {
			name: 'probation',
			display: '试用期(月)',
			sortable: true,
			width: 60
		}, {
			name: 'contractYear',
			display: '合同期限(年)',
			sortable: true,
			width: 60
		}, {
			name: 'useSign',
			display: '签订《竞业限制协议》',
			sortable: true,
			width: 110
		}, {
			name: 'entryRemark',
			display: '入职进度备注',
			sortable: true
		}, {
			name: 'formDate',
			display: '单据日期',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'applyCode',
			display: '职位申请编号',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" +
					row.applyId +
					"\")'>" + v + "</a>";
			},
			hide: true
		}, {
			name: 'developPositionName',
			display: '研发职位',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'useDemandEqu',
			display: '需求设备',
			sortable: true,
			hide: true
		}, {
			name: 'leaveReason',
			display: '员工离职申请原因',
			sortable: true,
			width: 250
		}],

		lockCol: ['formCode', 'userName', 'stateC'], //锁定的列名

		toViewConfig: {
			toViewFn: function (p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_recruitment_entryNotice&action=toView&id=" + rowData[p.keyField]);
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		// 扩展右键
		menusEx: [{
			name: 'resume',
			text: '查看关联简历',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.resumeId > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId, '1');
				}
			}
		}, {
			name: 'jobApply',
			text: '查看关联职位申请',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.applyId > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId, '1');
				}
			}
		}, {
			name: 'apply',
			text: '查看关联增员申请',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.sourceId > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_apply&action=toView&id=' + row.sourceId,
						'1');
				}
			}
		}, {
			text: '查看放弃入职原因',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.state == 3) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin('?model=hr_recruitment_entryNotice&action=toViewCancel&id=' + row.id +
						'&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700'
					);
				}
			}
		}, {
			text: '查看离职原因',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.state == 2 && row.departReason != '') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toViewDepart&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700");
				}
			}
		}, {
			text: '编辑放弃入职原因',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 3) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toEditCancel&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
				}
			}
		}, {
			text: '关联职位申请表',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.applyId == 0 || row.applyId == '') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toLinkApply&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650"
					);
				}
			}
		}, {
			text: '填写离职原因',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toAddDepart&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700");
				}
			}
		}],

		searchitems: [{
			display: "单据编号",
			name: 'formCode'
		}, {
			display: "姓名",
			name: 'userName'
		}, {
			display: "简历编号",
			name: 'resumeCode'
		}, {
			display: "简历来源小类",
			name: 'hrSourceType2Name'
		}, {
			display: "入职日期",
			name: 'entryDate'
		}, {
			display: "性别",
			name: 'sex'
		}, {
			display: "联系电话",
			name: 'phone'
		}, {
			display: "电子邮箱",
			name: 'email'
		}, {
			display: "入职协助人",
			name: 'assistManName'
		}, {
			display: "用人部门",
			name: 'deptName'
		}, {
			display: "工作地点",
			name: 'workPlace'
		}, {
			display: "社保购买地",
			name: 'socialPlace'
		}, {
			display: "录用职位",
			name: 'hrJobName'
		}, {
			display: "是否管理岗",
			name: 'hrIsManageJob'
		}, {
			display: "归属区域或支撑中心",
			name: 'useAreaName'
		}, {
			display: "签订《竞业限制协议》",
			name: 'useSign'
		}, {
			display: "试用期",
			name: 'probation'
		}, {
			display: "合同期限",
			name: 'contractYear'
		}]
	});
});