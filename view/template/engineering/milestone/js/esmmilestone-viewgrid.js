var show_page = function(page) {
	$("#esmmilestoneGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
	$("#esmmilestoneGrid").yxgrid({
		model : 'engineering_milestone_esmmilestone',
		title : '项目里程碑',
		param : {
			"projectId" : $("#projectId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'milestoneName',
				display : '里程碑名称',
				sortable : true
			}, {
				name : 'planBeginDate',
				display : '计划开始日期',
				sortable : true,
				width : 80
			}, {
				name : 'planEndDate',
				display : '计划完成日期',
				sortable : true,
				width : 80
			}, {
				name : 'actBeginDate',
				display : '实际开始日期',
				sortable : true,
				process : function(v, row) {
					if (v == "0000-00-00") {
						return "";
					} else {
						return v;
					}
				},
				width : 80
			}, {
				name : 'actEndDate',
				display : '实际结束日期',
				sortable : true,
				process : function(v, row) {
					if (v == "0000-00-00") {
						return "";
					} else {
						return v;
					}
				},
				width : 80
			}, {
				name : 'projectId',
				display : '项目Id',
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
				hide : true
			}, {
				name : 'versionNo',
				display : '版本号',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				datacode : 'LCBZT',
				width : 80
			}, {
				name : 'preMilestoneId',
				display : '前置里程碑id',
				sortable : true,
				hide : true
			}, {
				name : 'preMilestoneName',
				display : '前置里程碑',
				sortable : true
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				width : 250
			}
		],
		toAddConfig : {
			plusUrl : "?model=engineering_milestone_esmmilestone&action=toAdd&id="
					+ projectId
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});