function reloadArrivalCombo() {// 收料通知单combogrid
	$("#relDocCode").yxcombogrid_arrival({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'state' : '0'
			},
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=purchase_arrival_arrival&action=getItemListJson",
									{
										arrivalId : data.id
									}, function(result) {
										if (result != "") {
											var itemscount = $('#itemscount')
													.val();
											for (var i = 0; i < itemscount; i++) {
												$("#stockName" + i)
														.yxcombogrid_stockinfo('remove');
											}
											$("#itembody").html("");
											$("#itembody").append(result);
											$("#itemscount")
													.val($("#itembody tr")
															.size());
											$("#supplierName")
													.val(data.supplierName);
											$("#supplierId")
													.val(data.supplierId);
											$("#purOrderCode")
													.val(data.purchaseCode);
											$("#purOrderId")
													.val(data.purchaseId);
											$("#inStockName")
													.val(data.stockName);
											$("#inStockId").val(data.stockId);
											// $("#inStockCode").val(data.stockCode);

											$("#itemscount")
													.val($("#itembody tr")
															.size());
											reloadItemStock();
											formateMoney();
										} else {
											alert("该收料单中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}

function reloadPurOrderCombo() {// 采购订单combogrid
	$("#relDocCode").yxcombogrid_purchcontract({
		hiddenId : 'relDocId',
		gridOptions : {
			param : {
				'state' : '7'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$
							.post(
									"?model=purchase_contract_purchasecontract&action=getItemList",
									{
										orderId : data.id
									}, function(result) {
										if (result != "") {
											var itemscount = $('#itemscount')
													.val();
											for (var i = 0; i < itemscount; i++) {
												$("#stockName" + i)
														.yxcombogrid_stockinfo('remove');
											}
											$("#itembody").html("");
											$("#itembody").append(result);
											$("#itemscount")
													.val($("#itembody tr")
															.size());
											$("#purchaserName")
													.val(data.sendName);
											$("#purchaserCode")
													.val(data.sendUserId);
											$("#supplierId").val(data.suppId);
											$("#supplierName")
													.val(data.suppName);
											$("#purOrderCode")
													.val(data.hwapplyNumb);
											$("#purOrderId").val(data.id);

											$("#itemscount")
													.val($("#itembody tr")
															.size());
											reloadItemStock();
											formateMoney();
										} else {
											alert("该订单单中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}

function reloadDeliveredCombo() {// 采购退料通知单combogrid
	$("#relDocCode").yxcombogrid_purchdelivered({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'state' : '0','ExaStatusArr':'完成'
			},
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=purchase_delivered_delivered&action=getItemList",
									{
										deliveredId : data.id
									}, function(result) {
										if (result != "") {
											var itemscount = $('#itemscount')
													.val();
											for (var i = 0; i < itemscount; i++) {
												$("#stockName" + i)
														.yxcombogrid_stockinfo('remove');
											}
											$("#supplierName")
													.val(data.supplierName);
											$("#supplierId")
													.val(data.supplierId);
											$("#purchaserCode").val(data.purchManId);
											$("#purchaserName").val(data.purchManName);
											$("#inStockId").val(data.stockId);
											$("#inStockName").val(data.stockName);
											$("#itembody").html("");
											$("#itembody").append(result);

											$("#itemscount")
													.val($("#itembody tr")
															.size());
											reloadItemStock();
											formateMoney();
										} else {
											alert("该退料单中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}

function reloadInvpurchaseCombo() {// 采购发票combogrid
	$("#relDocCode").yxcombogrid_invpurchase({
		hiddenId : 'relDocId',
		nameCol : 'objCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=finance_invpurchase_invpurchase&action=getItemListJson",
									{
										id : data.id
									}, function(result) {
										if (result != "") {
											var itemscount = $('#itemscount')
													.val();
											for (var i = 0; i < itemscount; i++) {
												$("#stockName" + i)
														.yxcombogrid_stockinfo('remove');
											}
											$("#supplierName")
													.val(data.supplierName);
											$("#supplierId")
													.val(data.supplierId);
											$("#itembody").html("");
											$("#itembody").append(result);

											$("#itemscount")
													.val($("#itembody tr")
															.size());

											reloadItemStock();
											formateMoney();
										} else {
											alert("该采购发票中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}
// TODO:HUANGZF 加下推功能 及过滤销售合同
function reloadContractCombo() {// 销售合同combogrid
	$("#relDocCode").yxcombogrid_allcontract({
		hiddenId : 'relDocId',
		nameCol : 'contractCode',
		isFocusoutCheck : false,
		gridOptions : {
			param : {
				ExaStatus : '完成'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#relDocName").val(data.contractName);
					$("#clientId").val(data.customerId);
					$("#clientName").val(data.customerName);
					// if (data.orderCode == "") {
					// $("#relDocCode").val(data.orderTempCode);
					// }
					$
							.post(
									"?model=contract_contract_contract&action=getItemListAtRkProduct",
									{
										contractId : data.id
									}, function(result) {
//										alert(result)
//										$.showDump(result);
										if (result != "") {
											var itemscount = $('#itemscount')
													.val();
											for (var i = 0; i < itemscount; i++) {
												$("#stockName" + i)
														.yxcombogrid_stockinfo('remove');
											}
											$("#itembody").html("");
											$("#itembody").append(result);

											$("#itemscount")
													.val($("#itembody tr")
															.size());
											reloadItemStock();
											formateMoney();
										} else {
											alert("该销售合同中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}

function reloadBlueStockinCombo() {// 蓝色入库单combogrid
	var docType = $("#docType").val();
	$("#relDocCode").yxcombogrid_stockin({
		hiddenId : 'relDocId',
		gridOptions : {
			param : {
				'docType' : docType,
				'docStatus' : 'YSH'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					$.post("?model=stock_instock_stockin&action=showRelItem", {
								id : data.id,
								docType : docType
							}, function(result) {
								if (result != "") {
									var itemscount = $('#itemscount').val();
									for (var i = 0; i < itemscount; i++) {
										$("#stockName" + i)
												.yxcombogrid_stockinfo('remove');
									}
									$("#itembody").html("");
									$("#itembody").append(result);
									$("#itemscount").val($("#itembody tr")
											.size());
									reloadItemStock();
									formateMoney();
								} else {
									alert("该入库单中没有物料信息，请重新选择！")
								}

							});

				}
			}
		}
	});
}

function reloadStockOutCombo() {// 其他出库单combogrid
	$("#relDocCode").yxcombogrid_stockout({
		hiddenId : 'relDocId',
		gridOptions : {
			param : {
				'isRed' : '0',
				'docType' : "CKOTHER",
				'docStatus' : 'YSH'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=stock_outstock_stockout&action=showItemAtInStock",
									{
										id : data.id,
										docType : "CKOTHER"
									}, function(result) {
										if (result != "") {
											var itemscount = $('#itemscount')
													.val();
											for (var i = 0; i < itemscount; i++) {
												$("#stockName" + i)
														.yxcombogrid_stockinfo('remove');
											}
											$("#itembody").html("");
											$("#itembody").append(result);
											$("#itemscount")
													.val($("#itembody tr")
															.size());
											reloadItemStock();
											formateMoney();
										} else {
											alert("该出库单中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}
