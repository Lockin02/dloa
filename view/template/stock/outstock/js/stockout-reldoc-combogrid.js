function reloadShipCombo() {// 发货单
	$("#relDocCode").yxcombogrid_ship({
		hiddenId : 'relDocId',
		height : 250,
		gridOptions : {
			showcheckbox : false,
			param : {
				'shipStatus' : '1'
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					//加入源单判断并且是否走此类出库
					var mark = true;
					if(data.docType == "oa_service_accessorder")
						$.ajax({
						    type: "POST",
							url : "?model=service_accessorder_accessorder&action=orderMoneyIsZero",
						    data: {"id" : data.docId},
						    async: false,
						    success: function(result){
								if(result*1 == 0){
									alert('此发货单对应配件订单金额为0，请从【其他出库单】做出库操作');
									mark = false;
									$("#itembody").html("");
								}
							}
						});
					if(mark == true)
						$.post("?model=stock_outplan_ship&action=getItemList",
							{shipId : data.id},
							function(result) {
								if (result != "") {
									$("#contractCode").val(data.docCode);
									$("#contractId").val(data.docId);
									$("#contractName").val("");
									$("#contractType").val(data.docType);
									$("#customerName").val(data.customerName);
									$("#customerId").val(data.customerId);

									var itemscount = $('#itemscount').val();
									for (var i = 0; i < itemscount; i++) {
										$("#stockName" + i).yxcombogrid_stockinfo('remove');
									}
									$("#itembody").html("");
									$("#itembody").append(result);
									$("#itemscount").val($("#itembody tr").size());
									reloadItemStock();
									formateMoney();
								} else {
									alert("该发货单中没有物料信息，请重新选择！")
								}
							}
						);
				}
			}
		}
	});
}

//发货单 -- 其他出库使用
function reloadShipComboOther(){
	$("#relDocCode").yxcombogrid_ship({
		hiddenId : 'relDocId',
		height : 250,
		gridOptions : {
			showcheckbox : false,
			param : {
				'shipStatus' : '1'
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					//加入源单判断并且是否走此类出库
					var mark = true;
					if(data.docType == "oa_service_accessorder")
						$.ajax({
						    type: "POST",
							url : "?model=service_accessorder_accessorder&action=orderMoneyIsZero",
						    data: {"id" : data.docId},
						    async: false,
						    success: function(result){
								if(result*1 != 0){
									alert('此发货单对应配件订单金额为0，请从【销售出库单】做出库操作');
									mark = false;
									$("#itembody").html("");
								}
							}
						});
					if(mark == true)
						$.post("?model=stock_outplan_ship&action=getItemListOther",
							{shipId : data.id},
							function(result) {
								if (result != "") {
									$("#contractCode").val(data.docCode);
									$("#contractId").val(data.docId);
									$("#contractName").val("");
									$("#contractType").val(data.docType);
									$("#customerName").val(data.customerName);
									$("#customerId").val(data.customerId);

									var itemscount = $('#itemscount').val();
									for (var i = 0; i < itemscount; i++) {
										$("#stockName" + i).yxcombogrid_stockinfo('remove');
									}
									$("#itembody").html("");
									$("#itembody").append(result);
									$("#itemscount").val($("#itembody tr").size());
									reloadItemStock();
									formateMoney();
								} else {
									alert("该发货单中没有物料信息，请重新选择！")
								}
							}
						);
				}
			}
		}
	});
}


function reloadOutplanCombo() {// 发货计划
	$("#relDocCode").yxcombogrid_outplan({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'docStatusArr' : ['WFH', 'BFFH'],
				'docTypeArr' : ['oa_contract_contract']
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$.post(
						"?model=stock_outplan_outplan&action=getItemList",
						{planId : data.id},
						function(result) {
							if (result != "") {
								$("#customerName").val(data.customerName);
								$("#customerId").val(data.customerId);
								$("#contractCode").val(data.docCode);
								$("#contractId").val(data.docId);
								$("#contractName").val(data.docName);
								$("#contractType").val(data.docType);
								$.ajax({// 获取对应合同负责人信息
									type : "POST",
									dataType : "json",
									async : false,
									url : "?model=projectmanagent_order_order&action=ajaxPrincipal",
									data : {
										"contractId" : data.docId,
										"contractType" : data.docType
									},
									success : function(contractArr) {
										$("#chargeName").val(contractArr[0]['prinvipalName']);
										$("#chargeCode").val(contractArr[0]['prinvipalId']);
									}
								});

								var itemscount = $('#itemscount').val();
								for (var i = 0; i < itemscount; i++) {
									$("#stockName" + i).yxcombogrid_stockinfo('remove');
								}
								$("#itembody").html("");
								$("#itembody").append(result);
								$("#itemscount").val($("#itembody tr").size());
								reloadItemStock();
								formateMoney();
							} else {
								alert("该发货计划中没有物料信息，请重新选择！")
							}
						}
					);
				}
			}
		}
	});
}

function reloadOrderbackCombo() {// 退货申请单
	$("#relDocCode").yxcombogrid_return({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'ExaStatus' : '完成'
			},
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=projectmanagent_return_return&action=getItemList",
									{
										returnId : data.id
									}, function(result) {
										if (result != "") {
											$("#contractCode")
													.val(data.contractCode);
											$("#contractId")
													.val(data.contractId);
											$("#contractName")
													.val(data.contractName);
											$("#contractType")
													.val("oa_contract_contract");

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
											alert("该销售退货通知单中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}

function reloadExchangeCombo() {// 换货申请单
	var relDocUrl="?model=projectmanagent_exchange_exchange&action=getItemListAtCkSalesRed";
	if($("#isRed").val()=="0"){//蓝字
		relDocUrl="?model=projectmanagent_exchange_exchange&action=getItemListAtCkSalesBlue";
	}
	$("#relDocCode").yxcombogrid_exchange({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'ExaStatus' : '完成'
			},
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(relDocUrl
									,
									{
										relDocId : data.id
									}, function(result) {
										if (result != "") {
											$("#contractCode")
													.val(data.contractCode);
											$("#contractId")
													.val(data.contractId);
											$("#contractName")
													.val(data.contractName);
											$("#contractType")
													.val("oa_contract_contract");

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
											alert("该换货通知单中没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}

function reloadBlueStockoutCombo() {// 蓝色出库单combogrid
	var docType = $("#docType").val();
	$("#relDocCode").yxcombogrid_stockout({
		hiddenId : 'relDocId',
		gridOptions : {
			param : {
				'docType' : docType,
				'docStatus' : 'YSH',
				'isRed' : '0'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					$.post("?model=stock_outstock_stockout&action=showRelItem",
							{
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
									alert("该出库单中没有物料信息，请重新选择！")
								}

							});

				}
			}
		}
	});
}

function reloadStocinCombo() {// 入库单combogrid
	var docType = "RKPURCHASE";// 外购
	$("#relDocCode").yxcombogrid_stockin({
		hiddenId : 'relDocId',
		gridOptions : {
			param : {
				'docType' : docType,
				'docStatus' : 'YSH',
				'isRed' : '0'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=stock_instock_stockin&action=showItemAtOutStock",
									{
										id : data.id,
										docType : docType
									}, function(result) {
										if (result != "") {
											$("#itembody").html("");
											$("#itembody").append(result);
											$("#itemscount")
													.val($("#itembody tr")
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


function reloadStocinCombo() {// 入库单combogrid
	var docType = "RKPURCHASE";// 外购
	$("#relDocCode").yxcombogrid_stockin({
		hiddenId : 'relDocId',
		gridOptions : {
			param : {
				'docType' : docType,
				'docStatus' : 'YSH',
				'isRed' : '0'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {

					$
							.post(
									"?model=stock_instock_stockin&action=showItemAtOutStock",
									{
										id : data.id,
										docType : docType
									}, function(result) {
										if (result != "") {
											$("#itembody").html("");
											$("#itembody").append(result);
											$("#itemscount")
													.val($("#itembody tr")
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

function reloadProjectCombo() {// 入库单combogrid
		$("#relDocCode").yxcombogrid_esmproject({
			isDown: true,
			hiddenId: 'relDocId',
			height: 250,
			gridOptions: {
				isTitle: true,
				showcheckbox: false,
				isFocusoutCheck: true,
				param: {attribute: 'GCXMSS-05', statusArr: 'GCXMZT02'},
				event : {
					'row_dblclick' : function(e,row,data) {
						$("#relDocName").val(data.projectName)
					}
				}
			}
		});
}

function reloadAllOrderCombo() {// 合同combogrid
	$("#relDocCode").yxcombogrid_allcontract({
		hiddenId : 'relDocId',
		valueCol : 'id',
		isFocusoutCheck : false,
		isDown : false,
		gridOptions : {
			param : {
				ExaStatus : '完成'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#rObjCode").val(data.objCode);
					$("#relDocName").val(data.contractName);
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#contractId").val(data.id);
					$("#contractCode").val(data.contractCode);
					$("#contractName").val(data.contractName);
					$("#contractType").val("oa_contract_contract");
					$("#contractObjCode").val(data.objCode);

					// 带出清单信息
					$
							.post(
									"?model=contract_contract_contract&action=getItemListAtCkSales",
									{
										contractId : data.id

									}, function(result) {
										// alert(result)
										if (result != "") {
											$("#itembody").html("");
											$("#itembody").append(result);
											$("#itemscount")
													.val($("#itembody tr")
															.size());
											reloadItemStock();
											formateMoney();
										} else {
											alert("该合同没有物料信息，请重新选择！")
										}

									});

				}
			}
		}
	});
}
