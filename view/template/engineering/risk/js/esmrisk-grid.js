var show_page = function(page) {
	$("#esmriskGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#esmriskGrid").yxgrid({
        model : 'engineering_risk_esmrisk',
        title : '项目风险',
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
            name : 'riskName',
            display : '风险问题',
            sortable : true,
            width : '300'
        }         ,{
            name : 'solution',
            display : '解决方案',
            sortable : true,
            width : '300'
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
        toAddConfig : {
			plusUrl : "?model=engineering_risk_esmrisk&action=toAdd&id="+ projectId
		},
		searchitems : [{
			display : '风险问题',
			name : 'riskName'
		},{
			display : '解决方案',
			name : 'solution'
		}]
    });
});