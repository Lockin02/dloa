var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model: 'hr_worktime_apply',
		title: '法定节假日申请表',
		bodyAlign: 'center',
		isDelAction: false,
		showcheckbox: false,
		param: {
			userAccount: $("#userAccount").val()
		},
		menusEx: [{
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '未提交') {
					return false;
				}
				return true;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_worktime_apply&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
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
					switch (row.wageLevelCode) {
					case "GZJBFGL":
						var auditType = '5';
						break; //非管理层
					case "GZJBJL":
						var auditType = '15';
						break; //经理、副总
					case "GZJBZJ":
						var auditType = '25';
						break; //总监
					case "GZJBZG":
						var auditType = '35';
						break; //主管
					case "GZJBFZ":
						var auditType = '35';
						break; //副总
					}
					var billArea = '';
					$.ajax({
						type: 'POST',
						url: '?model=engineering_officeinfo_range&action=getRangeByProvinceAndDept',
						data: {
							provinceId: row.workProvinceId,
							deptId: row.belongDeptId
						},
						async: false,
						success: function (data) {
							billArea = data;
						}
					});
					showThickboxWin("controller/hr/worktime/ewf_index1.php?actTo=ewfSelect&billId=" + row.id
						+ "&billDept=" + row.belongDeptId + "&flowMoney=" + auditType + "&billArea=" + billArea + "&proSid=" + row.projectManagerId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=600"');
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name: 'cancel',
			text: '撤回申请',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				var ewfurl = 'controller/hr/worktime/ewf_index1.php?actTo=delWork&billId=';
				$.ajax({
					type: "POST",
					url: "?model=common_workflow_workflow&action=isAudited",
					data: {
						billId: row.id,
						examCode: 'oa_hr_worktime_apply'
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
									url: ewfurl,
									data: {
										"billId": row.id
									},
									async: false,
									success: function (data) {
										if (data) {
											alert('撤回成功！');
											$("#applyGrid").yxgrid("reload");
										}
									}
								});
							}
						}
					}
				});
			}
		}, {
			name: 'delete',
			text: '删除',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == '未提交') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_worktime_apply&action=ajaxdeletes",
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
		}],

		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'applyCode',
			display: '申请单据',
			width: 180,
			sortable: true,
			process: function (v, row) {
				return
					'<a href="javascript:void(0)" title="点击查看" onclick="javascript:showThickboxWin(\'?model=hr_worktime_apply&action=toView&id=' +
					row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800\')">' +
					"<font color = 'blue'>" + v + "</font>" + '</a>';
			}
		}, {
			name: 'userAccount',
			display: '员工账户',
			sortable: true,
			hide: true
		}, {
			name: 'userNo',
			display: '员工编号',
			width: 60,
			sortable: true
		}, {
			name: 'userName',
			display: '员工姓名',
			width: 60,
			sortable: true
		}, {
			name: 'deptName',
			display: '直属部门',
			width: 80,
			sortable: true
		}, {
			name: 'deptNameS',
			display: '二级部门',
			width: 80,
			sortable: true
		}, {
			name: 'deptNameT',
			display: '三级部门',
			width: 80,
			sortable: true
		}, {
			name: 'deptNameF',
			display: '四级部门',
			width: 80,
			sortable: true
		}, {
			name: 'jobName',
			display: '职位',
			width: 100,
			sortable: true
		}, {
			name: 'applyDate',
			display: '申请日期',
			width: 70,
			sortable: true
		}, {
			name: 'holiday',
			display: '加班时间',
			width: 100,
			sortable: true,
			process: function (v) {
				var str = v.split(',');
				var holiday = '';
				var holidayInfo = '';
				var rs = '';
				for (var i = 0; i < str.length; i++) {
					holiday = str[i].substr(0, 10);
					holidayInfo = str[i].substr(-1);
					if (holidayInfo == '1') {
						holidayInfo = '上午';
					} else if (holidayInfo == '2') {
						holidayInfo = '下午';
					} else if (holidayInfo == '3') {
						holidayInfo = '全天';
					} else {
						holidayInfo = '';
					}
					rs += holiday + '&nbsp&nbsp' + holidayInfo + '<br>';
				}
				return rs;
			}
		}, {
			name: 'workBegin',
			display: '上班开始时间',
			width: 70,
			sortable: true
		}, {
			name: 'beginIdentify',
			display: '开始上/下午',
			width: 65,
			sortable: true,
			process: function (v) {
				if (v == 1) {
					return '上午';
				} else if (v == 2) {
					return '下午';
				}
				return '';
			}
		}, {
			name: 'workEnd',
			display: '上班结束时间',
			width: 70,
			sortable: true
		}, {
			name: 'endIdentify',
			display: '结束上/下午',
			width: 65,
			sortable: true,
			process: function (v) {
				if (v == 1) {
					return '上午';
				} else if (v == 2) {
					return '下午';
				}
				return '';
			}
		}, {
			name: 'dayNo',
			display: '天数',
			width: 40,
			sortable: true
		}, {
			name: 'workContent',
			display: '上班处理工作内容',
			width: 150,
			sortable: true
		}, {
			name: 'workProvince',
			display: '工作省份',
			width: 50,
			sortable: true
		}, {
			name: 'workProvinceId',
			display: '工作省份ID',
			sortable: true,
			hide: true
		}, {
			name: 'projectManager',
			display: '项目经理',
			width: 50,
			sortable: true
		}, {
			name: 'projectManagerId',
			display: '项目经理ID',
			sortable: true,
			hide: true
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			width: 50,
			sortable: true
		}, {
			name: 'ExaDT',
			display: '审批时间',
			sortable: true
		}],

		lockCol: ['userName', 'userNo'], //锁定的列名

		toAddConfig: {
			toAddFn: function() {
				alert("您好，新OA已上线，请到新OA提交申请。谢谢！");
				return false;
			},
			formHeight: 600
		},
		toEditConfig: {
			showMenuFn: function (row) {
				if (row.ExaStatus == '未提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action: 'toEdit',
			formWidth: 700,
			formHeight: 600
		},
		toDelConfig: {
			showMenuFn: function (row) {
				if (row.ExaStatus == '未提交') {
					return true;
				}
				return false;
			},
			formWidth: 700
		},
		toViewConfig: {
			formHeight: 800,
			action: 'toView'
		},

		//下拉过滤
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
			}, {
				text: '打回',
				value: '打回'
			}]
		}],

		searchitems: [{
			display: "申请单据",
			name: 'applyCodeS'
		}, {
			display: "直属部门",
			name: 'deptName'
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
			display: "申请日期",
			name: 'applyDate'
		}, {
			display: "加班时间",
			name: 'holiday'
		}]
	});
});