var show_page = function(page) {
	$("#salarytplateGrid").yxsubgrid("reload");
};
$(function() {
	$("#salarytplateGrid").yxgrid({
		model : 'hr_leave_salarytplate',
		title : '��ְ���ʽ����嵥ģ��',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {

			name : 'schemeName',
			display : '��������',
			width:200,
			sortable : true
		}, {
			name : 'jobName',
			display : 'ְλ����',
			sortable : true
		}, {
			name : 'companyName',
			display : '���ƣ���˾��',
			sortable : true
		}, {
			name : 'leaveTypeCode',
			display : '��ְ����',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			width:300,
			sortable : true

		}, {
			name : 'createName',
			display : '������',
			sortable : true

		} ],
//		// ���ӱ������
//		subGridOptions : {
//			url : '?model=hr_leave_salarytplateitem&action=pageItemJson',
//			param : [ {
//				paramId : 'mainId',
//				colId : 'id'
//			} ],
//			colModel : [ {
//				name : 'XXX',
//				display : '�ӱ��ֶ�'
//			} ]
//		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "�����ֶ�",
			name : 'XXX'
		} ]
	});
});