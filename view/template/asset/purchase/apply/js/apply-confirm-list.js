// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#confirmGrid").yxsubgrid("reload");
};
$(function() {
	$("#confirmGrid").yxsubgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'asset_purchase_apply_apply',
		action : 'myConfirmListPageJson',
		title : '待物料确认的采购申请列表',
		isToolBar : false,
		showcheckbox : false,
		//		param : {
		//			'productSureStatus' : '0'
		//		},

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width : 120
		}, {
			name : 'applyTime',
			display : '申请日期',
			sortable : true
		}, {
			name : 'applicantName',
			display : '申请人名称',
			sortable : true,
			width : 120
		}, {
			display : '确认状态',
			name : 'productSureStatus',
			process : function(v, data) {
				if (v == 0) {
					return "未确认";
				} else if (v == 1) {
					return "已确认";
				} else {
					return "部分确认";
				}
			}
		}, {
			name : 'userName',
			display : '使用人名称',
			sortable : true,
			width : 120
		}, {
			name : 'useDetName',
			display : '使用部门',
			sortable : true
		}, {
			name : 'purchCategory',
			display : '采购种类',
			sortable : true,
			datacode : 'CGZL',
			width : 120
		}, {
			name : 'assetUse',
			display : '资产用途',
			sortable : true,
			width : 120
		}],
		comboEx : [{
			text : '确认状态',
			key : 'productSureStatusArr',
			data : [{
				text : '未确认',
				value : '0,2'
			}, {
//				text : '部分确认',
//				value : 2
//			}, {
				text : '已确认',
				value : '1'
			}],
			value : '0,2'
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=pageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCategoryName',
				display : '物料类别',
				width : 50
			}, {
				name : 'productCode',
				display : '物料编号'
			}, {
				name : 'productName',
				width : 200,
				display : '物料名称',
				process : function(v, data) {
					if (v == "") {
						return data.inputProductName;
					} else {
						return v;
					}
				}
			}, {
				display : '规格',
				name : 'pattem',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '单位',
				name : 'unitName',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '申请数量',
				name : 'purchAmount',
				tclass : 'txtshort'
			}, {
				display : '下达任务数量',
				name : 'issuedAmount',
				tclass : 'txtshort'
			}, {
				display : '希望交货日期',
				name : 'dateHope',
				type : 'date'
			}, {
				display : '备注',
				name : 'remark',
				tclass : 'txt'
			}, {
				display : '采购部门',
				name : 'purchDept',
				tclass : 'txt',
				process : function($input, rowData) {
					if (rowData.purchDept == '0') {
						return '行政部';
					} else if (rowData.purchDept == '1') {
						return '交付部';
					}
				}
			}]
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=asset_purchase_apply_apply&action=toRead&id="
							+ row.id;
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '物料确认',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus == 1) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=asset_purchase_apply_apply&action=toConfirmProduct&id="
							+ row.id
							+ "&purchType="
							+ row.purchType
							+ "&skey="
							+ row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		}],
		// 快速搜索
		searchitems : [{
			display : '采购申请编号',
			name : 'seachPlanNumb'
		}, {
			display : '物料编号',
			name : 'productNumb'
		}, {
			display : '物料名称',
			name : 'productName'
		}, {
			display : '申请源单据号',
			name : 'sourceNumb'
		}, {
			display : '批次号',
			name : 'batchNumb'
		}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "updateTime",
		// 默认搜索顺序
		sortorder : "DESC",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});