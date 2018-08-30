var show_page = function(page) {
	$("#teachrecordsGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
//        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        },
        	{
			name : 'exportIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_training_teachrecords&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
	];

	$("#teachrecordsGrid").yxgrid({
		height:150,
		showcheckbox : false,
		model : 'hr_training_teachrecords',
		param : {
//			'teacherName' : $('#userName').val(),
			'userNo' : $('#userNo').val()
		},
		title : '培训管理-授课记录',
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
				sortable : true,
				width : 100
			}, {
				name : 'teacherName',
				display : '讲师名称',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teacher&action=toView&id=" + row.teacherId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'teachDate',
				display : '授课日期',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teachrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			},{
				name : 'courseName',
				display : '课程名称',
				sortable : true,
				width : 100
			},{
				name : 'teachingClass',
				display : '授课课时',
				sortable : true,
				width : 50
			}, {
				name : 'address',
				display : '授课地点',
				sortable : true,
				width : 100
			}, {
				name : 'assessmentScore',
				display : '授课评估得分',
				sortable : true,
				width : 80
			},{
				name : 'theParticipationName',
				display : '参训人姓名',
				sortable : true,
				width : 250
			},{
				name : 'subsidiesToTeach',
				display : '授课补贴金额',
				sortable : true,
				width : 80
			},{
				name : 'distribution',
				display : '授课补贴发放情况',
				sortable : true,
				width : 100
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人名称',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 130,
				hide : true
			}],
			lockCol:['userNo','teacherName'],//锁定的列名
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
//		buttonsEx : buttonsArr,
		searchitems : [{
			display : "讲师名称",
			name : 'teacherNameM'
		},{
			display : "课程名称",
			name : 'courseNameM'
		}]
	});
});