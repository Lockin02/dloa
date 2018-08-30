var show_page = function(page) {
	$("#esmmilestoneGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#esmmilestoneGrid").yxgrid({
        model : 'engineering_milestone_esmmilestone',
        title : '项目里程碑',
        param : { "projectId" : projectId },
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'milestoneName',
            display : '里程碑名称',
            sortable : true
        }         ,{
            name : 'planBeginDate',
            display : '计划开始日期',
            sortable : true
        }         ,{
            name : 'planEndDate',
            display : '计划完成日期',
            sortable : true
        }         ,{
            name : 'actBeginDate',
            display : '实际开始日期',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			}
        }         ,{
            name : 'actEndDate',
            display : '实际结束日期',
            sortable : true,
			process : function(v,row){
				if(v=="0000-00-00"){
					return "";
				}else{
					return  v;
				}
			}
        }         ,{
            name : 'preMilestoneName',
            display : '前置里程碑',
            sortable : true
        }         ,{
            name : 'status',
            display : '状态',
            sortable : true,
            datacode : 'LCBZT'
        }         ,{
            name : 'versionNo',
            display : '版本号',
            sortable : true
        }         ,{
            name : 'projectId',
            display : '项目Id',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectCode',
            display : '项目编号',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectName',
            display : '项目名称',
            sortable : true,
            hide : true
        }         ,{
            name : 'isUsing',
            display : '是否使用',
            sortable : true,
            process : function(v,row){
				if(v=="1"){
					return "是";
				}else{
					return "否";
				}
			},
            hide : true
        }         ,{
            name : 'effortRate',
            display : '完成率',
            sortable : true,
            hide : true
        }         ,{
            name : 'warpRate',
            display : '偏差率',
            sortable : true,
            hide : true
        }         ,{
            name : 'preMilestoneId',
            display : '前置里程碑id',
            sortable : true,
            hide : true
        }         ,{
            name : 'remark',
            display : '备注说明',
            sortable : true,
            width : 200
        }] ,
		toAddConfig : {
			plusUrl : "&projectId=" + projectId
		},
		// 默认搜索字段名
		sortname : "planBeginDate",
		// 默认搜索顺序 升序
		sortorder : "ASC"
    });
});