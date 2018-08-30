var show_page = function(page) {
	$("#qualitytaskitemGrid").yxgrid("reload");
};
$(function() {
	$("#qualitytaskitemGrid").yxgrid({
		model : 'produce_quality_qualitytaskitem',
		title : '交检任务单清单',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '物料编号',
			sortable : true
		}, {
			name : 'productName',
			display : '物料名称',
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
			name : 'assignNum',
			display : '数量',
			sortable : true
		}, {
			name : 'standardNum',
			display : '合格数量',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true
		} ],

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