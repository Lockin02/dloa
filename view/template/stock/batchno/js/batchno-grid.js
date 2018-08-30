var show_page = function(page) {
	$("#batchnoGrid").yxgrid("reload");
};
$(function() {
	$("#batchnoGrid").yxgrid({
		model : 'stock_batchno_batchno',
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		title : '物料批次号台账',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'inDocId',
			display : '入库单id',
			sortable : true,
			hide : true
		}, {
			name : 'inDocCode',
			display : '入库单编号',
			sortable : true
		}, {
			name : 'inDocItemId',
			display : '入库清单id',
			sortable : true,
			hide : true
		}, {
			name : 'outDocCode',
			display : '出库单编号',
			sortable : true,
			hide : true
		}, {
			name : 'outDocId',
			display : '出库单id',
			sortable : true,
			hide : true
		}, {
			name : 'outDocItemId',
			display : '出库单清单id',
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
			width : 200,
			sortable : true

		}, {
			name : 'batchNo',
			display : '批次号',
			sortable : true
		}, {
			name : 'stockId',
			display : '仓库id',
			sortable : true,
			hide : true
		}, {
			name : 'stockCode',
			display : '仓库代码',
			sortable : true
		}, {
			name : 'stockName',
			display : '仓库名称',
			sortable : true

		}, {
			name : 'stockNum',
			display : '入库数量',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true
		} ],
		searchitems : [ {
			display : '批次号',
			name : 'batchNo'
		}, {
			name : 'productName',
			display : '物料名称'

		} ]
	});
});