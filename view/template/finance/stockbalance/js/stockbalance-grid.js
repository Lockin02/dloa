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

	//�����������б�
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
		title: '�ڳ����',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		customCode: 'stockbalanceGrid',
        noCheckIdValue: 'noId',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'thisYear',
			display: '��',
			sortable: true,
			width: 60
		}, {
			name: 'thisMonth',
			display: '��',
			sortable: true,
			width: 30
		}, {
			name: 'thisDate',
			display: '����',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'stockId',
			display: '�ֿ�id',
			width: 80,
			sortable: true,
			hide: true
		}, {
			name: 'stockName',
			display: '�ֿ�����',
			width: 80,
			sortable: true
		}, {
			name: 'productNo',
			display: '���ϱ��',
			sortable: true,
			width: 90
		}, {
			name: 'k3Code',
			display: 'K3����',
			sortable: true,
			width: 90
		}, {
			name: 'productName',
			display: '��������',
			sortable: true,
			width: 140
		}, {
			name: 'productModel',
			display: '����ͺ�',
			sortable: true,
			hide: true
		}, {
			name: 'units',
			display: '��λ',
			sortable: true,
			width: 60
		}, {
			name: 'pricing',
			display: '�Ƽ۷�ʽ',
			sortable: true,
			hide: true
		}, {
			name: 'clearingNum',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'price',
			display: '���㵥��',
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
			display: '�����',
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
			display: '�ѳ���',
			sortable: true,
			process: function(v) {
				if (v == 1) {
					return '<span class="red">��</span>';
				} else {
					return '��';
				}
			},
			width: 50
		}],
		buttonsEx: [{
			text: "����",
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
					alert('����ѡ���¼');
				}
			}
		}, {
	        name: 'excelIn',
	        text: "����",
			icon : 'excel',
			items : [{
				text: "�ڳ�����",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=finance_stockbalance_stockbalance"
					+ "&action=toAddBalance"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
					+ "&width=600");
				}
			}, {
				text: "���۸���",
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
			text : "����",
			icon : 'excel',
			action: function() {
				showThickboxWin("?model=finance_stockbalance_stockbalance"
				+ "&action=toExportExcel&thisDate=" + $("#thisDate").val()
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
				+ "&width=600");
			}
		}],
		menusEx: [{
			text: "������¼",
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
			text: "�����ϸ",
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
			text: '������',
			key: 'thisDate',
			value: $("#thisDate").val(),
			data: periodArr
		}],
		searchitems: [
			{
				display: '���ϱ��',
				name: 'productNoLike'
			},
			{
				display: 'K3���',
				name: 'k3CodeLike'
			},
			{
				display: '�ֿ�����',
				name: 'stockName'
			}
		],
		sortname: 'c.thisDate desc,c.stockId asc,c.productNo',
		sortorder: 'ASC'
	});
});