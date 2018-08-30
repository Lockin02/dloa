var show_page = function(page) {
	$("#worklogGrid").yxgrid("reload");
};
var date1;

$(function() {
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=produce_log_weeklog&action=getByAjax",
		data : {
			id : $('#weekid')[0].value
		},
		success : function(result) {
			result = eval("(" + result + ")");
			date1 = result['weekBeginDate'] + "~" + result['weekEndDate'];
		}
	})
	$("#worklogGrid").yxgrid({
		model : 'produce_log_worklog',
		title : "周報日期:(" + date1 + ")",// date1.getFullYear()+"-"+(date1.getMonth()+1)+"-"+date1.getDate()+"~"+date2.getFullYear()+"-"+(date2.getMonth()+1)+"-"+date2.getDate(),
		// 列信息
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		buttonsEx : [ {
			text : '返回',
			icon : 'view',
			action : function(row) {
				history.back();
			}
		} ],
		param : {
			weekId : $('#weekid')[0].value
		},

		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'produceTaskCode',
			display : '任务编号',
			sortable : true
		}, {
			name : 'executionDate',
			display : '执行日期',
			sortable : true
		}, {
			name : 'effortRate',
			display : '完成率',
			sortable : true
		}, {
			name : 'warpRate',
			display : '偏差率',
			sortable : true
		}, {
			name : 'workloadDay',
			display : '当日投入工作量',
			sortable : true
		}, {
			name : 'workloadSurplus',
			display : '预计剩余工作量',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '预计完成时间',
			sortable : true
		}, {
			name : 'description',
			display : '描述',
			sortable : true
		}, {
			name : 'problem',
			display : '存在问题',
			sortable : true
		}, {
			name : 'createName',
			display : '员工名称',
			sortable : true
		} ],
		showcheckbox : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		toViewConfig : {
			action : 'toView'
		},
		rchitems : [ {
			display : "执行日期",
			name : 'executionDate'
		} ]
	});
});