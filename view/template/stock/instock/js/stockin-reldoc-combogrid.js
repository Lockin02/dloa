function reloadArrivalCombo() {// ����֪ͨ��combogrid
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
											alert("�����ϵ���û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadPurOrderCombo() {// �ɹ�����combogrid
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
											alert("�ö�������û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadDeliveredCombo() {// �ɹ�����֪ͨ��combogrid
	$("#relDocCode").yxcombogrid_purchdelivered({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'state' : '0','ExaStatusArr':'���'
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
											alert("�����ϵ���û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadInvpurchaseCombo() {// �ɹ���Ʊcombogrid
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
											alert("�òɹ���Ʊ��û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}
// TODO:HUANGZF �����ƹ��� ���������ۺ�ͬ
function reloadContractCombo() {// ���ۺ�ͬcombogrid
	$("#relDocCode").yxcombogrid_allcontract({
		hiddenId : 'relDocId',
		nameCol : 'contractCode',
		isFocusoutCheck : false,
		gridOptions : {
			param : {
				ExaStatus : '���'
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
											alert("�����ۺ�ͬ��û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadBlueStockinCombo() {// ��ɫ��ⵥcombogrid
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
									alert("����ⵥ��û��������Ϣ��������ѡ��")
								}

							});

				}
			}
		}
	});
}

function reloadStockOutCombo() {// �������ⵥcombogrid
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
											alert("�ó��ⵥ��û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}
