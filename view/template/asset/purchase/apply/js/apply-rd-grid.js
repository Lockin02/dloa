var show_page = function(page) {
	$("#rdGrid").yxsubgrid("reload");
};
$(function() {
	$("#rdGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		param : {
			"ExaStatus" : '完成',
			"purchType" : 'rdproject'
		},
		title : '研发设备采购申请',
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
			sortable : true
		}, {
			name : 'applyTime',
			display : '申请日期',
			sortable : true,
			width : 120
		}, {
			name : 'applicantName',
			display : '申请人名称',
			sortable : true,
			width : 120
		}, {
			name : 'rdProjectCode',
			display : '研发专项编号',
			sortable : true,
			width : 120
		}, {
			name : 'rdProject',
			display : '研发专项项目',
			sortable : true,
			width : 120
		}, {
			name : 'assetUse',
			display : '资产用途',
			sortable : true,
			width : 120
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=IsDelPageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				display : '设备编码',
				name : 'productCode'
			}, {
				display : '设备名称',
				name : 'productName'
			}, {
				display : '规格型号',
				name : 'pattem'
			}, {
				display : '供应商',
				name : 'supplierName'
			}, {
				display : '单位',
				name : 'unitName'
			}, {
				display : '数量',
				name : 'applyAmount'
			}, {
				display : '希望交货日期',
				name : 'dateHope'
			}, {
				display : '设备使用年限',
				name : 'life'
			}, {
				display : '预计购入单价',
				name : 'exPrice',
				tclass : 'txtshort',
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '是否归属固定资产',
				name : 'isAsset'
			}, {
				display : '备注',
				name : 'remark',
				tclass : 'txt'
			}]
		},
//			buttonsEx : [{
//                name : 'Add',
//                text : "添加",
//                icon : 'add',
//                action : function() {
//					showThickboxWin('?model=asset_purchase_apply_apply&action=toRDAdd&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//                }
//
//			}],
			menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=RDinit&id='
						+ row.id
						+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}
//		,{
//			text : '编辑',
//			icon : 'edit',
//			action : function(row) {
//				showThickboxWin('?model=asset_purchase_apply_apply&action=RDinit&id='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//			}
//		}
		],
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
			display : '设备名称',
			name : 'productName'
		}]
	});
});