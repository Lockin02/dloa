var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};

$(function() {
	$("#weeklogGrid").yxgrid({
		model : 'rdproject_worklog_rdweeklog',
		action : 'pageJsonWorklogResult',
		title : '日志查询结果',
		param : {
			"w_projectId" : $("#projectIds").val(),
			"departmentIds" : $("#departmentIds").val(),
			"personIds" : $("#personIds").val(),
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
				width : 150
			}, {
				name : 'effortRate',
				display : '总体完成率',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " %";
				}
			}, {
				name : 'workloadDay',
				display : '当日投入',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " 小时";
				}
			}, {
				name : 'workloadSurplus',
				display : '预计剩余',
				sortable : true,
				width : 70,
				process : function (v){
					return v + " 小时";
				}
			}, {
				name : 'planEndDate',
				display : '预计完成',
				sortable : true,
				width : 80
			}, {
				name : 'executionDate',
				display : '执行日期 ',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '填写人',
				sortable : true,
				width : 90
			}, {
				name : 'updateTime',
				display : '填写时间',
				sortable : true,
				width : 130
			}
		],
		buttonsEx : [
	        {
				name : 'back',
				text : "返回",
				icon : 'edit',
				action : function() {
					history.back();
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