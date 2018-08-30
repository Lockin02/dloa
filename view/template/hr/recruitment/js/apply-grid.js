var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {

	buttonsArr = [];

	//表头按钮数组
	excelInArr = {
		name: 'exportOut',
		text: "导入",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toExcelIn&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
		}
	};

	//表头按钮数组
	excelOutArr = {
		name: 'exportOut',
		text: "导出",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toExcelOut&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
		}
	};

	//表头按钮数组
	highSearch = {
		name: 'view',
		text: "高级搜索",
		icon: 'view',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toSearch&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950");
		}
	};

	$.ajax({
		type: 'POST',
		url: '?model=hr_recruitment_apply&action=getLimits',
		data: {
			'limitName': '导入权限'
		},
		async: false,
		success: function (data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelInArr);
				buttonsArr.push(highSearch);
			}
		}
	});

	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		action: "pageJsonList",
		title: '增员申请',
		isDelAction: false,
		isAddAction: false,
		isEditAction: false,
		showcheckbox: false,
		param: {
			state_d: '0'
		},
		isOpButton: false,
		bodyAlign: 'center',
		customCode: 'hr_recruitment_apply_grid',

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
				if (row.viewType == 1) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toTabView&id=" + row.id + "\",1)'>" + v + "</a>";
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
			width: 40
		}, {
			name: 'formManName',
			display: '填表人',
			width: 70,
			sortable: true
		}, {
			name: 'resumeToName',
			display: '接口人',
			width: 70,
			sortable: true
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
		// 	name: 'tutor',
		// 	display: '导师',
		// 	width: 60,
		// 	sortable: true
		// }, {
		// 	name: 'computerConfiguration',
		// 	display: '电脑配置',
		// 	width: 60,
		// 	sortable: true
		// }, {
			name: 'formDate',
			display: '填表日期',
			width: 80,
			sortable: true
		}, {
			name: 'ExaDT',
			display: '申请通过时间',
			width: 120,
			sortable: true,
			process: function (v, row) {
				if (row.state >= 1 && row.state <= 7) {
					return v;
				} else {
					return '';
				}
			}
		}, {
			name: 'assignedDate',
			display: '下达日期',
			width: 80,
			sortable: true
		}, {
			name: 'createTime',
			display: '录用日期',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'createTime',
			display: '第一个offer时间',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'lastOfferTime',
			display: '最后一个offer时间',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'entryDate',
			display: '到岗时间',
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
			name: 'editHeadTime',
			display: '修改负责人时间',
			width: 130,
			sortable: true
		}, {
			name: 'userName',
			display: '录用名单',
			sortable: true,
			width: 200,
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

		comboEx: [{
			text: '单据状态',
			key: 'state',
			data: [{
				text: '提交',
				value: '8'
			}, {
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

		menusEx: [{
			text: '修改',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.stateC == "提交" && row.ExaStatus == '未提交') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toEdit&id=" + row.id + "&skey=" + row['skey_'] + "&editFromApply=1", 1);
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
				if (row) {
					if (row.deptId == '130' || row.postType == 'YPZW-WY') {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'], 1);
					} else {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
					}
				}
			}
		}, {
			text: '修改关键要点',
			icon: 'edit',
			action: function (row) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toEditKeyPoints&id=" + row.id + "&act=page", 1);
				}
			}
		}, {
			text: '分配负责人',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 1) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_apply&action=toGive&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			text: '修改负责人',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_apply&action=toEditHead&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			}
		}, {
			text: '修改录用名单',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state != 4 && row.ExaStatus == '完成') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_apply&action=toEditEmploy&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900");
			}
		}, {
			name: 'sumbit',
			text: '提交审批',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == '未提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=ewf&id=" + row.id + '&examCode=oa_hr_recruitment_apply&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=600"');
				} else {
					alert("请选中一条数据");
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_apply&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
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
						+ "&state=3&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
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
						+ "&state=7&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
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
						+ "&state=2&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
				}
			}
		}, {
			text: '撤回审批',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=common_workflow_workflow&action=isAudited",
						data: {
							billId: row.id,
							examCode: 'oa_hr_recruitment_apply'
						},
						success: function (msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤回审批！');
								$("#leaveGrid").yxgrid("reload");
								return false;
							} else {
								if (confirm('确定要撤回审批吗？')) {
									$.ajax({
										type: "GET",
										url: "?model=hr_recruitment_apply&action=delewf",
										data: {
											id: row.id
										},
										async: false,
										success: function (ewfurl) {
											if (ewfurl) {
												$.ajax({
													type: "GET",
													url: ewfurl,
													data: {
														"billId": row.id
													},
													async: false,
													success: function (msg) {
														if (msg) {
															alert('撤回成功！');
															$("#applyGrid").yxgrid("reload");
														}
													}
												})
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}, {
			text: '打回单据',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == '未提交' && row.stateC == '提交') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=hr_recruitment_apply&action=getBack",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg) {
								alert("操作成功！");
								$("#applyGrid").yxgrid("reload");
							} else {
								alert("操作失败！");
								$("#applyGrid").yxgrid("reload");
							}
						}
					});
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
					showThickboxWin("?model=hr_recruitment_apply&action=toViewStartstop&id=" + row.id + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
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
					showThickboxWin("?model=hr_recruitment_apply&action=toViewCancel&id=" + row.id + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}],

		buttonsEx: buttonsArr,

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_apply&action=toTabView&id=" + get [p.keyField] + "&ExaStatus=" + get ['ExaStatus'], '1');
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
			display: '录用日期',
			name: 'createTimeSea'
		}, {
			display: '到岗时间',
			name: 'entryDateSea'
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
			display: '录用名单',
			name: 'userName'
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