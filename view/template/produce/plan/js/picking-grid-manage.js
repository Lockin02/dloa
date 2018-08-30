var show_page = function (page) {
	$("#pickingGrid").yxsubgrid("reload");
};

$(function () {
	//数据过滤条件
	var param = {
		ExaStatus: '完成'
	};
	if ($("#perform").val() == 'yes') {
		param.docStatusIn = '5';
	} else {
		param.docStatusIn = '2,4';
	}

	$("#pickingGrid").yxsubgrid({
		model: 'produce_plan_picking',
		param: param,
		title: '生产领料申请单',
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
			name: 'relDocCode',
			display: '源单编号',
			sortable: true,
			width: 130,
            process: function (v, row) {
            	if(row.relDocTypeCode == 'HTLX-XSHT' || row.relDocTypeCode == 'HTLX-FWHT' || 
            		row.relDocTypeCode == 'HTLX-ZLHT' || row.relDocTypeCode == 'HTLX-YFHT'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.relDocId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            	}
            	return v;
            }
		},{
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docDate',
			display: '单据日期',
			sortable: true
		},  {
			name: 'relDocName',
			display: '源单名称',
			sortable: true,
			width: 200
		}, {
			name: 'relDocType',
			display: '源单类型',
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
			url: '?model=produce_plan_pickingitem&action=pageJson',
			param: [{
				paramId: 'pickingId',
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
				name: 'realityNum',
				display: '出库数量',
				sortable: true
			}, {
				name: 'planDate',
				display: '计划领料日期',
				sortable: true
			}, {
				name: 'realityDate',
				display: '实际领料日期',
				sortable: true
			}]
		},

		//扩张右键菜单
		menusEx: [{
			text: "生产仓出库",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == '完成' && row.isCanOut > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=produce_plan_picking&action=toAddOut&id=" + row.id, '1');
				}
			}
		}, {
			text: "下推出库单",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == '完成' && row.docStatus != 5) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_outstock_stockout&action=toAddByPicking&id=" + row.id, '1');
				}
			}
		}, 
//		{
//			text: "退料申请",
//			icon: 'add',
//			showMenuFn: function (row) {
//				if (row.ExaStatus == '完成' && row.isCanBack > 0) {
//					return true;
//				}
//				return false;
//			},
//			action: function (row, rows, grid) {
//				if (row) {
//					showModalWin("?model=produce_plan_picking&action=toAddBack&id=" + row.id, '1');
//				}
//			}
//		}, 
		{
			name: 'aduit',
			text: '审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_picking&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_picking&action=toViewTab&id=" + get[p.keyField], '1');
				}
			}
		},
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