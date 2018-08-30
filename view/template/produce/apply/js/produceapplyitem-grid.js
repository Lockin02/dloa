var show_page = function(page) {
	$("#produceapplyitemGrid").yxgrid("reload");
};
$(function() {
	$("#produceapplyitemGrid").yxgrid({
		model : 'produce_apply_produceapplyitem',
		title : '生产申请清单',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '产品编号',
			sortable : true
		}, {
			name : 'productName',
			display : '产品名称',
			sortable : true
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true
		}, {
			name : 'unitName',
			display : '单位',
			sortable : true
		}, {
			name : 'fittings',
			display : '配置',
			sortable : true
		}, {
			name : 'produceNum',
			display : '数量',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '计划交货时间',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true
		}, {
			name : 'exeNum',
			display : '已下达任务',
			sortable : true
		}, {
			name : 'isTemp',
			display : '是否临时对象',
			sortable : true
		}, {
			name : 'changeTips',
			display : '变更标志',
			sortable : true
		}, {
			name : 'idDel',
			display : '假删除标志位',
			sortable : true
		} ],
		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_apply_NULL&action=pageJson',
			param : [ {
				paramId : 'mainId',
				colId : 'id'
			} ],
			colModel : [ {
				name : 'XXX',
				display : '从表字段'
			} ]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "搜索字段",
			name : 'XXX'
		} ]
	});
});