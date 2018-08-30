var show_page = function() {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {
	var combogrid = window.dialogArguments[0];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
	if (eventStr.row_dblclick) {
		var dbclickFunLast = eventStr.row_dblclick;
		eventStr.row_dblclick = function(e, row, data) {
			dbclickFunLast(e, row, data);
			window.returnValue = row.data('data');
			window.close();
		};
	} else {
		eventStr.row_dblclick = function(e, row, data) {
			window.returnValue = row.data('data');
			window.close();
		};
	}

	var gridOptions = combogrid.options.gridOptions;
	//��Ŀ״̬����ֵ��Ĭ��Ϊ�ڽ�
	var statusComboExVal = 'GCXMZT02';
	if(gridOptions.param != undefined){
		var statusArr = gridOptions.param.statusArr;
		if(statusArr != undefined){
			if(statusArr.indexOf(',') != -1){//����ֵ�ж����������Ĭ��ֵΪ����
				statusComboExVal = '';
			}else{
				statusComboExVal = statusArr;
			}
		}
	}
	
	// ����ֻ��ʾ�ڽ����깤״̬����� PMS2600
    var comboExArr = (gridOptions.param != undefined && gridOptions.param.setStatusComboEx == 'true')?
        // ���ù���ѡ��Ϊ�깤���ڽ�
        [{
            text: "��Ŀ״̬",
            key: 'status',
            data : [
                {
                    text: '�ڽ�',
                    value: 'GCXMZT02'
                },
                {
                    text: '�깤',
                    value: 'GCXMZT04'
                }
            ],
            value : statusComboExVal
        }]
        :
        // Ĭ����ʾ����״̬ѡ��
        [{
            text: "��Ŀ״̬",
            key: 'status',
            datacode : 'GCXMZT',
            value : statusComboExVal
        }];

    $("#esmprojectGrid").yxgrid({
        model : 'engineering_project_esmproject',
		action : gridOptions.action,
        param : gridOptions.param,
		title : "˫��ѡ����Ŀ",
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		showcheckbox : false,
        isOpButton : false,
        autoload: false,
		customCode : 'esmprojectGridSelect',
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'officeId',
            display : '���´�ID',
            sortable : true,
            hide : true
        },{
            name : 'officeName',
            display : '����',
            sortable : true
        },{
            name : 'projectName',
            display : '��Ŀ����',
            sortable : true,
            width : 150
        },{
            name : 'projectCode',
            display : '��Ŀ���',
            sortable : true,
            width : 120
        },{
            name : 'status',
            display : '��Ŀ״̬',
            sortable : true,
			datacode : 'GCXMZT',
            width : 70
        },{
            name : 'contractId',
            display : '������ͬid',
            sortable : true,
            hide : true
        },{
            name : 'contractCode',
            display : '������ͬ���',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'contractTempCode',
            display : '��ʱ��ͬ���',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'rObjCode',
            display : 'ҵ����',
            sortable : true,
            width : 120,
            hide : true
        },{
            name : 'customerId',
            display : '�ͻ�id',
            sortable : true,
            hide : true
        },{
            name : 'customerName',
            display : '�ͻ�����',
            sortable : true,
            hide : true
        },{
            name : 'province',
            display : '����ʡ��',
            width : 80,
            sortable : true
        },{
            name : 'deptName',
            display : '��������',
            sortable : true,
            hide : true
        },{
            name : 'managerName',
            display : '��Ŀ����',
            sortable : true
        },{
            name : 'ExaStatus',
            display : '����״̬',
            sortable : true,
            width : 80,
            hide : true
        },{
            name : 'ExaDT',
            display : '��������',
            sortable : true,
            hide : true,
            width : 80
        },{
            name : 'peopleNumber',
            display : '����',
            sortable : true,
            width : 80,
            hide : true
        },{
            name : 'nature',
            display : '��������',
            sortable : true,
            datacode : 'GCXMXZ',
            hide : true
        },{
            name : 'outsourcing',
            display : '�������',
            sortable : true,
			datacode : 'WBLX',
            width : 80,
            hide : true
        },{
            name : 'cycle',
            display : '��/����',
            sortable : true,
            width : 80,
            datacode : 'GCCDQ',
            hide : true
        },{
            name : 'category',
            display : '��Ŀ���',
            sortable : true,
            width : 80,
            datacode : 'XMLB',
            hide : true
        }],
		searchitems : [{
			display : '����',
			name : 'officeName'
		}, {
			display : '��Ŀ���',
			name : 'projectCodeSearch'
		}, {
			display : '��Ŀ����',
			name : 'projectName'
		}, {
			display : '��Ŀ����',
			name : 'managerName'
		}],
		// ����״̬���ݹ���
		comboEx : comboExArr,
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr
    });
});