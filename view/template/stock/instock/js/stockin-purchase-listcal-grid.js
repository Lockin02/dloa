var show_page = function(page) {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#purhasestorageGrid").yxsubgrid({
		model : 'stock_instock_stockin',
//		action : 'action',
		title : "暂估入库",
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			'docType' : $('#docType').val(),
			'docStatus' : 'YSH',
			'catchStatusNo' : 'CGFPZT-YGJ',
			'thisYear' : $("#thisYear").val(),
			'thisMonth' : $("#thisMonth").val(),
			'noPrice' : 1
		},
//		isShowNum : false,
//		usepager : false, // 是否分页

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
					sortable : true,
					width : 80
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
					datacode : 'RKDYDLX',
					width : 80,
					hide : true
				}, {
					name : 'relDocCode',
					display : '源单编号',
					sortable : true,
					hide : true
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
					sortable : true,
					hide : true
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
					display : '收料仓库',
					sortable : true,
					width : 80
				}, {
					name : 'supplierId',
					display : '供应商id',
					sortable : true,
					hide : true
				}, {
					name : 'supplierName',
					display : '供应商名称',
					sortable : true,
					width : 150
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
					display : '采购员',
					sortable : true
				}, {
					name : 'docStatus',
					display : '单据状态',
					sortable : true,
					width : 80,
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
					datacode : 'CGFPZT',
					width : 80
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
					sortable : true,
					hide : true
				}, {
					name : 'auditerName',
					display : '审核人',
					sortable : true
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_instock_stockinitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称'
					}, {
						name : 'productModel',
						width : 200,
						display : '规格型号'
					}, {
						name : 'actNum',
						display : "实收数量"
					}, {
						name : 'price',
						display : "单价",
						process : function(v){
							return moneyFormat2(v);
						}
					}, {
						name : 'subPrice',
						display : "金额",
						process : function(v){
							return moneyFormat2(v);
						}
					}, {
						name : 'unHookNumber',
						display : '未勾稽数量',
						process : function(v){
							return moneyFormat2(v);
						}
					}, {
						name : 'unHookAmount',
						display : '未勾稽金额',
						process : function(v){
							return moneyFormat2(v);
						}
					}]
		},
		menusEx : [
			{
				name : 'edit',
				text : "修改",
				icon : 'edit',
				action : function(row, rows, grid) {
					showOpenWin("?model=stock_instock_stockin&action=toEditPrice&id="
							+ row.id
							+ "&docType="
							+ row.docType
							+ "&skey="
							+ row.skey_
							);
				}
			},
			{
				name : 'view',
				text : "查看",
				icon : 'view',
				action : function(row, rows, grid) {
					showThickboxWin("?model=stock_instock_stockin&action=toView&id="
							+ row.id
							+ "&docType="
							+ row.docType
							+ "&skey="
							+ row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		],
		searchitems : [ {
			display : '单据编号',
			name : 'docCode'
		}, {
			display : '收料仓库名称',
			name : 'inStockName'
		}],
		sortorder : "ASC"

	});
});