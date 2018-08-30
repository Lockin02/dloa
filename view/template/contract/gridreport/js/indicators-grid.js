var show_page = function(page) {
	$("#indicatorsGrid").yxgrid("reload");
};
$(function() {
	$("#indicatorsGrid").yxgrid({
		model : 'contract_gridreport_indicators',
		title : 'ָ��ֵ��',
		param : {
			groupBy : 'c.setCode'
		},
		isAddAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'setCode',
			display : '���ñ���',
			sortable : true
		},{
			name : 'setName',
			display : '��������',
			sortable : true
		},{
			name : 'objCode',
			display : 'ҵ�����',
			sortable : true
		},{
			name : 'objName',
			display : 'ҵ������',
			sortable : true
		},{
			name : 'indicatorsCode',
			display : 'ָ�����',
			sortable : true,
			hide : true
		},{
			name : 'indicatorsName',
			display : 'ָ������',
			sortable : true,
			hide : true
		}],

		toEditConfig : {
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=contract_gridreport_indicators&action=toEdit&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=contract_gridreport_indicators&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems : [{
			display : "��������",
			name : 'setName'
		}]
	});
});