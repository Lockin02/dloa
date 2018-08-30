var show_page = function(page) {
	$("#requireinGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireinGrid").yxsubgrid({
		model : 'asset_require_requirein',
		title : '物料转资产申请',
		isAddAction : false,
		isToolBar : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			"requireId" : $("#requireId").val()
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'billNo',
			display : '单据编号',
			width : 120,
			sortable : true,
			width : 150
		}, {
			display : '需求id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '需求编号',
			width : 120,
			sortable : true,
			width : 150
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
			name : 'outStockStatus',
			display : '出库状态',
			sortable : true,
			process : function(v) {
				if(v == 'WCK')
					return "未出库";
				if(v == 'BFCK')
					return "部分出库";
				if(v == 'YCK')
					return "已出库";
			},
			width : 80
		}, {
			name : 'receiveStatus',
			display : '验收状态',
			sortable : true,
			process : function(v) {
				if(v == '0')
					return "未验收";
				if(v == '1')
					return "部分验收";
				if(v == '2')
					return "已验收";
			},
			width : 80
		}, {
			name : 'status',
			display : '单据状态',
			sortable : true,
			width : 80
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 250
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_require_requireinitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'name',
				display : '设备名称',
				width : 120
			}, {
				name : 'description',
				display : '设备描述',
				width : 120
			}, {
				name : 'productName',
				display : '物料名称',
				width : 120
			}, {
				name : 'productCode',
				display : '物料编号',
				width : 120
			}, {
				name : 'productPrice',
				display : '物料金额',
				width : 80,
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				name : 'brand',
				display : '物料品牌',
				width : 80
			}, {
				name : 'spec',
				display : '规格型号',
				width : 80
			}, {
				name : 'number',
				display : '数量',
				width : 60
			}, {
				name : 'executedNum',
				display : '已出库数量',
				width : 60
			}, {
				name : 'receiveNum',
				display : '已验收数量',
				width : 60
			}, {
				name : 'cardNum',
				display : '生成卡片数量',
				width : 80
			}]
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toView&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '待提交' || row.status == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toEdit&id="
							+ row.id 
							+ "&skey=" + row['skey_']);
				}
			}
		}, {
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '待提交' || row.status == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_require_requirein&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							} else {
								alert("删除失败! ");
							}
						}
					});
				}
			}
		}, {
			text : '填写验收单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.receiveStatus == "2" || row.outStockStatus == "WCK" || row.status == "已完成") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_purchase_receive_receive&action=toRequireinAdd"
							+ "&requireinCode="
							+ row.billNo
							+ "&requireinId="
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		comboEx : [{
			text : '出库状态',
			key : 'outStockStatusArr',
			data : [{
				text : '未出库',
				value : 'WCK'
			}, {
				text : '部分出库',
				value : 'BFCK'
			}, {
				text : '已出库',
				value : 'YCK'
			}, {
				text : '部分出库,已出库',
				value : 'BFCK,YCK'
			}]
		},{
			text : '验收状态',
			key : 'receiveStatusArr',
			data : [{
				text : '未验收',
				value : '0'
			}, {
				text : '部分验收',
				value : '1'
			}, {
				text : '已验收',
				value : '2'
			}, {
				text : '未验收,部分验收',
				value : '0,1'
			}]
		},{
			text : '单据状态',
			key : 'status',
			data : [{
				text : '待提交',
				value : '待提交'
			}, {
				text : '待确认',
				value : '待确认'
			}, {
				text : '已确认',
				value : '已确认'
			}, {
				text : '部分完成',
				value : '部分完成'
			}, {
				text : '已完成',
				value : '已完成'
			}, {
				text : '打回',
				value : '打回'
			}]
		}],
		searchitems : [{
			display : "单据编号",
			name : 'billNo'
		},{
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