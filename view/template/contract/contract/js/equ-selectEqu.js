var show_page = function(page) {
	$("#contractEquGrid").yxgrid("reload");
};
$(function() {
	var combogrid = window.dialogArguments[0];
	var gridOptions = combogrid.options.gridOptions;
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
	$("#contractEquGrid").yxgrid(
		{
			model : gridOptions.model,
			action : gridOptions.action,
			title : '合同物料信息选择',
			isToolBar : true,
			isViewAction : false,
			isDelAction : false,
			isEditAction : false,
			isAddAction : false,
			showcheckbox : gridOptions.showcheckbox,
			param : gridOptions.param,
			pageSize : 15,
			imSearch : true,// 即时搜索
			colModel : gridOptions.colModel,
			searchitems : gridOptions.searchitems,
			// 把事件复制过来
			event : eventStr
	
		});
});