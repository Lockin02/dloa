var show_page = function(page) {
	$("#qualityereportitemGrid").yxgrid("reload");
};
$(function() {
	$("#qualityereportitemGrid").yxgrid({
		model : 'produce_quality_qualityereportitem',
		title : '���鱨���嵥',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'dimensionName',
			display : '������Ŀ',
			sortable : true
		}, {
			name : 'examStartName',
			display : 'ָ���׼',
			sortable : true
		}, {
			name : 'examTypeName',
			display : '���鷽ʽ����',
			sortable : true
		}, {
			name : 'exmineResult',
			display : '������',
			sortable : true
		} ],
		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_quality_NULL&action=pageItemJson',
			param : [ {
				paramId : 'mainId',
				colId : 'id'
			} ],
			colModel : [ {
				name : 'XXX',
				display : '�ӱ��ֶ�'
			} ]
		},

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