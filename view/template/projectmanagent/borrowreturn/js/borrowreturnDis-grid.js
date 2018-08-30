var show_page = function(page) {
	$("#returnDisGrid").yxsubgrid("reload");
};
$(function() {
	$("#returnDisGrid").yxsubgrid({
		model : 'projectmanagent_borrowreturn_borrowreturnDis',
		title : '待调拨归还确认单',
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
			name : 'state',
			display : '提示灯',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_yellow.png" title="待归还"/>';
					case '1' : return '<img src="images/icon/cicle_blue.png" title="部分归还"/>';
					case '2' : return '<img src="images/icon/cicle_green.png" title="已归还"/>';
				}
			},
			width : 50
		}, {
			name : 'borrowreturnId',
			display : '归还单id',
			sortable : true,
			hide : true
		}, {
			name : 'borrowreturnCode',
			display : '归还单编号',
			sortable : true,
			width : 130
		}, {
			name : 'borrowId',
			display : '借用单ID',
			sortable : true,
			hide : true
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
			name : 'borrowreturnMan',
			display : '申请人',
			sortable : true,
			width : 60
		}, {
			name : 'customerName',
			display : '客户名称',
			width : 100,
			sortable : true
		}, {
			name : 'chargerName',
			display : '设备负责人',
			sortable : true,
			width : 60
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
			width : 60
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 140
		}, {
			name : 'Code',
			display : '处理单编号',
			sortable : true,
			width : 130
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
			text : '归还物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state != '2') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid,g) {
				if (row) {
					var idArr = g.getSubSelectRowCheckIds(rows);
					showModalWin("?model=stock_allocation_allocation&action=toPushReturn&relDocId="
							+ row.id +"&equIdArr="+idArr
							+ "&relDocType=DBDYDLXGH");
				}
			}
		}],
		comboEx : [{
			text : '归还状态',
			key : 'states',
			value : '0,1,3,4',
			data : [{
				text : '未归还',
				value : '0,1,3,4'
			}, {
				text : '已归还',
				value : '2'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '归还单编号',
			name : 'borrowreturnCode'
		}, {
			display : '借用单编号',
			name : 'borrowCode'
		}, {
			display : '处理单编号',
			name : 'Code'
		}, {
			display : '申请人姓名',
			name : 'borrowreturnMan'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '序列号',
			name : 'serialName'
		}]
	});
});