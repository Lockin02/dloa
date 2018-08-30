function reloadBorrowCombo() {// 借用申请combogrid
	//屏蔽个人借试用下拉选择
//	$("#relDocCode").yxcombogrid_borrow({
//				hiddenId : 'relDocId',
//				gridOptions : {
//					showcheckbox : false,
//					param : {
//						'ExaStatus' : '完成',
//						'initTip ' : '0'
//					},
//					event : {
//						'row_dblclick' : function(e, row, data) {
//							$("#itemscount").val($("#itembody tr").size());
//							reloadItemStock();
//							formateMoney();
//						}
//					}
//				}
//			});
}

function reloadOutplanCombo() {// 发货计划
	$("#relDocCode").yxcombogrid_outplan({
		hiddenId : 'relDocId',
		gridOptions : {
			showcheckbox : false,
			param : {
				'docStatusArr' : ['WFH', 'BFFH'],
				'docTypeArr' : ['oa_borrow_borrow']
			},
			event : {
				'row_dblclick' : function(e, row, data) {

					$.post("?model=stock_outplan_outplan&action=getItemList", {
								planId : data.id
							}, function(result) {

								if (result != "") {
									$("#customerName").val(data.customerName);
									$("#customerId").val(data.customerId);
									$("#contractCode").val(data.docCode);
									$("#contractId").val(data.docId);
									$("#contractName").val(data.docName);

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
									reSetStock();
								} else {
									alert("该发货计划中没有物料信息，请重新选择！")
								}

							});

				}
			}
		}
	});
}
