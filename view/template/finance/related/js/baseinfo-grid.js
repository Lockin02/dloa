var show_page = function(page) {
	$("#baseinfoGrid").yxgrid("reload");
};
$(function() {
	$("#baseinfoGrid").yxgrid({
		model : 'finance_related_baseinfo',
		action : 'pageJsonRelated',
		title : '钩稽日志',
		showcheckbox : false,
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
					display : '钩稽序号',
					name : 'relatedId',
					sortable : true,
					width : 60
				}, {
					display : '条目id',
					name : 'id',
					hide : true
				}, {
					display : '表单id',
					name : 'hookMainId',
					hide : true
				}, {
					name : 'years',
					display : '钩稽年度',
					sortable : true,
					width : 60
				}, {
					name : 'hookDate',
					display : '钩稽时间',
					sortable : true,
					width : 80
				}, {
					name : 'createName',
					display : '钩稽人',
					sortable : true
				}, {
					name : 'supplierName',
					display : '供应商',
					sortable : true,
					width : 150
				}, {
					name : 'hookObj',
					display : ' 钩稽对象',
					sortable : true,
					process : function(v) {
						if (v == 'invpurchase') {
							return '采购发票';
						} else if (v == 'invcost') {
							return '费用发票';
						} else {
							return '外购入库单';
						}
					},
					width : 80
				}, {
					name : 'hookObjCode',
					display : '表单编号',
					sortable : true
				}, {
					name : 'productNo',
					display : '物料编号',
					sortable : true
				}, {
					name : 'productName',
					display : '物品名称',
					sortable : true,
					width : 120
				}, {
					name : 'number',
					display : '钩稽数量',
					sortable : true,
					width : 60
				}, {
					name : 'amount',
					display : '钩稽金额',
					sortable : true,
					process : function(v) {
						return moneyFormat2(v);
					}
				}],
		menusEx : [{
			text : '查看单据',
			icon : 'view',
			action : function(row) {
				if (row.hookObj == 'invpurchase') {
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id='
							+ row.hookMainId + "&skey=" + row.skey_);
				} else if (row.hookObj == 'invcost') {
					showOpenWin('?model=finance_invcost_invcost&action=init&perm=view&id='
							+ row.hookMainId + "&skey=" + row.skey_);
				} else {
					showOpenWin('?model=stock_instock_stockin&action=toView&docType=RKPURCHASE&id='
							+ row.hookMainId + "&skey=" + row.skey_);
				}
			}
		}, {
			text : '查看钩稽结果',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=finance_related_baseinfo&action=init&perm=view&id='
						+ row.relatedId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 550 + "&width=" + 900);
			}
		}],
		searchitems : [{
					display : '表单编号',
					name : 'hookObjCode'
				}, {
					display : '钩稽序号',
					name : 'id'
				}, {
					display : '供应商名称',
					name : 'supplierName'
				}, {
					display : '钩稽人',
					name : 'createName'
				}],
		// 高级搜索
		advSearchOptions : {
			modelName : 'baseinfo',
			// 选择字段后进行重置值操作
			selectFn : function($valInput) {
				$valInput.yxselect_user("remove");
				$valInput.yxcombogrid_supplier("remove");
				$valInput.yxcombogrid_product("remove");
			},
			searchConfig : [{
						name : '创建日期',
						value : 'c.createTime',
						changeFn : function($t, $valInput) {
							$valInput.click(function() {
										WdatePicker({
													dateFmt : 'yyyy-MM-dd'
												});
									});
						}
					}, {
						name : '勾稽人',
						value : 'c.createName',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#createId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='createId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxselect_user({
										hiddenId : 'createId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '供应商',
						value : 'c.supplierName',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#supplierId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='supplierId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxcombogrid_supplier({
										hiddenId : 'supplierId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '勾稽对象',
						value : 'd.hookObj',
						type : 'select',
						options : [{
									dataCode : 'invpurchase',
									dataName : '采购发票'
								}, {
									dataCode : 'invcost',
									dataName : '费用发票'
								}, {
									dataCode : 'storage',
									dataName : '外购入库单'
								}]
					}, {
						name : '物料编号',
						value : 'd.productNo',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#productId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='productId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxcombogrid_product({
										hiddenId : 'productId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '物料名称',
						value : 'd.productName',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#productId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='productId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxcombogrid_product({
										nameCol : 'productName',
										hiddenId : 'productId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '勾稽数量',
						value : 'd.number'
					}, {
						name : '勾稽金额',
						value : 'd.amount'
					}]
		}
	});
});