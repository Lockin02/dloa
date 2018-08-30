var show_page = function(page) {
	$("#rdprojectGrid").yxgrid("reload");
};

$(function() {
	var combogrid = window.dialogArguments[0];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
	var titleVal = "˫��ѡ����Ŀ";

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
    // �Ƿ���ʾ����ѡ��
    gridOptions.comboEx = gridOptions.comboEx != false ? [{
        text : '��Ŀ����',
        key : 'projectType',
        data : [{
            text : '�з���Ŀ',
            value : 'rd'
        }, {
            text : '������Ŀ',
            value : 'esm'
        }, {
            text : '��Ʒ��Ŀ',
            value : 'con'
        }]
    }] : false;

	$("#rdprojectGrid").yxgrid({
		model : 'rdproject_project_rdproject',
		action : gridOptions.action,
		title : titleVal,
		isToolBar : true,
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		param : gridOptions.param,
		pageSize : 15,
        isOpButton : false,
		imSearch : true,// ��ʱ����
		// ����Ϣ
		colModel : [{
				display : '��Ŀ����',
				name : 'projectType',
				process : function(v){
                    if (v == 'esm') {
                        return '������Ŀ';
                    } else if (v == 'con') {
                        return '��Ʒ��Ŀ';
                    } else {
                        return '�з���Ŀ';
                    }
				}
			}, {
				display : '��Ŀ���',
				name : 'projectCode',
				width : 150
			}, {
				display : '��Ŀ����',
				name : 'projectName',
				width : 200
			}, {
				display : '��Ŀ����',
				name : 'managerName'
			}
		],
		// ��������
		searchitems : [{
				display : '��Ŀ����',
				name : 'searhDProjectName'
			},{
				display : '��Ŀ���',
				name : 'searhDProjectCode'
			}
		],
		//��������
		comboEx : gridOptions.comboEx,
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr

	});
});