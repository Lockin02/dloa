var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	$("#requirementGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		// action : 'adminJson',
		param : {
			"ExaStatus" : '完成'
			,"state" : '已提交'
		},
		title : '资产采购需求',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
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
		}, {
			name : 'estimatPrice',
			display : '预估总价',
			sortable : true,
			width : 150,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'moneyAll',
			display : '总金额',
			sortable : true,
			width : 150,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=IsDelPageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				display : '物料名称',
				name : 'productName',
				tclass : 'readOnlyTxtItem',
				process : function(v,row) {
					if( v == '' ){
						return row.inputProductName;
					}
						return v;
				}
			}, {
				display : '规格',
				name : 'pattem',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '申请数量',
				name : 'applyAmount',
				tclass : 'txtshort'
			}, {
				display : '供应商',
				name : 'supplierName',
				tclass : 'txtmiddle'
			}, {
				display : '单位',
				name : 'unitName',
				tclass : 'readOnlyTxtItem'
			}, {
				display : '采购数量',
				name : 'purchAmount',
				tclass : 'txtshort'
			}, {
				display : '单价',
				name : 'price',
				tclass : 'txtshort',
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '金额',
				name : 'moneyAll',
				tclass : 'txtshort',
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
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
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=initAdminRequire&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000');
			}
		}, {
			text : '拆分采购',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=initAssign&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1100');
			}
		}, {
			text : '下达采购任务',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_task_task&action=toAdd&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=1100');
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		}, {
			display : '申请日期',
			name : 'applyTime'
		}, {
			display : '申请人',
			name : 'applicantName'
		}, {
			display : '使用人名称',
			name : 'userName'
		}, {
			display : '物料名称',
			name : 'productName'
		}]
	});
});