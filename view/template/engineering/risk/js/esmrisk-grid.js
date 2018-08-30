var show_page = function(page) {
	$("#esmriskGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#esmriskGrid").yxgrid({
        model : 'engineering_risk_esmrisk',
        title : '��Ŀ����',
        param : { "projectId" : $("#projectId").val() },
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectId',
            display : '��Ŀid',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectCode',
            display : '��Ŀ���',
            sortable : true,
            hide : true
        }         ,{
            name : 'projectName',
            display : '��Ŀ����',
            sortable : true,
            hide : true
        }         ,{
            name : 'riskName',
            display : '��������',
            sortable : true,
            width : '300'
        }         ,{
            name : 'solution',
            display : '�������',
            sortable : true,
            width : '300'
        }         ,{
            name : 'submiterName',
            display : '�ύ��',
            sortable : true
        }         ,{
            name : 'submiterCode',
            display : '�ύ��id',
            sortable : true,
            hide : true
        }         ,{
            name : 'submitDate',
            display : '�ύ����',
            sortable : true
        }],
        toAddConfig : {
			plusUrl : "?model=engineering_risk_esmrisk&action=toAdd&id="+ projectId
		},
		searchitems : [{
			display : '��������',
			name : 'riskName'
		},{
			display : '�������',
			name : 'solution'
		}]
    });
});