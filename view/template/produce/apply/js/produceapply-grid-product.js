var show_page = function (page) {
	$("#productGrid").yxgrid("reload");
};

$(function () {
	$("#productGrid").yxgrid({
		model: 'produce_apply_produceapplyitem',
		action: 'productPageJson',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		title: '订单/合同物料汇总需求',
		// 列信息
		colModel: [{
			display: 'productId',
			name: 'productId',
			sortable: true,
			hide: true
		}, {
			name: 'proType',
			display: '物料类型',
			sortable: true,
			width: 100
		}, {
			name: 'productCode',
			display: '物料编码',
			sortable: true,
			width: 100
		}, {
			name: 'productName',
			display: '物料名称',
			sortable: true,
			width: 300
		}, {
			name: 'pattern',
			display: '规格型号',
			sortable: true,
			width: 150
		}, {
			name: 'unitName',
			display: '单位',
			sortable: true,
			width: 60,
			align: 'center'
		}, {
			name: 'produceNumAll',
			display: '申请总数量',
			sortable: true,
			align: 'center',
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct&code=" +
					row.productCode + "&num=" + row.produceNumAll + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'exeNumAll',
			display: '已下达任务数量',
			sortable: true,
			align: 'center'
		}, {
			name: 'inventory',
			display: '库存数量',
			width: 80,
			sortable: true,
			align: 'center'
		}, {
			name: 'detail',
			display: '详细',
			sortable: true,
			align: 'center',
			width: 60,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewProduct&productId=" +
					row.productId + "\",1)'>详细</a>";
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var row = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toViewProduct&productId=" + row.productId, '1');
				}
			}
		},

		//扩展右键菜单
		menusEx: [],

		searchitems: [{
			display: "合同编号",
			name: 'relDocCode'
		}, {
			display: "物料类型",
			name: 'proType'
		}, {
			display: "物料编码",
			name: 'productCode'
		}, {
			display: "物料名称",
			name: 'productName'
		}]
	});
});