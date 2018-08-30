var show_page = function(page) {
	$("#prostockwarnGrid").yxgrid("reload");
};
$(function() {
	$("#prostockwarnGrid").yxgrid({
		model : 'stock_productinfo_prostockwarn',
		title : '物料库存预警信息配置',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '物料编号',
			sortable : true
		}, {
			name : 'productName',
			display : '物料名称',
			sortable : true,
			width:200
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true
		}, {
			name : 'unitName',
			display : '单位',
			sortable : true
		}, {
			name : 'maxNum',
			display : '最大库存',
			sortable : true
		}, {
			name : 'miniNum',
			display : '最小库存',
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
			display : "物料编号",
			name : 'lproductCode'
		}, {
			display : "物料名称",
			name : 'lproductName'
		} ]
	});
});