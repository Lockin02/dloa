$(function() {
	// ���뵥����combogrid
	$("#applyCode").yxcombogrid_reduce({
		hiddenId : 'applyId',
		nameCol : 'docCode',
		width : 600,
		isShowButton : false,
		event : {
			'clearValue' : function() {
				$("#customerName").val("");
				$("#adress").val("");
				$("#applyUserName").val("");
				$("#subCost").val("");
			}
		},
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#adress").val(data.adress);
					$("#applyUserName").val(data.applyUserName);
					$("#subCost").val(data.subCost);

					$
							.post(
									"?model=service_repair_repairapply&action=getItemStrAtReduce",
									{
										reduceapplyId : data.id
									}, function(result) {

										if (result != "") {

											$("#itembody").html("").append(result);
											$("#itemscount")
													.val($("#itembody tr")
															.size());
											formateMoney();
										} else {
											alert("�����뵥��û��������Ϣ��������ѡ��")
										}

									});

				}

			}
		}
	});

	// ����������
	$("#applyUserName").yxselect_user({
				hiddenId : 'applyUserCode'

			});
});