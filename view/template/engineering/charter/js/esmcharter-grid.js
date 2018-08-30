var show_page = function(page) {
	$("#esmcharterGrid").yxgrid("reload");
};

$(function() {
    $("#esmcharterGrid").yxgrid({
        model : 'engineering_charter_esmcharter',
        title : '��Ŀ�³�',
        isDelAction : false,
        isAddAction : false,
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'contractId',
            display : '��ͬid',
            sortable : true,
            hide : true
        },{
            name : 'contractCode',
            display : '��ͬ���',
            sortable : true,
            hide : true
        },{
            name : 'rObjCode',
            display : 'ҵ����',
            sortable : true,
            hide : true
        },{
            name : 'projectId',
            display : '��Ŀid',
            sortable : true,
            hide : true
        },{
            name : 'projectCode',
            display : '��Ŀ���',
            sortable : true
        },{
            name : 'projectName',
            display : '��Ŀ����',
            sortable : true
        },{
            name : 'workRate',
            display : '����ռ��',
            sortable : true,
			process : function(v){
				return v + ' %';
			}
        } ,{
            name : 'proName',
            display : '����ʡ��',
            sortable : true
        },{
            name : 'proCode',
            display : 'ʡ�ݱ���',
            sortable : true,
            hide : true
        },{
            name : 'officeName',
            display : '���´�',
            sortable : true
        },{
            name : 'officeId',
            display : '���´�id',
            sortable : true,
            hide : true
        },{
            name : 'deptName',
            display : '��������',
            sortable : true
        },{
            name : 'deptId',
            display : '����id',
            sortable : true,
            hide : true
        },{
            name : 'managerName',
            display : '��Ŀ����',
            sortable : true
        },{
            name : 'managerId',
            display : '��Ŀ�����˺�',
            sortable : true,
            hide : true
        },{
            name : 'planBeginDate',
            display : 'Ԥ����������',
            sortable : true
        },{
            name : 'planEndDate',
            display : 'Ԥ�ƽ�������',
            sortable : true
        },{
            name : 'projectObjectives',
            display : '��ĿĿ��',
            sortable : true
        },{
            name : 'description',
            display : '��Ŀ����',
            sortable : true,
            hide : true
        },{
            name : 'remark',
            display : '��ע',
            sortable : true,
            hide : true
        },{
            name : 'createId',
            display : '������Id',
            sortable : true,
            hide : true
        },{
            name : 'createName',
            display : '����������',
            sortable : true,
            hide : true
        },{
            name : 'createTime',
            display : '����ʱ��',
            sortable : true,
            hide : true
        },{
            name : 'updateId',
            display : '�޸���Id',
            sortable : true,
            hide : true
        },{
            name : 'updateName',
            display : '�޸�������',
            sortable : true,
            hide : true
        },{
            name : 'updateTime',
            display : '�޸�ʱ��',
            sortable : true,
            hide : true
        }],
        toAddConfig : {
			formWidth : 1000,
			formHeight : 550
		},
		toEditConfig : {
			formWidth : 800,
			formHeight : 500
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 500
		},
		searchitems : [{
			display : 'ָ�����´�',
			name : 'officeName'
		}, {
			display : '��Ŀ���',
			name : 'projectCode'
		}, {
			display : '��Ŀ����',
			name : 'projectName'
		}],
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����
		sortorder : "DESC"
    });
});