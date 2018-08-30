var show_page = function (page) {
	$("#certifyapplyGrid").yxgrid("reload");
};

$(function () {
	//表头按钮数组
	buttonsArr = [{
		name: 'view',
		text: "高级查询",
		icon: 'view',
		action: function () {
			alert('功能暂未开发完成');
		}
	}];

	//表头按钮数组
	excelOutArr = {
		name: 'exportIn',
		text: "导入",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelIn" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	buttonsArr.push(excelOutArr);

	$("#certifyapplyGrid").yxgrid({
		model: 'hr_personnel_certifyapply',
		title: '任职资格信息',
		param: {
			userNo: $('#userNo').val()
		},
		showcheckbox: false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'userNo',
			display: '员工编号',
			sortable: true
		}, {
			name: 'userAccount',
			display: '员工账号',
			sortable: true,
			hide: true
		}, {
			name: 'userName',
			display: '员工姓名',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certifyapply&action=toView&id=" + row.id +
					'&skey=' + row.skey_ +
					"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			name: 'deptName',
			display: '部门名称',
			sortable: true,
			hide: true
		}, {
			name: 'deptId',
			display: '部门Id',
			sortable: true,
			hide: true
		}, {
			name: 'applyDate',
			display: '申请日期',
			sortable: true,
			width: 80
		}, {
			name: 'careerDirectionName',
			display: '申请通道',
			sortable: true,
			width: 80
		}, {
			name: 'baseLevelName',
			display: '申请级别',
			sortable: true,
			width: 70
		}, {
			name: 'baseGradeName',
			display: '申请级等',
			sortable: true,
			width: 70
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
			name: 'ExaStatus',
			display: '审批结果',
			sortable: true,
			width: 70
		}, {
			name: 'ExaDT',
			display: '审批日期',
			sortable: true,
			hide: true,
			width: 70
		}, {
			name: 'baseScore',
			display: '考试得分',
			sortable: true,
			width: 70
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
			width: 70
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
			width: 70
		}, {
			name: 'finalScore',
			display: '认证得分',
			sortable: true,
			width: 70
		}, {
			name: 'finalCareerName',
			display: '认证通道',
			sortable: true,
			width: 80
		}, {
			name: 'finalLevelName',
			display: '认证级别',
			sortable: true,
			width: 70
		}, {
			name: 'finalTitleName',
			display: '认证称谓',
			sortable: true,
			width: 80
		}, {
			name: 'finalGradeName',
			display: '认证级等',
			sortable: true,
			width: 70
		}, {
			name: 'finalDate',
			display: '认证结果生效日期',
			sortable: true,
			width: 70
		}],

		lockCol: ['userNo', 'userName'], //锁定的列名

		toEditConfig: {
			action: 'toEdit',
			formHeight: 500,
			formWidth: 900
		},
		toViewConfig: {
			action: 'toView',
			formHeight: 500,
			formWidth: 900
		},

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