function reloadShipCombo() {// ������
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
					//����Դ���жϲ����Ƿ��ߴ������
					var mark = true;
					if(data.docType == "oa_service_accessorder")
						$.ajax({
						    type: "POST",
							url : "?model=service_accessorder_accessorder&action=orderMoneyIsZero",
						    data: {"id" : data.docId},
						    async: false,
						    success: function(result){
								if(result*1 == 0){
									alert('�˷�������Ӧ����������Ϊ0����ӡ��������ⵥ�����������');
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
									alert("�÷�������û��������Ϣ��������ѡ��")
								}
							}
						);
				}
			}
		}
	});
}

//������ -- ��������ʹ��
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
					//����Դ���жϲ����Ƿ��ߴ������
					var mark = true;
					if(data.docType == "oa_service_accessorder")
						$.ajax({
						    type: "POST",
							url : "?model=service_accessorder_accessorder&action=orderMoneyIsZero",
						    data: {"id" : data.docId},
						    async: false,
						    success: function(result){
								if(result*1 != 0){
									alert('�˷�������Ӧ����������Ϊ0����ӡ����۳��ⵥ�����������');
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
									alert("�÷�������û��������Ϣ��������ѡ��")
								}
							}
						);
				}
			}
		}
	});
}


function reloadOutplanCombo() {// �����ƻ�
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
								$.ajax({// ��ȡ��Ӧ��ͬ��������Ϣ
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
								alert("�÷����ƻ���û��������Ϣ��������ѡ��")
							}
						}
					);
				}
			}
		}
	});
}

function reloadOrderbackCombo() {// �˻����뵥
	$("#relDocCode").yxcombogrid_return({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'ExaStatus' : '���'
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
											alert("�������˻�֪ͨ����û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadExchangeCombo() {// �������뵥
	var relDocUrl="?model=projectmanagent_exchange_exchange&action=getItemListAtCkSalesRed";
	if($("#isRed").val()=="0"){//����
		relDocUrl="?model=projectmanagent_exchange_exchange&action=getItemListAtCkSalesBlue";
	}
	$("#relDocCode").yxcombogrid_exchange({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'ExaStatus' : '���'
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
											alert("�û���֪ͨ����û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadBlueStockoutCombo() {// ��ɫ���ⵥcombogrid
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
									alert("�ó��ⵥ��û��������Ϣ��������ѡ��")
								}

							});

				}
			}
		}
	});
}

function reloadStocinCombo() {// ��ⵥcombogrid
	var docType = "RKPURCHASE";// �⹺
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
											alert("����ⵥ��û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}


function reloadStocinCombo() {// ��ⵥcombogrid
	var docType = "RKPURCHASE";// �⹺
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
											alert("����ⵥ��û��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}

function reloadProjectCombo() {// ��ⵥcombogrid
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

function reloadAllOrderCombo() {// ��ͬcombogrid
	$("#relDocCode").yxcombogrid_allcontract({
		hiddenId : 'relDocId',
		valueCol : 'id',
		isFocusoutCheck : false,
		isDown : false,
		gridOptions : {
			param : {
				ExaStatus : '���'
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

					// �����嵥��Ϣ
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
											alert("�ú�ͬû��������Ϣ��������ѡ��")
										}

									});

				}
			}
		}
	});
}
