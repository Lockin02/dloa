var show_page = function(page) {
	$("#gridindicatorsGrid").yxgrid("reload");
};
$(function() {
	$("#gridindicatorsGrid").yxgrid({
		model : 'contract_gridreport_gridindicators',
		title : '表格指标表',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'objCode',
			display : '对象编码',
			sortable : true
		},{
			name : 'objName',
			display : '对象名称',
			sortable : true
		},{
			name : 'createId',
			display : '创建人Id',
			hide : true
		},{
			name : 'createName',
			display : '创建人',
			sortable : true
		},{
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 150
		}],

		//扩展右键菜单
		menusEx : [{
			text : "添加设置范围",
			icon : 'add',
			action : function(row ,rows ,grid) {
				if(row) {
					showModalWin("?model=contract_gridreport_indicators&action=toAdd&gridId=" + row.id ,1);
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});