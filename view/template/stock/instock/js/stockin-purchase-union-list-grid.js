var show_page = function(page) {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#purhasestorageGrid").yxsubgrid({
		model : 'stock_instock_stockin',
		action : 'pageListGridJson',
		title : "外购入库单关联源单处理",
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		menusEx : [{
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_instock_stockin&action=toView&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			name : 'business',
			text : "关联订单",
			icon : 'business',
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toUnionOrderForm&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']);
			}
		}, {
			name : 'business',
			text : "关联收料单",
			icon : 'business',
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toUnionArrivalForm&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']);
			}
		}],
		param : {
			'docType' : 'RKPURCHASE',
			'relDocId' : '0'
		},

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'isRed',
					display : '红蓝色',
					sortable : true,
					align : 'center',
					width : '35',
					process : function(v, row) {
						if (row.isRed == '0') {
							return "<img src='images/icon/hblue.gif' />";
						} else {
							return "<img src='images/icon/hred.gif' />";
						}
					}
				}, {
					name : 'docCode',
					display : '单据编号',
					sortable : true
				}, {
					name : 'auditDate',
					display : '单据日期',
					sortable : true
				}, {
					name : 'docType',
					display : '入库类型',
					sortable : true,
					hide : true
				}, {
					name : 'relDocId',
					display : '源单id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : '源单类型',
					sortable : true,
					datacode : 'RKDYDLX'
				}, {
					name : 'relDocCode',
					display : '源单编号',
					sortable : true
				}, {
					name : 'relDocName',
					display : '源单名称',
					sortable : true,
					hide : true

				}, {
					name : 'contractId',
					display : '合同id',
					sortable : true,
					hide : true
				}, {
					name : 'contractCode',
					display : '合同编号',
					sortable : true,
					hide : true
				}, {
					name : 'contractName',
					display : '合同名称',
					sortable : true,
					hide : true
				}, {
					name : 'purOrderCode',
					display : '采购订单编号',
					sortable : true
				}, {
					name : 'inStockId',
					display : '收料仓库id',
					sortable : true,
					hide : true
				}, {
					name : 'inStockCode',
					display : '收料仓库代码',
					sortable : true,
					hide : true
				}, {
					name : 'inStockName',
					display : '收料仓库名称',
					sortable : true
				}, {
					name : 'supplierId',
					display : '供应商id',
					sortable : true,
					hide : true
				}, {
					name : 'supplierName',
					display : '供应商名称',
					sortable : true
				}, {
					name : 'clientName',
					display : '客户名称',
					sortable : true,
					hide : true
				}, {
					name : 'purchMethod',
					display : '采购方式',
					sortable : true,
					datacode : 'cgfs',
					hide : true
				}, {
					name : 'payDate',
					display : '付款日期',
					sortable : true,
					hide : true
				}, {
					name : 'accountingCode',
					display : '往来科目',
					sortable : true,
					datacode : 'KJKM',
					hide : true
				}, {
					name : 'purchaserName',
					display : '采购员名称',
					sortable : true
				}, {
					name : 'docStatus',
					display : '单据状态',
					sortable : true,
					width : 50,
					process : function(v, row) {
						if (row.docStatus == 'WSH') {
							return "未审核";
						} else {
							return "已审核";
						}
					}

				}, {
					name : 'catchStatus',
					display : '钩稽状态',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true
				}, {
					name : 'purchaserCode',
					display : '采购员编号',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorName',
					display : '验收人名称',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorCode',
					display : '验收人编号',
					sortable : true,
					hide : true
				}, {
					name : 'chargeName',
					display : '负责人名称',
					sortable : true,
					hide : true
				}, {
					name : 'chargeCode',
					display : '负责人编号',
					sortable : true,
					hide : true
				}, {
					name : 'custosName',
					display : '保管人名称',
					sortable : true,
					hide : true
				}, {
					name : 'custosCode',
					display : '保管人编号',
					sortable : true,
					hide : true
				}, {
					name : 'auditerName',
					display : '审核人名称',
					sortable : true,
					hide : true
				}, {
					name : 'auditerCode',
					display : '审核人编号',
					sortable : true,
					hide : true
				}, {
					name : 'accounterName',
					display : '记账人',
					sortable : true,
					hide : true
				}, {
					name : 'accounterCode',
					display : '记账人编号',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '制单',
					sortable : true
				}, {
					name : 'auditerName',
					display : '审核人',
					sortable : true
				}],
		// 主从表格设置
				//主从表中加了个字段   规格型号   2013.7.5
		subGridOptions : {
			url : '?model=stock_instock_stockinitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号',
						width : '80'
					}, {
						name : 'productName',
						width : 150,
						display : '物料名称'
					},{
						name : 'pattern',
						width : 200,
						display : '规格型号'
					}, {
						name : 'actNum',
						display : "实收数量",
						width : '60'
					}, {
						name : 'serialnoName',
						display : "序列号",
						width : '450'
					}, {
						name : 'unHookNumber',
						display : '未勾稽数量',
						width : 80
						
					}, {
						name : 'unHookAmount',
						display : '未勾稽金额'
					}]
		},
		comboEx : [{
					text : '单据状态',
					key : 'docStatus',
					data : [{
								text : '未审核',
								value : 'WSH'
							}, {
								text : '已审核',
								value : 'YSH'
							}]
				}],
		searchitems : [{
					display : "序列号",
					name : 'serialnoName'
				}, {
					display : '批次号',
					name : 'batchNum'
				}, {
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '收料仓库名称',
					name : 'inStockName'
				}, {
					display : '物料代码',
					name : 'productCode'
				}, {
					display : '物料名称',
					name : 'productName'
				}, {
					display : '物料规格型号',
					name : 'pattern'
				}],
		sortorder : "DESC",
		buttonsEx : [{
			name : 'Add',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=stock_instock_stockin&action=toAdvancedSearch&docType=RKPURCHASE"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=stock_instock_stockin&action=toAdd&docType=RKPURCHASE")
			},
			formWidth : 880,
			formHeight : 600
		}

	});
});
