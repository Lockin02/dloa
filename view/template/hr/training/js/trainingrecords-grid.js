var show_page = function(page) {
	$("#trainingrecordsGrid").yxgrid("reload");
};

$(function() {
	//表头按钮数组
	buttonsArr = [{
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        },{
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_training_trainingrecords&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	}];

	$("#trainingrecordsGrid").yxgrid({
//		showcheckbox : false,
		model : 'hr_training_trainingrecords',
		title : '培训记录',
		isAddAction : false,
		bodyAlign:'center',
		customCode : 'hr_training_trainingrecords',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			width:80,
			sortable : true
		},{
			name : 'userName',
			display : '姓名',
			sortable : true,
			width:100,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_trainingrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		},{
			name : 'deptName',
			display : '部门',
			width:90,
			sortable : true
		},{
			name : 'jobName',
			display : '职位',
			width:80,
			sortable : true
		},{
			name : 'courseCode',
			display : '课程编号',
			sortable : true
		},{
			name : 'courseName',
			display : '课程名称',
			sortable : true
		},{
			name : 'duration',
			display : '时长',
			width:50,
			sortable : true
		},{
			name : 'trainsTypeName',
			display : '培训类型',
			width:60,
			sortable : true
		},{
			name : 'trainsMethod',
			display : '培训方式',
			sortable : true,
			width:60
		},{
			name : 'orgDeptName',
			display : '组织部门',
			width:90,
			sortable : true
		},{
			name : 'trainsMonth',
			display : '培训月份',
			width:60,
			sortable : true
		},{
			name : 'beginDate',
			display : '开始日期',
			width:80,
			sortable : true
		},{
			name : 'endDate',
			display : '结束日期',
			width:80,
			sortable : true
		},{
			name : 'trainsNum',
			display : '培训次数',
			width:70,
			sortable : true
		},{
			name : 'agency',
			display : '培训机构',
			sortable : true
		},{
			name : 'address',
			display : '培训地点',
			width:100,
			sortable : true
		},{
			name : 'teacherName',
			display : '培训讲师',
			width:70,
			sortable : true
		},{
			name : 'fee',
			display : '培训费用',
			width:80,
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		},{
			name : 'status',
			display : '状态',
			width:60,
			sortable : true,
			datacode : 'HRPXZT'
		},{
			name : 'assessmentName',
			display : '考核类型',
			width:80,
			sortable : true
		},{
			name : 'assessmentScore',
			display : '考核成绩',
			width : 60,
			sortable : true
		},{
			name : 'courseEvaluateScore',
			display : '课程评估分数',
			width : 80,
			sortable : true
		},{
			name : 'trainsOrgEvaluateScore',
			display : '培训组织评估分数',
			width : 100,
			sortable : true
		},{
			name : 'followTime',
			display : '效果及绩效跟进时间',
			width : 100,
			sortable : true
		// },{
		// 	name : 'isUploadTA',
		// 	display : '《外请讲师授课评估表》提交状态',
		// 	sortable : true,
		// 	hide : true,
		// 	process : function(v) {
		// 		if (v == '1'){
		// 			return '已提交';
		// 		} else if (v == '2') {
		// 			return '不需提交';
		// 		} else {
		// 			return '未提交';
		// 		}
		// 	}
		// },{
		// 	name : 'isUploadTU',
		// 	display : '《培训反馈及改进计划》提交状态',
		// 	sortable : true,
		// 	hide : true,
		// 	process : function(v) {
		// 		if (v == '1'){
		// 			return '已提交';
		// 		} else if (v == '2') {
		// 			return '不需提交';
		// 		} else {
		// 			return '未提交';
		// 		}
		// 	}
		}],

		// lockCol:['userNo','userName','deptName'],//锁定的列名

		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},

		buttonsEx : buttonsArr,

		searchitems : [{
			display : "员工编号",
			name : 'userNoM'
		},{
			display : "姓名",
			name : 'userNameM'
		},{
			display : "部门",
			name : 'deptNameM'
		},{
			display : "职位",
			name : 'jobName'
		},{
			display : "课程名称",
			name : 'courseNameM'
		},{
			display : "培训机构",
			name : 'agencyM'
		},{
			display : "培训地点",
			name : 'address'
		},{
			display : "培训讲师",
			name : 'teacherNameM'
		}]
	});
});