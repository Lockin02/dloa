var show_page = function() {
	$("#stockbalanceGrid").yxgrid("reload");
};

$(function() {
	$("#stockbalanceGrid").yxgrid({
		model: 'finance_stockbalance_stockbalance',
		action: 'detailPageJson',
		title: '余额明细 : ' + $('#stockName').val() + ' - 物料名称 ：' + $('#productName').val(),
		param: {
			thisYear: $("#thisYear").val(),
			thisMonth: $("#thisMonth").val(),
			stockId: $("#stockId").val(),
			productId: $("#productId").val()
		},
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		isViewAction: false,
		showcheckbox: false,
		isRightMenu: false,
		isShowNum: false,
		usepager: false, // 是否分页
		//列信息
		colModel: [{
			name: 'formDate',
			display: '日期',
			sortable: true,
			width: 80
		}, {
			name: 'formType',
			display: '单据类型',
			sortable: true,
			process: function(v) {
				switch (v) {
					case 'balance' :
						return '期初余额';
						break;
					case 'costAdjust' :
						return '成本调整单';
						break;
					case 'adjustment' :
						return '补差单';
						break;
					case 'RKPURCHASE' :
						return '外购入库';
						break;
					case 'RKPRODUCT' :
						return '产品入库';
						break;
					case 'RKOTHER' :
						return '其他入库';
						break;
					case 'allo-in' :
						return '调拨单-入';
						break;
					case 'CKSALES' :
						return '销售出库';
						break;
					case 'CKPICKING' :
						return '领料出库';
						break;
					case 'CKOTHER' :
						return '其他出库';
						break;
					case 'allo-out' :
						return '调拨单-出';
						break;
					default :
						return v;
				}
			},
			width: 80
		}, {
			name: 'formNo',
			display: '单据编号',
			sortable: true,
			width: 130,
			process: function(v, row) {
				if (row.isRed == 0) {
					return v;
				} else {
					return "<span class='red'>" + v + "</span>";
				}
			}
		}, {
			name: 'inNumber',
			display: '入库数量',
			sortable: true,
			width: 80,
			process: function(v) {
				if (v * 1 != 0) {
					return '<span style="font-weight:bold">' + v + '</span> ';
				}
			}
		}, {
			name: 'inAmount',
			display: '入库金额',
			sortable: true,
			process: function(v) {
				if (v * 1 != 0)
					return moneyFormat2(v);
			}
		}, {
			name: 'inPrice',
			display: '入库单价',
			sortable: true,
			process: function(v, row) {
				if (row.inNumber * 1 != 0) {
					if (v * 1 == 0) {
						return "<span class='red'>" + v + "</span>";
					} else
						return moneyFormat2(v, 6);
				}
			}
		}, {
			name: 'outNumber',
			display: '出库数量',
			sortable: true,
			width: 80,
			process: function(v) {
				if (v * 1 != 0) {
					return '<span style="font-weight:bold">' + v + '</span> ';
				}
			}
		}, {
			name: 'outAmount',
			display: '出库金额',
			sortable: true,
			process: function(v) {
                if (v * 1 != 0)
                    return moneyFormat2(v);
			}
		}, {
			name: 'outPrice',
			display: '出库单价',
			sortable: true,
			process: function(v, row) {
				if (row.outNumber * 1 != 0) {
					if (v * 1 == 0) {
						return "<span class='red'>" + v + "</span>";
					} else
						return moneyFormat2(v, 6);
				}
			}
		}, {
			name: 'balNumber',
			display: '结存数量',
			sortable: true,
			width: 80,
			process: function(v) {
				return v * 1 > 0 ? '<span style="font-weight:bold">' + v + '</span> ' : '<span style="font-weight:bold" class="red">' + v + '</span> ';
			}
		}, {
			name: 'balAmount',
			display: '结存金额',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			}
		}
		]
	});
});