function reloadBorrowCombo() {// ��������combogrid
	//���θ��˽���������ѡ��
//	$("#relDocCode").yxcombogrid_borrow({
//				hiddenId : 'relDocId',
//				gridOptions : {
//					showcheckbox : false,
//					param : {
//						'ExaStatus' : '���',
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

function reloadOutplanCombo() {// �����ƻ�
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
									alert("�÷����ƻ���û��������Ϣ��������ѡ��")
								}

							});

				}
			}
		}
	});
}
