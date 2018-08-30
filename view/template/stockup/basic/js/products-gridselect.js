var show_page = function(page) {
	$("#productSelectGrid").yxgrid("reload");
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

    $("#productSelectGrid").yxgrid({
        model : 'stockup_basic_products',
		action : gridOptions.action,
		title : titleVal,
		showcheckbox : showcheckbox,
		param : gridOptions.param,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		showcheckbox : false,
		customCode : 'productSelectGrid',
        //列信息
        colModel : [{
					name : 'productName',
					display : '产品名称',
					width:100,
					sortable : true
					},{
					name : 'productCode',
					display : '产品编号',
					width:100,
					sortable : true
					},{
					name : 'remark',
					display : '备注',
					width:200,
					sortable : true
					}],
	// 快速搜索
					searchitems : [{
							display : '产品名称',
							name : 'productName'
						},{
							display : '产品编号',
							name : 'productCode'
						}],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr

    });
});