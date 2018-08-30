var show_page = function (page) {
	$("#backGrid").yxsubgrid("reload");
};
$(function () {
	$("#backGrid").yxsubgrid({
		model: 'produce_plan_back',
		param: {docStatusIn: '1,2,3'},
		title: '待入库生产退料列表',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'pickingCode',
			display: '领料单编号',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.pickingId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_back&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docDate',
			display: '单据日期',
			sortable: true
		}, {
			name: 'createName',
			display: '申请人',
			sortable: true
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 250
		}],

		// 主从表格设置
		subGridOptions: {
			url: '?model=produce_plan_backitem&action=pageJson',
			param: [{
				paramId: 'backId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '物料编码',
				sortable: true,
				width: 150
			}, {
				name: 'productName',
				display: '物料名称',
				width: 200,
				sortable: true
			}, {
				name: 'pattern',
				display: '规格型号',
				sortable: true
			}, {
				name: 'unitName',
				display: '单位',
				sortable: true
			}, {
				name: 'applyNum',
				display: '申请数量',
				sortable: true
			}, {
				name: 'backNum',
				display: '入库数量',
				sortable: true
			}]
		},

		menusEx : [{
			name : 'bluepush',
			text : '下推入库单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "1" || row.docStatus == "2") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPRODUCEBACK&relDocId="
						+ row.id + "&relDocCode=" + row.docCode,1,row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_back&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		comboEx : [{
			text : '入库状态',
			key : 'docStatusArr',
			value : '1,2',
			data : [{
				text : '未入库',
				value : '1'
			}, {
				text : '部分入库',
				value : '2'
			}, {
				text : '已入库',
				value : '3'
			}, {
				text : '未入库，部分入库',
				value : '1,2'
			}]
		}],
		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		}, {
			name: 'docDate',
			display: '单据日期'
		}, {
			name: 'relDocCode',
			display: '源单编号'
		}, {
			name: 'relDocName',
			display: '源单名称'
		}, {
			name: 'relDocType',
			display: '源单类型'
		}, {
			name: 'createName',
			display: '申请人'
		}]
	});
});