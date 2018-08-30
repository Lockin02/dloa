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
        //����Ϣ
        colModel : [{
					name : 'productName',
					display : '��Ʒ����',
					width:100,
					sortable : true
					},{
					name : 'productCode',
					display : '��Ʒ���',
					width:100,
					sortable : true
					},{
					name : 'remark',
					display : '��ע',
					width:200,
					sortable : true
					}],
	// ��������
					searchitems : [{
							display : '��Ʒ����',
							name : 'productName'
						},{
							display : '��Ʒ���',
							name : 'productCode'
						}],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr

    });
});