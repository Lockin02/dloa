var show_page = function(page) {
	$("#returnDisGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnDisGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturnDis',
		title : '待出库归还确认单',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'borrowId',
			display : '借用单ID',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '处理单编号',
			sortable : true,
			width : 130
		}, {
			name : 'borrowCode',
			display : '借用单编号',
			sortable : true
		}, {
			name : 'borrowLimit',
			display : '借用类型',
			sortable : true,
			width : 60
		}, {
			name : 'applyTypeName',
			display : '申请类型',
			sortable : true,
			width : 80
		}, {
			name : 'chargerName',
			display : '设备负责人',
			sortable : true,
			width : 90
		}, {
			name : 'deptName',
			display : '负责人部门',
			sortable : true,
			width : 80
		}, {
			name : 'state',
			display : '归还状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "待归还";
				} else if (v == '1') {
					return "部分归还";
				} else if (v == '2') {
					return "已归还";
				} else if (v == '3') {
					return "赔偿单确认";
				} else if (v == '4') {
					return "确认完成";
				} else{
				    return "--";
				}
			},
			width : 70
		}, {
			name : 'disposeState',
			display : '出库状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "无需出库";
				} else if (v == '1') {
					return "待出库";
				} else if (v == '2') {
					return "已出库";
				} else{
				    return "--";
				}
			},
			width : 70
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			width : 90
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 140
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_borrowreturnDisequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'disposeId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				display : '物料编号',
				name : 'productNo'
			},{
				display : '物料名称',
				name : 'productName',
				width : 160
			}, {
				display : '待归还数量',
				name : 'disposeNum',
				width : 80
			}, {
				display : '已归还数量',
				name : 'backNum',
				width : 80
			}, {
				display : '待出库数量',
				name : 'outNum',
				width : 80
			}, {
				display : '已出库数量',
				name : 'executedNum',
				width : 80
			}, {
				name : 'serialName',
				display : '序列号',
				width : 140
			}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		// 扩展右键菜单
		menusEx : [{
			text : '出库',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.disposeState == '1') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid,g) {
				if (row) {
					showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
							+ row.id + "&docType=CKOTHERGH"
							+ "&relDocType=DBDYDLXGH");
				}
			}
		}],
		comboEx : [{
			text : '出库状态',
			key : 'disposeState',
			value : '1',
			data : [{
				text : '无需出库',
				value : '0'
			}, {
				text : '待出库',
				value : '1'
			}, {
				text : '已出库',
				value : '2'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '处理单编号',
			name : 'Code'
		}, {
			display : '借用单编号',
			name : 'borrowCode'
		}]
	});
});