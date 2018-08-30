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
	var titleVal = "双击选择项目";

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
		imSearch : true,// 即时搜索
		// 列信息
		colModel : [{
				display : '项目编号',
				name : 'projectCode',
				width : 150
			}, {
				display : '项目名称',
				name : 'projectName',
				width : 200
			}, {
				display : '项目经理',
				name : 'managerName'
			}
		],
		// 快速搜索
		searchitems : [{
				display : '项目名称',
				name : 'searhDProjectName'
			},{
				display : '项目编号',
				name : 'searhDProjectCode'
			}
		],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr

	});
});