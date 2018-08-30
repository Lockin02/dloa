var show_page = function(page) {
	$(".stockcheckinstockGrid").yxgrid("reload");
};
$(function() {
	$(".stockcheckinstockGrid").yxgrid({
		model : 'stock_checkinfo_stockcheckinstock',
		title : '盘点入库',
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
			name : 'auditUserName',
			display : '审核人',
			sortable : true
		}, {
			name : 'auditUserId',
			display : '审核人id',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}],
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
		toAddConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 1000,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
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
			name : 'edit',
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待审核' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=toEdit&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
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
		}, {
			name : 'in',
			text : '盘点入库',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=intostock&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'sumbit',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待审核' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = 'controller/stock/checkinfo/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_stock_check_instock&formName=盘点审核';
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_stock_check_instock&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}]
	});
});