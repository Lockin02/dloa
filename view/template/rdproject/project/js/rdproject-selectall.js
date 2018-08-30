var show_page = function(page) {
	$("#rdprojectGrid").yxgrid("reload");
};

$(function() {
	var combogrid = window.dialogArguments[0];
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
    // 是否显示下拉选择
    gridOptions.comboEx = gridOptions.comboEx != false ? [{
        text : '项目类型',
        key : 'projectType',
        data : [{
            text : '研发项目',
            value : 'rd'
        }, {
            text : '工程项目',
            value : 'esm'
        }, {
            text : '产品项目',
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
		imSearch : true,// 即时搜索
		// 列信息
		colModel : [{
				display : '项目类型',
				name : 'projectType',
				process : function(v){
                    if (v == 'esm') {
                        return '工程项目';
                    } else if (v == 'con') {
                        return '产品项目';
                    } else {
                        return '研发项目';
                    }
				}
			}, {
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
		//过滤数据
		comboEx : gridOptions.comboEx,
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr

	});
});