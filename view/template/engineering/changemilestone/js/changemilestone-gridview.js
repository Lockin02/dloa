var show_page = function(page) {
	$("#changemilestoneGrid").yxgrid("reload");
};
$(function() {
	projectId = $("#projectId").val();
	changeId = $("#changeId").val();
	versionNo = $("#versionNo").val();
	$("#changemilestoneGrid").yxgrid({
		model : 'engineering_changemilestone_changemilestone',
		title : '项目里程碑变更表',
		param : {
			"changeId" : changeId
		},
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
				name : 'milestoneName',
				display : '里程碑点名称',
				sortable : true
			}, {
				name : 'planBeginDate',
				display : '计划开始',
				sortable : true,
				width : 80
			}, {
				name : 'planEndDate',
				display : '计划完成',
				sortable : true,
				width : 80
			}, {
				name : 'actBeginDate',
				display : '实际开始',
				sortable : true,
				width : 80,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				}
			}, {
				name : 'actEndDate',
				display : '实际结束',
				sortable : true,
				width : 80,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				}
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
				name : 'preMilestoneName',
				display : '前置里程碑',
				sortable : true
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				width : 250
			}, {
				name : 'createId',
				display : '创建人Id',
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
				hide : true
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}
		],
		toViewConfig : {
			action : 'toView'
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});