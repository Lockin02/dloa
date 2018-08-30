var show_page = function (page) {
	$("#interviewGrid").yxgrid("reload");
};

$(function () {
	//表头按钮数组
	buttonsArr = [{
		name: 'add',
		text: '发起面试评估',
		icon: 'add',
		action: function () {
			showOpenWin('?model=hr_recruitment_interview&action=toAdd');
		}
	}, {
		name: 'add',
		text: "新增面试评估",
		icon: 'add',
		action: function (row) {
			showModalWin("?model=hr_recruitment_interview&action=toInterviewAdd", '1');
		}
	}, {
		name: 'view',
		text: "高级搜索",
		icon: 'view',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_interview&action=toSearch&placeValuesBefore&TB_iframe=true&modal=false&height=370&width=900");
		}
	}];


	excelInArr = {
		name: 'exportIn',
		text: "导入",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_interview&action=toExcelIn&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	$.ajax({
		type: 'POST',
		url: '?model=hr_recruitment_interview&action=getLimits',
		data: {
			'limitName': '导入权限'
		},
		async: false,
		success: function (data) {
			if (data == 1) {
				buttonsArr.push(excelInArr);
			}
		}
	});

	var urseId = $("#userid").val();
	$("#interviewGrid").yxgrid({
		model: 'hr_recruitment_interview',
		title: '面试记录',
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: false,
		isOpButton: false,
		param: {
			createId: urseId
		},
		bodyAlign: 'center',

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
				return "<a href='#' onclick='showModalWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'formDate',
			display: '单据日期',
			width: 70,
			sortable: true
		}, {
			name: 'userName',
			display: '姓名',
			width: 60,
			sortable: true
		}, {
			name: 'sexy',
			display: '性别',
			width: 50,
			sortable: true
		}, {
			name: 'positionsName',
			display: '应聘岗位',
			sortable: true
		}, {
			name: 'deptState',
			display: '部门确认状态',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == 1) {
					return "已确认";
				} else {
					return "未确认";
				}
			}
		}, {
			name: 'hrState',
			display: '人力资源确认状态',
			sortable: true,
			width: 95,
			process: function (v) {
				if (v == 1) {
					return "已确认";
				} else {
					return "未确认";
				}
			}
		}, {
			name: 'stateC',
			display: '状态',
			width: 60
		}, {
			name: 'ExaStatus',
			display: '审核状态',
			width: 60,
			sortable: true
		}, {
			name: 'entryState',
			display: '入职状态',
			width: 60,
			sortable: true,
			process: function (v) {
				switch (v) {
					case '1':
						return '待入职';
					case '2':
						return '已入职';
					case '3':
						return '放弃入职';
					default:
						return '';
				}
			}
		}, {
			name: 'deptName',
			display: '用人部门',
			sortable: true
		}, {
			name: 'useInterviewResult',
			display: '面试结果',
			width: 70,
			sortable: true,
			process: function (v) {
				if (v == 0) {
					return "储备人才";
				} else {
					return "立即录用";
				}
			}
		}, {
			name: 'hrSourceType1Name',
			display: '简历来源大类',
			sortable: true
		}, {
			name: 'hrSourceType2Name',
			display: '简历来源小类',
			sortable: true
		}],

		lockCol: ['formCode', 'formDate', 'userName'], //锁定的列名

		toViewConfig: {
			toViewFn: function (p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showModalWin("?model=hr_recruitment_interview&action=toview&id=" + rowData[p.keyField] + keyUrl, 1);
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		menusEx: [{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_interview&pid=" + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
				}
			}
		}, {
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
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800', '1');
				}
			}
		}, {
			name: 'resume',
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
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800', '1');
				}
			}
		}, {
			name: 'operationLog',
			text: '操作日志',
			icon: 'view',
			action: function (row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue=" + row.id
					+ "&tableName=oa_hr_recruitment_interview"
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			text: '填写部门意见',
			icon: 'add',
			action: function (row) {
				showOpenWin("?model=hr_recruitment_interview&action=todeptedit&id=" + row.id, '1');
			},
			showMenuFn: function (row) {
				if (row.deptState == 0)
					return true;
				else
					return false;
			}
		}, {
			text: '确认HR信息',
			icon: 'add',
			action: function (row) {
				showOpenWin("?model=hr_recruitment_interview&action=tolastedit&id=" + row.id, '1');
			},
			showMenuFn: function (row) {
				if (row.hrState == 0 && row.deptState == 1) {
					return true;
				} else {
					return false;
				}
			}
		}, {
			text: '背景调查',
			icon: 'edit',
			action: function (row) {
				$.ajax({
					url: '?model=hr_recruitment_investigation&action=isToEdit',
					type: 'post',
					data: {
						"id": row.id
					},
					success: function (data) {
						data = eval("(" + data + ")");
						if (data.consultationName != null) {
							location = "?model=hr_recruitment_investigation&action=toEdit&id=" + row.id;
						} else {
							location = "?model=hr_recruitment_interview&action=toInvestigation&id=" + row.id;
						}
					}
				})
			}
		}, {
			text: '提交审批',
			icon: 'add',
			showMenuFn: function (row) { //部门已确认HR未确认（试点专区、服务执行区）
				if (row.hrState == 0 && row.deptState == 1
						&& ((row.ExaStatus == "未提交" || row.ExaStatus == "打回") || (row.ExaStatus == '完成' && row.changeTip == '1'))
						&& (row.parentDeptId == '130' || row.parentDeptId == '131')
						&& row.postType == 'YPZW-WY') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				if (row) {
					location = 'controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect&billId=' + row.id
					+ '&billDept=' + row.deptId
					+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '提交审批',
			icon: 'add',
			showMenuFn: function (row) { //部门已确认并审批HR未确认（试点专区、服务执行区）
				if (row.hrState == 1 && row.deptState == 1
						&& (row.ExaStatus == "完成" || (row.ExaStatus == '完成' && row.changeTip == '1'))
						&& (row.parentDeptId == '130' || row.parentDeptId == '131')
						&& row.postType == 'YPZW-WY' && row.state == '') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				if (row) {
					location = 'controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect&billId=' + row.id
					+ '&billDept=' + row.deptId
					+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '提交审批',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.hrState == 1 && row.deptState == 1
						&& ((row.ExaStatus == "未提交" || row.ExaStatus == "打回") || (row.ExaStatus == '完成' && row.changeTip == '1'))
						|| (row.parentDeptId == '130' && row.postType != 'YPZW-WY')) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				if (row) {
					if ((row.parentDeptId == '130' || row.parentDeptId == '131') && row.postType != 'YPZW-WY') {
						location = 'controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect&billId=' + row.id
						+ '&billDept=' + row.deptId
						+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
					} else {
						location = 'controller/hr/recruitment/ewf_interview_index.php?actTo=ewfSelect&billId=' + row.id
						+ '&billDept=' + row.deptId
						+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text: '编辑',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "未提交" || row.ExaStatus == "打回") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&id=" + row.id);
			}
		}, {
			text: '编辑',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "完成" && row.changeTip == '0') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&audit=true&id=" + row.id);
			}
		}, {
			text: '撤消审批',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "部门审批") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (confirm('确定要撤消审批吗？')) {
					$.ajax({
						type: "POST",
						url: "?model=common_workflow_workflow&action=isAuditedContract",
						data: {
							billId: row.id,
							examCode: 'oa_hr_recruitment_interview'
						},
						success: function (msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤销审批！');
								return false;
							} else {
								location = 'controller/hr/recruitment/ewf_interview_index.php?actTo=delWork&billId=' + row.id
								+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
							}
						}
					});
				}
			}
		}, {
			text: '转为备用人才',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.deptState == 1 && row.useInterviewResult != 0 && row.state != "2") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (window.confirm(("确定要提交吗?"))) {
					$.ajax({
						type: "POST",
						url: "?model=hr_recruitment_interview&action=change",
						data: {
							id: row.id,
							type: row.interviewType
						},
						success: function (msg) {
							if (msg == 1) {
								alert('提交成功！');
								show_page();
							}
						}
					});
				}
			}
		}, {
			text: '转为立即录用',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.deptState == 1 && row.useInterviewResult == 0 && row.state != "2"
						&& (row.ExaStatus != "未提交" || row.ExaStatus != "打回")) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&changeHire=true&id=" + row.id, '1');
			}
		}, {
			text: '发送录用通知',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == "完成" && row.state == "1" && row.useInterviewResult == "1" && row.hrState == "1") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				showModalWin("?model=hr_recruitment_entryNotice&action=toadd&id=" + row.id, '1');
			}
		}, {
			text: '复制新评估',
			icon: 'add',
			action: function (row, rows, grid) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&isCopy=true&id=" + row.id);
			}
		}],

		buttonsEx: buttonsArr,

		searchitems: [{
			display: '单据编号',
			name: 'formCode'
		}, {
			display: '单据日期',
			name: 'formDate'
		}, {
			display: '姓名',
			name: 'userNameSearch'
		}, {
			display: '性别',
			name: 'sexy'
		}, {
			display: '应聘岗位',
			name: 'positionsNameSearch'
		}, {
			display: '用人部门',
			name: 'deptNamSearche'
		}, {
			display: '简历来源大类',
			name: 'hrSourceType1Name'
		}, {
			display: '简历来源小类',
			name: 'hrSourceType2Name'
		}]
	});
});