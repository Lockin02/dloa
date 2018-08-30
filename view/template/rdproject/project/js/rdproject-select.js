var show_page = function(page) {
	$("#rdprojectGrid").yxgrid("reload");
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
	$("#rdprojectGrid").yxgrid({
		model : 'rdproject_project_rdproject',
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
		// ����Ϣ
		colModel : [{
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
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr

	});
});