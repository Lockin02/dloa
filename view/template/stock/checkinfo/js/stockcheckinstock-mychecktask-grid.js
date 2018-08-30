var show_page = function(page) {
	$(".mychecktaskGrid").yxgrid("reload");
};
$(function() {
	$(".mychecktaskGrid").yxgrid({
		model : 'stock_checkinfo_stockcheckinstock',
		action : 'mytaskPJ',
		title : '我的盘点审批任务',
		isToolBar : false,
		showcheckbos : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'stockId',
			display : '仓库id',
			sortable : true,
			hide : true
		}, {
			name : 'stockName',
			display : '仓库名称',
			sortable : true
		}, {
			name : 'stockCode',
			display : '仓库编号',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'checkType',
			display : '盘点类型',
			sortable : true,
			datacode : 'PDLX'
		}, {
			name : 'dealUserId',
			display : '经办人id',
			sortable : true,
			hide : true
		}, {
			name : 'dealUserName',
			display : '经办人',
			sortable : true
		}, {
			name : 'auditUserName',
			display : '审核人',
			sortable : true
		}, {
			name : 'auditUserId',
			display : '审核人id',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			width : '150',
			sortable : true
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}, {
			display : 'taskId',
			name : 'taskId',
			sortable : true,
			hide : true
		}, {
			display : 'spid',
			name : 'spid',
			sortable : true,
			hide : true
		}],
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isDelAction : false,
		toAddConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 400
		},
		toEditConfig : {
			action : 'toEdit',

			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 400

		},
		toViewConfig : {
			action : 'toRead',

			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 400

		},
		// 扩展右键菜单
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=toRead&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 700 + "&width=" + 900);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'audit',
			text : '审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					location = "controller/stock/checkinfo/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_stock_check_instock";
			}
		}],
		// 扩展工具栏按钮
		buttonsEx : [{
			name : 'audit',
			text : '审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "controller/stock/checkinfo/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_stock_check_instock";
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=toRead&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 700 + "&width=" + 900);
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});
});