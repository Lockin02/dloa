$(function() {
		//����������
		$("#applyUserName").yxselect_user({
				hiddenId : 'applyUserCode'

				});
		});

	/**
	 *�豸���������豸��Ϣ
	 */
		function reloadChangeCombo() {
				$("#relDocCode").yxcombogrid_reduce({
						hiddenId : 'relDocId',
						nameCol : 'relDocCode',
						width : 600,
						isShowButton : false,
						event : {
							'clearValue' : function() {
								$("#customerName").val("");
								$("#adress").val("");
								$("#applyUserName").val("");
							}
						},
						gridOptions : {
							isShowButton : true,
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#relDocCode").val(data.docCode);
									$("#customerName").val(data.customerName);
									$("#adress").val(data.adress);
									$("#applyUserName").val(data.applyUserName);

									$.post("?model=service_repair_repairapply&action=getItemStrAtChange",
											{
												changeapplyId : data.id
											}, function(result) {

												if (result != "") {

													$("#itembody").html("");
													$("#itembody").append(result);
													$("#itemscount").val($("#itembody tr").size());
												} else {
													alert("�÷�������û��������Ϣ��������ѡ��")
												}

											});

								 }

							}
						}
				});
		}