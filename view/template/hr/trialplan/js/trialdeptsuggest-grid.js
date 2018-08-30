var show_page = function(page) {
	$("#trialdeptsuggestGrid").yxgrid("reload");
};

$(function() {
	$("#trialdeptsuggestGrid").yxgrid({
		model : 'hr_trialplan_trialdeptsuggest',
		title : '部门建议',
		isAddAction : false,
//		isEditAction : false,
		isDelAction : false,
		customCode : 'trialdeptsuggestGrid',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width : 80
		},{
			name : 'userAccount',
			display : '员工账号',
			sortable : true,
			hide : true
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width : 80
		},{
			name : 'deptName',
			display : '所属部门',
			sortable : true,
			width : 80
		},{
			name : 'deptId',
			display : '部门Id',
			sortable : true,
			hide : true
		},{
			name : 'jobName',
			display : '职位名称',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'deptSuggest',
			display : '部门建议',
			sortable : true,
			hide : true
		},{
			name : 'deptSuggestName',
			display : '部门建议',
			sortable : true,
			width : 80
		},{
			name : 'suggestion',
			display : '建议描述',
			sortable : true,
			width : 150
		},{
			name : 'permanentDate',
			display : '计划转正日期',
			sortable : true,
			width : 70
		},{
			name : 'beforeSalary',
			display : '面试评估工资',
			sortable : true,
			width : 70,
			process : function(v) {
				return moneyFormat2(v);
			}
		},{
			name : 'afterSalary',
			display : '部门建议工资',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name : 'hrSalary',
			display : '人事建议工资',
			sortable : true,
			width : 70,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name : 'beforePersonLv',
			display : '原技术等级',
			sortable : true,
			width : 70
		},{
			name : 'personLevel',
			display : '部门建议等级',
			sortable : true,
			width : 70
		},{
			name : 'afterPositionName',
			display : '转正后职位',
			sortable : true,
			width : 70
//			},{
//				name : 'levelName',
//				display : '转正后职级',
//				sortable : true,
//				width : 70
		},{
			name : 'levelCode',
			display : '转正后职级编号',
			sortable : true,
			hide : true
		},{
			name : 'positionCode',
			display : '转正后职位编号',
			sortable : true,
			hide : true
		},{
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		},{
			name : 'ExaDT',
			display : '审批日期',
			sortable : true,
			width : 70
		},{
			name : 'status',
			display : '状态',
			sortable : true,
			hide : true
		},{
			name : 'createName',
			display : '创建人',
			sortable : true
		},{
			name : 'createId',
			display : '创建人ID',
			sortable : true,
			hide : true
		},{
			name : 'createTime',
			display : '创建时间',
			sortable : true
		},{
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		},{
			name : 'updateId',
			display : '修改人ID',
			sortable : true,
			hide : true
		},{
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		}],

		lockCol:['userNo','userName','deptName'],//锁定的列名

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : 'toEdit',
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500
		},

		//扩展右键
		menusEx : [{
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if(row.hrSalary == '') {
					if(confirm('未录入人事建议工资，不能提交审批，要现在进行录入吗？')){
						showThickboxWin('?model=hr_trialplan_trialdeptsuggest&action=toEdit&id='
							+ row.id
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					} else {
						return false;
					}
				} else {
					if(row.hrSalary *1 != row.afterSalary *1 || row.beforePersonLv != row.personLevel){
						showThickboxWin('controller/hr/trialplan/ewf_index1.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					} else {
						showThickboxWin('controller/hr/trialplan/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}
				}
			}
		}],

		searchitems : [{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "员工姓名",
			name : 'userNameSearch'
		},{
			display : "所属部门",
			name : 'deptName'
		},{
			display : "部门建议",
			name : 'deptSuggestName'
		},{
			display : "建议描述",
			name : 'suggestion'
		},{
			display : "计划转正日期",
			name : 'permanentDate'
		},{
			display : "面试评估工资",
			name : 'beforeSalary'
		},{
			display : "部门建议工资",
			name : 'afterSalary'
		},{
			display : "人事建议工资",
			name : 'hrSalary'
		},{
			display : "原技术等级",
			name : 'beforePersonLv'
		},{
			display : "部门建议等级",
			name : 'personLevel'
		},{
			display : "转正后职位",
			name : 'afterPositionName'
		},{
			display : "创建人",
			name : 'createName'
		},{
			display : "创建时间",
			name : 'createTime'
		}]
	});
});