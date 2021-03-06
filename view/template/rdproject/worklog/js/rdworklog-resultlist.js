var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};

$(function() {
	$("#weeklogGrid").yxgrid({
		model : 'rdproject_worklog_rdworklog',
//		action : 'pageJsonWorklogResult',
		title : '日志查询结果',
		param : {
			"projectIds" : $("#projectIds").val(),
			"memberIds" : $("#memberIds").val(),
			"beginDate" : $("#beginDate").val(),
			"overDate" : $("#overDate").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectId',
				display : '项目id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 130
			}, {
				name : 'taskName',
				display : '任务名称',
				sortable : true,
				width : 130
			}, {
				name : 'createName',
				display : '日报填写人',
				sortable : true,
				width : 80
			}, {
				name : 'effortRate',
				display : '当日完成率',
				sortable : true,
				process : function (v){
					return v + " %";
				},
				width : 80
			}, {
				name : 'workloadDay',
				display : '当日投入工作量',
				sortable : true,
				process : function (v){
					return v + " 小时";
				},
				width : 90
			}, {
				name : 'workloadSurplus',
				display : '预计剩余',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " 小时";
				},
				hide : true
			}, {
				name : 'planEndDate',
				display : '预计完成',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'executionDate',
				display : '执行日期 ',
				sortable : true,
				width : 80
			}, {
				name : 'description',
				display : '工作描述 ',
				width : 170,
				sortable : true
			}, {
				name : 'problem',
				display : '存在问题 ',
				width : 170,
				sortable : true
			}, {
				name : 'updateTime',
				display : '填写时间',
				sortable : true,
				width : 130,
				hide : true
			}
		],
		buttonsEx : [
	        {
				name : 'back',
				text : "返回查询页",
				icon : 'edit',
				action : function() {
					location.href = "?model=rdproject_worklog_rdweeklog&action=searchLogForManeger";
				}
	        }
        ],
		// 扩展右键菜单
		menusEx : [
			{
				text : '查看',
				icon : 'view',
				action : function(row, rows, grid) {
					showThickboxWin("?model=rdproject_worklog_rdworklog&action=viewWorkLog&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		],
		searchitems : [{
			display : '任务名称',
			name : 'wtaskName'
		},{
			display : '项目名称',
			name : 'wprojectName'
		},{
			display : '填写人',
			name : 'wcreateName'
		},{
			display : '执行日期',
			name : 'executionDate'
		}]
	});
});