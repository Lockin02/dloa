var show_page = function(page) {
	$("#employmentGrid").yxgrid("reload");
};

$(function() {
	var showcheckbox = $("#showcheckbox").val();
	var showButton = $("#showButton").val();

	var textArr = [];
	var valArr = [];
	var indexArr = [];
	var combogrid = window.dialogArguments[0];
	var opener = window.dialogArguments[1];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
	var titleVal = "˫��ѡ����Ա";

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
	$("#employmentGrid").yxgrid({
		model : 'hr_recruitment_employment',
		action : gridOptions.action,
		title : titleVal,
		isToolBar : true,
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : showcheckbox,
		param : gridOptions.param,
		pageSize : 15,
		showcheckbox : false,
		imSearch : true,// ��ʱ����
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'employmentCode',
			display : '���',
			sortable : true,
			width : 120,
			process : function(v, row) {
					return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
				}
		}, {
			name : 'name',
			display : '����',
			width : 70,
			sortable : true
		}, {
			name : 'sex',
			display : '�Ա�',
			width : 40,
			sortable : true
		}, {
			name : 'nation',
			display : '����',
			width : 40,
			sortable : true
		}, {
			name : 'age',
			display : '����',
			width : 40,
			sortable : true
		}, {
			name : 'highEducationName',
			display : 'ѧ��',
			width : 70,
			sortable : true
		}, {
			name : 'highSchool',
			display : '��ҵѧУ',
			sortable : true
		}, {
			name : 'professionalName',
			display : 'רҵ',
			sortable : true
		}, {
			name : 'telephone',
			display : '�̶��绰',
			sortable : true
		}, {
			name : 'mobile',
			display : '�ƶ��绰',
			sortable : true
		}, {
			name : 'personEmail',
			display : '���˵�������',
			sortable : true
		}, {
			name : 'QQ',
			display : 'QQ',
			sortable : true
		}],
			// ��������
			searchitems : [{
					display : '���ݱ��',
					name : 'employmentCode'
				},{
					display : '����',
					name : 'name'
				}
			],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr

	});
});