var show_page = function(page) {
	$("#esmqualityGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#esmqualityGrid").yxgrid({
        model : 'engineering_quality_esmquality',
        title : '项目质量',
        param : { "projectId" : $("#projectId").val() },
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectId',
            display : '项目id',
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
            name : 'qualityProblem',
            display : '质量问题',
            sortable : true,
            width : '300'
        }         ,{
            name : 'problemType',
            display : '问题类型',
            sortable : true
        }         ,{
            name : 'isDeal',
            display : '是否解决',
            sortable : true,
            process : function(v,row){
				if(v=="1"){
					return "是";
				}else{
					return "否";
				}
			}
        }         ,{
            name : 'solution',
            display : '解决方案',
            sortable : true,
            width : '300'
        }         ,{
            name : 'results',
            display : '结果反馈',
            sortable : true,
            width : '300',
            hide : true
        }         ,{
            name : 'submiterName',
            display : '提交人',
            sortable : true
        }         ,{
            name : 'submiterCode',
            display : '提交人id',
            sortable : true,
            hide : true
        }         ,{
            name : 'submitDate',
            display : '提交日期',
            sortable : true
        }],
		searchitems : [{
			display : '问题类型',
			name : 'problemType'
		},{
			display : '质量问题',
			name : 'qualityProblem'
		}],
		isDelAction:false,
		isAddAction:false,
		isEditAction:false
    });
});