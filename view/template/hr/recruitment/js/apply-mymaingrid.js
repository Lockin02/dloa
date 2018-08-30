var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	buttonsArr = [];

	//表头按钮数组
	highSearch = {
		name: 'view',
		text: "高级搜索",
		icon: 'view',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toSearch" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
		}
	};

	buttonsArr.push(highSearch);

	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		title: '负责的增员申请',
		isDelAction: false,
		isAddAction: false,
		isEditAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		//列信息
		action: 'myMainPageJson',
		customCode: 'hr_recruitment_apply_mymain',

		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'formCode',
			display: '单据编号',
			sortable: true,
			width: 130,
			process: function (v, row) {
				if (row.viewType == 1) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toTabView&id=" + row.id +
						"\",1)'>" + v + "</a>";
				} else {
					return "";
				}
			}
		}, {
			name: 'stateC',
			display: '单据状态',
			width: 60
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 70
		}, {
			name: 'formManName',
			display: '填表人',
			width: 70,
			sortable: true
		}, {
			name: 'resumeToName',
			display: '接口人',
			width: 150,
			sortable: true
		}, {
			name: 'deptName',
			display: '需求部门',
			sortable: true
		}, {
			name: 'workPlace',
			display: '工作地点',
			width: 70,
			sortable: true
		}, {
			name: 'postTypeName',
			display: '职位类型',
			width: 80,
			sortable: true
		}, {
			name: 'positionName',
			display: '需求职位',
			sortable: true
		}, {
			name: 'positionNote',
			display: '职位备注',
			sortable: true,
			width: 180,
			process: function (v, row) {
				var tmp = '';
				if (row.developPositionName) {
					tmp += row.developPositionName + '，';
				}
				if (row.network) {
					tmp += row.network + '，';
				}
				if (row.device) {
					tmp += row.device;
				}
				return tmp;
			}
		}, {
			name: 'positionLevel',
			display: '级别',
			width: 70,
			sortable: true
		}, {
			name: 'projectGroup',
			display: '所在项目组',
			width: 100,
			sortable: true
		}, {
			name: 'isEmergency',
			display: '是否紧急',
			sortable: true,
			width: 60,
			process: function (v, row) {
				if (v == "1") {
					return "是"
				} else if (v == "0") {
					return "否";
				} else {
					return "";
				}
			}
		}, {
			name: 'formDate',
			display: '填表日期',
			width: 80,
			sortable: true
		}, {
			name: 'hopeDate',
			display: '希望到岗时间',
			sortable: true
		}, {
			name: 'addType',
			display: '增员类型',
			sortable: true
		}, {
			name: 'leaveManName',
			display: '离职/换岗人姓名',
			sortable: true
		}, {
			name: 'needNum',
			display: '需求人数',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'entryNum',
			display: '已入职人数',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'beEntryNum',
			display: '待入职人数',
			width: 60,
			sortable: true,
			process: function (v, row) {
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
			name: 'recruitManName',
			display: '招聘负责人',
			width: 70,
			sortable: true
		}, {
			name: 'assistManName',
			display: '招聘协助人',
			sortable: true,
			width: 200
		}, {
			name: 'applyReason',
			display: '需求原因',
			width: 200,
			sortable: true
		}, {
			name: 'workDuty',
			display: '工作职责',
			width: 200,
			sortable: true
		}, {
			name: 'jobRequire',
			display: '任职要求',
			width: 200,
			sortable: true
		}, {
			name: 'keyPoint',
			display: '关键要点',
			width: 200,
			sortable: true
		}, {
			name: 'attentionMatter',
			display: '注意事项',
			width: 200,
			sortable: true
		}, {
			name: 'leaderLove',
			display: '部门领导喜好',
			width: 200,
			sortable: true
		}, {
			name: 'applyRemark',
			display: '进度备注',
			sortable: true,
			width: 300
		}],

		lockCol: ['formCode', 'stateC', 'ExaStatus'], //锁定的列名

		comboEx: [{
			text: '单据状态',
			key: 'state',
			data: [{
				text: '未下达',
				value: '1'
			}, {
				text: '招聘中',
				value: '2'
			}, {
				text: '暂停',
				value: '3'
			}, {
				text: '完成',
				value: '4'
			}, {
				text: '取消',
				value: '7'
			}]
		}, {
			text: '是否紧急',
			key: 'isEmergency',
			data: [{
				text: '是',
				value: '1'
			}, {
				text: '否',
				value: '0'
			}]
		}],

		buttonsEx: buttonsArr,

		menusEx: [{
			text: '修改',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "完成") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (row.deptId == '130' || row.postType == 'YPZW-WY') {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'], 1);
					} else {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
					}
				}
			}
		}, {
			text: '添加简历',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showOpenWin("?model=hr_recruitment_apply&action=toTabView&id=" + row.id + "#tab2", '1');
			}
		}, {
			text: '发送面试通知',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 2)
					return true;
				else
					return false;
			},
			action: function (row) {
				showOpenWin("?model=hr_recruitment_apply&action=toTabView&id=" + row.id + "#tab2", '1');
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
			text: '编辑关键点',
			icon: 'edit',
			action: function (row) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toEditKeyPoints&id=" + row.id + "&act=mymainpage", 1);
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
		}, {
			text: '启用暂停记录',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.stopStart != '') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=toViewStartstop&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}, {
			text: '取消招聘原因',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.state == 7) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=toViewCancel&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}],

		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_apply&action=toTabView&id=" + get[p.keyField], '1');
				}
			}
		},

		searchitems: [{
			display: "单据编号",
			name: 'formCode'
		}, {
			display: '填表人',
			name: 'formManName'
		}, {
			display: '接口人',
			name: 'resumeToNameSearch'
		}, {
			display: "需求部门",
			name: 'deptName'
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
			display: "级别",
			name: 'positionLevelSearch'
		}, {
			display: "所在项目组",
			name: 'projectGroupSearch'
		}, {
			display: '填表时间',
			name: 'formDate'
		}, {
			display: '申请通过时间',
			name: 'ExaDTSea'
		}, {
			display: '增员类型',
			name: 'addType'
		}, {
			display: '离职/换岗人姓名',
			name: 'leaveManName'
		}, {
			display: '招聘负责人',
			name: 'recruitManName'
		}, {
			display: '招聘协助人',
			name: 'assistManNameSearch'
		}, {
			display: '需求原因',
			name: 'applyReasonSearch'
		}, {
			display: '工作职责',
			name: 'workDutySearch'
		}, {
			display: '任职要求',
			name: 'jobRequireSearch'
		}, {
			display: '关键要点',
			name: 'keyPoint'
		}, {
			display: '注意事项',
			name: 'attentionMatter'
		}, {
			display: '部门领导喜好',
			name: 'leaderLove'
		}, {
			display: '进度备注',
			name: 'applyRemarkSearch'
		}]
	});
});