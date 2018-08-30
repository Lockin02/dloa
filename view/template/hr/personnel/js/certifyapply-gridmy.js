var show_page = function (page) {
	$("#certifyapplyGrid").yxgrid("reload");
};

$(function () {
	$("#certifyapplyGrid").yxgrid({
		model: 'hr_personnel_certifyapply',
		action: 'myPageJson',
		title: '我的任职资格认证申请',
		isDelAction: false,
		isAddAction: true,
		isEditAction: true,
		isOpButton: false,
		showcheckbox: false,
		bodyAlign: 'center',
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
			width: 70,
			hide: true
		}, {
			name: 'userName',
			display: '员工姓名',
			sortable: true,
			width: 60,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_personnel_certifyapply&action=toViewApplyPerson&id=" +
					row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
			}
		}, {
			name: 'deptName',
			display: '部门名称',
			sortable: true,
			hide: true
		}, {
			name: 'applyDate',
			display: '申请日期',
			sortable: true,
			width: 70
		}, {
			name: 'careerDirectionName',
			display: '申请通道',
			sortable: true,
			width: 80
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
			name: 'backReason',
			display: '打回原因',
			sortable: true,
			width: 420
		}],

		toAddConfig: {
			formHeight: 500,
			formWidth: 900,
			toAddFn: function (p, g) {
				showModalWin("?model=hr_personnel_certifyapply&action=toAddApply");
			}
		},
		toEditConfig: {
			showMenuFn: function (row) {
				if (row.status == '0' || row.status == '12') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				action: showModalWin("?model=hr_personnel_certifyapply&action=toEditApply&id=" + g.getSelectedRow().data(
					'data')['id']);
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				action: showOpenWin("?model=hr_personnel_certifyapply&action=toViewApplyPerson&id=" + g.getSelectedRow().data(
					'data')['id']);
			}
		},

		menusEx: [{
			text: '提交',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.status == '0' || row.status == '12') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				$.ajax({
					type: "POST",
					url: "?model=hr_personnel_certifyapply&action=submitApply",
					data: {
						id: row.id
					},
					success: function (msg) {
						if (msg == 1) {
							alert('提交成功！');
							show_page(1);
						} else {
							alert("提交失败! ");
						}
					}
				});
			}
		}, {
			text: "删除",
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.status == '0' || row.status == '12') {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type: "POST",
						url: "?model=hr_personnel_certifyapply&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function (msg) {
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
		}],

		searchitems: [{
			display: "员工姓名",
			name: 'userNameSearch'
		}, {
			display: "申请日期",
			name: 'applyDateSearch'
		}, {
			display: "申请通道",
			name: 'careerDirectionNameSearch'
		}, {
			display: "申请级别",
			name: 'baseLevelName'
		}, {
			display: "申请级等",
			name: 'baseGradeName'
		}]
	});
});