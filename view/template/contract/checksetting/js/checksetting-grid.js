var show_page = function(page) {
	$("#checksettingGrid").yxgrid("reload");
};
$(function() {
	$("#checksettingGrid").yxgrid({
		model : 'contract_checksetting_checksetting',
		title : '���չ�������',
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'clause',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'dateName',
			display : '����ʱ��ڵ�',
			sortable : true
		}, {
			name : 'dateCode',
			display : '����ʱ��ڵ����',
			sortable : true,
			hide : true
		}, {
			name : 'days',
			display : '��������',
			sortable : true
		}, {
			name : 'description',
			display : '˵��',
			sortable : true,
			width : 300
		} ],

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