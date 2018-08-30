var show_page = function (page) {
	$("#transferGrid").yxgrid("reload");
};

$(function () {
	var userId = $('#userId').val();
	$("#transferGrid").yxgrid({
		model: 'hr_transfer_transfer',
		action: 'pageJson',
		param: {
			listId: userId
		},
		title: '调动记录',
		isAddAction: true,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		showcheckbox: false,
		isOpButton: false,
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
				return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +
					"\"'>" + v + "</a>";
			}
		}, {
			name: 'userNo',
			display: '员工编号',
			width: 80,
			sortable: true
		}, {
			name: 'userName',
			display: '员工姓名',
			sortable: true,
			width: 70
		}, {
			name: 'stateC',
			display: '单据状态',
			width: 70
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 70
		}, {
			name: 'transferTypeName',
			display: '调动类型',
			sortable: true,
			width: 200
		}, {
			name: 'entryDate',
			display: '入职日期',
			sortable: true,
			width: 80
		}, {
			name: 'applyDate',
			display: '申请日期',
			sortable: true,
			width: 80
		}, {
			name: 'preUnitTypeName',
			display: '调动前单位',
			sortable: true,
			hide: true
		}, {
			name: 'preUnitName',
			display: '调动前公司',
			sortable: true
		}, {
			name: 'afterUnitTypeName',
			display: '调动后单位类型',
			sortable: true,
			hide: true
		}, {
			name: 'afterUnitName',
			display: '调动后公司',
			sortable: true
		}, {
			name: 'preBelongDeptName',
			display: '调动前所属部门',
			sortable: true
		}, {
			name: 'afterBelongDeptName',
			display: '调动后所属部门',
			sortable: true
		}, {
			name: 'preDeptNameS',
			display: '调动前二级部门',
			hide: true
		}, {
			name: 'preDeptNameT',
			display: '调动前三级部门',
			hide: true
		}, {
			name: 'afterDeptNameS',
			display: '调动后二级部门',
			hide: true
		}, {
			name: 'afterDeptNameT',
			display: '调动后三级部门',
			hide: true
		}, {
			name: 'preJobName',
			display: '调动前职位',
			sortable: true
		}, {
			name: 'afterJobName',
			display: '调动后职位',
			sortable: true
		}, {
			name: 'preUseAreaName',
			display: '调动前归属区域',
			sortable: true
		}, {
			name: 'afterUseAreaName',
			display: '调动后归属区域',
			sortable: true
		}, {
			name: 'prePersonClass',
			display: '调动前人员分类',
			sortable: true
		}, {
			name: 'afterPersonClass',
			display: '调动后人员分类',
			sortable: true
		}, {
			name: 'reason',
			display: '调动原因',
			sortable: true,
			hide: true,
			width: 130
		}, {
			name: 'remark',
			display: '备注说明',
			sortable: true,
			hide: true,
			width: 130
		}],

		lockCol: ['formCode', 'userNo', 'userName'], //锁定的列名

		toViewConfig: {
			action: 'toViewJobTran',
			formHeight: 500,
			formWidth: 900
		},
		toAddConfig: {
			toAddFn : function(p, g){
				alert("您好，新OA已上线，请到新OA提交申请。谢谢！");
				return false;
			},
			action: 'toAddJobTran',
			formHeight: 500,
			formWidth: 900
		},

		//拓展右键菜单
		menusEx: [{
			text: '查看',
			icon: 'view',
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
				}
			}
		}, {
			text: '修改',
			icon: 'edit',
			showMenuFn: function (row) {
				if ((row.stateC == "未提交" || row.ExaStatus == "打回") && (row.managerId == userId || row.userAccount == userId)) {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_transfer_transfer&action=toEditJobTran&id=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
				}
			}
		}, {
			text: '删除',
			icon: 'delete',
			showMenuFn: function (row) {
				if ((row.stateC == "未提交" || row.ExaStatus == "打回") && (row.managerId == userId || row.userAccount == userId)) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_transfer_transfer&action=ajaxdeletes",
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
			text: '提交',
			icon: 'add',
			showMenuFn: function (row) {
				if ((row.stateC == "未提交" || row.ExaStatus == "打回") && (row.managerId == userId || row.userAccount == userId)) {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (window.confirm("确认要提交?")) {
					$.ajax({
						type: "POST",
						url: "?model=hr_transfer_transfer&action=ajaxSubmit",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == 1) {
								alert('提交成功!');
								show_page();
							} else {
								alert('提交失败!');
								show_page();
							}
						}
					});
				}
			}
		}, {
			text: '员工意见',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.employeeOpinion != 1 && row.userAccount == userId && row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (row) {
					location = "?model=hr_transfer_transfer&action=toOpinionView&id=" + row.id;
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_personnel_transfer&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

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

		/**
		 * 快速搜索
		 */
		searchitems: [{
			display: '单据编号',
			name: 'formCode'
		}, {
			display: '员工编号',
			name: 'userNoSearch'
		}, {
			display: '员工姓名',
			name: 'userNameSearch'
		}, {
			display: '入职日期',
			name: 'entryDate'
		}, {
			display: '申请日期',
			name: 'applyDate'
		}, {
			display: '调动前公司',
			name: 'preUnitName'
		}, {
			display: '调动前所属部门',
			name: 'preBelongDeptName'
		}, {
			display: '调动后公司',
			name: 'afterUnitName'
		}, {
			display: '调动后所属部门',
			name: 'afterBelongDeptName'
		}, {
			display: '调动前职位',
			name: 'preJobName'
		}, {
			display: '调动后职位',
			name: 'afterJobName'
		}, {
			display: '调动前归属区域',
			name: 'preUseAreaName'
		}, {
			display: '调动后归属区域',
			name: 'afterUseAreaName'
		}, {
			display: '调动前人员分类',
			name: 'prePersonClass'
		}, {
			display: '调动后人员分类',
			name: 'afterPersonClass'
		}]
	});
});