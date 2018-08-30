var show_page = function(page) {
	$("#accessorderGrid").yxsubgrid("reload");
};
$(function() {
	$("#accessorderGrid").yxsubgrid({
		model : 'service_accessorder_accessorder',
		title : '零配件订单',
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '单据编号',
					sortable : true
				}, {
					name : 'docDate',
					display : '签订日期',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 200
				}, {
					name : 'contactUserName',
					display : '客户联系人',
					sortable : true
				}, {
					name : 'telephone',
					display : '联系电话',
					sortable : true
				}, {
					name : 'adress',
					display : '客户地址',
					sortable : true,
					hide : true
				}, {
					name : 'chargeUserName',
					display : '负责人名称',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true,
					hide : true
				}, {
					name : 'auditerUserName',
					display : '审批人名称',
					sortable : true,
					hide : true
				}, {
					name : 'docStatus',
					display : '单据状态',
					sortable : true,
					process : function(v, row) {
						if (row.docStatus == 'WZX') {
							return "未执行";
						} else if (row.docStatus == 'ZXZ') {
							return "执行中";
						} else if (row.docStatus == 'YWC') {
							return "已完成";
						}
					}
				}, {
					name : 'saleAmount',
					display : '订单金额',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'areaLeaderName',
					display : '区域负责人名称',
					sortable : true,
					hide : true
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '创建人',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '创建日期',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改日期',
					sortable : true,
					hide : true
				}],

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "完成" || row.ExaStatus == "打回") {
					if (row) {
						showModalWin("?model=service_accessorder_accessorder&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showThickboxWin("?model=service_accessorder_accessorder&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		},{
			name : 'business',
			text : "下推出库单",
			icon : 'business',
			showMenuFn : function(row) {
				// if (row.docStatus == "YSH" && row.isRed == "0") {
				return true;
				// }
				// return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
						+ row.id + "&docType=CKSALES&relDocType=XSCKFHJH")
			}
		}],

		// 主从表格设置
		subGridOptions : {
			url : '?model=service_accessorder_accessorderitem&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号'
					}, {
						name : 'productName',
						display : '物料名称',
						width : 250
					}, {
						name : 'pattern',
						display : '规格型号'
					}, {
						name : 'price',
						display : '单价',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}, {
						name : 'proNum',
						display : '订单数量',
						width : '80'
					}, {
						name : 'actOutNum',
						display : '已出库数量',
						width : '80'
					}, {
						name : 'subCost',
						display : '金额',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}]
		},
		toAddConfig : {
			formWidth : '1100px',
			formHeight : 600
		},
		toEditConfig : {
			action : 'toEdit',
			formWidth : '1100px',
			formHeight : 600
		},
		searchitems : [{
					display : '物料编号',
					name : 'productCode'
				}, {
					name : 'productName',
					display : '物料名称'
				}, {
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '客户联系人',
					name : 'contactUserName'
				}],
		comboEx : [{
					text : '单据状态',
					key : 'docStatus',
					data : [{
								text : '未执行',
								value : 'WZX'
							}, {
								text : '执行中',
								value : 'ZXZ'
							}, {
								text : '已完成',
								value : 'YWC'
							}]
				}, {
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
								text : '待提交',
								value : '待提交'
							}, {
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '完成',
								value : '完成'
							}, {
								text : '打回',
								value : '打回'
							}]
				}]
	});
});