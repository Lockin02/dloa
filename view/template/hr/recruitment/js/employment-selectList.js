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
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		isOpButton:false,
		bodyAlign:'center',
		title : 'ְλ�����',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'name',
			display : '����',
			width:60,
			sortable : true
		},{
			name : 'employmentCode',
			display : '���ݱ��',
			width:120,
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		},{
			name : 'sex',
			display : '�Ա�',
			width:50,
			sortable : true
		},{
			name : 'mobile',
			display : '�绰'
		}],

//		lockCol:['employmentCode','name'],//����������

		searchitems : [{
			display : "���ݱ��",
			name : 'employmentCode'
		},{
			display : "����",
			name : 'name'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "�绰",
			name : 'mobile'
		}],

		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr
	});
});