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
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		isOpButton:false,
		bodyAlign:'center',
		title : '职位申请表',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'name',
			display : '姓名',
			width:60,
			sortable : true
		},{
			name : 'employmentCode',
			display : '单据编号',
			width:120,
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		},{
			name : 'sex',
			display : '性别',
			width:50,
			sortable : true
		},{
			name : 'mobile',
			display : '电话'
		}],

//		lockCol:['employmentCode','name'],//锁定的列名

		searchitems : [{
			display : "单据编号",
			name : 'employmentCode'
		},{
			display : "名称",
			name : 'name'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "电话",
			name : 'mobile'
		}],

		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr
	});
});