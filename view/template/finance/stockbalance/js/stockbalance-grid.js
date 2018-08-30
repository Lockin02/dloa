var show_page = function() {
	$("#stockbalanceGrid").yxgrid("reload");
};

$(function() {
	$("#tree").yxtree({
		showLine: false,
		url: '?model=stock_stockinfo_stockinfo&action=getTreeStock',
		event: {
			node_click: function(event, treeId, treeNode) {
				var stockbalanceGrid = $("#stockbalanceGrid").data('yxgrid');
				if (treeNode.id == -1) {
					stockbalanceGrid.options.extParam['stockId'] = "";
					stockbalanceGrid.reload();
				} else {
					stockbalanceGrid.options.extParam['stockId'] = treeNode.id;
					stockbalanceGrid.reload();
				}
			},
			node_success: function() {
				$("#tree").yxtree("expandAll");
			}
		}
	});

	//财务期下拉列表
	var periodArr = [];
	$.ajax({
		type: "POST",
		url: "?model=finance_period_period&action=getAllPeriod",
		data: "",
		async: false,
		success: function(data) {
			periodArr = eval("(" + data + ")");
		}
	});

	$("#stockbalanceGrid").yxgrid({
		model: 'finance_stockbalance_stockbalance',
		title: '期初余额',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		customCode: 'stockbalanceGrid',
        noCheckIdValue: 'noId',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'thisYear',
			display: '年',
			sortable: true,
			width: 60
		}, {
			name: 'thisMonth',
			display: '月',
			sortable: true,
			width: 30
		}, {
			name: 'thisDate',
			display: '日期',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'stockId',
			display: '仓库id',
			width: 80,
			sortable: true,
			hide: true
		}, {
			name: 'stockName',
			display: '仓库名称',
			width: 80,
			sortable: true
		}, {
			name: 'productNo',
			display: '物料编号',
			sortable: true,
			width: 90
		}, {
			name: 'k3Code',
			display: 'K3编码',
			sortable: true,
			width: 90
		}, {
			name: 'productName',
			display: '物料名称',
			sortable: true,
			width: 140
		}, {
			name: 'productModel',
			display: '规格型号',
			sortable: true,
			hide: true
		}, {
			name: 'units',
			display: '单位',
			sortable: true,
			width: 60
		}, {
			name: 'pricing',
			display: '计价方式',
			sortable: true,
			hide: true
		}, {
			name: 'clearingNum',
			display: '结算数量',
			sortable: true,
			width: 80
		}, {
			name: 'price',
			display: '结算单价',
			sortable: true,
			width: 80,
			hide: true,
			process: function(v) {
				if (v >= 0) {
					return moneyFormat2(v, 6, 6);
				} else {
					return '<span class="red">' + moneyFormat2(v, 6, 6) + '</span>';
				}
			}
		}, {
			name: 'balanceAmount',
			display: '结存金额',
			sortable: true,
			process: function(v) {
				if (v >= 0) {
					return moneyFormat2(v);
				} else {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				}
			},
			width: 80
		}, {
			name: 'isDeal',
			display: '已出单',
			sortable: true,
			process: function(v) {
				if (v == 1) {
					return '<span class="red">是</span>';
				} else {
					return '否';
				}
			},
			width: 50
		}],
		buttonsEx: [{
			text: "出单",
			icon: 'add',
			action: function(row, rows, idArr) {
				if (row) {
					idStr = idArr.toString();
					showThickboxWin("?model=finance_costajust_costajust"
					+ "&action=toAddInStockBal"
					+ "&ids="
					+ idStr
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
					+ "&width=900");
				} else {
					alert('请先选择记录');
				}
			}
		}, {
	        name: 'excelIn',
	        text: "导入",
			icon : 'excel',
			items : [{
				text: "期初余额导入",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=finance_stockbalance_stockbalance"
					+ "&action=toAddBalance"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
					+ "&width=600");
				}
			}, {
				text: "余额价格导入",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=finance_stockbalance_stockbalance"
					+ "&action=toAddProductPrice"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
					+ "&width=600");
				}
			}]
		}, {
			name : 'excelOut',
			text : "导出",
			icon : 'excel',
			action: function() {
				showThickboxWin("?model=finance_stockbalance_stockbalance"
				+ "&action=toExportExcel&thisDate=" + $("#thisDate").val()
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
				+ "&width=600");
			}
		}],
		menusEx: [{
			text: "出单记录",
			icon: 'view',
			showMenuFn: function(row) {
				return row.isDeal == 1;
			},
			action: function(row) {
				showThickboxWin("?model=finance_costajust_costajust"
				+ "&action=initForStockBal&perm=view"
				+ "&stockbalId="
				+ row.id
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
				+ "&width=900");
			}
		}, {
			text: "余额明细",
			icon: 'view',
			action: function(row) {
				showModalWin("?model=finance_stockbalance_stockbalance&action=calDetail&productId="
					+ row.productId
					+ "&stockId=" + row.stockId
					+ "&thisYear=" + row.thisYear
					+ "&thisMonth=" + row.thisMonth
					+ "&stockName=" + row.stockName
					+ "&productName=" + row.productName,
					1, row.stockName + row.productCode
				);
			}
		}],
		comboEx: [{
			text: '财务期',
			key: 'thisDate',
			value: $("#thisDate").val(),
			data: periodArr
		}],
		searchitems: [
			{
				display: '物料编号',
				name: 'productNoLike'
			},
			{
				display: 'K3编号',
				name: 'k3CodeLike'
			},
			{
				display: '仓库名称',
				name: 'stockName'
			}
		],
		sortname: 'c.thisDate desc,c.stockId asc,c.productNo',
		sortorder: 'ASC'
	});
});