var show_page = function(page) {
	$("#qualityereportitemGrid").yxgrid("reload");
};
$(function() {
	$("#qualityereportitemGrid").yxgrid({
		model : 'produce_quality_qualityereportitem',
		title : '检验报告清单',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'dimensionName',
			display : '检验项目',
			sortable : true
		}, {
			name : 'examStartName',
			display : '指标标准',
			sortable : true
		}, {
			name : 'examTypeName',
			display : '检验方式名称',
			sortable : true
		}, {
			name : 'exmineResult',
			display : '检验结果',
			sortable : true
		} ],
		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_quality_NULL&action=pageItemJson',
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