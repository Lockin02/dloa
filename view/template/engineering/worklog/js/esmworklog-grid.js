var show_page = function(page) {
	$("#esmworklogGrid").yxgrid("reload");
};

$(function() {
    $("#esmworklogGrid").yxgrid({
        model : 'engineering_worklog_esmworklog',
        title : '������־',
		showcheckbox : false,
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'weekId',
            display : '����־id',
            sortable : true,
            hide : true
        }         ,{
            name : 'executionDate',
            display : 'ִ������',
            sortable : true
        }         ,{
            name : 'proCode',
            display : '���ڳ��б���',
            sortable : true,
            hide : true
        }         ,{
            name : 'proName',
            display : '���ڳ���',
            sortable : true
        }         ,{
            name : 'workloadDay',
            display : '����Ͷ�빤����',
            sortable : true
        }         ,{
            name : 'description',
            display : '��������',
            sortable : true,
            width : 200
//        }         ,{
//            name : 'problem',
//            display : '��������',
//            sortable : true,
//            width : 200
        }         ,{
            name : 'createId',
            display : '������Id',
            sortable : true,
            hide : true
        }         ,{
            name : 'createName',
            display : '����������',
            sortable : true,
            hide : true
        }         ,{
            name : 'createTime',
            display : '����ʱ��',
            sortable : true,
            hide : true
        }         ,{
            name : 'updateId',
            display : '�޸���Id',
            sortable : true,
            hide : true
        }         ,{
            name : 'updateName',
            display : '�޸�������',
            sortable : true,
            hide : true
        }         ,{
            name : 'updateTime',
            display : '�޸�ʱ��',
            sortable : true,
            width : 150
        }         ],
		searchitems : [{
				display : "ִ������",
				name : 'executionDate'
			}, {
				display : "���ڳ���",
				name : 'proName'
			}]
    });
});