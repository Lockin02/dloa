var show_page = function(page) {
	$("#produceapplyitemGrid").yxgrid("reload");
};
$(function() {
	$("#produceapplyitemGrid").yxgrid({
		model : 'produce_apply_produceapplyitem',
		title : '���������嵥',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '��Ʒ���',
			sortable : true
		}, {
			name : 'productName',
			display : '��Ʒ����',
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
			name : 'produceNum',
			display : '����',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '�ƻ�����ʱ��',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		}, {
			name : 'exeNum',
			display : '���´�����',
			sortable : true
		}, {
			name : 'isTemp',
			display : '�Ƿ���ʱ����',
			sortable : true
		}, {
			name : 'changeTips',
			display : '�����־',
			sortable : true
		}, {
			name : 'idDel',
			display : '��ɾ����־λ',
			sortable : true
		} ],
		// ���ӱ������
		subGridOptions : {
			url : '?model=produce_apply_NULL&action=pageJson',
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