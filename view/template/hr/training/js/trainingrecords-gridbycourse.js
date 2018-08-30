var show_page = function(page) {
	$("#trainingrecordsGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
        {
			name : 'view',
			text : "高级查询",
			icon : 'view',
			action : function() {
				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }
	];

	$("#trainingrecordsGrid").yxgrid({
		model : 'hr_training_trainingrecords',
		param : { 'courseId' : $("#courseId").val()},
		title : '培训记录',
		isAddAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true
			}, {
				name : 'userName',
				display : '姓名',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_trainingrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'deptName',
				display : '部门',
				sortable : true
			}, {
				name : 'jobName',
				display : '职位',
				sortable : true
			}, {
				name : 'courseName',
				display : '课程名称',
				sortable : true
			}, {
				name : 'isInner',
				display : '内训/外训',
				sortable : true,
				process : function(v){
					if(v == '1'){
						return '内训';
					}else{
						return '外训';
					}
				}
			}, {
				name : 'trainsTypeName',
				display : '培训类型',
				sortable : true
			}, {
				name : 'beginDate',
				display : '开始日期',
				sortable : true
			}, {
				name : 'endDate',
				display : '结束日期',
				sortable : true
			}, {
				name : 'agency',
				display : '培训机构',
				sortable : true
			}, {
				name : 'address',
				display : '培训地点',
				sortable : true
			}, {
				name : 'teacherName',
				display : '培训讲师',
				sortable : true
			}, {
				name : 'fee',
				display : '培训费用',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				datacode : 'HRPXZT'
			}, {
				name : 'assessmentName',
				display : '考核类型',
				sortable : true
			}, {
				name : 'assessmentScore',
				display : '考核成绩',
				sortable : true
			}, {
				name : 'isUploadTA',
				display : '《外请讲师授课评估表》提交状态',
				sortable : true,
				hide : true
			}, {
				name : 'isUploadTU',
				display : '《培训反馈及改进计划》提交状态',
				sortable : true,
				hide : true
			}],
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
//		buttonsEx : buttonsArr,
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
			display : "课程名称",
			name : 'courseNameM'
		},{
			display : "培训机构",
			name : 'agencyM'
		}]
	});
});