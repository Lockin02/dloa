var show_page = function(page) {
	$("#qualitytaskitemGrid").yxgrid("reload");
};
$(function() {
	$("#qualitytaskitemGrid").yxgrid({
		model : 'produce_quality_qualitytaskitem',
		title : '���������嵥',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '���ϱ��',
			sortable : true
		}, {
			name : 'productName',
			display : '��������',
			sortable : true
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true
		}, {
			name : 'unitName',
			display : '��λ',
			sortable : true
		}, {
			name : 'fittings',
			display : '����',
			sortable : true
		}, {
			name : 'assignNum',
			display : '����',
			sortable : true
		}, {
			name : 'standardNum',
			display : '�ϸ�����',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
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