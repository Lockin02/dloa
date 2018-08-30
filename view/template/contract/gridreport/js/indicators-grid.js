var show_page = function(page) {
	$("#indicatorsGrid").yxgrid("reload");
};
$(function() {
	$("#indicatorsGrid").yxgrid({
		model : 'contract_gridreport_indicators',
		title : '指标值表',
		param : {
			groupBy : 'c.setCode'
		},
		isAddAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'setCode',
			display : '设置编码',
			sortable : true
		},{
			name : 'setName',
			display : '设置名称',
			sortable : true
		},{
			name : 'objCode',
			display : '业务编码',
			sortable : true
		},{
			name : 'objName',
			display : '业务名称',
			sortable : true
		},{
			name : 'indicatorsCode',
			display : '指标编码',
			sortable : true,
			hide : true
		},{
			name : 'indicatorsName',
			display : '指标名称',
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
			display : "设置名称",
			name : 'setName'
		}]
	});
});