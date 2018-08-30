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
	var titleVal = "双击选择人员";

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
		imSearch : true,// 即时搜索
		bodyAlign:'center',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'employmentCode',
			display : '编号',
			sortable : true,
			width : 120,
			process : function(v, row) {
					return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
				}
		}, {
			name : 'name',
			display : '姓名',
			width : 70,
			sortable : true
		}, {
			name : 'sex',
			display : '性别',
			width : 40,
			sortable : true
		}, {
			name : 'nation',
			display : '民族',
			width : 40,
			sortable : true
		}, {
			name : 'age',
			display : '年龄',
			width : 40,
			sortable : true
		}, {
			name : 'highEducationName',
			display : '学历',
			width : 70,
			sortable : true
		}, {
			name : 'highSchool',
			display : '毕业学校',
			sortable : true
		}, {
			name : 'professionalName',
			display : '专业',
			sortable : true
		}, {
			name : 'telephone',
			display : '固定电话',
			sortable : true
		}, {
			name : 'mobile',
			display : '移动电话',
			sortable : true
		}, {
			name : 'personEmail',
			display : '个人电子邮箱',
			sortable : true
		}, {
			name : 'QQ',
			display : 'QQ',
			sortable : true
		}],
			// 快速搜索
			searchitems : [{
					display : '单据编号',
					name : 'employmentCode'
				},{
					display : '姓名',
					name : 'name'
				}
			],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr

	});
});