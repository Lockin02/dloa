var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		action : 'pageJson',
		title : '采购申请单',
		showcheckbox : false,
		isDelAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true
		}, {
			name : 'applyTime',
			display : '申请日期',
			sortable : true
		}, {
			name : 'applicantName',
			display : '申请人名称',
			sortable : true
		}, {
			name : 'applyDetName',
			display : '申请部门',
			sortable : true
		}, {
			name : 'userName',
			display : '使用人名称',
			sortable : true
		}, {
			name : 'useDetName',
			display : '使用部门',
			sortable : true
		}, {
//			name : 'purchCategory',
//			display : '采购种类',
//			sortable : true,
//			datacode : 'CGZL'
//		}, {
			name : 'assetUse',
			display : '资产用途',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=pageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				name : 'inputProductName',
				width : 200,
				display : '物料名称'
			}, {
				name : 'pattem',
				display : "规格"
			}, {
				name : 'unitName',
				display : "单位",
				width : 50
			}, {
				name : 'applyAmount',
				display : "申请数量",
				width : 70
			}, {
				name : 'dateHope',
				display : "希望交货日期"
			}, {
				name : 'remark',
				display : "备注"
			}]
		},
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '待提交',
				value : '待提交'
			}, {
				text : '完成',
				value : '完成'
			}, {
				text : '打回',
				value : '打回'
			}]
		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 600
		},
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 600
		},
		toEditConfig : {
			/**
			 * 编辑表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 编辑表单默认高度
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
		// 扩展右键菜单
		menusEx : [{
//			text : '提交审批',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId='
//							+ row.id
//							+ '&flowDept='
//							+ row.useDetId
//							+ '&billDept='
//							+ row.useDetId
//							+ '&flowMoney='
//							+ row.amounts
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				} else {
//					alert("请选中一条数据");
//				}
//			}
//
//		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_purchase_apply&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_apply_apply&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#applyGrid").yxsubgrid("reload");
						}
					});
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		}, {
			display : '申请部门',
			name : 'applyDetName'
		}, {
			display : '申请人',
			name : 'applicantName'
		}, {
			display : '使用部门',
			name : 'useDetName'
		}, {
			display : '物料名称',
			name : 'productName'
		}]
	});
});