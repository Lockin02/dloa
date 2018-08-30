var show_page = function (page) {
	$("#entryNoticeGrid").yxgrid("reload");
};

$(function () {
	$("#entryNoticeGrid").yxgrid({
		model: 'hr_recruitment_entryNotice',
		title: '录用通知',
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		param: {
			stateArr: "2,3"
		},

		buttonsEx: [{
			name: 'expport',
			text: "导出",
			icon: 'excel',
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_entryNotice&action=toExport&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
			}
		}],

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
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id
					+ "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'formDate',
			display: '单据日期',
			sortable: true,
			width: 80
		}, {
			name: 'interviewType',
			display: '面试类型',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == 1) {
					return "增员申请";
				} else if (v == 2) {
					return "内部推荐";
				}
			}
		}, {
			name: 'resumeCode',
			display: '简历编号',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v + "\",1)'>" +
					v + "</a>";
			}
		}, {
			name: 'hrSourceType2Name',
			display: '简历来源小类',
			sortable: true
		}, {
			name: 'userName',
			display: '姓名',
			sortable: true,
			width: 60
		}, {
			name: 'entryDate',
			display: '入职日期',
			sortable: true,
			width: 80
		}, {
			name: 'stateC',
			display: '状态',
			width: 60
		}, {
			name: 'sex',
			display: '性别',
			sortable: true,
			width: 50
		}, {
			name: 'phone',
			display: '联系电话',
			sortable: true
		}, {
			name: 'applyCode',
			display: '职位申请编号',
			sortable: true,
			width: 110,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" + row.applyId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'email',
			display: '电子邮箱',
			sortable: true,
			width: 110
		}, {
			name: 'positionsName',
			display: '应聘岗位',
			sortable: true
		}, {
			name: 'developPositionName',
			display: '研发职位',
			sortable: true,
			width: 60
		}, {
			name: 'deptName',
			display: '用人部门',
			sortable: true
		}, {
			name: 'workPlace',
			display: '工作地点',
			sortable: true,
			width: 80,
			process: function (v, row) {
				return row.workProvince + ' - ' + row.workCity;
			}
		}, {
			name: 'useHireTypeName',
			display: '录用形式',
			sortable: true

		}, {
			name: 'useAreaName',
			display: '归属区域或支撑中心',
			sortable: true
		}, {
			name: 'assistManName',
			display: '入职协助人',
			sortable: true
		}, {
			name: 'useDemandEqu',
			display: '需求设备',
			sortable: true
		}, {
			name: 'useSign',
			display: '签订《竞业限制协议》',
			sortable: true
		}, {
			name: 'probation',
			display: '试用期',
			sortable: true,
			width: 60
		}, {
			name: 'contractYear',
			display: '合同期限',
			sortable: true,
			width: 60
		}, {
			name: 'hrSourceType1Name',
			display: '简历来源大类',
			sortable: true
		}, {
			name: 'hrJobName',
			display: '录用职位名称',
			sortable: true
		}, {
			name: 'hrIsManageJob',
			display: '是否管理岗',
			sortable: true,
			width: 80
		}, {
			name: 'leaveReason',
			display: '员工离职申请原因',
			sortable: true,
			width: 250
		}],

		lockCol: ['formCode', 'formDate', 'userName'], //锁定的列名

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
					showModalWin('?model=hr_recruitment_apply&action=toView&id=' + row.sourceId, '1');
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
			display: "单据日期",
			name: 'formDate'
		}, {
			display: "简历编号",
			name: 'resumeCode'
		}, {
			display: "简历来源小类",
			name: 'hrSourceType2Name'
		}, {
			display: "姓名",
			name: 'userName'
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
			display: "职位申请编号",
			name: 'applyCode'
		}, {
			display: "电子邮箱",
			name: 'email'
		}, {
			display: "应聘岗位",
			name: 'positionsName'
		}, {
			display: "研发职位",
			name: 'developPositionName'
		}, {
			display: "用人部门",
			name: 'deptName'
		}, {
			display: "工作地点",
			name: 'workPlace'
		}, {
			display: "录用形式",
			name: 'useHireTypeName'
		}, {
			display: "归属区域或支撑中心",
			name: 'useAreaName'
		}, {
			display: "入职协助人",
			name: 'assistManName'
		}, {
			display: "需求设备",
			name: 'useDemandEqu'
		}, {
			display: "签订《竞业限制协议》",
			name: 'useSign'
		}, {
			display: "试用期",
			name: 'probation'
		}, {
			display: "合同期限",
			name: 'contractYear'
		}, {
			display: "简历来源大类",
			name: 'hrSourceType1Name'
		}, {
			display: "录用职位名称",
			name: 'hrJobName'
		}, {
			display: "是否管理岗",
			name: 'hrIsManageJob'
		}]
	});
});