var show_page = function(page) {
	$("#teachrecordsGrid").yxgrid("reload");
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
			showThickboxWin("?model=hr_training_teachrecords&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	}];

	$("#teachrecordsGrid").yxgrid({
//		showcheckbox : false,
		model : 'hr_training_teachrecords',
		title : '培训管理-授课记录',
		isAddAction : false,
		bodyAlign : 'center',
		customCode : 'hr_training_teachrecords',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'courseCode',
			display : '课程编号',
			sortable : true
		},{
			name : 'courseName',
			display : '课程名称',
			sortable : true,
			width : 100
		},{
			name : 'duration',
			display : '时长',
			sortable : true,
			width : 50
		},{
			name : 'teacherName',
			display : '讲师名称',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teacher&action=toView&id=" + row.teacherId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width : 100
		},{
			name : 'trainsTypeName',
			display : '培训类型',
			sortable : true,
			width : 80
		},{
			name : 'trainsMethod',
			display : '培训方式',
			sortable : true,
			width : 80
		},{
			name : 'orgDeptName',
			display : '组织部门',
			sortable : true,
			width : 80
		},{
			name : 'trainsMonth',
			display : '培训月份',
			sortable : true,
			width : 80
		},{
			name : 'teachDate',
			display : '授课开始日期',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teachrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'teachEndDate',
			display : '授课结束日期',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teachrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'trainsNum',
			display : '培训次数',
			sortable : true
		},{
			name : 'agency',
			display : '培训机构',
			sortable : true,
			width : 100
		},{
			name : 'address',
			display : '授课地点',
			sortable : true,
			width : 100
		},{
			name : 'joinNum',
			display : '参与人数',
			sortable : true,
		},{
			name : 'fee',
			display : '费用',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'assessmentName',
			display : '考核类型',
			width : 80,
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
		},{
			name : 'createName',
			display : '创建人名称',
			sortable : true,
			hide : true
		},{
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 130,
			hide : true
		}],

		// lockCol:['userNo','teacherName'],//锁定的列名

		toEditConfig : {
			action : 'toEdit',
			formHeight : 450
		},
		toViewConfig : {
			action : 'toView'
		},

		buttonsEx : buttonsArr,

		searchitems : [{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "讲师名称",
			name : 'teacherNameM'
		},{
			display : "授课开始日期",
			name : 'teachDate'
		},{
			display : "授课结束日期",
			name : 'teachEndDate'
		},{
			display : "课程名称",
			name : 'courseNameM'
		},{
			display : "授课地点",
			name : 'address'
		}]
	});
});