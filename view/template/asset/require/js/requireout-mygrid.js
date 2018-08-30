var show_page = function(page) {
	$("#requireoutGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireoutGrid").yxsubgrid({
		model : 'asset_require_requireout',
		action : 'myPageJson',
		title : '我的资产转物料申请',
		isToolBar : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '申请编号',
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
			},{
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
				width : 80
			}, {
				name : 'executedNum',
				display : '已入库数量',
				width : 80
			}]
		},
		buttonsEx : [{
			name : 'add',
			text : "新增",
			icon : 'add',
			action : function(row) {
				showOpenWin("?model=asset_require_requireout&action=toadd")
			}
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requireout&action=toView&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requireout&action=toEdit&id="
							+ row.id 
							+ "&skey=" + row['skey_']);
				}
			}
		}, {
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_require_requireout&action=ajaxdeletes",
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
		}],
		comboEx : [{
			text : '入库状态',
			key : 'inStockStatus',
			data : [{
				text : '未入库',
				value : 'WRK'
			}, {
				text : '部分入库',
				value : 'BFRK'
			}, {
				text : '已入库',
				value : 'YRK'
			}]
		}],
		searchitems : [{
			display : "申请编号",
			name : 'requireCode'
		}]
	});
});