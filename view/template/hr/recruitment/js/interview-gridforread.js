var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {
	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		action : 'pageJsonForRead',
		title : '面试记录查询',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '姓名',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'sexy',
				display : '性别',
				sortable : true,
				width : 70
			}, {
				name : 'positionsName',
				display : '应聘岗位',
				sortable : true
			}, {
				name : 'deptName',
				display : '用人部门',
				sortable : true
			}, {
				name : 'projectGroup',
				display : '所在项目组',
				sortable : true
			}, {
				name : 'useWriteEva',
				display : '用人部门笔试评价',
				sortable : true
			}, {
				name : 'useInterviewEva',
				display : '用人部门面试评价',
				sortable : true
			}, {
				name : 'useInterviewResult',
				display : '用人部门建议面试结果',
				sortable : true,
				width : 130
			}, {
				name : 'useInterviewer',
				display : '用人部门面试官',
				sortable : true
			}, {
				name : 'useInterviewDate',
				display : '用人部门面试日期',
				sortable : true
			}, {
				name : 'useHireTypeName',
				display : '用人部门建议录用形式',
				sortable : true,
				width : 120
			}, {
				name : 'useJobName',
				display : '用人-职位名称',
				sortable : true,
				hide : true
			}, {
				name : 'useAreaName',
				display : '归属区域或支撑中心',
				sortable : true,
				width : 120
			}, {
				name : 'useTrialWage',
				display : '用人部门建议试用期工资',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'useFormalWage',
				display : '用人部门建议转正工资',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'useDemandEqu',
				display : '办公电脑需求设备类型',
				sortable : true,
				width : 120
			}, {
				name : 'useSign',
				display : '是否签订《竞业限制协议》',
				sortable : true,
				width : 140
			}, {
				name : 'useManager',
				display : '用人部门负责人批准',
				sortable : true
			}, {
				name : 'useSignDate',
				display : '用人部门负责人签字日期',
				sortable : true,
				hide : true
			}, {
				name : 'hrInterviewResult',
				display : 'HR面试评价',
				sortable : true
			}, {
				name : 'hrInterviewer',
				display : 'HR面试官',
				sortable : true
			}, {
				name : 'hrInterviewDate',
				display : 'HR面试日期',
				sortable : true
			}, {
				name : 'hrHireTypeName',
				display : '用工方式',
				sortable : true
			}, {
				name : 'hrRequire',
				display : '招聘需求',
				sortable : true
			}, {
				name : 'hrSourceType1',
				display : '简历来源大类',
				sortable : true
			}, {
				name : 'hrSourceType2',
				display : '简历来源小类',
				sortable : true
			}, {
				name : 'hrJobName',
				display : '录用职位名称确认',
				sortable : true
			}, {
				name : 'hrIsManageJob',
				display : '是否管理岗',
				sortable : true
			}, {
				name : 'hrIsMatch',
				display : '基本工资与与薪点及薪资区间是否对应',
				sortable : true
			}, {
				name : 'hrCharger',
				display : '招聘主管批准',
				sortable : true
			}, {
				name : 'hrManager',
				display : '招聘经理批准',
				sortable : true
			}, {
				name : 'manager',
				display : '人力资源部负责人批准',
				sortable : true
			}, {
				name : 'deputyManager',
				display : '副总经理批准',
				sortable : true
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
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
					showModalWin("?model=hr_recruitment_interview&action=toView&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			},
			formHeight : 500,
			formWidth : 900
		},
		searchitems : [{
			display : '姓名',
			name : 'userNameSearch'
		}, {
			display : '用人部门',
			name : 'deptNamSearche'
		}]
	});
});