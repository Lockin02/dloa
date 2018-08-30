var show_page = function(page) {
	$("#esmqualityGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
    $("#esmqualityGrid").yxgrid({
        model : 'engineering_quality_esmquality',
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
            name : 'qualityProblem',
            display : '��������',
            sortable : true,
            width : '300'
        }         ,{
            name : 'problemType',
            display : '��������',
            sortable : true
        }         ,{
            name : 'isDeal',
            display : '�Ƿ���',
            sortable : true,
            process : function(v,row){
				if(v=="1"){
					return "��";
				}else{
					return "��";
				}
			}
        }         ,{
            name : 'solution',
            display : '�������',
            sortable : true,
            width : '300'
        }         ,{
            name : 'results',
            display : '�������',
            sortable : true,
            width : '300',
            hide : true
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
		searchitems : [{
			display : '��������',
			name : 'problemType'
		},{
			display : '��������',
			name : 'qualityProblem'
		}],
		isDelAction:false,
		isAddAction:false,
		isEditAction:false
    });
});