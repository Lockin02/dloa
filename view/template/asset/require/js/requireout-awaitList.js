var show_page = function(page) {
	$("#requireoutGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireoutGrid").yxsubgrid({
		model : 'asset_require_requireout',
		title : '资产入库',
		isToolBar : false,
		showcheckbox : false,
		param : {
			 'ExaStatus' : '完成'
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '申请编号',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requireout&action=toView&id=" + row.id
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'applyName',
			display : '申请人',
			sortable : true,
			width : 80
		}, {
			name : 'applyDeptName',
			display : '申请部门',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 70
		}, {
			name : 'inStockStatus',
			display : '入库状态',
			sortable : true,
			process : function(v) {
				if(v == 'WRK')
					return "未入库";
				if(v == 'BFRK')
					return "部分入库";
				if(v == 'YRK')
					return "已入库";
			},
			width : 80
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 80
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_require_requireoutitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'assetName',
				display : '资产名称',
				width : 150
			}, {
				name : 'assetCode',
				display : '资产编号',
				width : 150
			}, {
				display : '资产残值',
				name : 'salvage',
				tclass : 'txt',
				process : function(v) {
					return moneyFormat2(v);
				},
				width : 120
			}, {
				name : 'productName',
				display : '物料名称',
				width : 150
			}, {
				name : 'productCode',
				display : '物料编号',
				width : 150
			}, {
				name : 'number',
				display : '数量',
				width : 60
			}, {
				name : 'executedNum',
				display : '已入库数量',
				width : 60
			}]
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					if (row) {
						showThickboxWin("?model=asset_require_requireout&action=toView&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
					}
				}
			}
		}, {
			text : '确认物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.inStockStatus == 'YRK') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requireout&action=toConfirm&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : "入库",
			icon : 'business',
			showMenuFn : function(row) {
				if (row.inStockStatus == 'WRK' || row.inStockStatus == 'BFRK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toAddBlueByAsset&id="
						+ row.id
						+ "&skey="
						+ row['skey_'])
			}
		}],
		comboEx : [{
			text : '入库状态',
			key : 'inStockStatusArr',
			value : 'WRK,BFRK',
			data : [{
				text : '未入库',
				value : 'WRK'
			}, {
				text : '部分入库',
				value : 'BFRK'
			}, {
				text : '已入库',
				value : 'YRK'
			}, {
				text : '未入库，部分入库',
				value : 'WRK,BFRK'
			}]
		}],
		searchitems : [{
			display : "需求编号",
			name : 'requireCode'
		}, {
			display : "申请人",
			name : 'applyName'
		}, {
			display : "申请部门",
			name : 'applyDeptName'
		}]
	});
});